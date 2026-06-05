<?php

use CodeIgniter\Test\CIUnitTestCase;
use App\Models\OrderModel;

/**
 * Tests for OrderModel — validation rules and model configuration.
 *
 * Uses the model's own validate() method so no live database is needed.
 *
 * @internal
 */
final class OrderModelTest extends CIUnitTestCase
{
    private OrderModel $model;

    protected function setUp(): void
    {
        parent::setUp();
        $this->model = new OrderModel();
    }

    // ── Configuration ──────────────────────────────────────────────

    public function testTableName(): void
    {
        $this->assertSame('order', $this->model->table);
    }

    public function testPrimaryKey(): void
    {
        $this->assertSame('order_id', $this->model->primaryKey);
    }

    public function testReturnTypeIsArray(): void
    {
        $this->assertSame('array', $this->model->returnType);
    }

    // ── Validation rules ───────────────────────────────────────────

    /**
     * A fully populated order row should pass validation.
     */
    public function testValidDataPassesValidation(): void
    {
        $data = $this->validOrderData();

        $this->assertTrue(
            $this->model->validate($data),
            implode('; ', $this->model->errors())
        );
    }

    public function testEmptyServiceTypeFailsValidation(): void
    {
        // CI4 model validation only runs on fields present in $data;
        // pass empty string to trigger the required rule.
        $data                 = $this->validOrderData();
        $data['service_type'] = '';

        $this->assertFalse($this->model->validate($data));
        $this->assertArrayHasKey('service_type', $this->model->errors());
    }

    public function testEmptyFirstNameFailsValidation(): void
    {
        $data               = $this->validOrderData();
        $data['first_name'] = '';

        $this->assertFalse($this->model->validate($data));
        $this->assertArrayHasKey('first_name', $this->model->errors());
    }

    public function testEmptyLastNameFailsValidation(): void
    {
        $data              = $this->validOrderData();
        $data['last_name'] = '';

        $this->assertFalse($this->model->validate($data));
        $this->assertArrayHasKey('last_name', $this->model->errors());
    }

    public function testInvalidEmailFailsValidation(): void
    {
        $data          = $this->validOrderData();
        $data['email'] = 'not-an-email';

        $this->assertFalse($this->model->validate($data));
        $this->assertArrayHasKey('email', $this->model->errors());
    }

    public function testEmptyEmailFailsValidation(): void
    {
        $data          = $this->validOrderData();
        $data['email'] = '';

        $this->assertFalse($this->model->validate($data));
        $this->assertArrayHasKey('email', $this->model->errors());
    }

    public function testEmptyPhoneFailsValidation(): void
    {
        $data          = $this->validOrderData();
        $data['phone'] = '';

        $this->assertFalse($this->model->validate($data));
        $this->assertArrayHasKey('phone', $this->model->errors());
    }

    public function testEmptyOrderDetailsJsonFailsValidation(): void
    {
        $data                      = $this->validOrderData();
        $data['order_details_json'] = '';

        $this->assertFalse($this->model->validate($data));
        $this->assertArrayHasKey('order_details_json', $this->model->errors());
    }

    public function testSocialMustBeInteger(): void
    {
        $data           = $this->validOrderData();
        $data['social'] = 'not-an-int';

        $this->assertFalse($this->model->validate($data));
        $this->assertArrayHasKey('social', $this->model->errors());
    }

    public function testServiceTypeTooLongFailsValidation(): void
    {
        $data                 = $this->validOrderData();
        $data['service_type'] = str_repeat('x', 256);

        $this->assertFalse($this->model->validate($data));
        $this->assertArrayHasKey('service_type', $this->model->errors());
    }

    // ── Helpers ────────────────────────────────────────────────────

    private function validOrderData(): array
    {
        return [
            'service_type'       => 'delivery',
            'first_name'         => 'Ahmad',
            'last_name'          => 'Rahman',
            'id_num'             => '990101-13-1234',
            'email'              => 'ahmad@example.com',
            'phone'              => '+601234567890',
            'social'             => 1,
            'social_num'         => 'TW-123456',
            'order_details_json' => '{"items":[]}',
        ];
    }
}
