-- ============================================================
-- Table: `order_booking`  (new table — created by Migration 2)
-- ============================================================
-- Purpose: Stores structured / indexed booking details split
--          out of order.order_details_json for fast querying.
--          One row per order (1-to-1 with `order`).
--          booking_json holds the raw wizard payload;
--          dropoff_at / pickup_at / origin / destination /
--          storage_location / quantity are extracted columns
--          so they can be indexed without JSON parsing.
-- ============================================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- --------------------------------------------------------

CREATE TABLE `order_booking` (
  `order_id`         int(11) UNSIGNED  NOT NULL,
  `booking_json`     json              DEFAULT NULL,
  `dropoff_at`       datetime          DEFAULT NULL,
  `pickup_at`        datetime          DEFAULT NULL,
  `origin`           varchar(255)      DEFAULT NULL,
  `destination`      varchar(255)      DEFAULT NULL,
  `storage_location` varchar(255)      DEFAULT NULL,
  `quantity`         tinyint(3) UNSIGNED NOT NULL DEFAULT 1,
  `created_at`       datetime          DEFAULT NULL,
  `updated_at`       datetime          DEFAULT NULL,
  PRIMARY KEY (`order_id`),
  KEY `idx_ob_dropoff` (`dropoff_at`),
  KEY `idx_ob_pickup`  (`pickup_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
