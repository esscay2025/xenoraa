-- ============================================================
-- POS Module Migration for Production MySQL
-- Run this on the xenoraa MySQL database
-- ============================================================

-- POS Sessions
CREATE TABLE IF NOT EXISTS `pos_sessions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tenant_id` bigint unsigned NOT NULL,
  `cashier_id` bigint unsigned NOT NULL,
  `session_number` varchar(20) NOT NULL,
  `status` enum('open','closed') NOT NULL DEFAULT 'open',
  `opening_cash` decimal(12,2) NOT NULL DEFAULT '0.00',
  `closing_cash` decimal(12,2) DEFAULT NULL,
  `expected_cash` decimal(12,2) DEFAULT NULL,
  `cash_difference` decimal(12,2) DEFAULT NULL,
  `total_orders` int NOT NULL DEFAULT '0',
  `total_sales` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_discount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_tax` decimal(12,2) NOT NULL DEFAULT '0.00',
  `notes` text,
  `opened_at` timestamp NULL DEFAULT NULL,
  `closed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pos_sessions_session_number_unique` (`session_number`),
  KEY `pos_sessions_tenant_id_status_index` (`tenant_id`,`status`),
  KEY `pos_sessions_cashier_id_index` (`cashier_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- POS Orders
CREATE TABLE IF NOT EXISTS `pos_orders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tenant_id` bigint unsigned NOT NULL,
  `session_id` bigint unsigned DEFAULT NULL,
  `cashier_id` bigint unsigned NOT NULL,
  `order_number` varchar(30) NOT NULL,
  `status` enum('completed','refunded','void') NOT NULL DEFAULT 'completed',
  `customer_name` varchar(150) DEFAULT NULL,
  `customer_phone` varchar(20) DEFAULT NULL,
  `customer_email` varchar(150) DEFAULT NULL,
  `subtotal` decimal(12,2) NOT NULL DEFAULT '0.00',
  `discount_amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `discount_type` varchar(10) NOT NULL DEFAULT 'fixed',
  `discount_value` decimal(10,2) NOT NULL DEFAULT '0.00',
  `tax_rate` decimal(5,2) NOT NULL DEFAULT '0.00',
  `tax_amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total` decimal(12,2) NOT NULL DEFAULT '0.00',
  `amount_paid` decimal(12,2) NOT NULL DEFAULT '0.00',
  `change_due` decimal(12,2) NOT NULL DEFAULT '0.00',
  `payment_method` enum('cash','card','upi','split') NOT NULL DEFAULT 'cash',
  `cash_paid` decimal(12,2) NOT NULL DEFAULT '0.00',
  `card_paid` decimal(12,2) NOT NULL DEFAULT '0.00',
  `upi_paid` decimal(12,2) NOT NULL DEFAULT '0.00',
  `upi_reference` varchar(100) DEFAULT NULL,
  `card_reference` varchar(100) DEFAULT NULL,
  `notes` text,
  `refund_reason` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pos_orders_order_number_unique` (`order_number`),
  KEY `pos_orders_tenant_id_status_index` (`tenant_id`,`status`),
  KEY `pos_orders_session_id_index` (`session_id`),
  KEY `pos_orders_created_at_index` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- POS Order Items
CREATE TABLE IF NOT EXISTS `pos_order_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `pos_order_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned DEFAULT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_sku` varchar(100) DEFAULT NULL,
  `unit_price` decimal(12,2) NOT NULL,
  `sale_price` decimal(12,2) DEFAULT NULL,
  `quantity` int NOT NULL,
  `discount_amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `tax_rate` decimal(5,2) NOT NULL DEFAULT '0.00',
  `tax_amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `line_total` decimal(12,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pos_order_items_pos_order_id_index` (`pos_order_id`),
  KEY `pos_order_items_product_id_index` (`product_id`),
  CONSTRAINT `pos_order_items_pos_order_id_foreign` FOREIGN KEY (`pos_order_id`) REFERENCES `pos_orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Record migration in migrations table
INSERT IGNORE INTO `migrations` (`migration`, `batch`)
SELECT '2026_06_07_500001_create_pos_tables', IFNULL(MAX(batch), 0) + 1
FROM `migrations`
WHERE migration NOT LIKE '%pos%';
