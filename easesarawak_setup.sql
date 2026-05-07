-- ============================================================
-- EASE Sarawak — Full Database Setup
-- Import this file in phpMyAdmin: Database > Import > Choose File
-- ============================================================

CREATE DATABASE IF NOT EXISTS `easesarawak`
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE `easesarawak`;

-- ------------------------------------------------------------
-- 1. user
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `user` (
  `user_id`         INT UNSIGNED     NOT NULL AUTO_INCREMENT,
  `role`            TINYINT(1)       NOT NULL DEFAULT 1 COMMENT '0=admin, 1=staff',
  `username`        VARCHAR(100)     NOT NULL,
  `password`        VARCHAR(255)     NOT NULL,
  `email`           VARCHAR(255)     NOT NULL,
  `profile_picture` VARCHAR(255)     DEFAULT NULL,
  `remember_token`  VARCHAR(255)     DEFAULT NULL,
  `reset_token`     VARCHAR(255)     DEFAULT NULL,
  `reset_expires`   DATETIME         DEFAULT NULL,
  `is_deleted`      TINYINT(1)       NOT NULL DEFAULT 0,
  `created_date`    DATETIME         NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_date`   DATETIME         DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `uq_user_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ------------------------------------------------------------
-- 2. order
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `order` (
  `order_id`          INT UNSIGNED     NOT NULL AUTO_INCREMENT,
  `service_type`      VARCHAR(255)     NOT NULL,
  `first_name`        VARCHAR(255)     NOT NULL,
  `last_name`         VARCHAR(255)     NOT NULL,
  `id_num`            VARCHAR(50)      NOT NULL,
  `email`             VARCHAR(255)     NOT NULL,
  `phone`             VARCHAR(20)      NOT NULL,
  `social`            VARCHAR(10)      NOT NULL DEFAULT '0' COMMENT '0=none,1=WhatsApp,2=WeChat,3=Line',
  `social_num`        VARCHAR(100)     DEFAULT NULL,
  `upload`            TEXT             DEFAULT NULL COMMENT 'JSON array of uploaded file paths',
  `special`           TINYINT(1)       NOT NULL DEFAULT 0 COMMENT '1=has special luggage',
  `special_note`      TEXT             DEFAULT NULL,
  `order_details_json` LONGTEXT        NOT NULL,
  `promo_code`        VARCHAR(100)     DEFAULT NULL,
  `status`            VARCHAR(50)      NOT NULL DEFAULT 'pending' COMMENT 'pending,completed,cancelled',
  `amount`            DECIMAL(10,2)    NOT NULL DEFAULT 0.00,
  `payment_method`    VARCHAR(50)      NOT NULL DEFAULT 'pending',
  `insurance_selected` TINYINT(1)      NOT NULL DEFAULT 0,
  `insurance_amount`  DECIMAL(10,2)    NOT NULL DEFAULT 0.00,
  `modified_by`       INT UNSIGNED     DEFAULT NULL COMMENT 'user_id of last staff who edited',
  `comment`           TEXT             DEFAULT NULL,
  `is_deleted`        TINYINT(1)       NOT NULL DEFAULT 0,
  `created_date`      DATETIME         NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_date`     DATETIME         DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`order_id`),
  KEY `idx_order_status`    (`status`),
  KEY `idx_order_email`     (`email`),
  KEY `idx_order_deleted`   (`is_deleted`),
  KEY `idx_order_modified_by` (`modified_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ------------------------------------------------------------
-- 3. payments  (Stripe card payments)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `payments` (
  `payment_intent_id` VARCHAR(255)  NOT NULL,
  `stripe_payment_id` VARCHAR(255)  DEFAULT NULL,
  `amount_cents`      INT UNSIGNED  NOT NULL DEFAULT 0,
  `currency`          VARCHAR(10)   NOT NULL DEFAULT 'myr',
  `status`            VARCHAR(50)   NOT NULL DEFAULT 'pending',
  `created_at`        DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`payment_intent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ------------------------------------------------------------
-- 4. promo_code
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `promo_code` (
  `id`                  INT UNSIGNED    NOT NULL AUTO_INCREMENT,
  `code`                VARCHAR(100)    NOT NULL,
  `discount_type`       ENUM('percentage','amount') NOT NULL DEFAULT 'percentage',
  `discount_percentage` DECIMAL(5,2)   DEFAULT NULL,
  `discount_amount`     DECIMAL(10,2)  DEFAULT NULL,
  `validation_date`     DATE           DEFAULT NULL COMMENT 'Start date the code is valid',
  `expired_date`        DATE           DEFAULT NULL COMMENT 'Expiry date of the code',
  `is_deleted`          TINYINT(1)     NOT NULL DEFAULT 0,
  `created_date`        DATETIME       NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_date`       DATETIME       DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_promo_code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ------------------------------------------------------------
-- 5. service_management
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `service_management` (
  `id`           INT UNSIGNED   NOT NULL AUTO_INCREMENT,
  `service_type` VARCHAR(255)   NOT NULL,
  `base_price`   DECIMAL(10,2)  NOT NULL DEFAULT 0.00,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_service_type` (`service_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Default service types
DELETE FROM `service_management` WHERE `service_type` IN ('standard', 'express', 'document');

INSERT IGNORE INTO `service_management` (`service_type`, `base_price`) VALUES
  ('delivery', 24.00),
  ('storage',  20.00);

-- ------------------------------------------------------------
-- 6. message  (public contact/enquiry form)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `message` (
  `msg_id`        INT UNSIGNED  NOT NULL AUTO_INCREMENT,
  `email`         VARCHAR(255)  NOT NULL,
  `phone`         VARCHAR(20)   DEFAULT NULL,
  `subject`       VARCHAR(255)  DEFAULT NULL,
  `msg`           TEXT          NOT NULL,
  `status`        ENUM('new','read') NOT NULL DEFAULT 'new',
  `is_deleted`    TINYINT(1)    NOT NULL DEFAULT 0,
  `created_date`  DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_date` DATETIME      DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`msg_id`),
  KEY `idx_message_status`  (`status`),
  KEY `idx_message_deleted` (`is_deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ------------------------------------------------------------
-- 7. activity_log  (admin/staff audit trail)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `activity_log` (
  `log_id`        INT UNSIGNED  NOT NULL AUTO_INCREMENT,
  `order_id`      INT UNSIGNED  DEFAULT NULL,
  `user_id`       INT UNSIGNED  DEFAULT NULL,
  `username`      VARCHAR(100)  DEFAULT NULL,
  `action`        TEXT          NOT NULL,
  `modified_date` DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`log_id`),
  KEY `idx_log_order`  (`order_id`),
  KEY `idx_log_user`   (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ------------------------------------------------------------
-- 8. ai_knowledge_base  (RAG knowledge store for AI chat)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `ai_knowledge_base` (
  `id`       INT UNSIGNED  NOT NULL AUTO_INCREMENT,
  `category` VARCHAR(100)  NOT NULL,
  `title`    VARCHAR(255)  NOT NULL,
  `content`  TEXT          NOT NULL,
  `keywords` VARCHAR(500)  DEFAULT NULL,
  PRIMARY KEY (`id`),
  FULLTEXT KEY `ft_search` (`title`, `content`, `keywords`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT IGNORE INTO `ai_knowledge_base` (`category`, `title`, `content`, `keywords`) VALUES
('policy',     'Cancellation Policy',  'Orders can be cancelled within 30 minutes of placement by contacting support. After 30 minutes, cancellations are subject to a processing fee. Completed orders cannot be cancelled.',                                       'cancel cancellation refund fee'),
('policy',     'Refund Policy',        'Refunds are processed within 3-5 business days to the original payment method. Cash payments are refunded in person at our office. Partial refunds may apply for partially completed deliveries.',                       'refund money back payment return'),
('service',    'Express Delivery',     'Express delivery guarantees same-day delivery for orders placed before 2PM. Available within Kuching city limits. Surcharge of RM10 applies.',                                                                          'express fast same-day delivery urgent speed'),
('service',    'Standard Delivery',    'Standard delivery takes 1-3 business days depending on location in Sarawak. Available statewide including rural areas. Tracking is provided via order ID.',                                                             'standard delivery regular normal tracking'),
('service',    'Document Courier',     'Secure document courier service with signature upon delivery. Ideal for legal, government, and business documents. Confidentiality is guaranteed. Insurance available on request.',                                       'document courier legal government business secure'),
('faq',        'Payment Methods',      'We accept FPX online banking, Visa/Mastercard credit and debit cards via Stripe, and cash on delivery (COD). Promo codes can be applied at checkout for eligible services.',                                            'payment FPX card credit debit cash COD promo'),
('faq',        'Promo Codes',          'Promo codes provide discounts on eligible orders. Each code has an expiry date and may have usage limits. Apply the code at checkout. Codes cannot be combined and are non-transferable.',                               'promo code discount voucher coupon offer'),
('faq',        'Order Tracking',       'Track your order using the order ID provided at booking. Status updates: Pending (awaiting pickup), In Transit (on the way), Completed (delivered). Contact support if no update after 24 hours.',                      'track order status pending transit completed delivered'),
('operations', 'Operating Hours',      'EASE Sarawak operates Monday to Saturday, 8AM to 6PM. Sunday and public holidays are closed. Emergency services may be arranged outside hours for an additional fee.',                                                   'hours operating open close time schedule holiday'),
('operations', 'Service Areas',        'We serve all major towns in Sarawak including Kuching, Miri, Sibu, Bintulu, and surrounding areas. Remote area deliveries may incur additional charges and longer transit times.',                                       'area location coverage Kuching Miri Sibu Bintulu Sarawak');
