<?php

namespace Tests\Integration;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;

/**
 * Integration tests for promo code CRUD and business rules.
 */
final class PromoCodeControllerTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    use FeatureTestTrait;

    protected $migrate     = true;
    protected $migrateOnce = false;
    protected $refresh     = true;
    protected $namespace   = 'App';

    private array $adminSession = ['access' => 1, 'role' => '1', 'user_id' => 1];

    // ── Auth guard ────────────────────────────────────────────────────────────

    public function testPromoIndexRequiresAuth(): void
    {
        $result = $this->call('GET', '/admin/promo_code');
        $result->assertRedirectTo(base_url('login'));
    }

    public function testPromoStoreRequiresAuth(): void
    {
        $result = $this->call('POST', '/admin/promo_code/store', []);
        $result->assertRedirectTo(base_url('login'));
    }

    public function testPromoDeleteRequiresAuth(): void
    {
        $result = $this->call('GET', '/admin/promo_code/delete/1');
        $result->assertRedirectTo(base_url('login'));
    }

    // ── Create promo ──────────────────────────────────────────────────────────

    public function testAdminCanCreatePromoCode(): void
    {
        $result = $this->withSession($this->adminSession)
                       ->call('POST', '/admin/promo_code/store', [
                           'code'                => 'SAVE20',
                           'discount_type'       => 'percentage',
                           'discount_percentage' => '20',
                           'discount_amount'     => '',
                           'validation_date'     => '2024-01-01T00:00',
                           'expired_date'        => '2030-12-31T23:59',
                       ]);

        $result->assertRedirect();
        $this->seeInDatabase('promo_code', ['code' => 'SAVE20', 'is_deleted' => 0]);
    }

    public function testPromoStoreFailsWithMissingCode(): void
    {
        $result = $this->withSession($this->adminSession)
                       ->call('POST', '/admin/promo_code/store', [
                           'code'            => '',
                           'discount_type'   => 'percentage',
                           'validation_date' => '2024-01-01T00:00',
                           'expired_date'    => '2030-12-31T23:59',
                       ]);

        $result->assertSessionHas('errors');
    }

    // ── Duplicate prevention ──────────────────────────────────────────────────

    public function testAjaxStoreRejectsDuplicateActiveCode(): void
    {
        $this->db->table('promo_code')->insert([
            'code'            => 'DUPE10',
            'discount_type'   => 'percentage',
            'discount_percentage' => 10,
            'discount_amount' => 0,
            'validation_date' => '2024-01-01 00:00:00',
            'expired_date'    => '2030-12-31 23:59:59',
            'is_deleted'      => 0,
            'created_date'    => date('Y-m-d H:i:s'),
        ]);

        $result = $this->withSession($this->adminSession)
                       ->withBodyFormat('json')
                       ->call('POST', '/admin/promo_code/store_ajax', [
                           'code'                => 'DUPE10',
                           'discount_type'       => 'percentage',
                           'discount_percentage' => '10',
                           'validation_date'     => '2024-01-01T00:00',
                           'expired_date'        => '2030-12-31T23:59',
                       ]);

        $body = json_decode($result->getJSON(), true);
        $this->assertFalse($body['success']);
    }

    // ── Soft delete ───────────────────────────────────────────────────────────

    public function testAdminCanSoftDeletePromoCode(): void
    {
        $this->db->table('promo_code')->insert([
            'code'            => 'DELETE_ME',
            'discount_type'   => 'amount',
            'discount_percentage' => 0,
            'discount_amount' => 5,
            'validation_date' => '2024-01-01 00:00:00',
            'expired_date'    => '2030-12-31 23:59:59',
            'is_deleted'      => 0,
            'created_date'    => date('Y-m-d H:i:s'),
        ]);

        $id = $this->db->insertID();

        $this->withSession($this->adminSession)->call('GET', "/admin/promo_code/delete/{$id}");

        $this->seeInDatabase('promo_code', ['id' => $id, 'is_deleted' => 1]);
    }

    // ── Promo code check endpoint ─────────────────────────────────────────────

    public function testCheckPromoReturnsValidForActiveCode(): void
    {
        $this->db->table('promo_code')->insert([
            'code'            => 'VALID30',
            'discount_type'   => 'percentage',
            'discount_percentage' => 30,
            'discount_amount' => 0,
            'validation_date' => '2024-01-01 00:00:00',
            'expired_date'    => '2030-12-31 23:59:59',
            'is_deleted'      => 0,
            'created_date'    => date('Y-m-d H:i:s'),
        ]);

        $result = $this->withBodyFormat('json')
                       ->call('POST', '/checkPromoCode', ['promo_code' => 'VALID30']);

        $body = json_decode($result->getJSON(), true);
        $this->assertTrue($body['valid']);
        $this->assertSame(30, $body['discount']);
    }

    public function testCheckPromoReturnsInvalidForExpiredCode(): void
    {
        $this->db->table('promo_code')->insert([
            'code'            => 'EXPIRED',
            'discount_type'   => 'percentage',
            'discount_percentage' => 10,
            'discount_amount' => 0,
            'validation_date' => '2020-01-01 00:00:00',
            'expired_date'    => '2020-12-31 23:59:59',
            'is_deleted'      => 0,
            'created_date'    => date('Y-m-d H:i:s'),
        ]);

        $result = $this->withBodyFormat('json')
                       ->call('POST', '/checkPromoCode', ['promo_code' => 'EXPIRED']);

        $body = json_decode($result->getJSON(), true);
        $this->assertFalse($body['valid']);
    }

    public function testCheckPromoReturnsInvalidForUnknownCode(): void
    {
        $result = $this->withBodyFormat('json')
                       ->call('POST', '/checkPromoCode', ['promo_code' => 'DOESNOTEXIST']);

        $body = json_decode($result->getJSON(), true);
        $this->assertFalse($body['valid']);
    }
}
