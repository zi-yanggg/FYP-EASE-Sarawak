<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAiKnowledgeBaseTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'       => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'category' => ['type' => 'VARCHAR', 'constraint' => 100],
            'title'    => ['type' => 'VARCHAR', 'constraint' => 255],
            'content'  => ['type' => 'TEXT'],
            'keywords' => ['type' => 'VARCHAR', 'constraint' => 500, 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('ai_knowledge_base');

        // Add FULLTEXT index after table creation
        $this->db->query('ALTER TABLE ai_knowledge_base ADD FULLTEXT ft_search (title, content, keywords)');

        // Seed initial knowledge base entries
        $this->db->table('ai_knowledge_base')->insertBatch([
            [
                'category' => 'policy',
                'title'    => 'Cancellation Policy',
                'content'  => 'Orders can be cancelled within 30 minutes of placement by contacting support. After 30 minutes, cancellations are subject to a processing fee. Completed orders cannot be cancelled.',
                'keywords' => 'cancel cancellation refund fee',
            ],
            [
                'category' => 'policy',
                'title'    => 'Refund Policy',
                'content'  => 'Refunds are processed within 3-5 business days to the original payment method. Cash payments are refunded in person at our office. Partial refunds may apply for partially completed deliveries.',
                'keywords' => 'refund money back payment return',
            ],
            [
                'category' => 'service',
                'title'    => 'Express Delivery',
                'content'  => 'Express delivery guarantees same-day delivery for orders placed before 2PM. Available within Kuching city limits. Surcharge of RM10 applies. Contact support to arrange express delivery.',
                'keywords' => 'express fast same-day delivery urgent speed',
            ],
            [
                'category' => 'service',
                'title'    => 'Standard Delivery',
                'content'  => 'Standard delivery takes 1-3 business days depending on location in Sarawak. Available statewide including rural areas. Tracking is provided via order ID.',
                'keywords' => 'standard delivery regular normal tracking',
            ],
            [
                'category' => 'service',
                'title'    => 'Document Courier',
                'content'  => 'Secure document courier service with signature upon delivery. Ideal for legal, government, and business documents. Confidentiality is guaranteed. Insurance available on request.',
                'keywords' => 'document courier legal government business secure confidential',
            ],
            [
                'category' => 'faq',
                'title'    => 'Payment Methods',
                'content'  => 'We accept FPX online banking, Visa/Mastercard credit and debit cards via Stripe, and cash on delivery (COD). Promo codes can be applied at checkout for eligible services.',
                'keywords' => 'payment FPX card credit debit cash COD promo code',
            ],
            [
                'category' => 'faq',
                'title'    => 'Promo Codes',
                'content'  => 'Promo codes provide discounts on eligible orders. Each code has an expiry date and may have usage limits. Apply the code at checkout. Codes cannot be combined and are non-transferable.',
                'keywords' => 'promo code discount voucher coupon offer',
            ],
            [
                'category' => 'faq',
                'title'    => 'Order Tracking',
                'content'  => 'Track your order using the order ID provided at booking. Status updates: Pending (awaiting pickup), In Transit (on the way), Completed (delivered). Contact support if no update after 24 hours.',
                'keywords' => 'track order status pending transit completed delivered',
            ],
            [
                'category' => 'operations',
                'title'    => 'Operating Hours',
                'content'  => 'EASE Sarawak operates Monday to Saturday, 8AM to 6PM. Sunday and public holidays are closed. Emergency services may be arranged outside hours for an additional fee.',
                'keywords' => 'hours operating open close time schedule holiday',
            ],
            [
                'category' => 'operations',
                'title'    => 'Service Areas',
                'content'  => 'We serve all major towns in Sarawak including Kuching, Miri, Sibu, Bintulu, and surrounding areas. Remote area deliveries may incur additional charges and longer transit times.',
                'keywords' => 'area location coverage Kuching Miri Sibu Bintulu Sarawak',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropTable('ai_knowledge_base');
    }
}
