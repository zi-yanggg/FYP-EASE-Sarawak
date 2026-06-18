-- ============================================================
-- Table: `order_customer`  (new table — created by Migration 2)
-- ============================================================
-- Purpose: Stores customer contact details split out of the
--          main `order` table. One row per order (1-to-1).
--          Allows customer data to be queried independently
--          without loading the full order row.
--          phone and social_num are VARCHAR here (never INT).
-- ============================================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- --------------------------------------------------------

CREATE TABLE `order_customer` (
  `order_id`     int(11) UNSIGNED  NOT NULL,
  `first_name`   varchar(255)      NOT NULL,
  `last_name`    varchar(255)      NOT NULL,
  `id_num`       varchar(50)       NOT NULL,
  `email`        varchar(255)      NOT NULL,
  `phone`        varchar(20)       NOT NULL DEFAULT '',
  `social`       int(11)           NOT NULL DEFAULT 0,
  `social_num`   varchar(100)      DEFAULT NULL,
  `upload`       text              DEFAULT NULL,
  `special`      tinyint(1)        NOT NULL DEFAULT 0,
  `special_note` text              DEFAULT NULL,
  `created_at`   datetime          DEFAULT NULL,
  `updated_at`   datetime          DEFAULT NULL,
  PRIMARY KEY (`order_id`),
  KEY `idx_oc_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
