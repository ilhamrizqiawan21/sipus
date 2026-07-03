-- Adminer 5.3.0 MySQL 8.4.3 dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

SET NAMES utf8mb4;

DROP TABLE IF EXISTS `activity_logs`;
CREATE TABLE `activity_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `action` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `module` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `model_type` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `model_id` bigint unsigned DEFAULT NULL,
  `old_values` json DEFAULT NULL,
  `new_values` json DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_activity_logs_action` (`action`),
  KEY `idx_activity_logs_model` (`model_type`,`model_id`),
  KEY `fk_activity_logs_user_id` (`user_id`),
  CONSTRAINT `fk_activity_logs_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `authors`;
CREATE TABLE `authors` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `biography` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_authors_name` (`name`),
  KEY `fk_authors_created_by` (`created_by`),
  KEY `fk_authors_updated_by` (`updated_by`),
  CONSTRAINT `fk_authors_created_by` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_authors_updated_by` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `book_authors`;
CREATE TABLE `book_authors` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `book_id` bigint unsigned NOT NULL,
  `author_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_book_authors_book_author` (`book_id`,`author_id`),
  KEY `fk_book_authors_author_id` (`author_id`),
  CONSTRAINT `fk_book_authors_author_id` FOREIGN KEY (`author_id`) REFERENCES `authors` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_book_authors_book_id` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `book_conditions`;
CREATE TABLE `book_conditions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_book_conditions_name` (`name`),
  KEY `fk_book_conditions_created_by` (`created_by`),
  KEY `fk_book_conditions_updated_by` (`updated_by`),
  CONSTRAINT `fk_book_conditions_created_by` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_book_conditions_updated_by` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `book_conditions` (`id`, `name`, `description`, `is_active`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1,	'Baik',	'Kondisi fisik buku baik dan layak dipinjamkan',	1,	NULL,	NULL,	'2026-07-03 00:38:35',	'2026-07-03 00:38:35',	NULL),
(2,	'Rusak Ringan',	'Kerusakan minor, masih layak dipinjamkan',	1,	NULL,	NULL,	'2026-07-03 00:38:35',	'2026-07-03 00:38:35',	NULL),
(3,	'Rusak Berat',	'Kerusakan signifikan, perlu perbaikan sebelum dipinjamkan',	1,	NULL,	NULL,	'2026-07-03 00:38:35',	'2026-07-03 00:38:35',	NULL),
(4,	'Hilang',	'Buku dinyatakan hilang',	1,	NULL,	NULL,	'2026-07-03 00:38:35',	'2026-07-03 00:38:35',	NULL);

DROP TABLE IF EXISTS `book_copies`;
CREATE TABLE `book_copies` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `book_id` bigint unsigned NOT NULL,
  `inventory_code` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `barcode` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bookshelf_id` bigint unsigned DEFAULT NULL,
  `condition_id` bigint unsigned NOT NULL,
  `source_id` bigint unsigned NOT NULL,
  `status_id` bigint unsigned NOT NULL,
  `procurement_item_id` bigint unsigned DEFAULT NULL,
  `acquisition_date` date DEFAULT NULL,
  `acquisition_price` decimal(12,2) DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_book_copies_inventory_code` (`inventory_code`),
  UNIQUE KEY `uq_book_copies_barcode` (`barcode`),
  KEY `fk_book_copies_book_id` (`book_id`),
  KEY `fk_book_copies_bookshelf_id` (`bookshelf_id`),
  KEY `fk_book_copies_condition_id` (`condition_id`),
  KEY `fk_book_copies_source_id` (`source_id`),
  KEY `fk_book_copies_status_id` (`status_id`),
  KEY `fk_book_copies_procurement_item_id` (`procurement_item_id`),
  KEY `fk_book_copies_created_by` (`created_by`),
  KEY `fk_book_copies_updated_by` (`updated_by`),
  CONSTRAINT `fk_book_copies_book_id` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_book_copies_bookshelf_id` FOREIGN KEY (`bookshelf_id`) REFERENCES `bookshelves` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_book_copies_condition_id` FOREIGN KEY (`condition_id`) REFERENCES `book_conditions` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_book_copies_created_by` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_book_copies_procurement_item_id` FOREIGN KEY (`procurement_item_id`) REFERENCES `book_procurement_items` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_book_copies_source_id` FOREIGN KEY (`source_id`) REFERENCES `book_sources` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_book_copies_status_id` FOREIGN KEY (`status_id`) REFERENCES `book_copy_statuses` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_book_copies_updated_by` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `book_copy_statuses`;
