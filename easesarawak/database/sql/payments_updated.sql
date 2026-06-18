-- ============================================================
-- Table: `payments`  (updated schema — reflects all migrations)
-- ============================================================
-- Changes applied vs payments.sql:
--   [Migration 1] order_id INT(11) NULL added after payment_intent_id
--                 KEY payments_order_id (order_id)
--   [Migration 2] KEY idx_payments_order_status (order_id, status)
--                 KEY idx_payments_created (created_at)
-- ============================================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- --------------------------------------------------------

CREATE TABLE `payments` (
  `payment_intent_id` varchar(255)  NOT NULL,
  `order_id`          int(11)       DEFAULT NULL,
  `stripe_payment_id` varchar(255)  DEFAULT NULL,
  `amount_cents`      int(11)       NOT NULL DEFAULT 0,
  `currency`          varchar(10)   NOT NULL DEFAULT 'myr',
  `status`            varchar(50)   NOT NULL DEFAULT 'unknown',
  `created_at`        timestamp     NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`payment_intent_id`),
  KEY `stripe_payment_id`          (`stripe_payment_id`),
  KEY `payments_order_id`          (`order_id`),
  KEY `idx_payments_order_status`  (`order_id`, `status`),
  KEY `idx_payments_created`       (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
