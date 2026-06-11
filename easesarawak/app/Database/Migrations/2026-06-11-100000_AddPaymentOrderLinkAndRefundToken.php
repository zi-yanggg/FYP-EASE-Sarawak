<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPaymentOrderLinkAndRefundToken extends Migration
{
    public function up()
    {
        if ($this->db->tableExists('payments') && ! $this->db->fieldExists('order_id', 'payments')) {
            $this->forge->addColumn('payments', [
                'order_id' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'null'       => true,
                    'after'      => 'payment_intent_id',
                ],
            ]);
            $this->forge->addKey('order_id', false, false, 'payments_order_id');
        }

        if ($this->db->tableExists('refund_form') && ! $this->db->fieldExists('access_token', 'refund_form')) {
            $this->forge->addColumn('refund_form', [
                'access_token' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 64,
                    'null'       => true,
                    'after'      => 'pdf_path',
                ],
            ]);
        }
    }

    public function down()
    {
        if ($this->db->tableExists('payments') && $this->db->fieldExists('order_id', 'payments')) {
            $this->forge->dropColumn('payments', 'order_id');
        }

        if ($this->db->tableExists('refund_form') && $this->db->fieldExists('access_token', 'refund_form')) {
            $this->forge->dropColumn('refund_form', 'access_token');
        }
    }
}