CREATE TABLE `book_copy_statuses` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_book_copy_statuses_code` (`code`),
  KEY `fk_book_copy_statuses_created_by` (`created_by`),
  KEY `fk_book_copy_statuses_updated_by` (`updated_by`),
  CONSTRAINT `fk_book_copy_statuses_created_by` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_book_copy_statuses_updated_by` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `book_copy_statuses` (`id`, `code`, `name`, `is_active`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1,	'available',	'Tersedia',	1,	NULL,	NULL,	'2026-07-03 00:38:35',	'2026-07-03 00:38:35',	NULL),
(2,	'borrowed',	'Dipinjam',	1,	NULL,	NULL,	'2026-07-03 00:38:35',	'2026-07-03 00:38:35',	NULL),
(3,	'reserved',	'Dipesan',	1,	NULL,	NULL,	'2026-07-03 00:38:35',	'2026-07-03 00:38:35',	NULL),
(4,	'in_repair',	'Dalam Perbaikan',	1,	NULL,	NULL,	'2026-07-03 00:38:35',	'2026-07-03 00:38:35',	NULL),
(5,	'damaged',	'Rusak',	1,	NULL,	NULL,	'2026-07-03 00:38:35',	'2026-07-03 00:38:35',	NULL),
(6,	'lost',	'Hilang',	1,	NULL,	NULL,	'2026-07-03 00:38:35',	'2026-07-03 00:38:35',	NULL),
(7,	'withdrawn',	'Ditarik',	1,	NULL,	NULL,	'2026-07-03 00:38:35',	'2026-07-03 00:38:35',	NULL);

