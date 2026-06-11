<?php

namespace App\Database\Migrations;

use App\Services\OrderDetailsService;
use CodeIgniter\Database\Migration;

class OrderBookingSplitAndPerformanceIndexes extends Migration
{
    public function up()
    {
        if (! $this->db->tableExists('order_booking')) {
            $this->forge->addField([
                'order_id' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'unsigned'   => true,
                ],
                'booking_json' => [
                    'type' => 'JSON',
                    'null' => true,
                ],
                'dropoff_at' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
                'pickup_at' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
                'origin' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 255,
                    'null'       => true,
                ],
                'destination' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 255,
                    'null'       => true,
                ],
                'storage_location' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 255,
                    'null'       => true,
                ],
                'quantity' => [
                    'type'       => 'TINYINT',
                    'constraint' => 3,
                    'unsigned'   => true,
                    'default'    => 1,
                ],
                'created_at' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
                'updated_at' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
            ]);
            $this->forge->addKey('order_id', true);
            $this->forge->addKey('dropoff_at', false, false, 'idx_ob_dropoff');
            $this->forge->addKey('pickup_at', false, false, 'idx_ob_pickup');
            $this->forge->createTable('order_booking', true);
        }

        if (! $this->db->tableExists('order_customer')) {
            $this->forge->addField([
                'order_id' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'unsigned'   => true,
                ],
                'first_name' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 255,
                ],
                'last_name' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 255,
                ],
                'id_num' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 50,
                ],
                'email' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 255,
                ],
                'phone' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 20,
                ],
                'social' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'default'    => 0,
                ],
                'social_num' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 100,
                    'null'       => true,
                ],
                'upload' => [
                    'type' => 'TEXT',
                    'null' => true,
                ],
                'special' => [
                    'type'       => 'TINYINT',
                    'constraint' => 1,
                    'default'    => 0,
                ],
                'special_note' => [
                    'type' => 'TEXT',
                    'null' => true,
                ],
                'created_at' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
                'updated_at' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
            ]);
            $this->forge->addKey('order_id', true);
            $this->forge->addKey('email', false, false, 'idx_oc_email');
            $this->forge->createTable('order_customer', true);
        }

        $this->addIndexIfMissing('order', 'idx_order_list', 'is_deleted, created_date');
        $this->addIndexIfMissing('order', 'idx_order_status', 'is_deleted, status, created_date');
        $this->addIndexIfMissing('order', 'idx_order_service', 'is_deleted, service_type, created_date');
        $this->addIndexIfMissing('order', 'idx_order_email', 'email, is_deleted');

        if ($this->db->tableExists('payments') && $this->db->fieldExists('order_id', 'payments')) {
            $this->addIndexIfMissing('payments', 'idx_payments_order_status', 'order_id, status');
            $this->addIndexIfMissing('payments', 'idx_payments_created', 'created_at');
        }

        if ($this->db->tableExists('refund_form')) {
            $this->addIndexIfMissing('refund_form', 'idx_refund_status_created', 'status_progress, created_at');
            $this->addIndexIfMissing('refund_form', 'idx_refund_order', 'order_id');
            $this->addIndexIfMissing('refund_form', 'idx_refund_email', 'email');

            if ($this->db->fieldExists('access_token', 'refund_form')) {
                $this->addIndexIfMissing('refund_form', 'idx_refund_access_token', 'access_token');
            }

            $this->normalizeRefundOrderIdColumn();
        }

        $this->backfillSplitTables();
    }

    public function down()
    {
        if ($this->db->tableExists('order_booking')) {
            $this->forge->dropTable('order_booking', true);
        }

        if ($this->db->tableExists('order_customer')) {
            $this->forge->dropTable('order_customer', true);
        }

        $this->dropIndexIfExists('order', 'idx_order_list');
        $this->dropIndexIfExists('order', 'idx_order_status');
        $this->dropIndexIfExists('order', 'idx_order_service');
        $this->dropIndexIfExists('order', 'idx_order_email');
        $this->dropIndexIfExists('payments', 'idx_payments_order_status');
        $this->dropIndexIfExists('payments', 'idx_payments_created');
        $this->dropIndexIfExists('refund_form', 'idx_refund_status_created');
        $this->dropIndexIfExists('refund_form', 'idx_refund_order');
        $this->dropIndexIfExists('refund_form', 'idx_refund_email');
        $this->dropIndexIfExists('refund_form', 'idx_refund_access_token');
    }

    private function addIndexIfMissing(string $table, string $indexName, string $columns): void
    {
        if (! $this->db->tableExists($table)) {
            return;
        }

        $exists = $this->db->query(
            'SHOW INDEX FROM `' . $table . '` WHERE Key_name = ?',
            [$indexName]
        )->getResult();

        if ($exists !== []) {
            return;
        }

        $this->db->query('ALTER TABLE `' . $table . '` ADD INDEX `' . $indexName . '` (' . $columns . ')');
    }

    private function dropIndexIfExists(string $table, string $indexName): void
    {
        if (! $this->db->tableExists($table)) {
            return;
        }

        $exists = $this->db->query(
            'SHOW INDEX FROM `' . $table . '` WHERE Key_name = ?',
            [$indexName]
        )->getResult();

        if ($exists === []) {
            return;
        }

        $this->db->query('ALTER TABLE `' . $table . '` DROP INDEX `' . $indexName . '`');
    }

    private function normalizeRefundOrderIdColumn(): void
    {
        $field = $this->db->query("SHOW COLUMNS FROM `refund_form` LIKE 'order_id'")->getRowArray();
        if ($field === null) {
            return;
        }

        $type = strtolower((string) ($field['Type'] ?? ''));
        if (str_contains($type, 'int')) {
            return;
        }

        $this->db->query(
            'ALTER TABLE `refund_form` MODIFY `order_id` INT UNSIGNED NOT NULL'
        );
    }

    private function backfillSplitTables(): void
    {
        if (! $this->db->tableExists('order')) {
            return;
        }

        $detailsService = new OrderDetailsService();
        $orders         = $this->db->table('`order`')->get()->getResultArray();
        $now            = date('Y-m-d H:i:s');

        foreach ($orders as $order) {
            $orderId = (int) ($order['order_id'] ?? 0);
            if ($orderId <= 0) {
                continue;
            }

            if ($this->db->tableExists('order_customer')) {
                $exists = $this->db->table('order_customer')->where('order_id', $orderId)->countAllResults();
                if ($exists === 0) {
                    $this->db->table('order_customer')->insert([
                        'order_id'     => $orderId,
                        'first_name'   => (string) ($order['first_name'] ?? ''),
                        'last_name'    => (string) ($order['last_name'] ?? ''),
                        'id_num'       => (string) ($order['id_num'] ?? ''),
                        'email'        => (string) ($order['email'] ?? ''),
                        'phone'        => (string) ($order['phone'] ?? ''),
                        'social'       => (int) ($order['social'] ?? 0),
                        'social_num'   => (string) ($order['social_num'] ?? ''),
                        'upload'       => (string) ($order['upload'] ?? ''),
                        'special'      => (int) ($order['special'] ?? 0),
                        'special_note' => $order['special_note'] ?? null,
                        'created_at'   => $order['created_date'] ?? $now,
                        'updated_at'   => $order['modified_date'] ?? null,
                    ]);
                }
            }

            if (! $this->db->tableExists('order_booking')) {
                continue;
            }

            $exists = $this->db->table('order_booking')->where('order_id', $orderId)->countAllResults();
            if ($exists > 0) {
                continue;
            }

            $bookingData = $detailsService->resolveBookingData($order);
            if ($bookingData === []) {
                continue;
            }

            $indexed = $detailsService->extractIndexedFields($bookingData);

            $this->db->table('order_booking')->insert([
                'order_id'         => $orderId,
                'booking_json'     => json_encode($bookingData, JSON_UNESCAPED_SLASHES),
                'dropoff_at'       => $indexed['dropoff_at'],
                'pickup_at'        => $indexed['pickup_at'],
                'origin'           => $indexed['origin'],
                'destination'      => $indexed['destination'],
                'storage_location' => $indexed['storage_location'],
                'quantity'         => $indexed['quantity'],
                'created_at'       => $order['created_date'] ?? $now,
                'updated_at'       => $order['modified_date'] ?? null,
            ]);
        }
    }
}
