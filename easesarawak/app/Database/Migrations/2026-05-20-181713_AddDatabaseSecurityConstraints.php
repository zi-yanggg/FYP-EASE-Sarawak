<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDatabaseSecurityConstraints extends Migration
{
    public function up(): void
    {
        // ── user ─────────────────────────────────────────────────────────────
        // Unique email prevents duplicate accounts
        $this->db->query('ALTER TABLE `user` ADD CONSTRAINT uq_user_email UNIQUE (email)');
        // Indexed token lookups — avoid full-table scans on login/reset
        $this->db->query('ALTER TABLE `user` ADD INDEX idx_user_reset_token (reset_token(64))');
        $this->db->query('ALTER TABLE `user` ADD INDEX idx_user_remember_token (remember_token(64))');

        // ── payments ──────────────────────────────────────────────────────────
        // DB-level uniqueness on payment_intent_id — idempotency backstop
        $this->db->query('ALTER TABLE `payments` ADD CONSTRAINT uq_payments_intent UNIQUE (payment_intent_id)');
        // stripe_payment_id (ch_...) unique when not null
        $this->db->query('ALTER TABLE `payments` ADD CONSTRAINT uq_payments_charge UNIQUE (stripe_payment_id)');

        // ── promo_code ────────────────────────────────────────────────────────
        // Composite index speeds up the active-code duplicate check
        $this->db->query('ALTER TABLE `promo_code` ADD INDEX idx_promo_lookup (code, is_deleted, expired_date)');

        // ── order ─────────────────────────────────────────────────────────────
        // Dashboard fires 10+ date-range queries — these make them fast
        $this->db->query('ALTER TABLE `order` ADD INDEX idx_order_date (created_date)');
        $this->db->query('ALTER TABLE `order` ADD INDEX idx_order_status (status, is_deleted)');

        // ── refund_form ───────────────────────────────────────────────────────
        $this->db->query('ALTER TABLE `refund_form` ADD INDEX idx_refund_status (status)');

        // ── message ───────────────────────────────────────────────────────────
        $this->db->query('ALTER TABLE `message` ADD INDEX idx_message_status (status, is_deleted)');
    }

    public function down(): void
    {
        $this->db->query('ALTER TABLE `user` DROP CONSTRAINT uq_user_email');
        $this->db->query('ALTER TABLE `user` DROP INDEX idx_user_reset_token');
        $this->db->query('ALTER TABLE `user` DROP INDEX idx_user_remember_token');
        $this->db->query('ALTER TABLE `payments` DROP CONSTRAINT uq_payments_intent');
        $this->db->query('ALTER TABLE `payments` DROP CONSTRAINT uq_payments_charge');
        $this->db->query('ALTER TABLE `promo_code` DROP INDEX idx_promo_lookup');
        $this->db->query('ALTER TABLE `order` DROP INDEX idx_order_date');
        $this->db->query('ALTER TABLE `order` DROP INDEX idx_order_status');
        $this->db->query('ALTER TABLE `refund_form` DROP INDEX idx_refund_status');
        $this->db->query('ALTER TABLE `message` DROP INDEX idx_message_status');
    }
}
