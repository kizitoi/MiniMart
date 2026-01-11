-- phpMyAdmin SQL Dump
-- version 
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jan 11, 2026 at 03:44 AM
-- Server version: 8.4.3-3
-- PHP Version: 8.4.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ikapel_minimart`
--

-- --------------------------------------------------------

--
-- Table structure for table `barcode_label_sizes`
--

CREATE TABLE `barcode_label_sizes` (
  `id` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `width_mm` float NOT NULL,
  `height_mm` float NOT NULL,
  `user_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `branches`
--

CREATE TABLE `branches` (
  `id` int NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bulksms_settings`
--

CREATE TABLE `bulksms_settings` (
  `id` int NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `api_url` text NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cancellation_reason`
--

CREATE TABLE `cancellation_reason` (
  `id` int NOT NULL,
  `reason` varchar(255) NOT NULL,
  `reason_for` enum('driver','customer') NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `chats`
--

CREATE TABLE `chats` (
  `chat_id` int NOT NULL,
  `sender_id` int NOT NULL,
  `receiver_id` int NOT NULL,
  `message` text NOT NULL,
  `timestamp` datetime DEFAULT CURRENT_TIMESTAMP,
  `is_read` tinyint(1) DEFAULT '0',
  `seen` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `client_reports`
--

CREATE TABLE `client_reports` (
  `id` int NOT NULL,
  `report_title` varchar(255) NOT NULL,
  `client_id` int NOT NULL,
  `incident_id` int NOT NULL,
  `report_date` date NOT NULL,
  `report_time` time NOT NULL,
  `created_by` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `companies`
--

CREATE TABLE `companies` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `branch_id` int DEFAULT NULL,
  `address` text,
  `town_id` int DEFAULT NULL,
  `county_id` int DEFAULT NULL,
  `country_id` int DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `website` varchar(100) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `receipt_thank_you` text,
  `receipt_footer` text,
  `qr_code_link` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `counties`
--

CREATE TABLE `counties` (
  `id` int NOT NULL,
  `name` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

CREATE TABLE `countries` (
  `id` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `country_code` char(2) NOT NULL,
  `currency` varchar(100) NOT NULL,
  `currency_code` char(3) NOT NULL,
  `exchange_rate` decimal(10,4) NOT NULL COMMENT 'Exchange rate vs 1 USD',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `min_balance` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `customer_id` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `address` text,
  `phone` varchar(20) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `points` int NOT NULL DEFAULT '0',
  `customer_type` enum('Individual','Company') NOT NULL DEFAULT 'Individual',
  `contact_person` varchar(255) DEFAULT NULL,
  `contact_person_designation` varchar(255) DEFAULT NULL,
  `contact_person_department` varchar(255) DEFAULT NULL,
  `town_id` int DEFAULT NULL,
  `map_embed` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer_accounts`
--

CREATE TABLE `customer_accounts` (
  `id` int NOT NULL,
  `customer_id` int NOT NULL,
  `type` enum('credit','debit') NOT NULL,
  `description_id` int NOT NULL,
  `payment_method_id` int NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `balance` decimal(10,2) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `created_by_user_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer_inquiries`
--

CREATE TABLE `customer_inquiries` (
  `id` int NOT NULL,
  `customer_id` int NOT NULL,
  `inquiry_subject` varchar(255) NOT NULL,
  `inquiry_details` text,
  `qualification_required` tinyint(1) DEFAULT '0',
  `qualification_notes` text,
  `feedback` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer_quotes`
--

CREATE TABLE `customer_quotes` (
  `id` int NOT NULL,
  `quotation_no` varchar(50) DEFAULT NULL,
  `customer_id` int NOT NULL,
  `quote_subject` varchar(255) NOT NULL,
  `quote_details` text,
  `total_amount` decimal(12,2) DEFAULT '0.00',
  `status` enum('draft','sent','accepted','rejected') DEFAULT 'draft',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer_quote_items`
--

CREATE TABLE `customer_quote_items` (
  `id` int NOT NULL,
  `quote_id` int NOT NULL,
  `item_id` int NOT NULL,
  `quantity` int NOT NULL DEFAULT '1',
  `unit_price` decimal(12,2) NOT NULL,
  `total_price` decimal(12,2) GENERATED ALWAYS AS ((`quantity` * `unit_price`)) STORED
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer_reports`
--

CREATE TABLE `customer_reports` (
  `report_id` int NOT NULL,
  `customer_id` int NOT NULL,
  `incident_id` int NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `id` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `discount_settings`
--

CREATE TABLE `discount_settings` (
  `id` int NOT NULL,
  `discount_name` varchar(100) NOT NULL,
  `discount_amount` decimal(10,2) NOT NULL,
  `min_shopping_amount` decimal(10,2) DEFAULT '0.00',
  `maximum_shopping_amount` decimal(10,2) DEFAULT '0.00',
  `enabled` tinyint(1) DEFAULT '1',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `drivers`
--

CREATE TABLE `drivers` (
  `id` int NOT NULL,
  `user_id` varchar(255) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `license_number` varchar(50) DEFAULT NULL,
  `vehicle_model` varchar(100) DEFAULT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `address` text,
  `online` tinyint(1) NOT NULL DEFAULT '0',
  `active` tinyint NOT NULL DEFAULT '0',
  `vehicle_type_id` int DEFAULT NULL,
  `approved` tinyint(1) DEFAULT '0',
  `on_ride` tinyint(1) NOT NULL DEFAULT '0',
  `current_lat` decimal(9,6) DEFAULT NULL,
  `current_lng` decimal(9,6) DEFAULT NULL,
  `wallet_balance` double NOT NULL DEFAULT '0',
  `currency` varchar(11) NOT NULL,
  `country_code` varchar(11) DEFAULT NULL,
  `fcm_token` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `driver_documents`
--

CREATE TABLE `driver_documents` (
  `id` int NOT NULL,
  `document_name` varchar(255) NOT NULL,
  `status` enum('required','optional','not required') NOT NULL DEFAULT 'required',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `is_vehicle_photo` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `driver_required_documents`
--

CREATE TABLE `driver_required_documents` (
  `id` int NOT NULL,
  `document_name` varchar(100) NOT NULL,
  `status` enum('required','optional','not_required') NOT NULL DEFAULT 'required',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `driver_submitted_documents`
--

CREATE TABLE `driver_submitted_documents` (
  `id` int NOT NULL,
  `user_uid` varchar(100) NOT NULL,
  `document_id` int NOT NULL,
  `document_name` varchar(255) NOT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `status` enum('not submitted','new','approved','declined','pending','reupload') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT 'not submitted',
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `admin_note` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `access_token` varchar(64) NOT NULL,
  `is_vehicle_photo` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `driver_submitted_vehicles`
--

CREATE TABLE `driver_submitted_vehicles` (
  `id` int NOT NULL,
  `user_id` varchar(255) NOT NULL,
  `vehicle_detail_id` int DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `value` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` varchar(25) DEFAULT NULL,
  `admin_note` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `email_verifications`
--

CREATE TABLE `email_verifications` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `token` varchar(255) NOT NULL,
  `expires_at` datetime NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `id` int NOT NULL,
  `category_id` int NOT NULL,
  `item_no` varchar(50) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `quantity` int DEFAULT '0',
  `unit_price` double DEFAULT NULL,
  `for_hire` tinyint(1) NOT NULL DEFAULT '0',
  `for_sale` tinyint(1) NOT NULL DEFAULT '0',
  `reorder_level_quantity` double DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `vatable` tinyint(1) DEFAULT '0',
  `vat_id` int DEFAULT NULL,
  `scanned_code` text NOT NULL,
  `item_no_name` text,
  `item_id_no` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `items_units_of_measure`
--

CREATE TABLE `items_units_of_measure` (
  `id` int NOT NULL,
  `item_id` int NOT NULL,
  `unit_id` int UNSIGNED NOT NULL,
  `unit_value` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `item_categories`
--

CREATE TABLE `item_categories` (
  `id` int NOT NULL,
  `category_name` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `shop_id` int DEFAULT NULL,
  `category_image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `item_check_in`
--

CREATE TABLE `item_check_in` (
  `id` int NOT NULL,
  `item_id` int NOT NULL,
  `supplier_id` int NOT NULL,
  `check_in_quantity` int NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `unit_price` double NOT NULL,
  `total_price` double NOT NULL,
  `user_id` int NOT NULL,
  `user_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `item_return`
--

CREATE TABLE `item_return` (
  `id` int NOT NULL,
  `item_id` int NOT NULL,
  `customer_id` int NOT NULL,
  `return_quantity` int NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `unit_price` double NOT NULL,
  `total_price` double NOT NULL,
  `user_id` int NOT NULL,
  `user_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `login_attempts`
--

CREATE TABLE `login_attempts` (
  `id` int NOT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `username` varchar(100) DEFAULT NULL,
  `attempt_time` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mpesa_settings`
--

CREATE TABLE `mpesa_settings` (
  `id` int NOT NULL,
  `paybill` varchar(20) NOT NULL,
  `account_number` varchar(50) DEFAULT NULL,
  `till_number` varchar(20) DEFAULT NULL,
  `consumer_key` varchar(100) NOT NULL,
  `consumer_secret` varchar(100) NOT NULL,
  `shortcode` varchar(20) NOT NULL,
  `passkey` varchar(255) NOT NULL,
  `active` tinyint(1) DEFAULT '1',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mpesa_transactions`
--

CREATE TABLE `mpesa_transactions` (
  `id` int NOT NULL,
  `MerchantRequestID` varchar(100) DEFAULT NULL,
  `CheckoutRequestID` varchar(100) DEFAULT NULL,
  `ResultCode` int DEFAULT NULL,
  `ResultDesc` varchar(255) DEFAULT NULL,
  `Amount` decimal(10,2) DEFAULT NULL,
  `MpesaReceiptNumber` varchar(50) DEFAULT NULL,
  `Balance` varchar(50) DEFAULT NULL,
  `TransactionDate` datetime DEFAULT NULL,
  `PhoneNumber` varchar(20) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `new_feature_table`
--

CREATE TABLE `new_feature_table` (
  `id` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `numbering`
--

CREATE TABLE `numbering` (
  `id` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `apply_to` enum('Category','Items','Quotations','Invoices','Receipts','Sales Orders','Customers','Suppliers') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `prefix` varchar(50) DEFAULT NULL,
  `start` int DEFAULT '1',
  `last_used` int DEFAULT '0',
  `auto_increment` tinyint(1) DEFAULT '0',
  `allow_manual_entry` tinyint(1) DEFAULT '1',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `numbering_settings`
--

CREATE TABLE `numbering_settings` (
  `id` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `apply_to` enum('Category','Items','Quotations','Invoices','Receipts','Sales Orders','Customers','Suppliers') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `prefix` varchar(50) DEFAULT NULL,
  `start` int DEFAULT '1',
  `last_used` int DEFAULT '0',
  `auto_increment` tinyint(1) DEFAULT '0',
  `allow_manual_entry` tinyint(1) DEFAULT '1',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `token` varchar(255) NOT NULL,
  `expires_at` datetime NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `token` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `expires_at` datetime NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_descriptions`
--

CREATE TABLE `payment_descriptions` (
  `id` int NOT NULL,
  `description` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_instructions`
--

CREATE TABLE `payment_instructions` (
  `id` int NOT NULL,
  `country_code` varchar(5) NOT NULL,
  `payment_type` varchar(50) NOT NULL,
  `instruction` text NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_methods`
--

CREATE TABLE `payment_methods` (
  `id` int NOT NULL,
  `method` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `label` varchar(55) NOT NULL,
  `link` varchar(255) NOT NULL,
  `icon` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `isbutton` tinyint(1) DEFAULT '1',
  `top_menu` tinyint NOT NULL DEFAULT '0',
  `position` int DEFAULT '1',
  `button_style` varchar(11) DEFAULT 'btn-warning',
  `group_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permission_groups`
--

CREATE TABLE `permission_groups` (
  `id` int NOT NULL,
  `name` varchar(55) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pos_list`
--

CREATE TABLE `pos_list` (
  `id` int NOT NULL,
  `shop_id` int NOT NULL,
  `item_id` int NOT NULL,
  `selling_price` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `privacy_policy`
--

CREATE TABLE `privacy_policy` (
  `id` int NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `referral_codes`
--

CREATE TABLE `referral_codes` (
  `id` int NOT NULL,
  `user_uid` varchar(255) NOT NULL COMMENT 'firebase uid of owner',
  `code` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reminders`
--

CREATE TABLE `reminders` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `title` varchar(255) NOT NULL,
  `about` text,
  `remind_at` datetime NOT NULL,
  `status` enum('New','Pending','Completed') NOT NULL DEFAULT 'New',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `id` int UNSIGNED NOT NULL,
  `report_name` varchar(255) NOT NULL,
  `link` text NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ride_bookings`
--

CREATE TABLE `ride_bookings` (
  `id` int NOT NULL,
  `driver_uid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT 'Null',
  `start_location` varchar(255) NOT NULL,
  `start_lat` decimal(10,8) DEFAULT NULL,
  `start_lng` decimal(11,8) DEFAULT NULL,
  `destination` varchar(255) NOT NULL,
  `destination_lat` decimal(10,8) DEFAULT NULL,
  `destination_lng` decimal(11,8) DEFAULT NULL,
  `vehicle_type` varchar(100) NOT NULL,
  `distance` decimal(10,2) DEFAULT NULL,
  `estimated_cost` int DEFAULT NULL,
  `estimated_time` int DEFAULT NULL,
  `number_of_passengers` int DEFAULT NULL,
  `current_driver_lat` decimal(10,8) DEFAULT NULL,
  `current_driver_lng` decimal(11,8) DEFAULT NULL,
  `customer_user_id` varchar(255) DEFAULT NULL,
  `customer_id` varchar(255) DEFAULT NULL,
  `customer_name` varchar(255) DEFAULT NULL,
  `customer_phone` varchar(20) DEFAULT NULL,
  `customer_not_listed` tinyint(1) DEFAULT '0',
  `status` enum('new','in_progress','completed','cancelled','started') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'new',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `country_code` varchar(11) DEFAULT NULL,
  `currency` varchar(11) DEFAULT NULL,
  `stop_locations` json DEFAULT NULL,
  `cancelled_by` varchar(255) DEFAULT NULL,
  `reason` text,
  `cancellation_fee` double DEFAULT '0',
  `payment_method_id` int DEFAULT NULL,
  `commission_amount` double DEFAULT '0',
  `commission_paid` tinyint DEFAULT '0',
  `reason_id` int DEFAULT NULL,
  `cancellation_reason_id` int DEFAULT NULL,
  `start_ride_code` int NOT NULL DEFAULT '0',
  `customer_rating` int NOT NULL DEFAULT '0',
  `driver_rating` int NOT NULL DEFAULT '0',
  `payment_received` tinyint(1) NOT NULL DEFAULT '0',
  `payment_made` tinyint(1) NOT NULL DEFAULT '0',
  `datetime` datetime DEFAULT NULL,
  `vehicle_type_id` int DEFAULT NULL,
  `driver_initiated` tinyint(1) NOT NULL DEFAULT '0',
  `commission` double NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int NOT NULL,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `role_permissions`
--

CREATE TABLE `role_permissions` (
  `id` int NOT NULL,
  `role_id` int NOT NULL,
  `permission_id` int NOT NULL,
  `can_view` tinyint(1) DEFAULT '0',
  `can_add` tinyint(1) DEFAULT '0',
  `can_edit` tinyint(1) DEFAULT '0',
  `can_delete` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id` int NOT NULL,
  `sales_order_id` int DEFAULT NULL,
  `category_id` int DEFAULT NULL,
  `dateTime` datetime DEFAULT CURRENT_TIMESTAMP,
  `discount` decimal(10,2) DEFAULT '0.00',
  `item_id` int NOT NULL,
  `item_name` varchar(255) DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `quantity` int DEFAULT NULL,
  `sellingPrice` decimal(10,2) DEFAULT NULL,
  `shop_id` int DEFAULT NULL,
  `total_cost` decimal(10,2) DEFAULT NULL,
  `total_discount` decimal(10,2) DEFAULT '0.00',
  `vat_amnt` decimal(10,2) DEFAULT '0.00',
  `vat_code` varchar(50) DEFAULT NULL,
  `vat_rate` decimal(5,2) DEFAULT '0.00',
  `vatable_amnt` decimal(10,2) DEFAULT '0.00',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `cashier_id` int DEFAULT NULL,
  `customer_id` int DEFAULT NULL,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sales_orders`
--

CREATE TABLE `sales_orders` (
  `id` int NOT NULL,
  `date_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `paid_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `order_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `balance` decimal(10,2) NOT NULL DEFAULT '0.00',
  `shop_id` int DEFAULT NULL,
  `created_by_user_id` int NOT NULL,
  `created_by_user_name` varchar(255) NOT NULL,
  `status` enum('new','closed','on-hold') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'new',
  `closed_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `cashier_id` int DEFAULT NULL,
  `discount_id` int DEFAULT NULL,
  `payment_method` enum('Cash','Card','Mobile Money','Bank Transfer','Credit','Mpesa','Cash & Mobile Money') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'Cash',
  `customer_id` int DEFAULT NULL,
  `credit_paid` tinyint(1) NOT NULL DEFAULT '0',
  `credit_paid_date` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `mobile_no` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int NOT NULL,
  `ride_acceptance_radius` decimal(5,2) NOT NULL DEFAULT '5.00' COMMENT 'in km',
  `referral_points_per_driver` int NOT NULL DEFAULT '0',
  `referral_points_per_customer` int NOT NULL DEFAULT '0',
  `dollars_per_point` decimal(10,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `shops`
--

CREATE TABLE `shops` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `submitted_referrals`
--

CREATE TABLE `submitted_referrals` (
  `id` int NOT NULL,
  `user_uid` varchar(255) NOT NULL COMMENT 'the user who submitted a referral code (firebase uid)',
  `referred_by_code` int NOT NULL COMMENT 'the referral code they submitted',
  `referred_user_uid` varchar(255) DEFAULT NULL COMMENT 'optional: the referred user uid (if known at submission)',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `address` text,
  `town` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `system_administration`
--

CREATE TABLE `system_administration` (
  `id` int NOT NULL,
  `action` varchar(255) NOT NULL,
  `status` enum('pending','completed','failed') DEFAULT 'pending',
  `details` text,
  `executed_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `system_documentation`
--

CREATE TABLE `system_documentation` (
  `id` int NOT NULL,
  `title` varchar(255) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_banners`
--

CREATE TABLE `tbl_banners` (
  `banner_id` int NOT NULL,
  `banner_name` varchar(255) NOT NULL,
  `banner_link` varchar(255) NOT NULL,
  `customer_name` varchar(100) DEFAULT NULL,
  `customer_id` int DEFAULT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_screen_banner_views`
--

CREATE TABLE `tbl_screen_banner_views` (
  `id` int NOT NULL,
  `datetime` datetime DEFAULT NULL,
  `entity_name` varchar(50) DEFAULT NULL,
  `banner_name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `entity_identity` varchar(25) DEFAULT NULL,
  `serial_no` varchar(125) DEFAULT NULL,
  `estimpressions` int DEFAULT '0',
  `route` varchar(25) DEFAULT NULL,
  `customerId` int DEFAULT NULL,
  `deleted` smallint DEFAULT '0',
  `active` smallint DEFAULT '1',
  `status` varchar(25) DEFAULT NULL,
  `views` int DEFAULT '0',
  `registration` varchar(11) DEFAULT NULL,
  `date` varchar(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_screen_video_ads_views`
--

CREATE TABLE `tbl_screen_video_ads_views` (
  `id` int NOT NULL,
  `datetime` datetime DEFAULT NULL,
  `entity_name` varchar(50) DEFAULT NULL,
  `video_name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `customerId` int DEFAULT NULL,
  `entity_identity` varchar(25) DEFAULT NULL,
  `serial_no` varchar(125) DEFAULT NULL,
  `estimpressions` int DEFAULT '0',
  `route` varchar(25) DEFAULT NULL,
  `deleted` int DEFAULT '0',
  `active` int DEFAULT '1',
  `status` varchar(25) DEFAULT NULL,
  `views` int DEFAULT '0',
  `date` varchar(25) DEFAULT NULL,
  `registration` varchar(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_unregistered_customers`
--

CREATE TABLE `tbl_unregistered_customers` (
  `id` int NOT NULL,
  `first_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `passengers` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_videos`
--

CREATE TABLE `tbl_videos` (
  `video_id` int NOT NULL,
  `video_name` varchar(255) NOT NULL,
  `video_link` varchar(255) NOT NULL,
  `customer_name` varchar(100) DEFAULT NULL,
  `customer_id` int DEFAULT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `topup_transactions`
--

CREATE TABLE `topup_transactions` (
  `id` int NOT NULL,
  `driver_id` int NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `datetime` datetime NOT NULL,
  `added_by_id` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'Transaction',
  `ride_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `towns`
--

CREATE TABLE `towns` (
  `id` int NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `unit_of_measure`
--

CREATE TABLE `unit_of_measure` (
  `id` int UNSIGNED NOT NULL,
  `unit_name` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int NOT NULL,
  `username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `role_id` int DEFAULT NULL,
  `profile_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'https://ob.transcommedia.com/public/images/avatar.png',
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `mobile` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `name` varchar(55) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `signature_link` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `designation` varchar(55) NOT NULL,
  `logged_in` tinyint(1) NOT NULL DEFAULT '0',
  `shop_id` int DEFAULT NULL,
  `company_id` int NOT NULL DEFAULT '1',
  `is_verified` tinyint(1) DEFAULT '0',
  `reset_token` varchar(64) DEFAULT NULL,
  `reset_expires` datetime DEFAULT NULL,
  `company` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_notifications`
--

CREATE TABLE `user_notifications` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `new_user_registration` tinyint(1) DEFAULT '0',
  `new_user_registration_sms` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `low_stock` tinyint(1) NOT NULL DEFAULT '0',
  `low_stock_sms` int DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vat_settings`
--

CREATE TABLE `vat_settings` (
  `id` int NOT NULL,
  `vat_name` varchar(100) NOT NULL,
  `vat_perc` decimal(5,2) NOT NULL,
  `vat_code` varchar(50) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vehicle_details`
--

CREATE TABLE `vehicle_details` (
  `id` int NOT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `status` enum('required','optional','not required') NOT NULL DEFAULT 'required',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vehicle_types`
--

CREATE TABLE `vehicle_types` (
  `id` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text,
  `base_fare` decimal(10,2) NOT NULL,
  `min_fare` decimal(10,2) NOT NULL,
  `max_fare` decimal(10,2) NOT NULL,
  `cost_per_km` decimal(10,2) NOT NULL,
  `cost_per_minute` decimal(10,2) DEFAULT '0.00',
  `waiting_charge` decimal(10,2) DEFAULT '0.00',
  `surge_multiplier` float DEFAULT '1',
  `image` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `category` varchar(25) DEFAULT NULL,
  `vehicle_capacity` int NOT NULL DEFAULT '4',
  `is_electric` tinyint(1) NOT NULL DEFAULT '0',
  `is_luxury` tinyint(1) NOT NULL DEFAULT '0',
  `availability` varchar(11) NOT NULL,
  `traffic_factor` decimal(11,0) DEFAULT NULL,
  `weather_factor` decimal(11,0) DEFAULT NULL,
  `demand_factor` decimal(11,0) DEFAULT NULL,
  `driver_cancellation_fee_perc` double DEFAULT NULL,
  `customer_cancellation_fee_perc` double DEFAULT NULL,
  `payable_commission_per` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `videos`
--

CREATE TABLE `videos` (
  `video_id` int NOT NULL,
  `video_name` varchar(255) NOT NULL,
  `video_link` varchar(255) NOT NULL,
  `customer_name` varchar(100) DEFAULT NULL,
  `customer_id` int DEFAULT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `barcode_label_sizes`
--
ALTER TABLE `barcode_label_sizes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `branches`
--
ALTER TABLE `branches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bulksms_settings`
--
ALTER TABLE `bulksms_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cancellation_reason`
--
ALTER TABLE `cancellation_reason`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `chats`
--
ALTER TABLE `chats`
  ADD PRIMARY KEY (`chat_id`),
  ADD KEY `sender_id` (`sender_id`),
  ADD KEY `receiver_id` (`receiver_id`);

--
-- Indexes for table `client_reports`
--
ALTER TABLE `client_reports`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `companies`
--
ALTER TABLE `companies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `branch_id` (`branch_id`),
  ADD KEY `town_id` (`town_id`),
  ADD KEY `county_id` (`county_id`),
  ADD KEY `country_id` (`country_id`);

--
-- Indexes for table `counties`
--
ALTER TABLE `counties`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`customer_id`),
  ADD KEY `town_id` (`town_id`);

--
-- Indexes for table `customer_accounts`
--
ALTER TABLE `customer_accounts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `description_id` (`description_id`),
  ADD KEY `payment_method_id` (`payment_method_id`),
  ADD KEY `created_by_user_id` (`created_by_user_id`);

--
-- Indexes for table `customer_inquiries`
--
ALTER TABLE `customer_inquiries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `customer_quotes`
--
ALTER TABLE `customer_quotes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `customer_quote_items`
--
ALTER TABLE `customer_quote_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `quote_id` (`quote_id`),
  ADD KEY `item_id` (`item_id`);

--
-- Indexes for table `customer_reports`
--
ALTER TABLE `customer_reports`
  ADD PRIMARY KEY (`report_id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `discount_settings`
--
ALTER TABLE `discount_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `drivers`
--
ALTER TABLE `drivers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `driver_documents`
--
ALTER TABLE `driver_documents`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `driver_required_documents`
--
ALTER TABLE `driver_required_documents`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `driver_submitted_documents`
--
ALTER TABLE `driver_submitted_documents`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `driver_submitted_vehicles`
--
ALTER TABLE `driver_submitted_vehicles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_entry` (`user_id`,`vehicle_detail_id`);

--
-- Indexes for table `email_verifications`
--
ALTER TABLE `email_verifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `vat_id` (`vat_id`);

--
-- Indexes for table `items_units_of_measure`
--
ALTER TABLE `items_units_of_measure`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_custom_item_id` (`item_id`),
  ADD KEY `fk_custom_unit_id` (`unit_id`);

--
-- Indexes for table `item_categories`
--
ALTER TABLE `item_categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `shop_id` (`shop_id`);

--
-- Indexes for table `item_check_in`
--
ALTER TABLE `item_check_in`
  ADD PRIMARY KEY (`id`),
  ADD KEY `item_id` (`item_id`),
  ADD KEY `supplier_id` (`supplier_id`);

--
-- Indexes for table `item_return`
--
ALTER TABLE `item_return`
  ADD PRIMARY KEY (`id`),
  ADD KEY `item_id` (`item_id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `login_attempts`
--
ALTER TABLE `login_attempts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mpesa_settings`
--
ALTER TABLE `mpesa_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mpesa_transactions`
--
ALTER TABLE `mpesa_transactions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `new_feature_table`
--
ALTER TABLE `new_feature_table`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `numbering`
--
ALTER TABLE `numbering`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `numbering_settings`
--
ALTER TABLE `numbering_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payment_descriptions`
--
ALTER TABLE `payment_descriptions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `description` (`description`);

--
-- Indexes for table `payment_instructions`
--
ALTER TABLE `payment_instructions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payment_methods`
--
ALTER TABLE `payment_methods`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `method` (`method`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD KEY `fk_permissions_group` (`group_id`);

--
-- Indexes for table `permission_groups`
--
ALTER TABLE `permission_groups`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD UNIQUE KEY `name_2` (`name`),
  ADD UNIQUE KEY `name_3` (`name`);

--
-- Indexes for table `pos_list`
--
ALTER TABLE `pos_list`
  ADD PRIMARY KEY (`id`),
  ADD KEY `shop_id` (`shop_id`),
  ADD KEY `item_id` (`item_id`);

--
-- Indexes for table `privacy_policy`
--
ALTER TABLE `privacy_policy`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `referral_codes`
--
ALTER TABLE `referral_codes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_uid` (`user_uid`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `reminders`
--
ALTER TABLE `reminders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_remind_at` (`user_id`,`remind_at`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ride_bookings`
--
ALTER TABLE `ride_bookings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `role_permissions`
--
ALTER TABLE `role_permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `role_id` (`role_id`,`permission_id`),
  ADD KEY `permission_id` (`permission_id`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_sales_user_date` (`user_id`,`created_at`);

--
-- Indexes for table `sales_orders`
--
ALTER TABLE `sales_orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_sales_orders_shop` (`shop_id`),
  ADD KEY `fk_discount` (`discount_id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `shops`
--
ALTER TABLE `shops`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `submitted_referrals`
--
ALTER TABLE `submitted_referrals`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_user_submission` (`user_uid`),
  ADD KEY `idx_referred_by_code` (`referred_by_code`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `system_administration`
--
ALTER TABLE `system_administration`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `system_documentation`
--
ALTER TABLE `system_documentation`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_banners`
--
ALTER TABLE `tbl_banners`
  ADD PRIMARY KEY (`banner_id`);

--
-- Indexes for table `tbl_screen_banner_views`
--
ALTER TABLE `tbl_screen_banner_views`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_screen_video_ads_views`
--
ALTER TABLE `tbl_screen_video_ads_views`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customerId` (`customerId`);

--
-- Indexes for table `tbl_unregistered_customers`
--
ALTER TABLE `tbl_unregistered_customers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_videos`
--
ALTER TABLE `tbl_videos`
  ADD PRIMARY KEY (`video_id`);

--
-- Indexes for table `topup_transactions`
--
ALTER TABLE `topup_transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `driver_id` (`driver_id`),
  ADD KEY `added_by_id` (`added_by_id`);

--
-- Indexes for table `towns`
--
ALTER TABLE `towns`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `unit_of_measure`
--
ALTER TABLE `unit_of_measure`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD KEY `role_id` (`role_id`),
  ADD KEY `shop_id` (`shop_id`);

--
-- Indexes for table `user_notifications`
--
ALTER TABLE `user_notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `vat_settings`
--
ALTER TABLE `vat_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vehicle_details`
--
ALTER TABLE `vehicle_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vehicle_types`
--
ALTER TABLE `vehicle_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `videos`
--
ALTER TABLE `videos`
  ADD PRIMARY KEY (`video_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `barcode_label_sizes`
--
ALTER TABLE `barcode_label_sizes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `branches`
--
ALTER TABLE `branches`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bulksms_settings`
--
ALTER TABLE `bulksms_settings`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cancellation_reason`
--
ALTER TABLE `cancellation_reason`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `chats`
--
ALTER TABLE `chats`
  MODIFY `chat_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `client_reports`
--
ALTER TABLE `client_reports`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `companies`
--
ALTER TABLE `companies`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `counties`
--
ALTER TABLE `counties`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `countries`
--
ALTER TABLE `countries`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `customer_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customer_accounts`
--
ALTER TABLE `customer_accounts`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customer_inquiries`
--
ALTER TABLE `customer_inquiries`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customer_quotes`
--
ALTER TABLE `customer_quotes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customer_quote_items`
--
ALTER TABLE `customer_quote_items`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customer_reports`
--
ALTER TABLE `customer_reports`
  MODIFY `report_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `discount_settings`
--
ALTER TABLE `discount_settings`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `drivers`
--
ALTER TABLE `drivers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `driver_documents`
--
ALTER TABLE `driver_documents`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `driver_required_documents`
--
ALTER TABLE `driver_required_documents`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `driver_submitted_documents`
--
ALTER TABLE `driver_submitted_documents`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `driver_submitted_vehicles`
--
ALTER TABLE `driver_submitted_vehicles`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `email_verifications`
--
ALTER TABLE `email_verifications`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `items_units_of_measure`
--
ALTER TABLE `items_units_of_measure`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `item_categories`
--
ALTER TABLE `item_categories`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `item_check_in`
--
ALTER TABLE `item_check_in`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `item_return`
--
ALTER TABLE `item_return`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `login_attempts`
--
ALTER TABLE `login_attempts`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mpesa_settings`
--
ALTER TABLE `mpesa_settings`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mpesa_transactions`
--
ALTER TABLE `mpesa_transactions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `new_feature_table`
--
ALTER TABLE `new_feature_table`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `numbering`
--
ALTER TABLE `numbering`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `numbering_settings`
--
ALTER TABLE `numbering_settings`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment_descriptions`
--
ALTER TABLE `payment_descriptions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment_instructions`
--
ALTER TABLE `payment_instructions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment_methods`
--
ALTER TABLE `payment_methods`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `permission_groups`
--
ALTER TABLE `permission_groups`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pos_list`
--
ALTER TABLE `pos_list`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `privacy_policy`
--
ALTER TABLE `privacy_policy`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `referral_codes`
--
ALTER TABLE `referral_codes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reminders`
--
ALTER TABLE `reminders`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ride_bookings`
--
ALTER TABLE `ride_bookings`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `role_permissions`
--
ALTER TABLE `role_permissions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sales_orders`
--
ALTER TABLE `sales_orders`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `shops`
--
ALTER TABLE `shops`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `submitted_referrals`
--
ALTER TABLE `submitted_referrals`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `system_administration`
--
ALTER TABLE `system_administration`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `system_documentation`
--
ALTER TABLE `system_documentation`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_banners`
--
ALTER TABLE `tbl_banners`
  MODIFY `banner_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_screen_banner_views`
--
ALTER TABLE `tbl_screen_banner_views`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_screen_video_ads_views`
--
ALTER TABLE `tbl_screen_video_ads_views`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_unregistered_customers`
--
ALTER TABLE `tbl_unregistered_customers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_videos`
--
ALTER TABLE `tbl_videos`
  MODIFY `video_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `topup_transactions`
--
ALTER TABLE `topup_transactions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `towns`
--
ALTER TABLE `towns`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `unit_of_measure`
--
ALTER TABLE `unit_of_measure`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_notifications`
--
ALTER TABLE `user_notifications`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vat_settings`
--
ALTER TABLE `vat_settings`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vehicle_details`
--
ALTER TABLE `vehicle_details`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vehicle_types`
--
ALTER TABLE `vehicle_types`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `videos`
--
ALTER TABLE `videos`
  MODIFY `video_id` int NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `chats`
--
ALTER TABLE `chats`
  ADD CONSTRAINT `chats_ibfk_1` FOREIGN KEY (`sender_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `chats_ibfk_2` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `companies`
--
ALTER TABLE `companies`
  ADD CONSTRAINT `companies_ibfk_1` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`),
  ADD CONSTRAINT `companies_ibfk_2` FOREIGN KEY (`town_id`) REFERENCES `towns` (`id`),
  ADD CONSTRAINT `companies_ibfk_3` FOREIGN KEY (`county_id`) REFERENCES `counties` (`id`),
  ADD CONSTRAINT `companies_ibfk_4` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`);

--
-- Constraints for table `customers`
--
ALTER TABLE `customers`
  ADD CONSTRAINT `fk_customers_towns` FOREIGN KEY (`town_id`) REFERENCES `towns` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `customer_accounts`
--
ALTER TABLE `customer_accounts`
  ADD CONSTRAINT `customer_accounts_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`customer_id`),
  ADD CONSTRAINT `customer_accounts_ibfk_2` FOREIGN KEY (`description_id`) REFERENCES `payment_descriptions` (`id`),
  ADD CONSTRAINT `customer_accounts_ibfk_3` FOREIGN KEY (`payment_method_id`) REFERENCES `payment_methods` (`id`),
  ADD CONSTRAINT `customer_accounts_ibfk_4` FOREIGN KEY (`created_by_user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `customer_inquiries`
--
ALTER TABLE `customer_inquiries`
  ADD CONSTRAINT `customer_inquiries_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`customer_id`) ON DELETE CASCADE;

--
-- Constraints for table `customer_quotes`
--
ALTER TABLE `customer_quotes`
  ADD CONSTRAINT `customer_quotes_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`customer_id`) ON DELETE CASCADE;

--
-- Constraints for table `customer_quote_items`
--
ALTER TABLE `customer_quote_items`
  ADD CONSTRAINT `customer_quote_items_ibfk_1` FOREIGN KEY (`quote_id`) REFERENCES `customer_quotes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `customer_quote_items_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `customer_reports`
--
ALTER TABLE `customer_reports`
  ADD CONSTRAINT `customer_reports_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`customer_id`);

--
-- Constraints for table `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `items_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `item_categories` (`id`),
  ADD CONSTRAINT `items_ibfk_2` FOREIGN KEY (`vat_id`) REFERENCES `vat_settings` (`id`);

--
-- Constraints for table `items_units_of_measure`
--
ALTER TABLE `items_units_of_measure`
  ADD CONSTRAINT `fk_custom_item_id` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_custom_unit_id` FOREIGN KEY (`unit_id`) REFERENCES `unit_of_measure` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `item_check_in`
--
ALTER TABLE `item_check_in`
  ADD CONSTRAINT `item_check_in_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `item_check_in_ibfk_2` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `item_return`
--
ALTER TABLE `item_return`
  ADD CONSTRAINT `item_return_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `item_return_ibfk_2` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`customer_id`) ON DELETE CASCADE;

--
-- Constraints for table `permissions`
--
ALTER TABLE `permissions`
  ADD CONSTRAINT `fk_permissions_group` FOREIGN KEY (`group_id`) REFERENCES `permission_groups` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `pos_list`
--
ALTER TABLE `pos_list`
  ADD CONSTRAINT `pos_list_ibfk_1` FOREIGN KEY (`shop_id`) REFERENCES `shops` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pos_list_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_permissions`
--
ALTER TABLE `role_permissions`
  ADD CONSTRAINT `role_permissions_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_permissions_ibfk_2` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sales_orders`
--
ALTER TABLE `sales_orders`
  ADD CONSTRAINT `fk_discount` FOREIGN KEY (`discount_id`) REFERENCES `discount_settings` (`id`),
  ADD CONSTRAINT `fk_sales_orders_shop` FOREIGN KEY (`shop_id`) REFERENCES `shops` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `topup_transactions`
--
ALTER TABLE `topup_transactions`
  ADD CONSTRAINT `topup_transactions_ibfk_1` FOREIGN KEY (`driver_id`) REFERENCES `drivers` (`id`),
  ADD CONSTRAINT `topup_transactions_ibfk_2` FOREIGN KEY (`added_by_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`);

--
-- Constraints for table `user_notifications`
--
ALTER TABLE `user_notifications`
  ADD CONSTRAINT `user_notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
