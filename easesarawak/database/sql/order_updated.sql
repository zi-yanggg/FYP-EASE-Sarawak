-- ============================================================
-- Table: `order`  (updated schema — reflects all migrations)
-- ============================================================
-- Changes applied vs order.sql:
--   [Migration 3] phone       INT        → VARCHAR(20)  NOT NULL DEFAULT ''
--   [Migration 3] social_num  INT        → VARCHAR(100) NOT NULL DEFAULT ''
--   [Migration 3] special_note INT NULL  → TEXT NULL
--   [Migration 5] order_details_json LONGTEXT(utf8mb4_bin) → JSON
--   [Migration 2] Composite indexes added (idx_order_list, idx_order_status,
--                 idx_order_service, idx_order_email)
-- ============================================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- --------------------------------------------------------

CREATE TABLE `order` (
  `order_id`          int           NOT NULL AUTO_INCREMENT,
  `service_type`      varchar(255)  COLLATE utf8mb4_unicode_ci NOT NULL,
  `first_name`        varchar(255)  COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name`         varchar(255)  COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_num`            varchar(50)   COLLATE utf8mb4_unicode_ci NOT NULL,
  `email`             varchar(255)  COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone`             varchar(20)   COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `social`            int           NOT NULL DEFAULT '0',
  `social_num`        varchar(100)  COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `upload`            varchar(255)  COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `special`           int           DEFAULT NULL,
  `special_note`      text          COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order_details_json` json         NOT NULL,
  `promo_code`        varchar(255)  COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `status`            tinyint       NOT NULL DEFAULT '0',
  `amount`            int           NOT NULL DEFAULT '0',
  `payment_method`    varchar(255)  COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `is_deleted`        tinyint       NOT NULL DEFAULT '0',
  `created_date`      datetime      NOT NULL,
  `modified_date`     datetime      DEFAULT NULL,
  `insurance_selected` tinyint(1)  NOT NULL DEFAULT '0',
  `insurance_amount`  decimal(10,2) NOT NULL DEFAULT '0.00',
  `modified_by`       varchar(255)  COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `comment`           text          COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`order_id`),
  KEY `idx_order_list`    (`is_deleted`, `created_date`),
  KEY `idx_order_status`  (`is_deleted`, `status`, `created_date`),
  KEY `idx_order_service` (`is_deleted`, `service_type`, `created_date`),
  KEY `idx_order_email`   (`email`, `is_deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `order`
  MODIFY `order_id` int NOT NULL AUTO_INCREMENT;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
