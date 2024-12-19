-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 18, 2024 at 04:23 PM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dollar_company`
--

-- --------------------------------------------------------

--
-- Table structure for table `account`
--

CREATE TABLE `account` (
  `AccountID` int NOT NULL,
  `UserName` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `UserPassword` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `CreatedDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `role_id` int DEFAULT NULL,
  `Employee_id` int NOT NULL,
  `Delete_Date` timestamp NULL DEFAULT NULL,
  `Last_login` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `account`
--

INSERT INTO `account` (`AccountID`, `UserName`, `UserPassword`, `CreatedDate`, `role_id`, `Employee_id`, `Delete_Date`, `Last_login`) VALUES
(100, 'ADMIN', '$2y$10$Dsfqf6r0cyhWLkjgRYf1De0mYrU4CL1EISSO8yihYYhQ.8pz.0.EO', '2024-12-18 16:20:01', 1, 225000, NULL, '2024-12-18 16:20:01'),
(101, 'mahmoud', '$2y$10$0r5WIz2txpdKLTAryZjGLehJnp6XkkWwo4BJJCzkk1w5AXaxZZJIy', '2024-12-18 16:16:57', 2, 225001, NULL, '2024-12-18 16:09:41'),
(102, 'mohammednader', '$2y$10$uFT/KlMPgSwEc7aC/FDWa.wTcoLRlz0gTDFbfhkKC8c2pA/f3VBxG', '2024-12-18 15:10:37', 2, 225002, NULL, NULL),
(103, 'IBRAHIM', '$2y$10$PGjlEdw1nBfKJmBEtX5PV.lbu7XJXh6aKRPxkZJYDwNAOJnLX0QzG', '2024-12-18 15:10:42', 2, 225003, NULL, NULL),
(104, 'anasouda43', '$2y$10$TTXrmvHenZIxHB9/t/Vk8uX7VtwCsmxiy41FL8qG1bAyYQdcU1rvu', '2024-12-18 15:10:47', 2, 225004, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `accounting`
--

CREATE TABLE `accounting` (
  `account_number` int NOT NULL,
  `account_code` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `account_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `account_type` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `account_Sname` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `Date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `currency_id` varchar(3) COLLATE utf8mb4_general_ci NOT NULL,
  `Delete_Date` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `accounting`
--

INSERT INTO `accounting` (`account_number`, `account_code`, `account_amount`, `account_type`, `account_Sname`, `Date`, `currency_id`, `Delete_Date`) VALUES
(10100, 'main_10100', 1000.00, '3000', 'دولار أمريكي', '2024-12-18 13:32:14', '001', NULL),
(10101, 'main_10101', 3630.00, '3000', 'شيكل إسرائيلي', '2024-12-18 13:32:39', '005', NULL),
(10102, 'main_10102', 710.00, '3000', 'دينار أردني', '2024-12-18 13:32:51', '010', NULL),
(10103, 'main_10103', 1070.00, '3000', 'يورو', '2024-12-18 13:32:58', '015', NULL),
(10104, 'main_10104', 51200.00, '3000', 'جنيه مصري', '2024-12-18 13:33:06', '020', NULL),
(10105, 'main_10105', 3730.00, '3000', 'ريال سعودي', '2024-12-18 13:33:17', '025', NULL),
(10106, 'main_10106', 3700.00, '3000', 'درهم إماراتي', '2024-12-18 13:33:31', '030', NULL),
(10107, 'main_10107', 810.00, '3000', 'جنيه إسترليني', '2024-12-18 13:33:43', '035', NULL),
(10200, 'sub_10200', 100.00, '3100', 'دولار أمريكي - فرعي', '2024-12-18 13:34:12', '001', NULL),
(10201, 'sub_10201', 100.00, '3100', 'شيكل إسرائيلي - فرعي', '2024-12-18 13:34:28', '005', NULL),
(10202, 'sub_10202', 100.00, '3100', 'بنك فلسطين - دولار أمريكي', '2024-12-18 13:34:42', '001', NULL),
(10203, 'sub_10203', 200.00, '3100', 'بنك فلسطين - شيكل إسرائيلي', '2024-12-18 13:35:00', '005', NULL),
(10300, 'income_10300', 100.00, '3200', 'إيراد فودافون كاش', '2024-12-18 14:34:09', '001', NULL),
(10301, 'income_10301', 500.00, '3200', 'إيراد دولار أمريكي', '2024-12-18 14:34:21', '001', NULL),
(10302, 'income_10302', 360.00, '3200', 'إيراد شيكل إسرائيلي', '2024-12-18 14:34:35', '005', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `bonds`
--

CREATE TABLE `bonds` (
  `id` int NOT NULL,
  `bond_type` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `bond_number` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `bond_name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `amount` decimal(10,0) NOT NULL,
  `currency` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `is_special` tinyint(1) NOT NULL DEFAULT '0',
  `Delete_Date` timestamp NULL DEFAULT NULL,
  `fund_name` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `company_info`
--

CREATE TABLE `company_info` (
  `id` int NOT NULL,
  `logo` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `companyName` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `companyAddress` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `mobileNumber` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `companyDescription` text COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `company_info`
--

INSERT INTO `company_info` (`id`, `logo`, `companyName`, `companyAddress`, `mobileNumber`, `companyDescription`) VALUES
(1, 'assets/media/dollar/Company_logo.png', 'HK7 Group', 'EGYPT', '+972597649797', 'خدمات مالية و دفع إلكتروني');

-- --------------------------------------------------------

--
-- Table structure for table `convert_types`
--

CREATE TABLE `convert_types` (
  `id` int NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `sname` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `delete_data` timestamp NULL DEFAULT NULL,
  `Delete_Date` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `convert_types`
--

INSERT INTO `convert_types` (`id`, `name`, `sname`, `date`, `delete_data`, `Delete_Date`) VALUES
(1, 'exchange', 'سند صرف', '2023-07-17 22:54:16', NULL, NULL),
(2, 'receipt', 'سند قبض', '2023-07-17 22:54:29', NULL, NULL),
(3, 'deposit', 'إيداع', '2023-07-18 10:45:14', NULL, NULL),
(4, 'withdraw', 'سحب', '2023-07-18 10:45:22', NULL, NULL),
(5, 'Salary', 'دفع راتب', '2023-07-22 13:32:36', NULL, NULL),
(6, 'Advances', 'دفع سلفة نقدية', '2023-07-22 13:33:05', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `currency`
--

CREATE TABLE `currency` (
  `currency_id` varchar(3) COLLATE utf8mb4_general_ci NOT NULL,
  `currency_sname` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `currency_symbole` varchar(3) COLLATE utf8mb4_general_ci NOT NULL,
  `ceeated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Delete_Date` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `currency`
--

INSERT INTO `currency` (`currency_id`, `currency_sname`, `currency_symbole`, `ceeated_at`, `Delete_Date`) VALUES
('001', 'دولار أمريكي', '$', '2023-07-06 21:34:07', NULL),
('005', 'شيكل إسرائيلي', '₪', '2023-07-06 21:35:09', NULL),
('010', 'دينار أردني', 'د.أ', '2023-07-06 21:36:20', NULL),
('015', 'يورو', '€', '2023-07-06 21:35:49', NULL),
('020', 'جنيه مصري', 'ج.م', '2023-07-06 21:48:09', NULL),
('025', 'ريال سعودي', 'ر.س', '2023-07-06 21:49:10', NULL),
('030', 'درهم إماراتي', 'د.إ', '2023-07-06 21:49:43', NULL),
('035', 'جنيه إسترليني', '£', '2023-08-14 14:17:04', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `currency_exchange`
--

CREATE TABLE `currency_exchange` (
  `id` int NOT NULL,
  `order_id` varchar(11) COLLATE utf8mb4_general_ci NOT NULL,
  `type` enum('sell','buy') COLLATE utf8mb4_general_ci NOT NULL,
  `currency_ex` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `quantity` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `exchange_rate` decimal(10,5) NOT NULL,
  `total` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `customer_id` int DEFAULT NULL,
  `created_by` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `reason_delete` text COLLATE utf8mb4_general_ci,
  `Delete_Date` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `customer_id` int NOT NULL,
  `full_name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `customer_address` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `customer_phone` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `Delete_Date` timestamp NULL DEFAULT NULL,
  `account_type` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`customer_id`, `full_name`, `customer_address`, `create_date`, `customer_phone`, `Delete_Date`, `account_type`) VALUES
(1, 'حساب تجريبي - زبون', 'قطاع غزة - الجلاء', '2024-12-14 10:16:59', '972599999999', NULL, 5001),
(2, 'حساب تجريبي - تاجر', 'قطاع غزة - الرمال - السرايا', '2024-12-14 10:17:28', '972599999999', NULL, 5000);

-- --------------------------------------------------------

--
-- Table structure for table `customer_transaction`
--

CREATE TABLE `customer_transaction` (
  `id` int NOT NULL,
  `tr_type` enum('deposit','withdraw') COLLATE utf8mb4_general_ci NOT NULL,
  `tr_amount` decimal(10,2) NOT NULL,
  `tr_descripcion` text COLLATE utf8mb4_general_ci NOT NULL,
  `tr_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `customer_id` int NOT NULL,
  `tr_currency` varchar(3) COLLATE utf8mb4_general_ci NOT NULL,
  `Delete_Date` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

CREATE TABLE `employee` (
  `Employee_id` int NOT NULL,
  `Employee_FullName` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `Employee_Email` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `Employee_Phone` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `Employee_Address` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `job_titel` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `Salary` decimal(10,0) NOT NULL,
  `loan` decimal(10,2) DEFAULT '0.00',
  `salary_paid` tinyint(1) DEFAULT '0',
  `avatar_path` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Delete_Date` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employee`
--

INSERT INTO `employee` (`Employee_id`, `Employee_FullName`, `Employee_Email`, `Employee_Phone`, `Employee_Address`, `job_titel`, `Salary`, `loan`, `salary_paid`, `avatar_path`, `Delete_Date`) VALUES
(225000, 'مدير النظام', 'admin@dollar-ex.com', '0599999999', 'Maneger', 'Maneger', 0, 0.00, 0, 'assets/media/employee/225000.jpg', NULL),
(225001, 'محمود نضال سالم', 'mahmoud@dollar-ex.com', '0597649797', 'بيت لاهيا - العامودي', 'مدير عام', 5000, 0.00, 0, 'assets/media/employee/225001.jpg', NULL),
(225002, 'محمد نادر الغصين', 'mohammednaderalghussin@gmail.com', '+201015244128', 'غزة - الرمال', 'قسم الصرافة', 2000, 0.00, 0, NULL, NULL),
(225003, 'ابراهيم السحار', 'sahharhema2@gmail.com', '+972598082921', 'شمال غزة - بيت لاهيا', 'قسم الحوالات والمتابعة', 2000, 0.00, 0, NULL, NULL),
(225004, 'أنس عادل عوده', 'anasouda43@gmail.com', '‪+972592970150‬', 'قطاع غزة - الرمال', 'قسم اامحاسبة', 2000, 0.00, 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `employee_transactions`
--

CREATE TABLE `employee_transactions` (
  `transaction_id` int NOT NULL,
  `employee_id` int NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `transaction_type` enum('Advances','Salary') COLLATE utf8mb4_general_ci NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Delete_Date` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `exchange_rates`
--

CREATE TABLE `exchange_rates` (
  `id` int NOT NULL,
  `currency_ex` varchar(25) COLLATE utf8mb4_general_ci NOT NULL,
  `buy_rate` decimal(10,3) NOT NULL,
  `sell_rate` decimal(10,3) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fund_sname` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `Delete_Date` timestamp NULL DEFAULT NULL,
  `last_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `first_account` varchar(3) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `second_account` varchar(3) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `exchange_rates`
--

INSERT INTO `exchange_rates` (`id`, `currency_ex`, `buy_rate`, `sell_rate`, `created_at`, `fund_sname`, `Delete_Date`, `last_updated`, `first_account`, `second_account`) VALUES
(36, '10100_10101', 3.600, 3.630, '2024-12-18 13:37:24', 'دولار أمريكي - شيكل إسرائيلي', NULL, '2024-12-18 13:37:24', '001', '005'),
(37, '10100_10102', 0.707, 0.710, '2024-12-18 13:37:45', 'دولار أمريكي - دينار أردني', NULL, '2024-12-18 13:37:45', '001', '010'),
(38, '10100_10103', 1.050, 1.070, '2024-12-18 13:37:56', 'دولار أمريكي - يورو', NULL, '2024-12-18 13:37:56', '001', '015'),
(39, '10100_10104', 50.900, 51.200, '2024-12-18 13:45:39', 'دولار أمريكي - جنيه مصري', NULL, '2024-12-18 13:45:39', '001', '020'),
(40, '10100_10105', 3.700, 3.730, '2024-12-18 13:45:48', 'دولار أمريكي - ريال سعودي', NULL, '2024-12-18 13:45:48', '001', '025'),
(41, '10100_10106', 3.650, 3.700, '2024-12-18 13:46:01', 'دولار أمريكي - درهم إماراتي', NULL, '2024-12-18 13:46:01', '001', '030'),
(42, '10100_10107', 0.790, 0.810, '2024-12-18 13:46:16', 'دولار أمريكي - جنيه إسترليني', NULL, '2024-12-18 13:46:16', '001', '035');

-- --------------------------------------------------------

--
-- Table structure for table `income_transfer`
--

CREATE TABLE `income_transfer` (
  `id` int NOT NULL,
  `ils_amount` decimal(10,2) NOT NULL,
  `usd_amount` decimal(10,2) NOT NULL,
  `CreatedDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Delete_Date` timestamp NULL DEFAULT NULL,
  `created_by` varchar(255) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` int NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Delete_Date` timestamp NULL DEFAULT NULL,
  `is_core` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `description`, `created_at`, `Delete_Date`, `is_core`) VALUES
(1, 'permission_1', 'لوحة التحكم', '2024-12-16 16:22:35', NULL, 1),
(2, 'permission_2', 'تحويل العملات', '2024-12-16 16:24:13', NULL, 1),
(3, 'permission_3', 'إدارة الحسابات', '2024-12-16 16:24:37', NULL, 1),
(4, 'permission_4', 'إدارة العملات', '2024-12-16 16:25:07', NULL, 1),
(5, 'permission_5', 'تحويلات / خدمات', '2024-12-16 16:26:33', NULL, 1),
(6, 'permission_6', 'تقرير تحويلات إلكترونية', '2024-12-16 16:26:50', NULL, 1),
(7, 'permission_7', 'صناديق العملات', '2024-12-16 16:26:58', NULL, 1),
(8, 'permission_8', 'سندات صرف - قبض', '2024-12-16 16:27:02', NULL, 1),
(9, 'permission_9', 'تقارير', '2024-12-16 16:27:09', NULL, 1),
(10, 'permission_10', 'حسابات الزبائن', '2024-12-16 16:27:16', NULL, 1),
(11, 'permission_11', 'الموظفين', '2024-12-16 16:27:22', NULL, 1),
(12, 'permission_12', 'إعدادات', '2024-12-16 16:27:27', NULL, 1),
(13, 'permission_13', 'إدارة المستخدمين', '2024-12-16 16:27:38', NULL, 1),
(14, 'permission_14', 'تقارير صرافة يومية', '2024-12-17 01:24:45', NULL, 1),
(15, 'permission_15', 'صناديق النقد الرئيسية', '2024-12-17 01:28:05', NULL, 0),
(16, 'permission_16', 'صناديق النقد الفرعية', '2024-12-17 01:28:11', NULL, 0),
(17, 'permission_17', 'صناديق إيرادات', '2024-12-17 01:28:17', NULL, 0),
(18, 'permission_18', 'إجمالي حسابات علينا - لنا', '2024-12-17 01:28:26', NULL, 0),
(19, 'permission_19', 'ترحيل إيرادات', '2024-12-17 01:42:57', NULL, 1),
(20, 'permission_20', 'تحديث اسعار الصرف', '2024-12-17 02:21:43', NULL, 0),
(21, 'permission_21', 'إرجاع فاتورة', '2024-12-17 02:21:49', NULL, 0),
(22, 'permission_22', 'عرض سجل التحويلات', '2024-12-17 02:23:23', NULL, 0),
(23, 'permission_23', 'إحصائيات كارت 1', '2024-12-17 02:25:06', NULL, 0),
(24, 'permission_24', 'إختصارات لوحة التحكم', '2024-12-17 02:26:02', NULL, 0),
(25, 'permission_25', 'إضافة فئة عملات', '2024-12-18 13:36:49', NULL, 0),
(26, 'permission_26', 'مجموع صناديق النقد الرئيسية', '2024-12-18 13:49:44', NULL, 1),
(27, 'permission_27', 'مجموع صناديق النقد الفرعية', '2024-12-18 13:49:57', NULL, 1),
(28, 'permission_28', 'مجموع صناديق النقد الإيرادات', '2024-12-18 13:50:12', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Delete_Date` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `description`, `created_at`, `Delete_Date`) VALUES
(1, 'role_1', 'مدير النظام - Maneger', '2024-12-15 22:24:03', NULL),
(2, 'role_2', 'قسم الإدارة - Admin', '2024-12-15 22:24:59', NULL),
(3, 'role_3', 'قسم الخدمات والتحويلات - Service', '2024-12-15 22:25:30', NULL),
(5, 'role_4', 'قسم الصرافة', '2024-12-15 23:53:28', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `role_permissions`
--

CREATE TABLE `role_permissions` (
  `id` int NOT NULL,
  `role_id` int NOT NULL,
  `permission_id` int NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Delete_Date` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `role_permissions`
--

INSERT INTO `role_permissions` (`id`, `role_id`, `permission_id`, `created_at`, `Delete_Date`) VALUES
(735, 1, 1, '2024-12-18 14:46:29', NULL),
(736, 1, 2, '2024-12-18 14:46:29', NULL),
(737, 1, 3, '2024-12-18 14:46:29', NULL),
(738, 1, 4, '2024-12-18 14:46:29', NULL),
(739, 1, 5, '2024-12-18 14:46:29', NULL),
(740, 1, 7, '2024-12-18 14:46:29', NULL),
(741, 1, 8, '2024-12-18 14:46:29', NULL),
(742, 1, 9, '2024-12-18 14:46:29', NULL),
(743, 1, 10, '2024-12-18 14:46:29', NULL),
(744, 1, 11, '2024-12-18 14:46:29', NULL),
(745, 1, 12, '2024-12-18 14:46:29', NULL),
(746, 1, 13, '2024-12-18 14:46:29', NULL),
(747, 1, 15, '2024-12-18 14:46:29', NULL),
(748, 1, 16, '2024-12-18 14:46:29', NULL),
(749, 1, 17, '2024-12-18 14:46:29', NULL),
(750, 1, 18, '2024-12-18 14:46:29', NULL),
(751, 1, 19, '2024-12-18 14:46:29', NULL),
(752, 1, 20, '2024-12-18 14:46:29', NULL),
(753, 1, 21, '2024-12-18 14:46:29', NULL),
(754, 1, 22, '2024-12-18 14:46:29', NULL),
(755, 1, 23, '2024-12-18 14:46:29', NULL),
(756, 1, 24, '2024-12-18 14:46:29', NULL),
(757, 1, 25, '2024-12-18 14:46:29', NULL),
(758, 1, 26, '2024-12-18 14:46:29', NULL),
(759, 1, 27, '2024-12-18 14:46:29', NULL),
(760, 1, 28, '2024-12-18 14:46:29', NULL),
(789, 2, 1, '2024-12-18 15:13:35', NULL),
(790, 2, 2, '2024-12-18 15:13:35', NULL),
(791, 2, 3, '2024-12-18 15:13:35', NULL),
(792, 2, 4, '2024-12-18 15:13:35', NULL),
(793, 2, 5, '2024-12-18 15:13:35', NULL),
(794, 2, 7, '2024-12-18 15:13:35', NULL),
(795, 2, 8, '2024-12-18 15:13:35', NULL),
(796, 2, 9, '2024-12-18 15:13:35', NULL),
(797, 2, 10, '2024-12-18 15:13:35', NULL),
(798, 2, 11, '2024-12-18 15:13:35', NULL),
(799, 2, 12, '2024-12-18 15:13:35', NULL),
(800, 2, 15, '2024-12-18 15:13:35', NULL),
(801, 2, 16, '2024-12-18 15:13:35', NULL),
(802, 2, 17, '2024-12-18 15:13:35', NULL),
(803, 2, 18, '2024-12-18 15:13:35', NULL),
(804, 2, 19, '2024-12-18 15:13:35', NULL),
(805, 2, 20, '2024-12-18 15:13:35', NULL),
(806, 2, 21, '2024-12-18 15:13:35', NULL),
(807, 2, 22, '2024-12-18 15:13:35', NULL),
(808, 2, 23, '2024-12-18 15:13:35', NULL),
(809, 2, 24, '2024-12-18 15:13:35', NULL),
(810, 2, 25, '2024-12-18 15:13:35', NULL),
(811, 2, 26, '2024-12-18 15:13:35', NULL),
(812, 2, 27, '2024-12-18 15:13:35', NULL),
(813, 2, 28, '2024-12-18 15:13:35', NULL),
(814, 3, 1, '2024-12-18 15:21:55', NULL),
(815, 3, 5, '2024-12-18 15:21:55', NULL),
(816, 3, 6, '2024-12-18 15:21:55', NULL),
(817, 3, 7, '2024-12-18 15:21:55', NULL),
(818, 3, 8, '2024-12-18 15:21:55', NULL),
(819, 3, 10, '2024-12-18 15:21:55', NULL),
(820, 3, 16, '2024-12-18 15:21:55', NULL),
(821, 3, 17, '2024-12-18 15:21:55', NULL),
(822, 3, 24, '2024-12-18 15:21:55', NULL),
(823, 3, 27, '2024-12-18 15:21:55', NULL),
(824, 5, 1, '2024-12-18 16:13:40', NULL),
(825, 5, 2, '2024-12-18 16:13:40', NULL),
(826, 5, 7, '2024-12-18 16:13:40', NULL),
(827, 5, 14, '2024-12-18 16:13:40', NULL),
(828, 5, 15, '2024-12-18 16:13:40', NULL),
(829, 5, 23, '2024-12-18 16:13:40', NULL),
(830, 5, 24, '2024-12-18 16:13:40', NULL),
(831, 5, 26, '2024-12-18 16:13:40', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int NOT NULL,
  `time_zone` varchar(255) NOT NULL,
  `date_format` varchar(255) NOT NULL,
  `time_format` varchar(255) NOT NULL,
  `fiscal_year_start` int NOT NULL,
  `whatsApp_logo` varchar(255) NOT NULL,
  `exchange_rate_sub` decimal(10,3) NOT NULL,
  `vodafone_cash_price` decimal(10,3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `time_zone`, `date_format`, `time_format`, `fiscal_year_start`, `whatsApp_logo`, `exchange_rate_sub`, `vodafone_cash_price`) VALUES
(1, 'Asia/Gaza', 'd-m-Y', '12', 1, 'assets/media/dollar/Whatsapp_code.png', 3.610, 40.000);

-- --------------------------------------------------------

--
-- Table structure for table `transfers`
--

CREATE TABLE `transfers` (
  `id` int NOT NULL,
  `transfer_id` int DEFAULT NULL,
  `from_account_id` int DEFAULT NULL,
  `from_amount` decimal(10,2) DEFAULT NULL,
  `from_type` enum('deposit','withdraw') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `to_account_id` int DEFAULT NULL,
  `to_amount` decimal(10,2) DEFAULT NULL,
  `to_type` enum('deposit','withdraw') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `income_fund` int DEFAULT NULL,
  `income_amount` decimal(10,2) DEFAULT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `created_by` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Delete_Date` timestamp NULL DEFAULT NULL,
  `from_account_type` enum('customer','funds') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `to_account_type` enum('customer','funds') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tr_from_id` varchar(11) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tr_to_id` varchar(11) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `cut_vodafone` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `type`
--

CREATE TABLE `type` (
  `type_id` varchar(4) COLLATE utf8mb4_general_ci NOT NULL,
  `type_name` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `type_sname` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `Delete_Date` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `type`
--

INSERT INTO `type` (`type_id`, `type_name`, `created_at`, `type_sname`, `Delete_Date`) VALUES
('3000', 'main_account', '2023-07-06 22:04:23', 'حساب رئيسي', NULL),
('3100', 'sub-account', '2023-07-06 22:06:21', 'حساب فرعي', NULL),
('3200', 'income_account', '2023-07-06 22:09:25', 'حساب إيرادات', NULL),
('5000', 'traders', '2024-10-24 00:33:13', 'حسابات تاجر', NULL),
('5001', 'customers', '2024-10-24 00:33:28', 'حسابات زبون', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `account`
--
ALTER TABLE `account`
  ADD PRIMARY KEY (`AccountID`);

--
-- Indexes for table `accounting`
--
ALTER TABLE `accounting`
  ADD PRIMARY KEY (`account_number`),
  ADD KEY `fk_accounting_account_type` (`account_type`),
  ADD KEY `fk_accounting_currency` (`currency_id`);

--
-- Indexes for table `bonds`
--
ALTER TABLE `bonds`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `company_info`
--
ALTER TABLE `company_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `convert_types`
--
ALTER TABLE `convert_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `currency`
--
ALTER TABLE `currency`
  ADD PRIMARY KEY (`currency_id`);

--
-- Indexes for table `currency_exchange`
--
ALTER TABLE `currency_exchange`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_traders_currency_customer` (`customer_id`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`customer_id`);

--
-- Indexes for table `customer_transaction`
--
ALTER TABLE `customer_transaction`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_traders_transaction_customer` (`customer_id`) USING BTREE,
  ADD KEY `tr_currency` (`tr_currency`);

--
-- Indexes for table `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`Employee_id`);

--
-- Indexes for table `employee_transactions`
--
ALTER TABLE `employee_transactions`
  ADD PRIMARY KEY (`transaction_id`),
  ADD KEY `employee_id` (`employee_id`);

--
-- Indexes for table `exchange_rates`
--
ALTER TABLE `exchange_rates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `income_transfer`
--
ALTER TABLE `income_transfer`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `role_permissions`
--
ALTER TABLE `role_permissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `role_id` (`role_id`),
  ADD KEY `permission_id` (`permission_id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transfers`
--
ALTER TABLE `transfers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `income_fund` (`income_fund`);

--
-- Indexes for table `type`
--
ALTER TABLE `type`
  ADD PRIMARY KEY (`type_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bonds`
--
ALTER TABLE `bonds`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `company_info`
--
ALTER TABLE `company_info`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `convert_types`
--
ALTER TABLE `convert_types`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `currency_exchange`
--
ALTER TABLE `currency_exchange`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3521;

--
-- AUTO_INCREMENT for table `customer_transaction`
--
ALTER TABLE `customer_transaction`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=713;

--
-- AUTO_INCREMENT for table `employee_transactions`
--
ALTER TABLE `employee_transactions`
  MODIFY `transaction_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `exchange_rates`
--
ALTER TABLE `exchange_rates`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `income_transfer`
--
ALTER TABLE `income_transfer`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `role_permissions`
--
ALTER TABLE `role_permissions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=832;

--
-- AUTO_INCREMENT for table `transfers`
--
ALTER TABLE `transfers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=816;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `accounting`
--
ALTER TABLE `accounting`
  ADD CONSTRAINT `fk_accounting_account_type` FOREIGN KEY (`account_type`) REFERENCES `type` (`type_id`),
  ADD CONSTRAINT `fk_accounting_currency` FOREIGN KEY (`currency_id`) REFERENCES `currency` (`currency_id`);

--
-- Constraints for table `currency_exchange`
--
ALTER TABLE `currency_exchange`
  ADD CONSTRAINT `fk_traders_currency_customer` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`customer_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `customer_transaction`
--
ALTER TABLE `customer_transaction`
  ADD CONSTRAINT `customer_transaction_ibfk_1` FOREIGN KEY (`tr_currency`) REFERENCES `currency` (`currency_id`),
  ADD CONSTRAINT `fk_traders_currency_exchange_customer` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`customer_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_traders_transaction_customer` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`customer_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `employee_transactions`
--
ALTER TABLE `employee_transactions`
  ADD CONSTRAINT `employee_transactions_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employee` (`Employee_id`);

--
-- Constraints for table `transfers`
--
ALTER TABLE `transfers`
  ADD CONSTRAINT `transfers_ibfk_3` FOREIGN KEY (`income_fund`) REFERENCES `accounting` (`account_number`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