DROP TABLE IF EXISTS `book_incidents`;
CREATE TABLE `book_incidents` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `book_copy_id` bigint unsigned NOT NULL,
  `incident_type` enum('damaged','lost') COLLATE utf8mb4_unicode_ci NOT NULL,
  `incident_date` date NOT NULL,
  `related_borrowing_item_id` bigint unsigned DEFAULT NULL,
  `reported_by` bigint unsigned DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `resolution` text COLLATE utf8mb4_unicode_ci,
  `status` enum('reported','resolved') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'reported',
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_book_incidents_incident_date` (`incident_date`),
  KEY `fk_book_incidents_book_copy_id` (`book_copy_id`),
  KEY `fk_book_incidents_related_item_id` (`related_borrowing_item_id`),
  KEY `fk_book_incidents_reported_by` (`reported_by`),
  KEY `fk_book_incidents_created_by` (`created_by`),
  KEY `fk_book_incidents_updated_by` (`updated_by`),
  CONSTRAINT `fk_book_incidents_book_copy_id` FOREIGN KEY (`book_copy_id`) REFERENCES `book_copies` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_book_incidents_created_by` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_book_incidents_related_item_id` FOREIGN KEY (`related_borrowing_item_id`) REFERENCES `borrowing_items` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_book_incidents_reported_by` FOREIGN KEY (`reported_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_book_incidents_updated_by` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `book_procurement_items`;
CREATE TABLE `book_procurement_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `procurement_id` bigint unsigned NOT NULL,
  `book_id` bigint unsigned NOT NULL,
  `quantity` int unsigned NOT NULL,
  `price_per_unit` decimal(12,2) NOT NULL,
  `subtotal` decimal(14,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_book_procurement_items_procurement_id` (`procurement_id`),
  KEY `fk_book_procurement_items_book_id` (`book_id`),
  CONSTRAINT `fk_book_procurement_items_book_id` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_book_procurement_items_procurement_id` FOREIGN KEY (`procurement_id`) REFERENCES `book_procurements` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `book_procurements`;
CREATE TABLE `book_procurements` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `procurement_code` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `source_id` bigint unsigned NOT NULL,
  `supplier_name` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `invoice_number` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `procurement_date` date NOT NULL,
  `total_amount` decimal(14,2) NOT NULL DEFAULT '0.00',
  `processed_by` bigint unsigned DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_book_procurements_code` (`procurement_code`),
  KEY `fk_book_procurements_source_id` (`source_id`),
  KEY `fk_book_procurements_processed_by` (`processed_by`),
  KEY `fk_book_procurements_created_by` (`created_by`),
  KEY `fk_book_procurements_updated_by` (`updated_by`),
  CONSTRAINT `fk_book_procurements_created_by` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_book_procurements_processed_by` FOREIGN KEY (`processed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_book_procurements_source_id` FOREIGN KEY (`source_id`) REFERENCES `book_sources` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_book_procurements_updated_by` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `book_sources`;
CREATE TABLE `book_sources` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_book_sources_name` (`name`),
  KEY `fk_book_sources_created_by` (`created_by`),
  KEY `fk_book_sources_updated_by` (`updated_by`),
  CONSTRAINT `fk_book_sources_created_by` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_book_sources_updated_by` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `book_sources` (`id`, `name`, `description`, `is_active`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1,	'Pembelian',	'Pengadaan melalui pembelian oleh sekolah',	1,	NULL,	NULL,	'2026-07-03 00:38:35',	'2026-07-03 00:38:35',	NULL),
(2,	'Hibah / Sumbangan',	'Sumbangan dari pihak ketiga, wali murid, atau alumni',	1,	NULL,	NULL,	'2026-07-03 00:38:35',	'2026-07-03 00:38:35',	NULL),
(3,	'Bantuan Pemerintah',	'Bantuan dari Kemenag/Kemendikbud atau dinas terkait',	1,	NULL,	NULL,	'2026-07-03 00:38:35',	'2026-07-03 00:38:35',	NULL),
(4,	'Hasil Karya Siswa/Guru',	'Karya tulis internal yang didokumentasikan sebagai koleksi',	1,	NULL,	NULL,	'2026-07-03 00:38:35',	'2026-07-03 00:38:35',	NULL);

DROP TABLE IF EXISTS `books`;
CREATE TABLE `books` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `isbn` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subtitle` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category_id` bigint unsigned NOT NULL,
  `publisher_id` bigint unsigned DEFAULT NULL,
  `language_id` bigint unsigned DEFAULT NULL,
  `publication_year` smallint unsigned DEFAULT NULL,
  `edition` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pages` int unsigned DEFAULT NULL,
  `call_number` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `synopsis` text COLLATE utf8mb4_unicode_ci,
  `cover_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_books_isbn` (`isbn`),
  KEY `idx_books_title` (`title`),
  KEY `fk_books_category_id` (`category_id`),
  KEY `fk_books_publisher_id` (`publisher_id`),
  KEY `fk_books_language_id` (`language_id`),
  KEY `fk_books_created_by` (`created_by`),
  KEY `fk_books_updated_by` (`updated_by`),
  CONSTRAINT `fk_books_category_id` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_books_created_by` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_books_language_id` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_books_publisher_id` FOREIGN KEY (`publisher_id`) REFERENCES `publishers` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_books_updated_by` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `bookshelves`;
CREATE TABLE `bookshelves` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `location` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `capacity` int unsigned DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_bookshelves_code` (`code`),
  KEY `fk_bookshelves_created_by` (`created_by`),
  KEY `fk_bookshelves_updated_by` (`updated_by`),
  CONSTRAINT `fk_bookshelves_created_by` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_bookshelves_updated_by` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `borrowing_items`;
CREATE TABLE `borrowing_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `borrowing_transaction_id` bigint unsigned NOT NULL,
  `book_copy_id` bigint unsigned NOT NULL,
  `book_title_snapshot` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `inventory_code_snapshot` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `condition_at_borrow_id` bigint unsigned NOT NULL,
  `due_date` date NOT NULL,
  `return_date` date DEFAULT NULL,
  `condition_at_return_id` bigint unsigned DEFAULT NULL,
  `status` enum('borrowed','returned','lost','damaged') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'borrowed',
  `fine_amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `returned_by` bigint unsigned DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_borrowing_items_due_date` (`due_date`),
  KEY `idx_borrowing_items_status` (`status`),
  KEY `fk_borrowing_items_transaction_id` (`borrowing_transaction_id`),
  KEY `fk_borrowing_items_book_copy_id` (`book_copy_id`),
  KEY `fk_borrowing_items_condition_at_borrow_id` (`condition_at_borrow_id`),
  KEY `fk_borrowing_items_condition_at_return_id` (`condition_at_return_id`),
  KEY `fk_borrowing_items_returned_by` (`returned_by`),
  KEY `fk_borrowing_items_created_by` (`created_by`),
  KEY `fk_borrowing_items_updated_by` (`updated_by`),
  CONSTRAINT `fk_borrowing_items_book_copy_id` FOREIGN KEY (`book_copy_id`) REFERENCES `book_copies` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_borrowing_items_condition_at_borrow_id` FOREIGN KEY (`condition_at_borrow_id`) REFERENCES `book_conditions` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_borrowing_items_condition_at_return_id` FOREIGN KEY (`condition_at_return_id`) REFERENCES `book_conditions` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_borrowing_items_created_by` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_borrowing_items_returned_by` FOREIGN KEY (`returned_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_borrowing_items_transaction_id` FOREIGN KEY (`borrowing_transaction_id`) REFERENCES `borrowing_transactions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_borrowing_items_updated_by` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `borrowing_transactions`;
CREATE TABLE `borrowing_transactions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `transaction_code` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `member_id` bigint unsigned NOT NULL,
  `member_code_snapshot` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `member_name_snapshot` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `member_class_snapshot` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `member_type_snapshot` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `borrow_date` date NOT NULL,
  `due_date` date NOT NULL,
  `status` enum('borrowed','partially_returned','returned','overdue') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'borrowed',
  `processed_by` bigint unsigned DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_borrowing_transactions_code` (`transaction_code`),
  KEY `idx_borrowing_transactions_borrow_date` (`borrow_date`),
  KEY `idx_borrowing_transactions_status` (`status`),
  KEY `fk_borrowing_transactions_member_id` (`member_id`),
  KEY `fk_borrowing_transactions_processed_by` (`processed_by`),
  KEY `fk_borrowing_transactions_created_by` (`created_by`),
  KEY `fk_borrowing_transactions_updated_by` (`updated_by`),
  CONSTRAINT `fk_borrowing_transactions_created_by` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_borrowing_transactions_member_id` FOREIGN KEY (`member_id`) REFERENCES `members` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_borrowing_transactions_processed_by` FOREIGN KEY (`processed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_borrowing_transactions_updated_by` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `parent_id` bigint unsigned DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_categories_code` (`code`),
  KEY `idx_categories_name` (`name`),
  KEY `fk_categories_parent_id` (`parent_id`),
  KEY `fk_categories_created_by` (`created_by`),
  KEY `fk_categories_updated_by` (`updated_by`),
  CONSTRAINT `fk_categories_created_by` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_categories_parent_id` FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_categories_updated_by` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `categories` (`id`, `name`, `code`, `parent_id`, `description`, `is_active`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1,	'Karya Umum',	'000',	NULL,	NULL,	1,	NULL,	NULL,	'2026-07-03 00:38:35',	'2026-07-03 00:38:35',	NULL),
(2,	'Filsafat',	'100',	NULL,	NULL,	1,	NULL,	NULL,	'2026-07-03 00:38:35',	'2026-07-03 00:38:35',	NULL),
(3,	'Agama',	'200',	NULL,	NULL,	1,	NULL,	NULL,	'2026-07-03 00:38:35',	'2026-07-03 00:38:35',	NULL),
(4,	'Ilmu Sosial',	'300',	NULL,	NULL,	1,	NULL,	NULL,	'2026-07-03 00:38:35',	'2026-07-03 00:38:35',	NULL),
(5,	'Bahasa',	'400',	NULL,	NULL,	1,	NULL,	NULL,	'2026-07-03 00:38:35',	'2026-07-03 00:38:35',	NULL),
(6,	'Ilmu Murni',	'500',	NULL,	NULL,	1,	NULL,	NULL,	'2026-07-03 00:38:35',	'2026-07-03 00:38:35',	NULL),
(7,	'Ilmu Terapan',	'600',	NULL,	NULL,	1,	NULL,	NULL,	'2026-07-03 00:38:35',	'2026-07-03 00:38:35',	NULL),
(8,	'Kesenian & Olahraga',	'700',	NULL,	NULL,	1,	NULL,	NULL,	'2026-07-03 00:38:35',	'2026-07-03 00:38:35',	NULL),
(9,	'Kesusastraan',	'800',	NULL,	NULL,	1,	NULL,	NULL,	'2026-07-03 00:38:35',	'2026-07-03 00:38:35',	NULL),
(10,	'Sejarah & Geografi',	'900',	NULL,	NULL,	1,	NULL,	NULL,	'2026-07-03 00:38:35',	'2026-07-03 00:38:35',	NULL),
(11,	'Fiksi',	'FIC',	NULL,	NULL,	1,	NULL,	NULL,	'2026-07-03 00:38:35',	'2026-07-03 00:38:35',	NULL),
(12,	'Referensi',	'REF',	NULL,	NULL,	1,	NULL,	NULL,	'2026-07-03 00:38:35',	'2026-07-03 00:38:35',	NULL);

DROP TABLE IF EXISTS `classes`;
CREATE TABLE `classes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `grade_level` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `academic_year` varchar(9) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_classes_name` (`name`),
  KEY `fk_classes_created_by` (`created_by`),
  KEY `fk_classes_updated_by` (`updated_by`),
  CONSTRAINT `fk_classes_created_by` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_classes_updated_by` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `fines`;
CREATE TABLE `fines` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `borrowing_item_id` bigint unsigned NOT NULL,
  `fine_type` enum('late','damage','lost') COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `reason` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('unpaid','paid','waived') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'unpaid',
  `paid_amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `paid_date` date DEFAULT NULL,
  `waived_by` bigint unsigned DEFAULT NULL,
  `waived_reason` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_fines_status` (`status`),
  KEY `fk_fines_borrowing_item_id` (`borrowing_item_id`),
  KEY `fk_fines_waived_by` (`waived_by`),
  KEY `fk_fines_created_by` (`created_by`),
  KEY `fk_fines_updated_by` (`updated_by`),
  CONSTRAINT `fk_fines_borrowing_item_id` FOREIGN KEY (`borrowing_item_id`) REFERENCES `borrowing_items` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_fines_created_by` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_fines_updated_by` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_fines_waived_by` FOREIGN KEY (`waived_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `import_logs`;
CREATE TABLE `import_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `import_type` enum('member','book') COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_rows` int unsigned NOT NULL DEFAULT '0',
  `success_rows` int unsigned NOT NULL DEFAULT '0',
  `failed_rows` int unsigned NOT NULL DEFAULT '0',
  `error_details` json DEFAULT NULL,
  `imported_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_import_logs_type` (`import_type`),
  KEY `fk_import_logs_imported_by` (`imported_by`),
  CONSTRAINT `fk_import_logs_imported_by` FOREIGN KEY (`imported_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `languages`;
CREATE TABLE `languages` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_languages_name` (`name`),
  KEY `fk_languages_created_by` (`created_by`),
  KEY `fk_languages_updated_by` (`updated_by`),
  CONSTRAINT `fk_languages_created_by` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_languages_updated_by` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `languages` (`id`, `name`, `code`, `is_active`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1,	'Indonesia',	'ID',	1,	NULL,	NULL,	'2026-07-03 00:38:35',	'2026-07-03 00:38:35',	NULL),
(2,	'English',	'EN',	1,	NULL,	NULL,	'2026-07-03 00:38:35',	'2026-07-03 00:38:35',	NULL),
(3,	'Arab',	'AR',	1,	NULL,	NULL,	'2026-07-03 00:38:35',	'2026-07-03 00:38:35',	NULL);

DROP TABLE IF EXISTS `member_types`;
CREATE TABLE `member_types` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `borrow_limit` int unsigned NOT NULL DEFAULT '2',
  `borrow_duration_days` int unsigned NOT NULL DEFAULT '7',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_member_types_name` (`name`),
  KEY `fk_member_types_created_by` (`created_by`),
  KEY `fk_member_types_updated_by` (`updated_by`),
  CONSTRAINT `fk_member_types_created_by` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_member_types_updated_by` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `member_types` (`id`, `name`, `borrow_limit`, `borrow_duration_days`, `is_active`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1,	'Siswa',	2,	7,	1,	NULL,	NULL,	'2026-07-03 00:38:35',	'2026-07-03 00:38:35',	NULL),
(2,	'Guru',	5,	14,	1,	NULL,	NULL,	'2026-07-03 00:38:35',	'2026-07-03 00:38:35',	NULL),
(3,	'Staff',	3,	7,	1,	NULL,	NULL,	'2026-07-03 00:38:35',	'2026-07-03 00:38:35',	NULL);

DROP TABLE IF EXISTS `members`;
CREATE TABLE `members` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `member_code` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nis_nisn` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nip` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `member_type_id` bigint unsigned NOT NULL,
  `class_id` bigint unsigned DEFAULT NULL,
  `name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gender` enum('L','P') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `card_number` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `join_date` date DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_members_member_code` (`member_code`),
  UNIQUE KEY `uq_members_card_number` (`card_number`),
  KEY `idx_members_name` (`name`),
  KEY `idx_members_nis_nisn` (`nis_nisn`),
  KEY `idx_members_nip` (`nip`),
  KEY `fk_members_member_type_id` (`member_type_id`),
  KEY `fk_members_class_id` (`class_id`),
  KEY `fk_members_created_by` (`created_by`),
  KEY `fk_members_updated_by` (`updated_by`),
  CONSTRAINT `fk_members_class_id` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_members_created_by` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_members_member_type_id` FOREIGN KEY (`member_type_id`) REFERENCES `member_types` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_members_updated_by` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `publishers`;
CREATE TABLE `publishers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_publishers_name` (`name`),
  KEY `fk_publishers_created_by` (`created_by`),
  KEY `fk_publishers_updated_by` (`updated_by`),
  CONSTRAINT `fk_publishers_created_by` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_publishers_updated_by` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `settings`;
CREATE TABLE `settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci,
  `type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'string',
  `group` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_settings_key` (`key`),
  KEY `fk_settings_updated_by` (`updated_by`),
  CONSTRAINT `fk_settings_updated_by` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `settings` (`id`, `key`, `value`, `type`, `group`, `description`, `updated_by`, `created_at`, `updated_at`) VALUES
(1,	'library_name',	'Perpustakaan MTs Al-Ihsan Batujajar',	'string',	'general',	'Nama perpustakaan yang tampil di header/cetakan',	NULL,	'2026-07-03 00:38:35',	'2026-07-03 00:38:35'),
(2,	'library_address',	'Batujajar, Kabupaten Bandung Barat, Jawa Barat',	'string',	'general',	'Alamat perpustakaan',	NULL,	'2026-07-03 00:38:35',	'2026-07-03 00:38:35'),
(3,	'academic_year_active',	'2025/2026',	'string',	'general',	'Tahun ajaran aktif saat ini',	NULL,	'2026-07-03 00:38:35',	'2026-07-03 00:38:35'),
(4,	'default_borrow_duration_days',	'7',	'integer',	'circulation',	'Lama peminjaman default jika member_type tidak mengatur khusus',	NULL,	'2026-07-03 00:38:35',	'2026-07-03 00:38:35'),
(5,	'fine_per_day',	'500',	'integer',	'circulation',	'Denda keterlambatan per hari (Rupiah)',	NULL,	'2026-07-03 00:38:35',	'2026-07-03 00:38:35'),
(6,	'max_renewal_count',	'1',	'integer',	'circulation',	'Maksimal perpanjangan per item peminjaman',	NULL,	'2026-07-03 00:38:35',	'2026-07-03 00:38:35');

DROP TABLE IF EXISTS `stock_opname_details`;
CREATE TABLE `stock_opname_details` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `stock_opname_id` bigint unsigned NOT NULL,
  `book_copy_id` bigint unsigned NOT NULL,
  `system_status_id` bigint unsigned NOT NULL,
  `physical_status_id` bigint unsigned NOT NULL,
  `condition_found_id` bigint unsigned NOT NULL,
  `notes` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_stock_opname_details_opname_id` (`stock_opname_id`),
  KEY `fk_stock_opname_details_book_copy_id` (`book_copy_id`),
  KEY `fk_stock_opname_details_system_status_id` (`system_status_id`),
  KEY `fk_stock_opname_details_physical_status_id` (`physical_status_id`),
  KEY `fk_stock_opname_details_condition_found_id` (`condition_found_id`),
  CONSTRAINT `fk_stock_opname_details_book_copy_id` FOREIGN KEY (`book_copy_id`) REFERENCES `book_copies` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_stock_opname_details_condition_found_id` FOREIGN KEY (`condition_found_id`) REFERENCES `book_conditions` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_stock_opname_details_opname_id` FOREIGN KEY (`stock_opname_id`) REFERENCES `stock_opnames` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_stock_opname_details_physical_status_id` FOREIGN KEY (`physical_status_id`) REFERENCES `book_copy_statuses` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_stock_opname_details_system_status_id` FOREIGN KEY (`system_status_id`) REFERENCES `book_copy_statuses` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `stock_opnames`;
CREATE TABLE `stock_opnames` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `opname_code` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `opname_date` date NOT NULL,
  `status` enum('in_progress','completed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'in_progress',
  `conducted_by` bigint unsigned DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_stock_opnames_code` (`opname_code`),
  KEY `fk_stock_opnames_conducted_by` (`conducted_by`),
  KEY `fk_stock_opnames_created_by` (`created_by`),
  KEY `fk_stock_opnames_updated_by` (`updated_by`),
  CONSTRAINT `fk_stock_opnames_conducted_by` FOREIGN KEY (`conducted_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_stock_opnames_created_by` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_stock_opnames_updated_by` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `employee_id` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `last_login_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_users_email` (`email`),
  KEY `idx_users_name` (`name`),
  KEY `fk_users_created_by` (`created_by`),
  KEY `fk_users_updated_by` (`updated_by`),
  CONSTRAINT `fk_users_created_by` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_users_updated_by` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP VIEW IF EXISTS `v_active_borrowings`;
CREATE TABLE `v_active_borrowings` (`borrowing_item_id` bigint unsigned, `transaction_code` varchar(30), `member_name_snapshot` varchar(150), `member_class_snapshot` varchar(50), `book_title_snapshot` varchar(255), `inventory_code_snapshot` varchar(30), `borrow_date` date, `due_date` date, `status` enum('borrowed','returned','lost','damaged'), `is_overdue` int, `days_overdue` int);


DROP VIEW IF EXISTS `v_book_availability`;
CREATE TABLE `v_book_availability` (`book_id` bigint unsigned, `title` varchar(255), `isbn` varchar(20), `category_name` varchar(150), `total_copies` bigint, `available_copies` decimal(23,0), `borrowed_copies` decimal(23,0));


DROP TABLE IF EXISTS `visit_purposes`;
CREATE TABLE `visit_purposes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_visit_purposes_name` (`name`),
  KEY `fk_visit_purposes_created_by` (`created_by`),
  KEY `fk_visit_purposes_updated_by` (`updated_by`),
  CONSTRAINT `fk_visit_purposes_created_by` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_visit_purposes_updated_by` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `visit_purposes` (`id`, `name`, `is_active`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1,	'Membaca di Tempat',	1,	NULL,	NULL,	'2026-07-03 00:38:35',	'2026-07-03 00:38:35',	NULL),
(2,	'Meminjam Buku',	1,	NULL,	NULL,	'2026-07-03 00:38:35',	'2026-07-03 00:38:35',	NULL),
(3,	'Mengembalikan Buku',	1,	NULL,	NULL,	'2026-07-03 00:38:35',	'2026-07-03 00:38:35',	NULL),
(4,	'Mengerjakan Tugas',	1,	NULL,	NULL,	'2026-07-03 00:38:35',	'2026-07-03 00:38:35',	NULL),
(5,	'Diskusi Kelompok',	1,	NULL,	NULL,	'2026-07-03 00:38:35',	'2026-07-03 00:38:35',	NULL),
(6,	'Lainnya',	1,	NULL,	NULL,	'2026-07-03 00:38:35',	'2026-07-03 00:38:35',	NULL);

DROP TABLE IF EXISTS `visitors`;
CREATE TABLE `visitors` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `member_id` bigint unsigned DEFAULT NULL,
  `guest_name` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `visit_purpose_id` bigint unsigned NOT NULL,
  `visit_date` date NOT NULL,
  `check_in_time` time DEFAULT NULL,
  `check_out_time` time DEFAULT NULL,
  `notes` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_visitors_visit_date` (`visit_date`),
  KEY `fk_visitors_member_id` (`member_id`),
  KEY `fk_visitors_visit_purpose_id` (`visit_purpose_id`),
  KEY `fk_visitors_created_by` (`created_by`),
  KEY `fk_visitors_updated_by` (`updated_by`),
  CONSTRAINT `fk_visitors_created_by` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_visitors_member_id` FOREIGN KEY (`member_id`) REFERENCES `members` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_visitors_updated_by` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_visitors_visit_purpose_id` FOREIGN KEY (`visit_purpose_id`) REFERENCES `visit_purposes` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `v_active_borrowings`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `v_active_borrowings` AS select `bi`.`id` AS `borrowing_item_id`,`bt`.`transaction_code` AS `transaction_code`,`bt`.`member_name_snapshot` AS `member_name_snapshot`,`bt`.`member_class_snapshot` AS `member_class_snapshot`,`bi`.`book_title_snapshot` AS `book_title_snapshot`,`bi`.`inventory_code_snapshot` AS `inventory_code_snapshot`,`bt`.`borrow_date` AS `borrow_date`,`bi`.`due_date` AS `due_date`,`bi`.`status` AS `status`,(case when ((`bi`.`status` = 'borrowed') and (`bi`.`due_date` < curdate())) then 1 else 0 end) AS `is_overdue`,(to_days(curdate()) - to_days(`bi`.`due_date`)) AS `days_overdue` from (`borrowing_items` `bi` join `borrowing_transactions` `bt` on((`bt`.`id` = `bi`.`borrowing_transaction_id`))) where (`bi`.`status` = 'borrowed');

DROP TABLE IF EXISTS `v_book_availability`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `v_book_availability` AS select `b`.`id` AS `book_id`,`b`.`title` AS `title`,`b`.`isbn` AS `isbn`,`c`.`name` AS `category_name`,count(`bc`.`id`) AS `total_copies`,sum((case when (`bcs`.`code` = 'available') then 1 else 0 end)) AS `available_copies`,sum((case when (`bcs`.`code` = 'borrowed') then 1 else 0 end)) AS `borrowed_copies` from (((`books` `b` left join `book_copies` `bc` on(((`bc`.`book_id` = `b`.`id`) and (`bc`.`deleted_at` is null)))) left join `book_copy_statuses` `bcs` on((`bcs`.`id` = `bc`.`status_id`))) left join `categories` `c` on((`c`.`id` = `b`.`category_id`))) where (`b`.`deleted_at` is null) group by `b`.`id`,`b`.`title`,`b`.`isbn`,`c`.`name`;

-- 2026-07-03 00:38:52 UTC
