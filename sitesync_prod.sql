-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 19, 2025 at 09:01 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sitesync_prod`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_material`
--

CREATE TABLE `activity_material` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `act_id` int(11) DEFAULT NULL,
  `category` text DEFAULT NULL,
  `unit` text DEFAULT NULL,
  `qty` int(11) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `c_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `activity_work`
--

CREATE TABLE `activity_work` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `import_id` int(11) DEFAULT NULL,
  `pro_id` int(11) DEFAULT NULL,
  `stage` varchar(200) DEFAULT NULL,
  `sub` text DEFAULT NULL,
  `cat` varchar(255) DEFAULT NULL,
  `qc` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `qc_per` int(11) DEFAULT NULL,
  `progress` varchar(20) DEFAULT NULL,
  `next_day` varchar(255) DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `file` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `status` varchar(255) DEFAULT 'Active',
  `c_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `pro_id` int(11) DEFAULT NULL,
  `category` varchar(200) DEFAULT NULL,
  `skilled` varchar(10) DEFAULT NULL,
  `mc` varchar(10) DEFAULT NULL,
  `fc` varchar(10) DEFAULT NULL,
  `status` varchar(30) DEFAULT NULL,
  `c_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`id`, `pro_id`, `category`, `skilled`, `mc`, `fc`, `status`, `c_by`, `created_at`, `updated_at`) VALUES
(1, 1, 'Centering', '4.0', '5.0', '5.0', 'Active', 3, '2025-12-03 17:25:05', '2025-12-03 17:25:05');

-- --------------------------------------------------------

--
-- Table structure for table `block_table`
--

CREATE TABLE `block_table` (
  `id` int(11) NOT NULL,
  `cat` varchar(100) DEFAULT NULL,
  `work_id` int(11) DEFAULT NULL,
  `c_by` int(11) DEFAULT NULL,
  `status` varchar(30) NOT NULL DEFAULT 'Active',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `category` varchar(100) NOT NULL,
  `category_title` varchar(255) NOT NULL,
  `category_description` text DEFAULT NULL,
  `status` varchar(20) DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `checklist`
--

CREATE TABLE `checklist` (
  `id` int(11) NOT NULL,
  `qc_id` int(11) NOT NULL,
  `question` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `comment`
--

CREATE TABLE `comment` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `task_id` bigint(20) UNSIGNED DEFAULT NULL,
  `comment_for` int(11) DEFAULT NULL,
  `desp` text DEFAULT NULL,
  `status` varchar(25) DEFAULT 'Active',
  `c_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `comment`
--

INSERT INTO `comment` (`id`, `task_id`, `comment_for`, `desp`, `status`, `c_by`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 'hhh', 'Active', 2, '2025-11-10 14:31:02', '2025-11-10 14:31:02'),
(2, 1, 2, 'yyhy', 'Active', 2, '2025-11-10 14:31:16', '2025-11-10 14:31:16'),
(3, 1, 2, 'ffff', 'Active', 3, '2025-11-10 14:31:27', '2025-11-10 14:31:27');

-- --------------------------------------------------------

--
-- Table structure for table `company`
--

CREATE TABLE `company` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `district` varchar(100) DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `pincode` varchar(10) DEFAULT NULL,
  `gst_number` varchar(20) DEFAULT NULL,
  `pan_number` varchar(20) DEFAULT NULL,
  `msme_number` varchar(20) DEFAULT NULL,
  `gst_attachment` varchar(255) DEFAULT NULL,
  `pancard_attachment` varchar(255) DEFAULT NULL,
  `msme_attachment` varchar(255) DEFAULT NULL,
  `bank_name` varchar(100) DEFAULT NULL,
  `account_holder_name` varchar(255) DEFAULT NULL,
  `account_number` varchar(50) DEFAULT NULL,
  `ifsc_code` varchar(20) DEFAULT NULL,
  `branch_name` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `company`
--

INSERT INTO `company` (`id`, `name`, `logo`, `address`, `district`, `state`, `pincode`, `gst_number`, `pan_number`, `msme_number`, `gst_attachment`, `pancard_attachment`, `msme_attachment`, `bank_name`, `account_holder_name`, `account_number`, `ifsc_code`, `branch_name`, `created_at`, `updated_at`) VALUES
(1, 'RAELN Buildtech LLP (SuperHomes)', 'company/SuperHomes_TM_Logo_(1).jpg', '2/515A, 2nd main road, Sandeep Avenue, Neelankarai', 'Chennai', 'TN', '600115', '234523', '24524', '25245', 'storage/uploads/company/Print Bill.pdf', 'storage/uploads/company/Print Barcodes.pdf', 'storage/uploads/company/Print Barcode1.pdf', '34534', '34534', '345345', '3534534 123', 'Main Road Branch 123', '2025-02-27 01:36:41', '2025-12-10 07:58:05');

-- --------------------------------------------------------

--
-- Table structure for table `designation`
--

