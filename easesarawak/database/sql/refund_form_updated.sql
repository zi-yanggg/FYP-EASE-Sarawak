-- ============================================================
-- Table: `refund_form`  (updated schema — reflects all migrations)
-- ============================================================
-- Changes applied vs refund_form.sql:
--   [Migration 1] access_token VARCHAR(64) NULL added after pdf_path
--   [Migration 2] order_id VARCHAR(100) → INT UNSIGNED NOT NULL
--                 KEY idx_refund_status_created (status_progress, created_at)
--                 KEY idx_refund_order (order_id)
--                 KEY idx_refund_email (email)
--                 KEY idx_refund_access_token (access_token)
-- ============================================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- --------------------------------------------------------

CREATE TABLE `refund_form` (
  `id`                  int(11)        NOT NULL AUTO_INCREMENT,
  `full_name`           varchar(255)   NOT NULL,
  `email`               varchar(255)   NOT NULL,
  `phone_number`        varchar(50)    NOT NULL,
  `order_id`            int(10) UNSIGNED NOT NULL,
  `date_of_purchase`    date           NOT NULL,
  `service_type`        enum('Town Delivery','Luggage Storage') NOT NULL,
  `bank_name`           varchar(255)   DEFAULT NULL,
  `account_holder_name` varchar(255)   DEFAULT NULL,
  `account_number`      varchar(255)   DEFAULT NULL,
  `status_progress`     tinyint(1)     NOT NULL DEFAULT 0,
  `status_updated_by`   int(11)        DEFAULT NULL,
  `status_updated_at`   datetime       DEFAULT NULL,
  `reason_for_refund`   text           DEFAULT NULL,
  `declaration`         tinyint(1)     NOT NULL,
  `pdf_path`            varchar(500)   DEFAULT NULL,
  `access_token`        varchar(64)    DEFAULT NULL,
  `created_at`          timestamp      NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_refund_status_created` (`status_progress`, `created_at`),
  KEY `idx_refund_order`          (`order_id`),
  KEY `idx_refund_email`          (`email`),
  KEY `idx_refund_access_token`   (`access_token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

ALTER TABLE `refund_form`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