CREATE TABLE `designation` (
  `id` int(11) NOT NULL,
  `department_id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `status` varchar(20) DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `designation`
--

INSERT INTO `designation` (`id`, `department_id`, `title`, `description`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'Accounts', 'Accounts Department', 'active', '2025-02-25 01:02:42', '2025-02-25 01:02:42'),
(2, 1, 'Finance', 'Finance Department', 'active', '2025-02-25 01:02:42', '2025-02-28 01:29:20'),
(3, 1, 'Developments', 'Development Department', 'active', '2025-02-24 22:15:37', '2025-02-28 01:29:23');

-- --------------------------------------------------------

--
-- Table structure for table `drawing`
--

CREATE TABLE `drawing` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `file_type` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `status` varchar(20) DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `drawing`
--

INSERT INTO `drawing` (`id`, `title`, `file_type`, `description`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Ground Floor drawing', '2D Floor Plans', 'ground floor drawing is must', 'active', '2025-02-26 03:25:12', '2025-12-19 06:48:29'),
(2, 'First Floor drawing', '2D Floor Plans', '.', 'active', '2025-02-26 03:25:12', '2025-12-19 06:48:30'),
(3, 'Floor Plan', 'Electrical Drawings', 'Floor Plan 3D', 'active', '2025-02-25 22:17:15', '2025-03-12 20:41:32'),
(4, 'WD Title', 'Working Drawings', 'WD Title Desc', 'active', '2025-03-12 20:41:53', '2025-03-12 20:41:53'),
(5, 'SD Title', 'Sectional Drawings', 'SD Title Desc', 'active', '2025-03-12 20:42:22', '2025-03-12 20:42:22'),
(6, 'STD Title', 'Structural Drawings', 'STD Title Desc', 'active', '2025-03-12 20:42:38', '2025-11-10 08:59:22'),
(7, 'Second Floor Drawing', '2D Floor Plans', '.', 'active', '2025-05-03 02:39:14', '2025-12-19 06:48:31'),
(8, 'new title', 'Structural Drawings', 'check', 'active', '2025-06-12 00:20:34', '2025-11-10 08:59:25');

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

CREATE TABLE `employee` (
  `id` int(11) NOT NULL,
  `employee_code` varchar(50) NOT NULL,
  `name` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `auth_token` varchar(100) DEFAULT NULL,
  `contact_number` varchar(15) DEFAULT NULL,
  `email_id` varchar(100) DEFAULT NULL,
  `designation_id` int(11) DEFAULT NULL,
  `role_id` int(11) DEFAULT NULL,
  `token` text DEFAULT NULL,
  `status` varchar(20) DEFAULT 'active',
  `image_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employee`
--

INSERT INTO `employee` (`id`, `employee_code`, `name`, `password`, `auth_token`, `contact_number`, `email_id`, `designation_id`, `role_id`, `token`, `status`, `image_path`, `created_at`, `updated_at`) VALUES
(1, '1001', 'Sugan', '123456', NULL, '6374943202', 'sugan@gmail.com', NULL, 1, NULL, 'active', 'employees/Mitchell_Strac_Pics.jpg', '2025-08-13 22:51:14', '2025-12-18 07:23:02'),
(2, 'SH-001', 'Vasanthakumar.T', '123456', NULL, '7603924190', 'vasanth@superhomes.co', NULL, 3, NULL, 'active', 'employees/Vasanth.jpg', '2025-08-19 23:20:28', '2025-12-18 07:29:31'),
(3, 'SH-000', 'Varun', '521652', NULL, '9360397461', 'varun@superhome.co', NULL, 1, 'cPGGH3ftQWWZ89R4B-iQJ-:APA91bG29P1Hn6FMZt_jsvyrvGNyUq8pFsaUv4ZpB9OhViopOym2a9o-9VMvSDXeNQufh7mqDgFJRfsEl5SxtjC9sZW6l3X9e36HZEz1vc1xpBwMDSXXBeA', 'active', NULL, '2025-08-19 23:21:04', '2025-12-19 06:19:49'),
(4, 'SH-002', 'Alex Pandian', '123456', NULL, '9486164004', 'alex@superhomes.co', NULL, 2, 'fB89_lnxTIieB4Rd35BkgI:APA91bFSSVSA5hEoInx-w7BZDYPRuByxynvNGOyMH-RAul_nloHZuA4_xyAxkzI01lP6z48Lm--kH4tGhA2B2VuEH8MZie6rATDhMxQ9jOSaKjgfOnSJyVU', 'active', 'employees/Alex.jpg', '2025-08-19 23:22:44', '2025-12-18 07:29:36'),
(7, 'SH-003', 'Shanmugam', '123456', NULL, '9751865983', 'shanmugam@superhomes.co', NULL, 5, NULL, 'active', 'employees/IMG-20220614-WA0015.jpg', '2025-12-10 08:07:35', '2025-12-13 09:03:22'),
(8, 'SH-004', 'Vignesh', '123456', NULL, '8122359665', 'vignesh@superhomes.co', NULL, 3, 'eNuC5I8PQPWr9RW7z_6tbM:APA91bEqAVxGdMdVBbUYsz5JJkgh6wZHeIaFB28cWw4x4xEN1uf3v_38syjGpo179YNUwMk0KweXgYJEecGQWZykCW3lMSDXUVothLlRP6Stqg73U8xnxKI', 'active', 'employees/DSC03247.jpg', '2025-12-10 08:09:02', '2025-12-12 11:12:26'),
(9, 'SH-005', 'Muruganandham', '123456', NULL, '9342260115', 'muruganandham@superhomes.co', NULL, 3, NULL, 'active', 'employees/Muruganandam_(2).jpeg', '2025-12-10 08:11:09', '2025-12-13 09:03:37'),
(10, 'SH-006', 'Ezhilarasan', '123456', NULL, '8220500631', 'ezhil@superhomes.co', NULL, 3, 'cFBl4g6RQ0alXzl6DE0H9Q:APA91bEtXHMKaikGh-5Po4odkCGZ_5jDFab87T-AE9JjKTpry-hIeeZFa6A7Ss4tlu3LWpTV7t6DPr5mn3jkmoa9yuc-wrrM1ZuuRuDwD9CptUNY19lz-8A', 'active', 'employees/Ezhil_(2).JPG', '2025-12-10 08:12:27', '2025-12-13 09:03:48'),
(11, 'SH-009', 'Tamilselvan', '123456', NULL, '8220191861', 'tamilselvan@superhomes.co', NULL, 3, 'dVsAmFi3RauaXHbsoCyqTT:APA91bFHWOqRwq8y797JsuBxBOGNQnBaBcVI4QoPbvqnmRxdIfrY6e7Y4obAjVt0gA49v0RkoFcOTi9k7i6ksvQBX_wgopGXAxEiwb7TXiS0g6soGoPw4oo', 'active', 'employees/WhatsApp_Image_2025-12-10_at_1.45.48_PM.jpeg', '2025-12-10 08:16:14', '2025-12-12 11:20:42'),
(12, 'SH-010', 'Praveen', '123456', NULL, '9791510075', 'praveen@superhome.co', NULL, 3, NULL, 'active', 'employees/WhatsApp_Image_2025-12-10_at_1.49.09_PM.jpeg', '2025-12-10 08:19:36', '2025-12-10 08:19:36'),
(13, 'SH-011', 'Ezhilraj', '123456', NULL, '6383807722', 'ezhilraj@superhomes.co', NULL, 3, 'cWVkDRPATBunzw7zQb512O:APA91bEinHNsJpPK8OC0tBqde3gSXexxPWKf_e7jCV3ojiLMwhlsFmoZ1E4wUnjO2fqtrNEMD1rUWZCHprxCQLyxX7n3BMdzIeX7hxpBC8yHX4LItLdLHr0', 'active', 'employees/WhatsApp_Image_2025-12-10_at_1.52.14_PM.jpeg', '2025-12-10 08:24:03', '2025-12-12 11:12:38');

-- --------------------------------------------------------

--
-- Table structure for table `entry_drawing`
--

CREATE TABLE `entry_drawing` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `project_id` int(11) DEFAULT NULL,
  `drawing_id` bigint(20) UNSIGNED NOT NULL,
  `uploaded_by` bigint(20) UNSIGNED NOT NULL,
  `uploaded_on` datetime NOT NULL,
  `version` varchar(50) DEFAULT NULL,
  `is_draft` int(11) DEFAULT 0,
  `file_attachment` varchar(255) NOT NULL,
  `file_name` varchar(255) DEFAULT NULL,
  `status` varchar(20) DEFAULT 'pending',
  `c_by` varchar(25) DEFAULT NULL,
  `approved_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `entry_drawing`
--

INSERT INTO `entry_drawing` (`id`, `project_id`, `drawing_id`, `uploaded_by`, `uploaded_on`, `version`, `is_draft`, `file_attachment`, `file_name`, `status`, `c_by`, `approved_by`, `created_at`, `updated_at`) VALUES
(1, 1, 7, 3, '2025-11-10 14:13:54', 'Version 1', 0, 'IMG-20251110-WA0022.jpg', 'IMG-20251110-WA0022.jpg', 'Approved', '3', 3, '2025-11-10 08:43:54', '2025-11-10 08:46:03'),
(2, 1, 3, 3, '2025-11-10 14:17:02', 'Version 1', 0, 'IMG-20251110-WA0022.jpg', 'IMG-20251110-WA0022.jpg', 'Approved', '3', 2, '2025-11-10 08:47:02', '2025-11-10 08:47:42'),
(3, 1, 4, 3, '2025-11-17 20:32:47', 'Version 1', 0, 'hariharan_first_floor.pdf', 'hariharan_first_floor.pdf', 'pending', '3', NULL, '2025-11-17 15:02:47', '2025-11-17 15:02:47'),
(4, 1, 1, 3, '2025-12-12 11:53:25', 'Version 1', 0, 'maran-_scheme-_OP5_(1)_-_13.11.25.pdf', 'maran-_scheme-_OP5_(1)_-_13.11.25.pdf', 'Approved', '3', 3, '2025-12-12 06:23:25', '2025-12-12 06:33:51'),
(5, 1, 1, 3, '2025-12-12 12:04:30', 'Version 2', 0, 'WORKING_DWG_NIRANJAN_SH-F.pdf', 'WORKING_DWG_NIRANJAN_SH-F.pdf', 'pending', '3', NULL, '2025-12-12 06:34:30', '2025-12-12 06:34:30'),
(6, 1, 2, 1, '2025-12-19 11:59:19', 'Version 1', 0, 'placeholder.pdf', NULL, 'Pending', NULL, NULL, '2025-12-19 06:29:19', '2025-12-19 06:29:19');

-- --------------------------------------------------------

--
-- Table structure for table `entry_qc`
--

CREATE TABLE `entry_qc` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `project_id` int(11) DEFAULT NULL,
  `qc_title` int(11) NOT NULL,
  `checklist` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `assigned_to` int(11) NOT NULL,
  `due_date` date NOT NULL,
  `file_name` varchar(255) DEFAULT NULL,
  `file_attachment` varchar(255) DEFAULT NULL,
  `status` varchar(100) DEFAULT 'in_progress',
  `c_by` varchar(25) DEFAULT NULL,
  `approved_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `entry_qc`
--

INSERT INTO `entry_qc` (`id`, `project_id`, `qc_title`, `checklist`, `assigned_to`, `due_date`, `file_name`, `file_attachment`, `status`, `c_by`, `approved_by`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '[\"1\",\"2\",\"3\",\"4\"]', 3, '2025-11-15', '3.pdf', '3.pdf', 'in_progress', '2', NULL, '2025-11-14 11:16:54', '2025-11-14 11:16:54'),
(2, 1, 2, '[\"5\",\"6\",\"7\",\"8\"]', 2, '2025-11-14', 'images_(1).jpeg', 'images_(1).jpeg', 'in_progress', '3', NULL, '2025-11-14 11:24:10', '2025-11-14 11:24:10');

-- --------------------------------------------------------

--
-- Table structure for table `entry_snag`
--

CREATE TABLE `entry_snag` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `project_id` int(11) DEFAULT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `description` text NOT NULL,
  `assigned_to` bigint(20) UNSIGNED NOT NULL,
  `due_date` date NOT NULL,
  `file_name` varchar(255) DEFAULT NULL,
  `file_attachment` varchar(255) DEFAULT NULL,
  `location` text DEFAULT NULL,
  `status` varchar(20) DEFAULT 'in_progress',
  `c_by` varchar(25) DEFAULT NULL,
  `approved_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `entry_snag`
--

INSERT INTO `entry_snag` (`id`, `project_id`, `category_id`, `description`, `assigned_to`, `due_date`, `file_name`, `file_attachment`, `location`, `status`, `c_by`, `approved_by`, `created_at`, `updated_at`) VALUES
(12, 1, 3, 'kk', 2, '2025-11-10', 'Veenar-Door.jpg', 'Veenar-Door.jpg', 'TF', 'approved', '3', 3, '2025-11-10 14:42:18', '2025-11-10 14:46:23'),
(14, 1, 1, 'Test', 3, '2025-11-15', '3.pdf', '3.pdf', 'First floor', 'completed', '2', NULL, '2025-11-14 11:17:12', '2025-11-14 11:17:31'),
(15, 1, 1, 'check plastering', 2, '2025-12-13', 'fed76e01-245a-4c23-b716-2c9823e687893576632621647195919.jpg', 'fed76e01-245a-4c23-b716-2c9823e687893576632621647195919.jpg', 'FF bedroom 2', 'approved', '3', 3, '2025-12-12 17:14:07', '2025-12-12 17:18:35');

-- --------------------------------------------------------

--
-- Table structure for table `entry_survey`
--

CREATE TABLE `entry_survey` (
  `id` int(11) NOT NULL,
  `project_id` int(11) DEFAULT NULL,
  `survey_id` int(11) NOT NULL,
  `instruction` text DEFAULT NULL,
  `assigned_to` int(11) NOT NULL,
  `due_date` date DEFAULT NULL,
  `file_attachment` varchar(255) DEFAULT NULL,
  `c_by` varchar(30) DEFAULT NULL,
  `approved_by` int(11) DEFAULT NULL,
  `reminder` varchar(20) NOT NULL DEFAULT 'false',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` varchar(20) DEFAULT 'in_progress'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `entry_survey`
--

INSERT INTO `entry_survey` (`id`, `project_id`, `survey_id`, `instruction`, `assigned_to`, `due_date`, `file_attachment`, `c_by`, `approved_by`, `reminder`, `created_at`, `updated_at`, `status`) VALUES
(1, 1, 3, 'jjj', 2, '2025-11-11', NULL, '3', 3, 'false', '2025-11-10 09:08:39', '2025-11-10 09:11:00', 'approved'),
(4, 1, 1, 'Test', 3, '2025-11-15', '1.pdf', '2', NULL, 'false', '2025-11-14 05:46:35', '2025-11-14 05:52:10', 'completed'),
(5, 9, 3, 'Trainining', 2, '2025-12-13', NULL, '3', 3, 'false', '2025-12-12 11:33:05', '2025-12-12 11:37:01', 'approved');

-- --------------------------------------------------------

--
-- Table structure for table `ent_role_permissions`
--

CREATE TABLE `ent_role_permissions` (
  `id` int(11) NOT NULL,
  `role_id` varchar(255) NOT NULL,
  `permission` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `item`
--

CREATE TABLE `item` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `status` varchar(20) DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `master_roles`
--

CREATE TABLE `master_roles` (
  `id` int(11) NOT NULL,
  `role_title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `status` varchar(20) DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `master_roles`
--

INSERT INTO `master_roles` (`id`, `role_title`, `description`, `status`, `created_at`, `updated_at`) VALUES
(5, 'Admin', 'admin description', 'active', '2025-03-11 03:15:37', '2025-03-11 03:15:37'),
(6, 'Super Admin', 'super admin description', 'active', '2025-03-11 03:16:02', '2025-03-11 03:16:02'),
(7, 'Employee', 'new', 'active', '2025-06-06 00:39:43', '2025-06-06 00:39:43'),
(8, 'Role - 1', 'Role Description', 'active', '2025-06-16 05:43:38', '2025-06-16 05:43:38'),
(9, 'Role-1', 'Role Description', 'active', '2025-06-16 05:45:22', '2025-06-16 05:45:22');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\Employee', 1),
(1, 'App\\Models\\Employee', 3),
(1, 'App\\Models\\Employee', 5),
(1, 'App\\Models\\Employee', 6),
(2, 'App\\Models\\Employee', 4),
(3, 'App\\Models\\Employee', 2),
(3, 'App\\Models\\Employee', 8),
(3, 'App\\Models\\Employee', 9),
(3, 'App\\Models\\Employee', 10),
(3, 'App\\Models\\Employee', 11),
(3, 'App\\Models\\Employee', 12),
(3, 'App\\Models\\Employee', 13),
(4, 'App\\Models\\Employee', 5),
(5, 'App\\Models\\Employee', 7);

-- --------------------------------------------------------

--
-- Table structure for table `notification`
--

CREATE TABLE `notification` (
  `id` int(11) NOT NULL,
  `to_id` int(11) DEFAULT NULL,
  `f_id` int(11) DEFAULT NULL,
  `type` varchar(100) DEFAULT NULL,
  `title` text DEFAULT NULL,
  `body` text DEFAULT NULL,
  `c_by` int(11) DEFAULT NULL,
  `seen` int(11) DEFAULT NULL,
  `reminder` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notification`
--

INSERT INTO `notification` (`id`, `to_id`, `f_id`, `type`, `title`, `body`, `c_by`, `seen`, `reminder`, `created_at`, `updated_at`) VALUES
(1, 2, 1, 'project', 'Project -  Karthik - Guduvanchery', 'New Project Assigned By Varun', 3, 1, 0, '2025-11-10 08:42:37', '2025-11-10 08:45:13'),
(2, 3, 1, 'project', 'Project -  Karthik - Guduvanchery', 'New Project Assigned By Varun', 3, 1, 0, '2025-11-10 08:42:37', '2025-11-10 08:42:46'),
(3, 4, 1, 'project', 'Project -  Karthik - Guduvanchery', 'New Project Assigned By Varun', 3, NULL, 0, '2025-11-10 08:42:38', '2025-11-10 08:42:38'),
(4, 4, 1, 'draw', 'New Drawing Created by Varun', 'Second Floor Plan - 2D Floor Plans-Karthik - Guduvanchery', 3, NULL, 0, '2025-11-10 08:43:54', '2025-11-10 08:43:54'),
(5, 3, 1, 'draw', 'Drawing Approved by Varun', 'Second Floor Plan - 2D Floor Plans-Karthik - Guduvanchery', 3, 1, 0, '2025-11-10 08:46:03', '2025-11-10 08:59:41'),
(6, 4, 2, 'draw', 'New Drawing Created by Varun', 'Floor Plan - Electrical Drawings-Karthik - Guduvanchery', 3, NULL, 0, '2025-11-10 08:47:02', '2025-11-10 08:47:02'),
(7, 3, 2, 'draw', 'Drawing Approved by Vasanthakumar.T', 'Floor Plan - Electrical Drawings-Karthik - Guduvanchery', 2, 1, 0, '2025-11-10 08:47:43', '2025-11-10 08:59:43'),
(8, 2, 1, 'task', 'New Task Assigned by Varun', 'test 234 in Karthik - Guduvanchery', 3, 1, 0, '2025-11-10 09:00:24', '2025-11-10 09:00:43'),
(9, 2, 1, 'comment', 'Comment Added', 'Vasanthakumar.T Commented - \"hhh\" for task - test 234', 2, 1, 0, '2025-11-10 09:01:02', '2025-11-10 09:08:59'),
(10, 3, 1, 'comment', 'Comment Added', 'Vasanthakumar.T Commented - \"hhh\" for task - test 234', 2, 1, 0, '2025-11-10 09:01:03', '2025-11-10 09:14:59'),
(11, 2, 1, 'comment', 'Comment Added', 'Vasanthakumar.T Commented - \"yyhy\" for task - test 234', 2, 1, 0, '2025-11-10 09:01:16', '2025-11-10 09:08:58'),
(12, 3, 1, 'comment', 'Comment Added', 'Vasanthakumar.T Commented - \"yyhy\" for task - test 234', 2, 1, 0, '2025-11-10 09:01:17', '2025-11-10 09:14:58'),
(13, 2, 1, 'comment', 'Comment Added', 'Varun Commented - \"ffff\" for task - test 234', 3, 1, 0, '2025-11-10 09:01:27', '2025-11-10 09:09:01'),
(14, 3, 1, 'comment', 'Comment Added', 'Varun Commented - \"ffff\" for task - test 234', 3, 1, 0, '2025-11-10 09:01:28', '2025-11-10 09:14:57'),
(15, 2, 1, 'survey', 'New Survey Assigned by Varun', 'New Construction Site in Karthik - Guduvanchery', 3, 1, 0, '2025-11-10 09:08:39', '2025-11-10 09:09:00'),
(16, 3, 1, 'survey', 'Survey Completed by Vasanthakumar.T', 'New Construction Site in Karthik - Guduvanchery', 2, 1, 0, '2025-11-10 09:09:54', '2025-11-10 09:14:57'),
(17, 2, 1, 'survey', 'Survey approved by Varun', 'New Construction Site in Karthik - Guduvanchery', 3, 1, 0, '2025-11-10 09:11:00', '2025-11-10 09:12:38'),
(18, 2, 12, 'snag', 'New Snag  Assigned by Varun', 'Flooring & Tiling in Karthik - Guduvanchery', 3, 1, 0, '2025-11-10 09:12:18', '2025-11-10 09:12:40'),
(19, 3, 12, 'snag_comment', 'Snag Comment Added', 'Vasanthakumar.T commented - \"hhgv\"', 2, 1, 0, '2025-11-10 09:13:46', '2025-11-10 09:14:55'),
(20, 3, 12, 'snag_comment', 'Snag Comment Added', 'Vasanthakumar.T commented - \"hhgv\"', 2, 1, 0, '2025-11-10 09:13:46', '2025-11-10 09:14:44'),
(21, 2, 12, 'snag_comment', 'Snag Comment Added', 'Varun commented - \"grdd\"', 3, 1, 0, '2025-11-10 09:14:48', '2025-11-10 09:16:50'),
(22, 2, 12, 'snag_comment', 'Snag Comment Added', 'Varun commented - \"grdd\"', 3, 1, 0, '2025-11-10 09:14:48', '2025-11-10 09:16:52'),
(23, 3, 12, 'snag', 'Snag Completed by Vasanthakumar.T', 'Flooring & Tiling in Karthik - Guduvanchery', 2, 1, 0, '2025-11-10 09:15:39', '2025-11-10 09:21:34'),
(24, 2, 12, 'snag', 'snag approved by Varun', 'Flooring & Tiling in Karthik - Guduvanchery', 3, 1, 0, '2025-11-10 09:16:23', '2025-11-10 09:16:53'),
(25, 2, 2, 'survey', 'New Survey Assigned by Varun', 'Service Quality in Karthik - Guduvanchery', 3, NULL, 0, '2025-11-14 05:06:38', '2025-11-14 05:06:38'),
(26, 2, 13, 'snag', 'New Snag  Assigned by Varun', 'Painting in Karthik - Guduvanchery', 3, NULL, 0, '2025-11-14 05:12:39', '2025-11-14 05:12:39'),
(27, 2, 3, 'survey', 'New Survey Assigned by Varun', 'Workload & Efficiency in Karthik - Guduvanchery', 3, NULL, 0, '2025-11-14 05:13:37', '2025-11-14 05:13:37'),
(28, 3, 4, 'survey', 'New Survey Assigned by Vasanthakumar.T', 'Service Quality in Karthik - Guduvanchery', 2, 1, 0, '2025-11-14 05:46:35', '2025-12-13 06:33:13'),
(29, 3, 1, 'qc', 'New QC Assigned by Vasanthakumar.T', 'Materials Quality in Karthik - Guduvanchery', 2, 1, 0, '2025-11-14 05:46:54', '2025-12-13 06:33:12'),
(30, 3, 14, 'snag', 'New Snag  Assigned by Vasanthakumar.T', 'Walls & Plastering in Karthik - Guduvanchery', 2, 1, 0, '2025-11-14 05:47:12', '2025-12-13 06:33:09'),
(31, 2, 14, 'snag', 'Snag Completed by Varun', 'Walls & Plastering in Karthik - Guduvanchery', 3, NULL, 0, '2025-11-14 05:47:31', '2025-11-14 05:47:31'),
(32, 2, 4, 'survey', 'Survey Completed by Varun', 'Service Quality in Karthik - Guduvanchery', 3, NULL, 0, '2025-11-14 05:52:10', '2025-11-14 05:52:10'),
(33, 2, 2, 'qc', 'New QC Assigned by Varun', 'Testing & Inspection in Karthik - Guduvanchery', 3, NULL, 0, '2025-11-14 05:54:10', '2025-11-14 05:54:10'),
(34, 4, 3, 'draw', 'New Drawing Created by Varun', 'WD Title - Working Drawings-Karthik - Guduvanchery', 3, NULL, 0, '2025-11-17 15:02:48', '2025-11-17 15:02:48'),
(35, 2, 2, 'project', 'Project -  test', 'New Project Assigned By Varun', 3, NULL, 0, '2025-12-03 03:57:31', '2025-12-03 03:57:31'),
(36, 3, 2, 'project', 'Project -  test', 'New Project Assigned By Varun', 3, 1, 0, '2025-12-03 03:57:32', '2025-12-13 06:33:05'),
(37, 2, 3, 'project', 'Project -  tet222', 'New Project Assigned By Varun', 3, NULL, 0, '2025-12-03 04:02:02', '2025-12-03 04:02:02'),
(38, 3, 3, 'project', 'Project -  tet222', 'New Project Assigned By Varun', 3, 1, 0, '2025-12-03 04:02:02', '2025-12-04 12:29:30'),
(39, 1, 7, 'project', 'Project -  test123', 'New Project Assigned By Varun', 3, NULL, 0, '2025-12-03 07:24:07', '2025-12-03 07:24:07'),
(40, 1, 8, 'project', 'Project -  test1234', 'New Project Assigned By Varun', 3, NULL, 0, '2025-12-03 07:26:12', '2025-12-03 07:26:12'),
(41, 3, 1, 'Attendance', 'Attendance Submitted', 'Attendance Submitted For Project -  Karthik - Guduvanchery- Date 03-12-2025', 3, 1, 0, '2025-12-03 11:55:05', '2025-12-04 12:29:28'),
(42, 4, 4, 'draw', 'New Drawing Created by Varun', 'Ground Floor drawing - 2D Floor Plans-Karthik - Guduvanchery', 3, NULL, 0, '2025-12-12 06:23:25', '2025-12-12 06:23:25'),
(43, 3, 4, 'draw', 'Drawing Approved by Varun', 'Ground Floor drawing - 2D Floor Plans-Karthik - Guduvanchery', 3, 1, 0, '2025-12-12 06:33:52', '2025-12-13 06:33:03'),
(44, 4, 5, 'draw', 'New Drawing Created by Varun', 'Ground Floor drawing - 2D Floor Plans-Karthik - Guduvanchery', 3, NULL, 0, '2025-12-12 06:34:30', '2025-12-12 06:34:30'),
(45, 2, 9, 'project', 'Project -  Richard - Uthandi', 'New Project Assigned By Varun', 3, NULL, 0, '2025-12-12 11:07:41', '2025-12-12 11:07:41'),
(46, 3, 9, 'project', 'Project -  Richard - Uthandi', 'New Project Assigned By Varun', 3, 1, 0, '2025-12-12 11:07:42', '2025-12-12 11:25:49'),
(47, 4, 9, 'project', 'Project -  Richard - Uthandi', 'New Project Assigned By Varun', 3, 1, 0, '2025-12-12 11:07:42', '2025-12-12 11:10:25'),
(48, 7, 9, 'project', 'Project -  Richard - Uthandi', 'New Project Assigned By Varun', 3, 1, 0, '2025-12-12 11:07:43', '2025-12-12 11:22:39'),
(49, 8, 9, 'project', 'Project -  Richard - Uthandi', 'New Project Assigned By Varun', 3, 1, 0, '2025-12-12 11:07:43', '2025-12-12 11:13:37'),
(50, 9, 9, 'project', 'Project -  Richard - Uthandi', 'New Project Assigned By Varun', 3, NULL, 0, '2025-12-12 11:07:44', '2025-12-12 11:07:44'),
(51, 10, 9, 'project', 'Project -  Richard - Uthandi', 'New Project Assigned By Varun', 3, 1, 0, '2025-12-12 11:07:44', '2025-12-13 09:03:59'),
(52, 11, 9, 'project', 'Project -  Richard - Uthandi', 'New Project Assigned By Varun', 3, 1, 0, '2025-12-12 11:07:45', '2025-12-12 11:23:07'),
(53, 12, 9, 'project', 'Project -  Richard - Uthandi', 'New Project Assigned By Varun', 3, NULL, 0, '2025-12-12 11:07:45', '2025-12-12 11:07:45'),
(54, 13, 9, 'project', 'Project -  Richard - Uthandi', 'New Project Assigned By Varun', 3, NULL, 0, '2025-12-12 11:07:46', '2025-12-12 11:07:46'),
(55, 2, 2, 'task', 'New Task Assigned by Varun', 'Training 1 in Richard - Uthandi', 3, NULL, 0, '2025-12-12 11:20:14', '2025-12-12 11:20:14'),
(56, 2, 5, 'survey', 'New Survey Assigned by Varun', 'New Construction Site in Richard - Uthandi', 3, NULL, 0, '2025-12-12 11:33:05', '2025-12-12 11:33:05'),
(57, 3, 5, 'survey', 'Survey Completed by Vasanthakumar.T', 'New Construction Site in Richard - Uthandi', 2, 1, 0, '2025-12-12 11:36:32', '2025-12-13 06:32:57'),
(58, 2, 5, 'survey', 'Survey approved by Varun', 'New Construction Site in Richard - Uthandi', 3, NULL, 0, '2025-12-12 11:37:01', '2025-12-12 11:37:01'),
(59, 2, 15, 'snag', 'New Snag  Assigned by Varun', 'Civil in Karthik - Guduvanchery', 3, NULL, 0, '2025-12-12 11:44:07', '2025-12-12 11:44:07'),
(60, 3, 15, 'snag_comment', 'Snag Comment Added', 'Vasanthakumar.T commented - \"hi\"', 2, 1, 0, '2025-12-12 11:46:56', '2025-12-13 06:32:52'),
(61, 3, 15, 'snag_comment', 'Snag Comment Added', 'Vasanthakumar.T commented - \"hi\"', 2, 1, 0, '2025-12-12 11:46:56', '2025-12-13 06:32:49'),
(62, 2, 15, 'snag_comment', 'Snag Comment Added', 'Varun commented - \"jjj\"', 3, 0, 0, '2025-12-12 11:47:31', '2025-12-12 11:47:31'),
(63, 2, 15, 'snag_comment', 'Snag Comment Added', 'Varun commented - \"jjj\"', 3, NULL, 0, '2025-12-12 11:47:31', '2025-12-12 11:47:31'),
(64, 3, 15, 'snag', 'Snag Completed by Vasanthakumar.T', 'Civil in Karthik - Guduvanchery', 2, 1, 0, '2025-12-12 11:48:11', '2025-12-13 06:32:47'),
(65, 2, 15, 'snag', 'snag approved by Varun', 'Civil in Karthik - Guduvanchery', 3, NULL, 0, '2025-12-12 11:48:35', '2025-12-12 11:48:35'),
(66, 3, 3, 'task', 'New Task Assigned by Vasanthakumar.T', 'uiii in Richard - Uthandi', 2, 1, 0, '2025-12-12 12:54:49', '2025-12-12 12:55:03'),
(67, 2, 5, 'task', 'New Task Assigned by Varun', 'test-today in Karthik - Guduvanchery', 3, NULL, 0, '2025-12-18 12:07:58', '2025-12-18 12:07:58'),
(68, 2, 6, 'task', 'New Task Assigned by Varun', 'Testing for file in Karthik - Guduvanchery', 3, NULL, 0, '2025-12-19 05:32:27', '2025-12-19 05:32:27');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(2, 'tab-survey', 'web', '2025-07-17 10:03:49', '2025-07-17 10:03:49'),
(3, 'tab-drawing', 'web', '2025-07-17 10:03:49', '2025-07-17 10:03:49'),
(4, 'tab-progress', 'web', '2025-07-17 10:03:49', '2025-07-17 10:03:49'),
(5, 'tab-qc', 'web', '2025-07-17 10:03:49', '2025-07-17 10:03:49'),
(6, 'tab-snags', 'web', '2025-07-17 10:03:49', '2025-07-17 10:03:49'),
(7, 'tab-docs/link', 'web', '2025-07-17 10:03:49', '2025-07-17 10:03:49'),
(8, 'add-survey', 'web', '2025-07-17 10:03:49', '2025-07-17 10:03:49'),
(10, 'approve-survey', 'web', '2025-07-17 10:03:49', '2025-07-17 10:03:49'),
(11, 'add-drawing', 'web', '2025-07-17 10:03:49', '2025-07-17 10:03:49'),
(13, 'approve-drawing', 'web', '2025-07-17 10:03:49', '2025-07-17 10:03:49'),
(15, 'add-stage', 'web', '2025-07-17 10:03:49', '2025-07-17 10:03:49'),
(16, 'add-qc', 'web', '2025-07-17 10:03:49', '2025-07-17 10:03:49'),
(18, 'approve-qc', 'web', '2025-07-17 10:03:49', '2025-07-17 10:03:49'),
(19, 'add-snag', 'web', '2025-07-17 10:03:49', '2025-07-17 10:03:49'),
(21, 'approve-snag', 'web', '2025-07-17 10:03:49', '2025-07-17 10:03:49'),
(22, 'project_create', 'web', '2025-07-17 10:03:49', '2025-07-17 10:03:49'),
(23, 'project_edit', 'web', '2025-07-17 10:03:49', '2025-07-17 10:03:49'),
(24, 'project_view', 'web', '2025-07-17 10:03:49', '2025-07-17 10:03:49'),
(25, 'task_create', 'web', '2025-07-17 10:03:49', '2025-07-17 10:03:49'),
(27, 'add-notification', 'web', '2025-07-17 10:03:49', '2025-07-17 10:03:49'),
(28, 'doc_create', 'web', '2025-07-17 10:03:49', '2025-07-17 10:03:49'),
(29, 'doc_view', 'web', '2025-07-17 10:03:49', '2025-07-17 10:03:49');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `personal_access_tokens`
--

INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `expires_at`, `created_at`, `updated_at`) VALUES
(1, 'App\\Models\\Employee', 1, 'token', '070ead28033aa560cdd44e48e94a77cfc39e9fa600105647d7eb7387a86227e0', '[\"*\"]', '2025-08-18 10:28:28', NULL, '2025-08-18 09:27:19', '2025-08-18 10:28:28'),
(3, 'App\\Models\\Employee', 1, 'token', '420bf734c2f8fbeca9c4d2fdfa3bcf67a9d3adf85c9b941597347d9684172e29', '[\"*\"]', '2025-08-19 07:28:14', NULL, '2025-08-19 07:28:11', '2025-08-19 07:28:14'),
(4, 'App\\Models\\Employee', 3, 'token', '455879d405d843bf4219fd8264c7cca425d854ab8f8c3fa10aaa71bba6421623', '[\"*\"]', '2025-08-25 04:42:05', NULL, '2025-08-20 05:00:33', '2025-08-25 04:42:05'),
(5, 'App\\Models\\Employee', 2, 'token', '7288b49e033d8124f742ae6bc0fd04c386bd43b1d5814d635e08768fcb31bd20', '[\"*\"]', '2025-09-26 05:31:55', NULL, '2025-08-20 05:01:05', '2025-09-26 05:31:55'),
(6, 'App\\Models\\Employee', 3, 'token', 'f2465788c59e077c3e585cdb40dc4182baa3795da3573fb47c5b80c6953f7081', '[\"*\"]', '2025-08-23 08:05:39', NULL, '2025-08-23 07:37:59', '2025-08-23 08:05:39'),
(7, 'App\\Models\\Employee', 3, 'token', 'ff44478316b4d3cc5f06a845f6d7946357bcff8707e395a907e7b1a592516d09', '[\"*\"]', '2025-08-23 11:36:40', NULL, '2025-08-23 09:12:34', '2025-08-23 11:36:40'),
(8, 'App\\Models\\Employee', 3, 'token', 'd042361f59a81c91d495ea01090c59ccb5c85bf9615a19c06cb4d3db4b7972f3', '[\"*\"]', '2025-08-28 11:21:51', NULL, '2025-08-28 11:04:34', '2025-08-28 11:21:51'),
(9, 'App\\Models\\Employee', 3, 'token', '809e78256ed0b7268be705848be9911399d01beeabf3b7051b779f36c6742e6e', '[\"*\"]', '2025-08-29 07:44:48', NULL, '2025-08-29 04:57:43', '2025-08-29 07:44:48'),
(10, 'App\\Models\\Employee', 3, 'token', '5988001ddf41e4b5b0f4ffdd0790b2cb8da4248a549849caa292fda1966850c3', '[\"*\"]', '2025-08-30 03:47:19', NULL, '2025-08-29 09:22:14', '2025-08-30 03:47:19'),
(11, 'App\\Models\\Employee', 3, 'token', '06e520432c3a8e604a0d3f2cef43ca95b5459c0f903f01aefb9b914080078cc3', '[\"*\"]', '2025-08-30 05:13:06', NULL, '2025-08-29 11:52:15', '2025-08-30 05:13:06'),
(16, 'App\\Models\\Employee', 4, 'token', '9844afe521aad13f2dbd9bbf1ab67803ab0f4bcc769032f6b8300c0f66a4b3b0', '[\"*\"]', '2025-08-30 12:18:18', NULL, '2025-08-30 11:18:13', '2025-08-30 12:18:18'),
(18, 'App\\Models\\Employee', 3, 'token', 'c6cb84458239171b92fb7e296973974c978f13efc1f2dc3278ac4e8f57d55d28', '[\"*\"]', '2025-08-30 12:19:58', NULL, '2025-08-30 12:11:34', '2025-08-30 12:19:58'),
(22, 'App\\Models\\Employee', 1, 'token', '7b95640c1d60c7d1cf302f2bade9d082015241c226a9d9d6bbb30c3dc9f830ce', '[\"*\"]', '2025-09-05 10:49:03', NULL, '2025-09-05 10:43:55', '2025-09-05 10:49:03'),
(25, 'App\\Models\\Employee', 5, 'token', 'c48ce3dc11ac95732fb4f7cc71022df4be1124348b64908627be498a665c6501', '[\"*\"]', '2025-09-05 12:41:45', NULL, '2025-09-05 12:17:01', '2025-09-05 12:41:45'),
(26, 'App\\Models\\Employee', 5, 'token', '7fd4c58aac5c0aeb379e1d3b92b29b2a09acbe22cee4e173dce334e4913de2d3', '[\"*\"]', '2025-09-06 04:05:35', NULL, '2025-09-05 12:25:38', '2025-09-06 04:05:35'),
(32, 'App\\Models\\Employee', 5, 'token', '9793ccf6ce39f65e08a4064e1364185126351bf9317eb61395b0eb5ef6f77f87', '[\"*\"]', '2025-09-06 04:31:36', NULL, '2025-09-06 03:58:40', '2025-09-06 04:31:36'),
(33, 'App\\Models\\Employee', 5, 'token', '20da7bfe2d15d0ce7d25c2e44bbd318c928259702942cce3541b22b3a1bcbe56', '[\"*\"]', '2025-09-06 11:14:55', NULL, '2025-09-06 04:07:28', '2025-09-06 11:14:55'),
(35, 'App\\Models\\Employee', 1, 'token', '7b9bb8ffa06371de9157af4e322a5037207a11886164587b2ad1fc49c0359778', '[\"*\"]', '2025-09-06 05:07:23', NULL, '2025-09-06 04:39:48', '2025-09-06 05:07:23'),
(37, 'App\\Models\\Employee', 5, 'token', 'b0df3c052419e9e1c6e8856b63740529805380df42b5491007b3683232950da7', '[\"*\"]', '2025-09-06 05:19:34', NULL, '2025-09-06 05:08:50', '2025-09-06 05:19:34'),
(41, 'App\\Models\\Employee', 1, 'token', '21b7c21243b9d1a8431b37c9b86b10cf5768df2e7beee71b7e2453c53fccca98', '[\"*\"]', '2025-09-06 09:51:08', NULL, '2025-09-06 07:36:11', '2025-09-06 09:51:08'),
(45, 'App\\Models\\Employee', 1, 'token', '2e3de6fba69e9ad337579eb59a015972a2521aa5d36491ef060e8f16aaefe9a6', '[\"*\"]', '2025-09-06 11:18:18', NULL, '2025-09-06 10:34:53', '2025-09-06 11:18:18'),
(46, 'App\\Models\\Employee', 1, 'token', 'cea44cbe8230e29b4d235f12de1d0b06e578ed628e05dda8830adafdc8eb0ad6', '[\"*\"]', '2025-09-25 11:28:50', NULL, '2025-09-25 11:28:50', '2025-09-25 11:28:50'),
(47, 'App\\Models\\Employee', 3, 'token', 'efd1d076815bb6f47e001229860e7086581fbf6eebe7a6714765fb46f98153ff', '[\"*\"]', '2025-09-26 05:32:07', NULL, '2025-09-25 11:33:05', '2025-09-26 05:32:07'),
(51, 'App\\Models\\Employee', 2, 'token', '7a6786230992deb786d27316bdd46cd0d86c98c1ce281bb4cdb8da9e705cf4b7', '[\"*\"]', '2025-09-26 05:35:15', NULL, '2025-09-26 05:35:10', '2025-09-26 05:35:15'),
(55, 'App\\Models\\Employee', 3, 'token', 'a3d789d3e2b352debeff858a9c33998fad2b97938d1bc65788d796d8474ea02f', '[\"*\"]', '2025-09-26 06:25:16', NULL, '2025-09-26 06:21:15', '2025-09-26 06:25:16'),
(58, 'App\\Models\\Employee', 2, 'token', 'e14ff2ae56468b6c6070b24e489bbbf8db46b9a942990eaebcf4f8210da1c35e', '[\"*\"]', '2025-09-27 03:46:51', NULL, '2025-09-27 03:46:07', '2025-09-27 03:46:51'),
(61, 'App\\Models\\Employee', 2, 'token', '18ad021d185f7d1b15351b7e1d8b1ce97b5c0836a5e6be3d0776d6bf3110b967', '[\"*\"]', '2025-09-27 12:28:20', NULL, '2025-09-27 12:23:46', '2025-09-27 12:28:20'),
(62, 'App\\Models\\Employee', 2, 'token', '9d078692b53c251054bb223785ea7683b11c4d841961f39a7230fc04b92fb693', '[\"*\"]', NULL, NULL, '2025-09-27 12:28:29', '2025-09-27 12:28:29'),
(63, 'App\\Models\\Employee', 2, 'token', '65582783a7c62f7061e8c68cadb8a0ae378d020bcd4c6cbc1871bea8b7227028', '[\"*\"]', '2025-09-27 14:19:52', NULL, '2025-09-27 12:28:33', '2025-09-27 14:19:52'),
(64, 'App\\Models\\Employee', 2, 'token', '633d08acf353777223a6e8726f5cb459b218a3be526339f6a6ba709c612c15e2', '[\"*\"]', '2025-09-27 12:39:43', NULL, '2025-09-27 12:32:17', '2025-09-27 12:39:43'),
(65, 'App\\Models\\Employee', 3, 'token', 'a1a08cbd24a423bfacdc0da5812557663d37d0f200601e13f9120c4bee5fe821', '[\"*\"]', '2025-09-27 14:28:30', NULL, '2025-09-27 14:20:24', '2025-09-27 14:28:30'),
(67, 'App\\Models\\Employee', 2, 'token', 'b11a2e57870e239aace9456d54e3b677908c36724910671a032f641f9f9a3d65', '[\"*\"]', '2025-09-27 14:41:28', NULL, '2025-09-27 14:39:00', '2025-09-27 14:41:28'),
(68, 'App\\Models\\Employee', 3, 'token', 'be373026d3f5911993a59cc08da66a219f4bfa4d3606848740336c312f73d7d6', '[\"*\"]', '2025-09-27 14:54:57', NULL, '2025-09-27 14:42:01', '2025-09-27 14:54:57'),
(69, 'App\\Models\\Employee', 3, 'token', 'df5c9c3d16380f4db5152879693d62d9b1e807436b3209205ade628220045dd2', '[\"*\"]', '2025-09-27 15:07:21', NULL, '2025-09-27 14:56:29', '2025-09-27 15:07:21'),
(72, 'App\\Models\\Employee', 3, 'token', 'f39b5f3619268680d74ac6d4f00a1a758d6c65315dd2d22a838ee7d69a2f3a58', '[\"*\"]', '2025-09-27 15:59:20', NULL, '2025-09-27 15:46:44', '2025-09-27 15:59:20'),
(73, 'App\\Models\\Employee', 3, 'token', 'ad2c7704611ab8eb5c21c561cf3349f5bf35e9fde531b65f846222db20d1abeb', '[\"*\"]', '2025-09-29 04:59:52', NULL, '2025-09-29 03:52:56', '2025-09-29 04:59:52'),
(74, 'App\\Models\\Employee', 2, 'token', '1630aa379e0b7382b9559936196be7636cbaf6c2d4dc49c55fdef4d122bcb67e', '[\"*\"]', '2025-09-29 04:27:36', NULL, '2025-09-29 04:14:18', '2025-09-29 04:27:36'),
(75, 'App\\Models\\Employee', 3, 'token', '4cd32da03376215e83b5e2ba749382719f547bab4b70455d47aa409d645a34b4', '[\"*\"]', '2025-09-30 12:05:28', NULL, '2025-09-29 11:40:38', '2025-09-30 12:05:28'),
(76, 'App\\Models\\Employee', 2, 'token', '9213f9e47a9a78be4b176ace61f7036fccfd006af5e6bf77885768b8d989b6d8', '[\"*\"]', '2025-10-02 11:48:53', NULL, '2025-09-29 13:45:13', '2025-10-02 11:48:53'),
(77, 'App\\Models\\Employee', 2, 'token', '86eaf7f513abf806d7601d3e9c3d13a32f01886767190b9b022294e43688c45c', '[\"*\"]', '2025-10-02 08:01:16', NULL, '2025-10-02 08:00:32', '2025-10-02 08:01:16'),
(78, 'App\\Models\\Employee', 3, 'token', 'd27f6941239c3e270afdcbe073c2c24089e5d28ea663204fe8baae4cdfef9d40', '[\"*\"]', '2025-10-02 08:45:52', NULL, '2025-10-02 08:01:41', '2025-10-02 08:45:52'),
(81, 'App\\Models\\Employee', 2, 'token', '61bd61bf1d2456f479a6a829e735121dc2d755129898c84ecf9e229950f714d4', '[\"*\"]', '2025-10-02 12:26:54', NULL, '2025-10-02 11:55:52', '2025-10-02 12:26:54'),
(82, 'App\\Models\\Employee', 2, 'token', '3b0462f0234899399b79a077ea236bef39a8676b4530dc4e705efca0b05ca810', '[\"*\"]', '2025-10-02 12:34:44', NULL, '2025-10-02 12:02:49', '2025-10-02 12:34:44'),
(84, 'App\\Models\\Employee', 3, 'token', '3bd0766f79381d38d65155c738d2bd52fad75a6e6c5738f723bbdb2ab31389db', '[\"*\"]', '2025-10-03 06:37:44', NULL, '2025-10-03 06:36:44', '2025-10-03 06:37:44'),
(85, 'App\\Models\\Employee', 3, 'token', '2aa07bd40e80fccc8739625e6e731a990baeb8d500e796016d04b3ab80077c9e', '[\"*\"]', '2025-12-13 12:06:39', NULL, '2025-10-04 11:36:04', '2025-12-13 12:06:39'),
(87, 'App\\Models\\Employee', 3, 'token', 'f7206153245af6905ea77150668ddfd17d6ac58de74ef1d2f826921b04e07047', '[\"*\"]', '2025-10-23 11:27:02', NULL, '2025-10-23 11:26:39', '2025-10-23 11:27:02'),
(94, 'App\\Models\\Employee', 1, 'token', '78e27dbc0becdb067544535037bdc682f1188f0eda9c9af77bd658849724cd4c', '[\"*\"]', '2025-10-29 15:28:18', NULL, '2025-10-29 15:27:17', '2025-10-29 15:28:18'),
(96, 'App\\Models\\Employee', 1, 'token', '7192a31352838c3cf17c0d229a16b97d7210811ec71f6b735b38382520ee2c58', '[\"*\"]', '2025-10-29 15:41:58', NULL, '2025-10-29 15:30:40', '2025-10-29 15:41:58'),
(97, 'App\\Models\\Employee', 1, 'token', 'f146d7a0a7bc319cf36844a2c88d5e615cee111d72b46548d1452945688a0af7', '[\"*\"]', NULL, NULL, '2025-10-29 15:36:53', '2025-10-29 15:36:53'),
(98, 'App\\Models\\Employee', 2, 'token', 'fa1151a7f8ab206e96492a466d3c1a83a7476ed587e278bd40c9c3b69f73e8fa', '[\"*\"]', NULL, NULL, '2025-10-29 15:37:09', '2025-10-29 15:37:09'),
(99, 'App\\Models\\Employee', 1, 'token', '2b1de1b45a5d8e8cdfca2509b0638e0529420298e741b7af3899d182dead8b0b', '[\"*\"]', NULL, NULL, '2025-10-29 15:37:42', '2025-10-29 15:37:42'),
(100, 'App\\Models\\Employee', 1, 'token', '9d85b49966e906dc74d7525862e81bebbb68075a18aee0291589ff64647886af', '[\"*\"]', '2025-10-29 15:41:50', NULL, '2025-10-29 15:41:12', '2025-10-29 15:41:50'),
(102, 'App\\Models\\Employee', 1, 'token', '3fc2fd60d98ec80ae67aac699dbcabe37a8607a4b5d83b7d1366dd8b68ddcc1c', '[\"*\"]', NULL, NULL, '2025-10-29 15:45:17', '2025-10-29 15:45:17'),
(103, 'App\\Models\\Employee', 1, 'token', '7c5d9146bda98a0f69590bbfa5b4e0e44c91b3ea0ed304aae8e5b9e25918f1c3', '[\"*\"]', NULL, NULL, '2025-10-29 15:52:34', '2025-10-29 15:52:34'),
(104, 'App\\Models\\Employee', 1, 'token', 'a4ab4a377849a8b0584f37212193f0f1a6de82857bdfbb7009c4e07062ae14e6', '[\"*\"]', NULL, NULL, '2025-10-29 15:52:37', '2025-10-29 15:52:37'),
(105, 'App\\Models\\Employee', 1, 'token', '8c7099ba057a44df38715c0665f9176babb404ee0da35a7ca06fac4357cc8a1f', '[\"*\"]', NULL, NULL, '2025-10-29 15:58:30', '2025-10-29 15:58:30'),
(106, 'App\\Models\\Employee', 1, 'token', 'ce724e97407136c2eff4efe4e0b33b9c6cbbcadba1c11db0fa5d1d9dcd405fb5', '[\"*\"]', NULL, NULL, '2025-10-29 15:58:50', '2025-10-29 15:58:50'),
(107, 'App\\Models\\Employee', 1, 'token', 'ea936d9965cd90c930a4c7a4a9e0b608194038a37c76c8d8fecefb9d9a4d745d', '[\"*\"]', NULL, NULL, '2025-10-29 16:02:44', '2025-10-29 16:02:44'),
(108, 'App\\Models\\Employee', 1, 'token', '2eda63a8d505ecd792063a96577814e00d79080c9afea8bdd27c8f8cc8551776', '[\"*\"]', NULL, NULL, '2025-10-29 16:13:42', '2025-10-29 16:13:42'),
(112, 'App\\Models\\Employee', 1, 'token', '1e3e5aaccdb39bcdfee81afd4a8acbbf22429de377591b33797e0dd4efcce12d', '[\"*\"]', '2025-10-29 18:25:09', NULL, '2025-10-29 17:25:57', '2025-10-29 18:25:09'),
(125, 'App\\Models\\Employee', 1, 'token', '1dc588f89c2b9ee84ac3f17b1a90abdceb3b2bf6f13105fb0390bed404d3a80c', '[\"*\"]', '2025-10-30 13:32:51', NULL, '2025-10-30 13:29:13', '2025-10-30 13:32:51'),
(126, 'App\\Models\\Employee', 1, 'token', '1c93c5a2befc06f3e217bcb3fe7fb3253a12cdaae2b96cc73a07f5abd40fafd9', '[\"*\"]', '2025-10-30 14:13:52', NULL, '2025-10-30 14:12:43', '2025-10-30 14:13:52'),
(128, 'App\\Models\\Employee', 3, 'token', '9135d1721bb9de804c8dc8f9543a1899e559904f20c08938fa0cbde9c5943679', '[\"*\"]', '2025-10-31 15:42:35', NULL, '2025-10-31 15:38:21', '2025-10-31 15:42:35'),
(129, 'App\\Models\\Employee', 3, 'token', 'e7472da2898a36ea16ab675835747189186a7c2cd46b2c59ae8bc43c63ed3962', '[\"*\"]', '2025-11-14 10:43:59', NULL, '2025-11-14 10:34:20', '2025-11-14 10:43:59'),
(135, 'App\\Models\\Employee', 3, 'token', '9f143677f90248c0c7063a0ffc16d7d9daf8a1f921b237de79fd335f4b9b7d51', '[\"*\"]', '2025-11-14 12:19:27', NULL, '2025-11-14 11:46:39', '2025-11-14 12:19:27'),
(136, 'App\\Models\\Employee', 3, 'token', '6e7c478a9ecaf57d0a3d0d9258b297b9afce150a9db92b0ac83dbc8f65f419bf', '[\"*\"]', '2025-11-14 12:29:56', NULL, '2025-11-14 12:24:06', '2025-11-14 12:29:56'),
(137, 'App\\Models\\Employee', 3, 'token', '7b4b6c552433b61e88f442ac2f5d5424cd95d4aa58fe8cb9d0de2303f650d876', '[\"*\"]', '2025-11-17 15:44:10', NULL, '2025-11-17 15:42:14', '2025-11-17 15:44:10'),
(139, 'App\\Models\\Employee', 2, 'token', 'e212b3594497fcac149bdcdfd7ef39c1b6b033e3bd9bd055b39170fd457421de', '[\"*\"]', '2025-11-26 11:19:41', NULL, '2025-11-26 10:17:36', '2025-11-26 11:19:41'),
(140, 'App\\Models\\Employee', 3, 'token', '07038be644ee1afbe474f7eca40658edf3f046dc9cb90fe25c7dfed4921f8b8c', '[\"*\"]', '2025-12-09 17:47:05', NULL, '2025-12-03 11:48:39', '2025-12-09 17:47:05'),
(141, 'App\\Models\\Employee', 1, 'token', 'f8cb5e01d5831ad7230f6f0ed6d1014505317b21c782c4fb51f5bba5f8947e43', '[\"*\"]', '2025-12-13 10:00:50', NULL, '2025-12-09 17:15:11', '2025-12-13 10:00:50'),
(142, 'App\\Models\\Employee', 2, 'token', '1dd407d44d0b5e1f00e42873bd41597118a63e8873c1a936212e9816be7e865c', '[\"*\"]', '2025-12-14 19:38:48', NULL, '2025-12-10 14:24:12', '2025-12-14 19:38:48'),
(143, 'App\\Models\\Employee', 4, 'token', '2c7232b981d20e2def3af5089ba65105b891162bd3d39e60825e803df929eda6', '[\"*\"]', '2025-12-12 17:13:32', NULL, '2025-12-12 11:39:26', '2025-12-12 17:13:32'),
(146, 'App\\Models\\Employee', 3, 'token', 'af87c4405608ead10f50bc6214f6d8f8a3fa3322cff9da580ca571664493d4b4', '[\"*\"]', '2025-12-12 12:43:02', NULL, '2025-12-12 12:42:57', '2025-12-12 12:43:02'),
(148, 'App\\Models\\Employee', 2, 'token', '45b1f56ee4351d6d45f6240f3784e3f29df4c79db01fc00f58e25f8ac9b083e4', '[\"*\"]', '2025-12-16 19:12:02', NULL, '2025-12-12 16:40:24', '2025-12-16 19:12:02'),
(149, 'App\\Models\\Employee', 7, 'token', 'b62d658e39162bc66f0b5897d6df4fbb661b05eb2e31b04d42270263f3e05104', '[\"*\"]', '2025-12-13 14:32:59', NULL, '2025-12-12 16:41:54', '2025-12-13 14:32:59'),
(150, 'App\\Models\\Employee', 8, 'token', '5f6bd812a60b42440a2ea19d5e5e7940b8c7f2bf660a762d627c3535aae74656', '[\"*\"]', '2025-12-12 16:48:47', NULL, '2025-12-12 16:42:26', '2025-12-12 16:48:47'),
(151, 'App\\Models\\Employee', 13, 'token', '6e7d92a9895c813c8fbfed7fc36f87701d32c804b10b308ea299651bd60ae793', '[\"*\"]', '2025-12-12 16:52:04', NULL, '2025-12-12 16:42:38', '2025-12-12 16:52:04'),
(152, 'App\\Models\\Employee', 9, 'token', '1153fba6f9f604b2587e45a8f6db376b62c9180b614befd322ec6cc43a23dd0c', '[\"*\"]', '2025-12-12 17:12:57', NULL, '2025-12-12 16:43:08', '2025-12-12 17:12:57'),
(153, 'App\\Models\\Employee', 11, 'token', 'ea06daebce023692538fb15d8f87122eafb5c8c53e1f2a6b5b1aa20c80bd018f', '[\"*\"]', '2025-12-12 17:30:11', NULL, '2025-12-12 16:50:42', '2025-12-12 17:30:11'),
(156, 'App\\Models\\Employee', 10, 'token', '3e43cd4d5be2f2886b69af68473fc59185607f60f9c0134616c49c02d85782bf', '[\"*\"]', '2025-12-17 01:38:17', NULL, '2025-12-13 14:33:48', '2025-12-17 01:38:17'),
(159, 'App\\Models\\Employee', 4, 'token', 'a62ba8c2cf1a278a435962638e21fe61d19eff477273d91a4e31f870a5d9668a', '[\"*\"]', '2025-12-18 13:03:45', NULL, '2025-12-18 12:59:36', '2025-12-18 13:03:45'),
(160, 'App\\Models\\Employee', 3, 'token', 'c775a7ce51f4fccebffbbe8f07fa4898b826b31bec322ca6abedbaa48d99cbab', '[\"*\"]', '2025-12-18 12:48:07', NULL, '2025-12-18 12:17:19', '2025-12-18 12:48:07'),
(161, 'App\\Models\\Employee', 3, 'token', '7e3e86f2f18521be62c52d26d8f5e84e4111f927ff689807e567062989907714', '[\"*\"]', '2025-12-19 05:54:44', NULL, '2025-12-19 04:05:54', '2025-12-19 05:54:44'),
(163, 'App\\Models\\Employee', 3, 'token', '3fc81a1f697724c4404fdc2e68ed4371dcc14b46a9b07b0d6555afc2b7265855', '[\"*\"]', '2025-12-19 05:51:25', NULL, '2025-12-19 05:22:25', '2025-12-19 05:51:25'),
(164, 'App\\Models\\Employee', 3, 'token', '7a2190154dce679519e6622c2552b92a36981715ed4ef4130d8b627c2336d262', '[\"*\"]', '2025-12-19 06:26:28', NULL, '2025-12-19 06:16:36', '2025-12-19 06:26:28'),
(165, 'App\\Models\\Employee', 3, 'token', 'b78315a2dcc5e0dd2d446ccddf44846354c9a1ca9689cfd85cb7d5b600cf55b4', '[\"*\"]', '2025-12-19 07:42:18', NULL, '2025-12-19 06:19:49', '2025-12-19 07:42:18');

-- --------------------------------------------------------

--
-- Table structure for table `progress_activity`
--

CREATE TABLE `progress_activity` (
  `id` int(11) NOT NULL,
  `pro_id` int(11) DEFAULT NULL,
  `stage` int(11) DEFAULT NULL,
  `activity` text NOT NULL,
  `qc` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `status` varchar(25) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp(),
  `c_by` varchar(25) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `progress_activity`
--

INSERT INTO `progress_activity` (`id`, `pro_id`, `stage`, `activity`, `qc`, `status`, `created_at`, `updated_at`, `c_by`) VALUES
(1, 2, 1, 'Site Cleaning & Shed Work', '1', '1', '2025-08-21 22:03:48', '2025-08-21 22:03:48', '3'),
(2, 2, 1, 'Footing Marking', '1', '1', '2025-08-21 22:03:48', '2025-08-21 22:03:48', '3'),
(3, 2, 2, 'Excavation For Footing and Sump', '1', '1', '2025-08-21 22:03:48', '2025-08-21 22:03:48', '3'),
(4, 2, 2, 'Trimming Work For Footings', '1', '1', '2025-08-21 22:03:48', '2025-08-21 22:03:48', '3'),
(5, 2, 2, 'PCC For Footing', '1', '1', '2025-08-21 22:03:48', '2025-08-21 22:03:48', '3'),
(6, 2, 2, 'Column Position Marking', '1', '1', '2025-08-21 22:03:48', '2025-08-21 22:03:48', '3'),
(7, 2, 2, 'Consolidation Work/ Column Starter Concrete', '1', '1', '2025-08-21 22:03:48', '2025-08-21 22:03:48', '3'),
(8, 2, 3, 'GF Column Concrete', '1', '1', '2025-08-21 22:03:48', '2025-08-21 22:03:48', '3'),
(9, 2, 3, 'PCC For Flooring', '1', '1', '2025-08-21 22:03:48', '2025-08-21 22:03:48', '3'),
(10, 2, 4, 'GF Flooring Work & Toilet tiling', '1', '1', '2025-08-21 22:03:48', '2025-08-21 22:03:48', '3'),
(11, 2, 4, 'FF Flooring Work & Toilet tiling', '1', '1', '2025-08-21 22:03:48', '2025-08-21 22:03:48', '3'),
(13, 1, 6, 'Footing Marking', '1', '1', '2025-12-02 14:50:07', '2025-12-02 14:50:07', '3'),
(14, 1, 7, 'Excavation For Footing and Sump', '1', '1', '2025-12-02 14:50:07', '2025-12-02 14:50:07', '3'),
(15, 1, 7, 'Trimming Work For Footings', '1', '1', '2025-12-02 14:50:07', '2025-12-02 14:50:07', '3'),
(16, 1, 7, 'PCC For Footing', '1', '1', '2025-12-02 14:50:07', '2025-12-02 14:50:07', '3'),
(17, 1, 7, 'Column Position Marking', '1', '1', '2025-12-02 14:50:07', '2025-12-02 14:50:07', '3'),
(18, 1, 7, 'Consolidation Work/ Column Starter Concrete', '1', '1', '2025-12-02 14:50:07', '2025-12-02 14:50:07', '3'),
(19, 1, 8, 'GF Column Concrete', '1', '1', '2025-12-02 14:50:07', '2025-12-02 14:50:07', '3'),
(20, 1, 8, 'PCC For Flooring', '1', '1', '2025-12-02 14:50:07', '2025-12-02 14:50:07', '3'),
(21, 1, 9, 'GF Flooring Work & Toilet tiling', '1', '1', '2025-12-02 14:50:07', '2025-12-02 14:50:07', '3'),
(22, 1, 9, 'FF Flooring Work & Toilet tiling', '1', '1', '2025-12-02 14:50:07', '2025-12-02 14:50:07', '3'),
(23, 1, 6, 'Test pit 2', '1', '1', '2025-12-02 17:32:29', '2025-12-02 17:32:29', '3'),
(26, 2, 11, 'Excavation For Footing and Sump', '0', '1', '2025-12-03 09:30:03', '2025-12-03 09:30:03', '3'),
(27, 2, 11, 'Trimming Work For Footings', '0', '1', '2025-12-03 09:30:03', '2025-12-03 09:30:03', '3'),
(28, 2, 11, 'PCC For Footing', '0', '1', '2025-12-03 09:30:03', '2025-12-03 09:30:03', '3'),
(29, 2, 11, 'Column Position Marking', '0', '1', '2025-12-03 09:30:03', '2025-12-03 09:30:03', '3'),
(30, 2, 11, 'Consolidation Work/ Column Starter Concrete', '0', '1', '2025-12-03 09:30:03', '2025-12-03 09:30:03', '3'),
(31, 2, 12, 'GF Column Concrete', '0', '1', '2025-12-03 09:30:03', '2025-12-03 09:30:03', '3'),
(32, 2, 12, 'PCC For Flooring', '0', '1', '2025-12-03 09:30:03', '2025-12-03 09:30:03', '3'),
(33, 2, 13, 'GF Flooring Work & Toilet tiling', '0', '1', '2025-12-03 09:30:03', '2025-12-03 09:30:03', '3'),
(34, 2, 13, 'FF Flooring Work & Toilet tiling', '0', '1', '2025-12-03 09:30:03', '2025-12-03 09:30:03', '3'),
(35, 3, 14, 'Site Cleaning & Shed Work', '0', '1', '2025-12-03 09:33:09', '2025-12-03 09:33:09', '3'),
(36, 3, 14, 'Footing Marking', '0', '1', '2025-12-03 09:33:09', '2025-12-03 09:33:09', '3'),
(37, 3, 15, 'Excavation For Footing and Sump', '0', '1', '2025-12-03 09:33:09', '2025-12-03 09:33:09', '3'),
(38, 3, 15, 'Trimming Work For Footings', '0', '1', '2025-12-03 09:33:09', '2025-12-03 09:33:09', '3'),
(39, 3, 15, 'PCC For Footing', '0', '1', '2025-12-03 09:33:09', '2025-12-03 09:33:09', '3'),
(40, 3, 15, 'Column Position Marking', '0', '1', '2025-12-03 09:33:09', '2025-12-03 09:33:09', '3'),
(42, 3, 16, 'GF Column Concrete', '0', '1', '2025-12-03 09:33:09', '2025-12-03 09:33:09', '3'),
(43, 3, 16, 'PCC For Flooring', '0', '1', '2025-12-03 09:33:09', '2025-12-03 09:33:09', '3'),
(44, 3, 17, 'GF Flooring Work & Toilet tiling', '0', '1', '2025-12-03 09:33:09', '2025-12-03 09:33:09', '3'),
(45, 3, 17, 'FF Flooring Work & Toilet tiling', '0', '1', '2025-12-03 09:33:09', '2025-12-03 09:33:09', '3');

-- --------------------------------------------------------

--
-- Table structure for table `progress_import`
--

CREATE TABLE `progress_import` (
  `id` int(11) NOT NULL,
  `pro_id` int(11) DEFAULT NULL,
  `stage` text DEFAULT NULL,
  `sub` text DEFAULT NULL,
  `duration` varchar(20) DEFAULT NULL,
  `st_date` date NOT NULL,
  `end_date` date NOT NULL,
  `sc_start` date NOT NULL,
  `sc_end` date NOT NULL,
  `qc` int(11) DEFAULT NULL,
  `status` varchar(25) NOT NULL DEFAULT '1',
  `c_on` datetime NOT NULL DEFAULT current_timestamp(),
  `c_by` varchar(25) DEFAULT NULL,
  `updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `progress_material`
--

CREATE TABLE `progress_material` (
  `id` int(11) NOT NULL,
  `work_id` int(11) NOT NULL,
  `mat_id` int(11) NOT NULL,
  `unit` varchar(100) DEFAULT NULL,
  `qty` varchar(50) DEFAULT NULL,
  `status` varchar(25) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp(),
  `c_by` varchar(25) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `progress_stage`
--

CREATE TABLE `progress_stage` (
  `id` int(11) NOT NULL,
  `pro_id` int(11) NOT NULL,
  `stage` text NOT NULL,
  `duration` varchar(50) NOT NULL,
  `st_date` date NOT NULL,
  `end_date` date NOT NULL,
  `sc_start` date NOT NULL,
  `sc_end` date NOT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'Active',
  `c_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `progress_stage`
--

INSERT INTO `progress_stage` (`id`, `pro_id`, `stage`, `duration`, `st_date`, `end_date`, `sc_start`, `sc_end`, `status`, `c_by`, `created_at`, `updated_at`) VALUES
(1, 2, 'PRELIMINARY WORKS', '1', '2025-07-02', '2025-07-03', '2025-07-02', '2025-07-03', 'Active', 3, '2025-08-21 11:03:48', '2025-08-21 11:03:48'),
(2, 2, 'SUB-STRUCTURE WORKS', '3', '2025-07-04', '2025-07-06', '2025-07-04', '2025-07-06', 'Active', 3, '2025-08-21 11:03:48', '2025-08-21 11:03:48'),
(3, 2, 'SUPER STRUCTURE WORKS', '3', '2025-07-07', '2025-07-09', '2025-07-07', '2025-07-09', 'Active', 3, '2025-08-21 11:03:48', '2025-08-21 11:03:48'),
(4, 2, 'FINISHING WORKS', '5', '2025-07-10', '2025-07-14', '2025-07-10', '2025-07-14', 'Active', 3, '2025-08-21 11:03:48', '2025-08-21 11:03:48'),
(5, 7, 'New', '1', '2025-10-30', '2025-11-08', '2025-10-30', '2025-11-08', 'Active', 1, '2025-10-28 06:18:40', '2025-10-28 06:18:40'),
(6, 1, 'PRELIMINARY WORKS', '1', '2025-07-02', '2025-07-03', '2025-07-02', '2025-07-03', 'Active', 3, '2025-12-02 09:20:07', '2025-12-02 09:20:07'),
(7, 1, 'SUB-STRUCTURE WORKS', '3', '2025-07-04', '2025-07-06', '2025-07-04', '2025-07-06', 'Active', 3, '2025-12-02 09:20:07', '2025-12-02 09:20:07'),
(8, 1, 'SUPER STRUCTURE WORKS', '3', '2025-07-07', '2025-07-09', '2025-07-07', '2025-07-09', 'Active', 3, '2025-12-02 09:20:07', '2025-12-02 09:20:07'),
(9, 1, 'FINISHING WORKS', '5', '2025-07-10', '2025-07-14', '2025-07-10', '2025-07-14', 'Active', 3, '2025-12-02 09:20:07', '2025-12-02 09:20:07'),
(10, 2, 'PRELIMINARY WORKS', '1', '2025-11-02', '2025-11-03', '2025-11-02', '2025-11-03', 'Active', 3, '2025-12-03 04:00:03', '2025-12-03 04:00:03'),
(11, 2, 'SUB-STRUCTURE WORKS', '3', '2025-11-03', '2025-11-06', '2025-11-03', '2025-11-06', 'Active', 3, '2025-12-03 04:00:03', '2025-12-03 04:00:03'),
(12, 2, 'SUPER STRUCTURE WORKS', '3', '2025-11-02', '2025-11-09', '2025-11-02', '2025-11-09', 'Active', 3, '2025-12-03 04:00:03', '2025-12-03 04:00:03'),
(13, 2, 'FINISHING WORKS', '5', '2025-11-03', '2025-11-14', '2025-11-03', '2025-11-14', 'Active', 3, '2025-12-03 04:00:03', '2025-12-03 04:00:03'),
(14, 3, 'PRELIMINARY WORKS', '1', '2025-12-02', '2025-12-03', '2025-12-02', '2025-12-03', 'Active', 3, '2025-12-03 04:03:09', '2025-12-03 04:03:09'),
(15, 3, 'SUB-STRUCTURE WORKS', '3', '2025-12-03', '2025-12-06', '2025-12-03', '2025-12-06', 'Active', 3, '2025-12-03 04:03:09', '2025-12-03 04:03:09'),
(16, 3, 'SUPER STRUCTURE WORKS', '3', '2025-12-02', '2025-12-09', '2025-12-02', '2025-12-09', 'Active', 3, '2025-12-03 04:03:09', '2025-12-03 04:03:09'),
(17, 3, 'FINISHING WORKS', '5', '2025-12-03', '2025-12-14', '2025-12-03', '2025-12-14', 'Active', 3, '2025-12-03 04:03:09', '2025-12-03 04:03:09');

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `id` int(11) NOT NULL,
  `project_name` varchar(255) NOT NULL,
  `project_id` varchar(100) NOT NULL,
  `client_name` varchar(255) NOT NULL,
  `contact_number` varchar(20) DEFAULT NULL,
  `alternate_contact_number` varchar(20) DEFAULT NULL,
  `email_id` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `pincode` varchar(10) DEFAULT NULL,
  `pro_address` text DEFAULT NULL,
  `pro_city` varchar(100) DEFAULT NULL,
  `pro_state` varchar(100) DEFAULT NULL,
  `pro_pincode` varchar(20) DEFAULT NULL,
  `plot_size` decimal(10,2) DEFAULT NULL,
  `plot_unit` enum('sq.ft','sqm','acre','ft') DEFAULT 'sq.ft',
  `total_building_area` decimal(10,2) DEFAULT NULL,
  `building_area_unit` enum('sqft','sqm','acre','ft') DEFAULT 'sqft',
  `additional_info` text DEFAULT NULL,
  `file_attachment` varchar(255) DEFAULT NULL,
  `file_name` varchar(255) DEFAULT NULL,
  `assigned_to` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `progress` varchar(50) DEFAULT NULL,
  `status` varchar(20) DEFAULT 'pending',
  `c_by` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`id`, `project_name`, `project_id`, `client_name`, `contact_number`, `alternate_contact_number`, `email_id`, `address`, `city`, `state`, `pincode`, `pro_address`, `pro_city`, `pro_state`, `pro_pincode`, `plot_size`, `plot_unit`, `total_building_area`, `building_area_unit`, `additional_info`, `file_attachment`, `file_name`, `assigned_to`, `progress`, `status`, `c_by`, `created_at`, `updated_at`) VALUES
(1, 'General', '000', 'General client', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', 0.00, 'sq.ft', 0.00, 'sqft', 'NULL', 'NULL', 'NULL', '[\"2\",\"3\",\"4\",\"7\",\"8\",\"9\",\"10\",\"11\",\"12\",\"13\"]', 'excel', 'active', '3', '2025-12-19 06:47:22', '2025-12-19 07:37:22'),
(2, 'Karthik - Guduvanchery', '272', 'Karthik', '884407669', NULL, 'reddyvarun88@gmail.com', NULL, NULL, NULL, NULL, 'jsdfnosjdfok', 'sdvnok', 'kosdvok', '0888', 2400.00, 'sq.ft', 3333.00, 'sqft', NULL, NULL, NULL, '[\"2\",\"3\",\"4\"]', 'excel', 'active', '3', '2025-11-10 08:42:37', '2025-12-19 07:37:19'),
(3, 'test', '222', 'www', '8887879798', NULL, 'jhkjnk@gmail.com', NULL, NULL, NULL, NULL, 'khbkjbk', 'hbhbkjb', 'kjbkjb', '896986', 8768.00, 'sq.ft', 8769.00, 'sqft', NULL, NULL, NULL, '[\"2\",\"3\"]', 'excel', 'active', '3', '2025-12-03 03:57:31', '2025-12-19 07:37:25'),
(9, 'Richard - Uthandi', '123', 'Richard', '9360397561', NULL, 'vbbj@gmail.com', NULL, NULL, NULL, NULL, 'Uthandi', 'Chennia', 'TN', '600115', 2400.00, 'sq.ft', 4000.00, 'sqft', NULL, NULL, NULL, '[\"2\",\"3\",\"4\",\"7\",\"8\",\"9\",\"10\",\"11\",\"12\",\"13\"]', NULL, 'active', '3', '2025-12-12 11:07:41', '2025-12-19 07:37:28');

-- --------------------------------------------------------

--
-- Table structure for table `pro_docs`
--

CREATE TABLE `pro_docs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `pro_id` int(11) NOT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `desp` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `link` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `c_by` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pro_docs`
--

INSERT INTO `pro_docs` (`id`, `pro_id`, `type`, `title`, `desp`, `link`, `status`, `c_by`, `created_at`, `updated_at`) VALUES
(1, 1, 'Link', 'Expense form', 'kkdk', 'https://sitesync.site/', 'Active', NULL, '2025-11-10 09:18:46', '2025-11-10 09:18:46'),
(2, 1, 'Document', 'klo', 'kk;llk', 'painted-walnut-wood-veneer-door-224.jpg', 'Active', NULL, '2025-11-10 09:19:42', '2025-11-10 09:19:42'),
(3, 1, 'Document', 'Final Spec', 'kkkk', 'Warranties.pdf', 'Active', NULL, '2025-11-10 09:20:35', '2025-11-10 09:20:35'),
(4, 1, 'Link', 'Expense form', '.', 'https://forms.gle/TLk7UC6b7W5RZ2et9', 'Active', NULL, '2025-12-12 06:37:03', '2025-12-12 06:37:03');

-- --------------------------------------------------------

--
-- Table structure for table `qc`
--

CREATE TABLE `qc` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `status` varchar(20) DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `qc`
--

INSERT INTO `qc` (`id`, `title`, `description`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Materials Quality', 'QC-focused survey', 'active', '2025-08-13 23:04:09', '2025-08-13 23:04:09'),
(2, 'Testing & Inspection', 'None', 'active', '2025-08-13 23:05:04', '2025-08-13 23:05:04');

-- --------------------------------------------------------

--
-- Table structure for table `qc_ans`
--

CREATE TABLE `qc_ans` (
  `id` int(11) NOT NULL,
  `qc_entry` int(11) DEFAULT NULL,
  `q_id` int(11) DEFAULT NULL,
  `answer` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `status` varchar(30) DEFAULT NULL,
  `c_by` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `qc_checklist`
--

CREATE TABLE `qc_checklist` (
  `id` int(10) UNSIGNED NOT NULL,
  `qc_id` int(10) UNSIGNED NOT NULL,
  `question` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `qc_checklist`
--

INSERT INTO `qc_checklist` (`id`, `qc_id`, `question`, `created_at`, `updated_at`) VALUES
(1, 1, 'Are all materials delivered as per the approved specifications?', '2025-08-13 23:04:09', '2025-08-13 23:04:09'),
(2, 1, 'Are materials stored properly to prevent damage or deterioration?', '2025-08-13 23:04:09', '2025-08-13 23:04:09'),
(3, 1, 'Is the work executed as per the approved drawings and technical standards?', '2025-08-13 23:04:09', '2025-08-13 23:04:09'),
(4, 1, 'Are surface finishes (plaster, paint, tiles, etc.) up to quality standards?', '2025-08-13 23:04:09', '2025-08-13 23:04:09'),
(5, 2, 'Are all required material tests (cement, steel, concrete cubes, etc.) conducted?', '2025-08-13 23:05:04', '2025-08-13 23:05:04'),
(6, 2, 'Are inspection checklists completed and documented for each stage?', '2025-08-13 23:05:04', '2025-08-13 23:05:04'),
(7, 2, 'Were any defects identified during this stage?', '2025-08-13 23:05:04', '2025-08-13 23:05:04'),
(8, 2, 'If defects found, was corrective action taken promptly?', '2025-08-13 23:05:04', '2025-08-13 23:05:04');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `role_description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'Active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `role_description`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'web', 'admin', 'Active', '2025-07-16 23:41:05', '2025-07-16 23:41:05'),
(2, 'Project Manager', 'web', 'Project Manager', 'Active', '2025-07-16 23:41:05', '2025-07-16 23:41:05'),
(3, 'Site Engineer', 'web', 'Site Engineer', 'Active', '2025-07-16 23:41:05', '2025-07-16 23:41:05'),
(4, 'Architect', 'web', 'Architect', 'Active', '2025-07-16 23:41:05', '2025-07-16 23:41:05'),
(5, 'QC Supervisior', 'web', 'QC Supervisior', 'Active', '2025-07-16 23:41:05', '2025-07-16 23:41:05');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(2, 1),
(2, 2),
(2, 3),
(2, 4),
(2, 5),
(3, 1),
(3, 2),
(3, 3),
(3, 4),
(3, 5),
(4, 1),
(5, 1),
(5, 2),
(5, 3),
(5, 5),
(6, 1),
(6, 2),
(6, 3),
(6, 5),
(7, 1),
(7, 2),
(7, 3),
(7, 4),
(7, 5),
(8, 1),
(8, 2),
(8, 3),
(8, 4),
(8, 5),
(10, 1),
(10, 2),
(10, 4),
(11, 1),
(11, 2),
(11, 4),
(11, 5),
(13, 1),
(13, 2),
(15, 1),
(15, 2),
(16, 1),
(16, 2),
(16, 3),
(16, 5),
(18, 1),
(18, 2),
(19, 1),
(19, 2),
(19, 3),
(19, 5),
(21, 1),
(21, 2),
(21, 5),
(22, 1),
(22, 2),
(23, 1),
(23, 2),
(24, 1),
(24, 2),
(24, 3),
(24, 4),
(24, 5),
(25, 1),
(25, 2),
(25, 3),
(25, 4),
(25, 5),
(27, 1),
(27, 2),
(27, 3),
(27, 4),
(27, 5),
(28, 1),
(28, 2),
(28, 3),
(28, 5),
(29, 1),
(29, 2),
(29, 3),
(29, 4),
(29, 5);

-- --------------------------------------------------------

--
-- Table structure for table `snag`
--

CREATE TABLE `snag` (
  `id` int(11) NOT NULL,
  `category` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `status` varchar(20) DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `snag`
--

INSERT INTO `snag` (`id`, `category`, `description`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Civil', '-', 'active', '2025-08-13 23:05:38', '2025-12-10 08:47:56'),
(2, 'Shuttering', '-', 'active', '2025-08-13 23:06:00', '2025-12-10 08:49:58'),
(3, 'Electrical', '-', 'active', '2025-08-13 23:06:20', '2025-12-10 08:49:19'),
(4, 'Plumbing', '-', 'active', '2025-12-10 08:49:52', '2025-12-10 08:49:52'),
(5, 'Joinery', '-', 'active', '2025-12-10 08:50:10', '2025-12-10 08:50:10'),
(6, 'Painting', '-', 'active', '2025-12-10 08:50:18', '2025-12-10 08:50:18'),
(7, 'UPVC', '-', 'active', '2025-12-10 08:50:29', '2025-12-10 08:50:29'),
(8, 'Tiles & Granite', '-', 'active', '2025-12-10 08:51:04', '2025-12-10 08:51:04'),
(9, 'Other', '-', 'active', '2025-12-10 08:51:22', '2025-12-10 08:51:22');

-- --------------------------------------------------------

--
-- Table structure for table `snag_ans`
--

CREATE TABLE `snag_ans` (
  `id` int(11) NOT NULL,
  `entry_snag` int(11) NOT NULL,
  `file` text DEFAULT NULL,
  `desp` text DEFAULT NULL,
  `status` varchar(30) DEFAULT NULL,
  `c_by` varchar(25) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `snag_ans`
--

INSERT INTO `snag_ans` (`id`, `entry_snag`, `file`, `desp`, `status`, `c_by`, `created_at`, `updated_at`) VALUES
(1, 12, '3f2c6547-6966-4a69-8e6b-da6a3f38f0d69169214995561690646.jpg', 'ghhb', 'Pending', '2', '2025-11-10 09:15:39', '2025-11-10 09:15:39'),
(2, 14, '0ccaa3d8-f752-4d91-bdb8-d74751d6b2768634114693941328950.jpg', 'test', 'Pending', '3', '2025-11-14 05:47:31', '2025-11-14 05:47:31'),
(3, 15, '1000547886.jpg', 'done', 'Pending', '2', '2025-12-12 11:48:11', '2025-12-12 11:48:11');

-- --------------------------------------------------------

--
-- Table structure for table `snag_comments`
--

CREATE TABLE `snag_comments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `snag_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `comment` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `snag_comments`
--

INSERT INTO `snag_comments` (`id`, `snag_id`, `user_id`, `comment`, `created_at`, `updated_at`) VALUES
(4, 12, 2, 'hhgv', '2025-11-10 09:13:46', '2025-11-10 09:13:46'),
(5, 12, 3, 'grdd', '2025-11-10 09:14:48', '2025-11-10 09:14:48'),
(6, 15, 2, 'hi', '2025-12-12 11:46:56', '2025-12-12 11:46:56'),
(7, 15, 3, 'jjj', '2025-12-12 11:47:31', '2025-12-12 11:47:31');

-- --------------------------------------------------------

--
-- Table structure for table `sub_category`
--

CREATE TABLE `sub_category` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `sub_category` varchar(100) NOT NULL,
  `sub_category_title` varchar(255) NOT NULL,
  `sub_category_description` text DEFAULT NULL,
  `status` varchar(20) DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `survey`
--

CREATE TABLE `survey` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `status` varchar(20) DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `survey`
--

INSERT INTO `survey` (`id`, `title`, `description`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Service Quality', NULL, 'active', '2025-08-13 23:01:54', '2025-12-12 11:31:13'),
(2, 'Workload & Efficiency', NULL, 'active', '2025-08-13 23:02:53', '2025-12-12 11:31:12'),
(3, 'New Construction Site', 'Survey of new construction site', 'active', '2025-08-19 23:46:39', '2025-12-12 11:31:08');

-- --------------------------------------------------------

--
-- Table structure for table `survey_ans`
--

CREATE TABLE `survey_ans` (
  `id` int(11) NOT NULL,
  `entry_ans` int(11) DEFAULT NULL,
  `q_type` varchar(30) DEFAULT NULL,
  `q_id` int(11) DEFAULT NULL,
  `answer` text DEFAULT NULL,
  `status` varchar(25) DEFAULT NULL,
  `c_by` varchar(25) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `survey_ans`
--

INSERT INTO `survey_ans` (`id`, `entry_ans`, `q_type`, `q_id`, `answer`, `status`, `c_by`, `created_at`, `updated_at`) VALUES
(1, 1, 'Text', 8, '4', 'pending', '2', '2025-11-10 09:09:54', '2025-11-10 09:09:54'),
(2, 1, 'Textarea', 9, '45', 'pending', '2', '2025-11-10 09:09:54', '2025-11-10 09:09:54'),
(3, 1, 'location', 10, '12.9548402,80.2591388', 'pending', '2', '2025-11-10 09:09:54', '2025-11-10 09:09:54'),
(4, 1, 'File', 11, '[\"31ac7718-3dc5-4874-8984-984b47e243e96448708276370406457.jpg\"]', 'pending', '2', '2025-11-10 09:09:54', '2025-11-10 09:09:54'),
(5, 1, 'File', 12, '[\"85b6e3e7-e143-4bb3-a387-811aaa9cd3106050822987983834648.jpg\"]', 'pending', '2', '2025-11-10 09:09:54', '2025-11-10 09:09:54'),
(6, 1, 'Checkbox', 13, '[44]', 'pending', '2', '2025-11-10 09:09:54', '2025-11-10 09:09:54'),
(7, 4, 'Checkbox', 1, '[36]', 'pending', '3', '2025-11-14 05:52:10', '2025-11-14 05:52:10'),
(8, 4, 'Radio', 2, 'Average – could be better', 'pending', '3', '2025-11-14 05:52:10', '2025-11-14 05:52:10'),
(9, 4, 'Text', 3, 'nil', 'pending', '3', '2025-11-14 05:52:10', '2025-11-14 05:52:10'),
(10, 4, 'File', 4, '[\"images_(1).jpeg\"]', 'pending', '3', '2025-11-14 05:52:10', '2025-11-14 05:52:10'),
(11, 4, 'location', 5, '11.6754488,78.1320789', 'pending', '3', '2025-11-14 05:52:10', '2025-11-14 05:52:10'),
(12, 5, 'location', 10, '12.9552539,80.2589908', 'pending', '2', '2025-12-12 11:36:32', '2025-12-12 11:36:32'),
(13, 5, 'Text', 14, '2ft', 'pending', '2', '2025-12-12 11:36:32', '2025-12-12 11:36:32'),
(14, 5, 'Textarea', 15, 'n25 s25', 'pending', '2', '2025-12-12 11:36:32', '2025-12-12 11:36:32'),
(15, 5, 'File', 16, '[\"1000547065.jpg\",\"1000547886.jpg\"]', 'pending', '2', '2025-12-12 11:36:32', '2025-12-12 11:36:32'),
(16, 5, 'Radio', 17, 'Yes', 'pending', '2', '2025-12-12 11:36:32', '2025-12-12 11:36:32'),
(17, 5, 'File', 19, '[\"1000547814.jpg\"]', 'pending', '2', '2025-12-12 11:36:32', '2025-12-12 11:36:32');

-- --------------------------------------------------------

--
-- Table structure for table `survey_choices`
--

CREATE TABLE `survey_choices` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `question_id` bigint(20) UNSIGNED NOT NULL,
  `choice` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `survey_choices`
--

INSERT INTO `survey_choices` (`id`, `question_id`, `choice`, `created_at`, `updated_at`) VALUES
(1, 2, 'ch1', '2025-03-02 00:30:13', '2025-03-02 00:30:13'),
(2, 2, 'ch2', '2025-03-02 00:30:13', '2025-03-02 00:30:13'),
(5, 5, 'rad1', '2025-03-02 08:56:12', '2025-03-02 08:56:12'),
(6, 5, 'rad2', '2025-03-02 08:56:12', '2025-03-02 08:56:12'),
(7, 7, 'rad1', '2025-03-02 08:57:47', '2025-03-02 08:57:47'),
(8, 7, 'rad2', '2025-03-02 08:57:47', '2025-03-02 08:57:47'),
(9, 7, 'rad3', '2025-03-02 08:57:47', '2025-03-02 08:57:47'),
(10, 7, 'rad4', '2025-03-02 08:57:47', '2025-03-02 08:57:47'),
(17, 15, 'rad1', '2025-03-02 18:07:02', '2025-03-02 18:07:02'),
(18, 15, 'rad2', '2025-03-02 18:07:02', '2025-03-02 18:07:02'),
(22, 19, 'Survey 1', '2025-03-30 23:24:46', '2025-03-30 23:24:46'),
(23, 19, 'Survey 2', '2025-03-30 23:24:46', '2025-03-30 23:24:46'),
(24, 19, 'Survey 3', '2025-03-30 23:24:46', '2025-03-30 23:24:46'),
(25, 20, '2', '2025-03-30 23:25:46', '2025-03-30 23:25:46'),
(26, 20, '3', '2025-03-30 23:25:46', '2025-03-30 23:25:46'),
(27, 20, '4', '2025-03-30 23:25:46', '2025-03-30 23:25:46'),
(28, 31, 'Yes', '2025-06-19 04:36:32', '2025-06-19 04:36:32'),
(29, 31, 'No', '2025-06-19 04:36:32', '2025-06-19 04:36:32'),
(30, 31, 'None of the Above', '2025-06-19 04:36:32', '2025-06-19 04:36:32'),
(31, 32, 'Morning', '2025-06-19 04:36:32', '2025-06-19 04:36:32'),
(32, 32, 'Afternoon', '2025-06-19 04:36:32', '2025-06-19 04:36:32'),
(33, 32, 'Evening', '2025-06-19 04:36:32', '2025-06-19 04:36:32'),
(34, 32, 'NIght', '2025-06-19 04:36:32', '2025-06-19 04:36:32'),
(35, 1, 'Excellent', '2025-08-13 23:01:54', '2025-08-13 23:01:54'),
(36, 1, 'Good', '2025-08-13 23:01:54', '2025-08-13 23:01:54'),
(37, 1, 'Average', '2025-08-13 23:01:54', '2025-08-13 23:01:54'),
(38, 1, 'Bad', '2025-08-13 23:01:54', '2025-08-13 23:01:54'),
(39, 2, 'Excellent – worth every penny', '2025-08-13 23:01:54', '2025-08-13 23:01:54'),
(40, 2, 'Good – fair for the quality', '2025-08-13 23:01:54', '2025-08-13 23:01:54'),
(41, 2, 'Average – could be better', '2025-08-13 23:01:54', '2025-08-13 23:01:54'),
(42, 2, 'Poor – not worth the cost', '2025-08-13 23:01:54', '2025-08-13 23:01:54'),
(45, 17, 'Yes', '2025-12-10 08:35:29', '2025-12-10 08:35:29'),
(46, 17, 'No', '2025-12-10 08:35:29', '2025-12-10 08:35:29');

-- --------------------------------------------------------

--
-- Table structure for table `survey_questions`
--

CREATE TABLE `survey_questions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `survey_id` bigint(20) UNSIGNED NOT NULL,
  `question` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `question_type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `survey_questions`
--

INSERT INTO `survey_questions` (`id`, `survey_id`, `question`, `question_type`, `created_at`, `updated_at`) VALUES
(1, 1, 'How would you rate the quality of our construction work?', 'Checkbox', '2025-08-13 23:01:54', '2025-08-13 23:01:54'),
(2, 1, 'How would you rate the value for money for our services?', 'Radio', '2025-08-13 23:01:54', '2025-08-13 23:01:54'),
(3, 1, 'Would you hire us again for future projects?', 'Text', '2025-08-13 23:01:54', '2025-08-13 23:01:54'),
(4, 1, 'Share me file', 'File', '2025-08-13 23:01:54', '2025-08-13 23:01:54'),
(5, 1, 'Share me the location of the site', 'location', '2025-08-13 23:01:54', '2025-08-13 23:01:54'),
(6, 2, 'File upload', 'File', '2025-08-13 23:02:53', '2025-08-13 23:02:53'),
(7, 2, 'Locaation of the site', 'location', '2025-08-13 23:02:53', '2025-08-13 23:02:53'),
(10, 3, 'Site location', 'location', '2025-08-19 23:46:39', '2025-08-19 23:46:39'),
(14, 3, 'What is the level difference between site NGL and abutting road? (Take average of 5 points)', 'Text', '2025-12-10 08:26:59', '2025-12-10 08:26:59'),
(15, 3, 'What are the site physical outer to outer dimensions? (Take length and width of all 4 sides. Including compound wall if its ours)', 'Textarea', '2025-12-10 08:29:20', '2025-12-10 08:29:20'),
(16, 3, 'Upload photos of full site from road side showing neighbours buildings', 'File', '2025-12-10 08:34:29', '2025-12-10 08:34:29'),
(17, 3, 'Is there existing compound wall inside site?', 'Radio', '2025-12-10 08:35:29', '2025-12-10 08:35:29'),
(19, 3, 'Upload hand sketch of site boundary with measurements and mark any trees, structure, sump, bore, septic tank inside site', 'File', '2025-12-10 08:39:17', '2025-12-10 08:39:17');

-- --------------------------------------------------------

--
-- Table structure for table `task`
--

CREATE TABLE `task` (
  `id` int(11) NOT NULL,
  `parent_task_id` int(11) DEFAULT 0,
  `project_id` int(11) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `assigned_to` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `sub_category_id` int(11) DEFAULT NULL,
  `priority` enum('Low','Medium','High','Critical') NOT NULL,
  `start_timestamp` datetime DEFAULT NULL,
  `end_timestamp` datetime NOT NULL,
  `description` text DEFAULT NULL,
  `additional_info` text DEFAULT NULL,
  `file_attachment` varchar(255) DEFAULT NULL,
  `file_name` varchar(255) DEFAULT NULL,
  `reminder` varchar(30) NOT NULL DEFAULT 'false',
  `status` varchar(100) DEFAULT 'in_progress',
  `is_assigned` int(11) DEFAULT 0,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `task`
--

INSERT INTO `task` (`id`, `parent_task_id`, `project_id`, `title`, `assigned_to`, `category_id`, `sub_category_id`, `priority`, `start_timestamp`, `end_timestamp`, `description`, `additional_info`, `file_attachment`, `file_name`, `reminder`, `status`, `is_assigned`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'test 234', 2, NULL, NULL, 'Medium', NULL, '2025-11-10 00:00:00', 'ttttt', NULL, 'task/c1ad89af-b287-4e2c-ad14-b482bc2950f71072653411084271672.jpg', 'c1ad89af-b287-4e2c-ad14-b482bc2950f71072653411084271672.jpg', 'false', 'completed', 0, 3, '2025-11-10 09:00:24', '2025-11-10 09:02:25'),
(2, 2, 9, 'Training 1', 2, NULL, NULL, 'Medium', NULL, '2025-12-13 00:00:00', 'mmmmmm', NULL, 'task/IMG-20250929-WA0047.jpg', 'IMG-20250929-WA0047.jpg', 'false', 'completed', 0, 3, '2025-12-12 11:20:14', '2025-12-12 11:24:36'),
(3, 3, 9, 'uiii', 3, NULL, NULL, 'Medium', NULL, '2025-12-12 00:00:00', 'nnnbbbh', NULL, 'task/8c25b8c2-a0ee-4331-877a-42b0095ec9e72346559148900576640.jpg', '8c25b8c2-a0ee-4331-877a-42b0095ec9e72346559148900576640.jpg', 'false', 'completed', 0, 2, '2025-12-12 12:54:49', '2025-12-12 12:55:35'),
(6, 6, 1, 'Testing for file', 2, NULL, NULL, 'Low', NULL, '2025-12-19 00:00:00', NULL, NULL, NULL, NULL, 'false', 'in_progress', 0, 3, '2025-12-19 05:32:27', '2025-12-19 05:32:27');

-- --------------------------------------------------------

--
-- Table structure for table `task_close`
--

CREATE TABLE `task_close` (
  `id` int(11) NOT NULL,
  `request_to_task` int(11) DEFAULT NULL,
  `request_by_task` int(11) DEFAULT NULL,
  `assign_to` int(11) DEFAULT NULL,
  `file` text NOT NULL,
  `remarks` text NOT NULL,
  `request_by` int(11) DEFAULT NULL,
  `status` varchar(50) DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `task_close`
--

INSERT INTO `task_close` (`id`, `request_to_task`, `request_by_task`, `assign_to`, `file`, `remarks`, `request_by`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 3, 'bc57ab96-7216-421d-a59c-9d93f1e8aac9405439413834361632.jpg', 'done', 2, 'approved', '2025-11-10 09:02:25', '2025-11-10 09:02:55'),
(2, 2, 2, 3, 'IMG-20251212-WA0018.jpg', 'completed', 2, 'approved', '2025-12-12 11:24:36', '2025-12-12 11:29:03'),
(3, 3, 3, 2, 'd2a6e143-1141-463e-9619-a01093f654e35973588183103391717.jpg', 'done', 3, 'approved', '2025-12-12 12:55:35', '2025-12-12 12:56:06');

-- --------------------------------------------------------

--
-- Table structure for table `uom`
--

CREATE TABLE `uom` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `status` varchar(20) DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_material`
--
ALTER TABLE `activity_material`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `activity_work`
--
ALTER TABLE `activity_work`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `block_table`
--
ALTER TABLE `block_table`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `category` (`category`);

--
-- Indexes for table `checklist`
--
ALTER TABLE `checklist`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `company`
--
ALTER TABLE `company`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `designation`
--
ALTER TABLE `designation`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `drawing`
--
ALTER TABLE `drawing`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `employee_code` (`employee_code`),
  ADD UNIQUE KEY `email_id` (`email_id`);

--
-- Indexes for table `entry_drawing`
--
ALTER TABLE `entry_drawing`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `entry_qc`
--
ALTER TABLE `entry_qc`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `entry_snag`
--
ALTER TABLE `entry_snag`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `entry_survey`
--
ALTER TABLE `entry_survey`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ent_role_permissions`
--
ALTER TABLE `ent_role_permissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `item`
--
ALTER TABLE `item`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `master_roles`
--
ALTER TABLE `master_roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `notification`
--
ALTER TABLE `notification`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `progress_activity`
--
ALTER TABLE `progress_activity`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `progress_import`
--
ALTER TABLE `progress_import`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `progress_material`
--
ALTER TABLE `progress_material`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `progress_stage`
--
ALTER TABLE `progress_stage`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `project_id` (`project_id`);

--
-- Indexes for table `pro_docs`
--
ALTER TABLE `pro_docs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `qc`
--
ALTER TABLE `qc`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `qc_ans`
--
ALTER TABLE `qc_ans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `qc_checklist`
--
ALTER TABLE `qc_checklist`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `snag`
--
ALTER TABLE `snag`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `snag_ans`
--
ALTER TABLE `snag_ans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `snag_comments`
--
ALTER TABLE `snag_comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `snag_comments_snag_id_foreign` (`snag_id`);

--
-- Indexes for table `sub_category`
--
ALTER TABLE `sub_category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `survey`
--
ALTER TABLE `survey`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `survey_ans`
--
ALTER TABLE `survey_ans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `survey_choices`
--
ALTER TABLE `survey_choices`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `survey_questions`
--
ALTER TABLE `survey_questions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `task`
--
ALTER TABLE `task`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `task_close`
--
ALTER TABLE `task_close`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `uom`
--
ALTER TABLE `uom`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_material`
--
ALTER TABLE `activity_material`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `activity_work`
--
ALTER TABLE `activity_work`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `block_table`
--
ALTER TABLE `block_table`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `checklist`
--
ALTER TABLE `checklist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `comment`
--
ALTER TABLE `comment`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `company`
--
ALTER TABLE `company`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `designation`
--
ALTER TABLE `designation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `drawing`
--
ALTER TABLE `drawing`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `employee`
--
ALTER TABLE `employee`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `entry_drawing`
--
ALTER TABLE `entry_drawing`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `entry_qc`
--
ALTER TABLE `entry_qc`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `entry_snag`
--
ALTER TABLE `entry_snag`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `entry_survey`
--
ALTER TABLE `entry_survey`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `ent_role_permissions`
--
ALTER TABLE `ent_role_permissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `item`
--
ALTER TABLE `item`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `master_roles`
--
ALTER TABLE `master_roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notification`
--
ALTER TABLE `notification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=166;

--
-- AUTO_INCREMENT for table `progress_activity`
--
ALTER TABLE `progress_activity`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `progress_import`
--
ALTER TABLE `progress_import`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `progress_material`
--
ALTER TABLE `progress_material`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `progress_stage`
--
ALTER TABLE `progress_stage`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `pro_docs`
--
ALTER TABLE `pro_docs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `qc`
--
ALTER TABLE `qc`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `qc_ans`
--
ALTER TABLE `qc_ans`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `qc_checklist`
--
ALTER TABLE `qc_checklist`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `snag`
--
ALTER TABLE `snag`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `snag_ans`
--
ALTER TABLE `snag_ans`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `snag_comments`
--
ALTER TABLE `snag_comments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `sub_category`
--
ALTER TABLE `sub_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `survey`
--
ALTER TABLE `survey`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `survey_ans`
--
ALTER TABLE `survey_ans`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `survey_choices`
--
ALTER TABLE `survey_choices`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `survey_questions`
--
ALTER TABLE `survey_questions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `task`
--
ALTER TABLE `task`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `task_close`
--
ALTER TABLE `task_close`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `uom`
--
ALTER TABLE `uom`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `snag_comments`
--
ALTER TABLE `snag_comments`
  ADD CONSTRAINT `snag_comments_snag_id_foreign` FOREIGN KEY (`snag_id`) REFERENCES `entry_snag` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
