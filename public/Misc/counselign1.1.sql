-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 27, 2026 at 03:45 PM
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
-- Database: `counselign`
--
CREATE DATABASE IF NOT EXISTS `counselign` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `counselign`;

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

DROP TABLE IF EXISTS `announcements`;
CREATE TABLE IF NOT EXISTS `announcements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `id` int(11) NOT NULL,
  `student_id` varchar(10) NOT NULL,
  `preferred_date` date NOT NULL,
  `preferred_time` varchar(50) NOT NULL,
  `consultation_type` varchar(50) NOT NULL,
  `method_type` varchar(50) NOT NULL,
  `purpose` text DEFAULT NULL,
  `counselor_preference` varchar(100) DEFAULT 'No preference',
  `description` text DEFAULT NULL,
  `reason` text DEFAULT NULL,
  `status` enum('pending','approved','rescheduled','completed') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `counselor_remarks` text DEFAULT NULL,
  `student_feedback_status` enum('Pending_feedback','Feedback Submitted') NOT NULL DEFAULT 'Pending_feedback'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`id`, `student_id`, `preferred_date`, `preferred_time`, `consultation_type`, `method_type`, `purpose`, `counselor_preference`, `description`, `reason`, `status`, `created_at`, `updated_at`, `counselor_remarks`, `student_feedback_status`) VALUES
(1, '2023305013', '2026-04-20', '7:00 AM - 7:30 AM', 'Individual Consultation', 'In-person', 'Counseling', '2023307088', 'no comment', NULL, 'completed', '2026-04-16 09:31:21', '2026-04-30 06:43:51', 'hello', 'Feedback Submitted'),
(2, '2023305013', '2026-04-20', '7:00 AM - 7:30 AM', 'Individual Consultation', 'In-person', 'Psycho-Social Support', '2023307088', 'naa koy gamay', NULL, 'completed', '2026-04-16 09:36:20', '2026-04-30 06:43:51', 'naa gyud deay gamay', 'Feedback Submitted'),
(3, '2023305013', '2026-04-20', '9:30 AM - 10:00 AM', 'Individual Consultation', 'In-person', 'Counseling', '2023307088', 'for missing clearance', NULL, 'completed', '2026-04-17 03:53:06', '2026-04-30 06:43:51', 'His quite headache', 'Feedback Submitted'),
(4, '2023305013', '2026-04-20', '7:00 AM - 7:30 AM', 'Individual Consultation', 'In-person', 'Counseling', '2023307088', 'no comment', NULL, 'completed', '2026-04-17 04:04:27', '2026-04-30 06:43:51', 'no comment', 'Feedback Submitted'),
(5, '2023305013', '2026-04-20', '7:00 AM - 7:30 AM', 'Individual Consultation', 'In-person', 'Counseling', '2023307088', 's', NULL, 'completed', '2026-04-17 04:32:43', '2026-04-30 06:43:51', 'okay you\'re done', 'Feedback Submitted'),
(6, '2023305013', '2026-04-20', '7:30 AM - 8:00 AM', 'Individual Consultation', 'In-person', 'Psycho-Social Support', '2023307088', 'The 50 words project aims to provide fifty words in every Indigenous language of Australia. We hope that this will be a useful resource for schools', NULL, 'completed', '2026-04-17 05:51:53', '2026-04-30 06:43:51', 'Done doing all the requirements', 'Feedback Submitted'),
(7, '2023305013', '2026-04-20', '7:30 AM - 8:00 AM', 'Individual Consultation', 'In-person', 'Counseling', '2023307088', 'For a counselor email feature, use clear action-based button names like “Send Email”, “Send Message”, or “Send Counseling Email”. Avoid generic labels like “Submit.” The button should clearly show that it sends an email to students. This improves usability, professionalism, and reduces confusion in your counseling system.', NULL, 'completed', '2026-04-17 06:08:22', '2026-04-30 06:43:51', 'For a counselor email feature, use clear action-based button names like “Send Email”, “Send Message”, or “Send Counseling Email”. Avoid generic labels like “Submit.” The button should clearly show that it sends an email to students. This improves usability, professionalism, and reduces confusion in your counseling system.', 'Feedback Submitted'),
(8, '2023305013', '2026-04-20', '7:30 AM - 8:00 AM', 'Group Consultation', 'In-person', 'Counseling', '2023307088', 'add a status badge for Feedback_Pending\n\nhttp://localhost/Counselign/public/counselor/appointments', NULL, 'completed', '2026-04-17 07:32:41', '2026-04-30 06:43:51', 'add a status badge for Feedback_Pending\r\n\r\nhttp://localhost/Counselign/public/counselor/appointmentsadd a status badge for Feedback_Pending\r\n\r\nhttp://localhost/Counselign/public/counselor/appointments', 'Feedback Submitted'),
(9, '2023305013', '2026-04-21', '7:00 AM', 'Individual Consultation', 'In-person', 'Counseling', '2023307088', 'no comment', 'Rescheduled by counselor. Reason: ..... Previous date: 2026-04-23 7:00 AM - 7:30 AM', 'completed', '2026-04-21 00:00:32', '2026-04-30 06:43:51', '111', 'Feedback Submitted'),
(10, '2023305013', '2026-04-21', '7:00 AM', 'Individual Consultation', 'In-person', 'Counseling', '2023307088', 'Demo', 'Rescheduled by counselor. Reason: ... Previous date: 2026-04-22 7:00 AM - 7:30 AM', 'completed', '2026-04-21 00:32:53', '2026-04-30 06:43:51', 'demo', 'Feedback Submitted'),
(11, '2023304958', '2026-04-21', '7:00 AM', 'Individual Consultation', 'In-person', 'Psycho-Social Support', '2023307088', 'sdfasdfsadf', 'Rescheduled by counselor. Reason: ..... Previous date: 2026-04-22 7:00 AM - 7:30 AM', 'completed', '2026-04-21 04:56:57', '2026-04-30 06:43:51', '.....', 'Feedback Submitted'),
(12, '2023307080', '2026-04-21', '7:00 AM', 'Individual Consultation', 'In-person', 'Counseling', '2023307088', 'I am stress, I need some help.', 'Rescheduled by counselor. Reason: .. Previous date: 2026-04-22 7:00 AM - 7:30 AM', 'completed', '2026-04-21 05:10:26', '2026-04-30 06:43:51', 'Ok siya.', 'Feedback Submitted'),
(13, '2023305013', '2026-04-21', '9:00 AM', 'Individual Consultation', 'In-person', 'Counseling', '2023307088', '.....', 'Rescheduled by counselor. Reason: ..... Previous date: 2026-04-22 7:30 AM - 8:00 AM', 'completed', '2026-04-21 05:57:23', '2026-04-30 06:43:51', 'no comment', 'Feedback Submitted'),
(24, '2023305013', '2026-05-11', '7:30 AM - 8:00 AM', 'Individual Consultation', 'In-person', 'Initial Interview', '2023307088', '11', NULL, 'completed', '2026-05-07 11:49:28', '2026-05-07 11:49:43', '112121', 'Pending_feedback');

--
-- Triggers `appointments`
--
DELIMITER $$
CREATE TRIGGER `prevent_double_booking` BEFORE INSERT ON `appointments` FOR EACH ROW BEGIN
                DECLARE conflict_count INT DEFAULT 0;
                
                -- Check for conflicts with same counselor, date, and time
                SELECT COUNT(*) INTO conflict_count
                FROM appointments 
                WHERE counselor_preference = NEW.counselor_preference 
                AND preferred_date = NEW.preferred_date 
                AND preferred_time = NEW.preferred_time 
                AND status IN ('pending', 'approved')
                AND counselor_preference != 'No preference';
                
                IF conflict_count > 0 THEN
                    SIGNAL SQLSTATE '45000' 
                    SET MESSAGE_TEXT = 'Counselor already has an appointment at this time';
                END IF;
            END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `prevent_double_booking_update` BEFORE UPDATE ON `appointments` FOR EACH ROW BEGIN
    DECLARE conflict_count INT DEFAULT 0;
    DECLARE individual_count INT DEFAULT 0;
    DECLARE group_count INT DEFAULT 0;
    
    IF (NEW.counselor_preference != OLD.counselor_preference 
        OR NEW.preferred_date != OLD.preferred_date 
        OR NEW.preferred_time != OLD.preferred_time
        OR NEW.consultation_type != OLD.consultation_type) THEN
        
        IF NEW.consultation_type = 'Individual Consultation' THEN
            SELECT COUNT(*) INTO conflict_count
            FROM appointments 
            WHERE counselor_preference = NEW.counselor_preference 
            AND preferred_date = NEW.preferred_date 
            AND preferred_time = NEW.preferred_time 
            AND status IN ('pending', 'approved')
            AND counselor_preference != 'No preference'
            AND id != NEW.id;
            
            IF conflict_count > 0 THEN
                SIGNAL SQLSTATE '45000' 
                SET MESSAGE_TEXT = 'This time slot is already booked. Individual consultations require exclusive time slots.';
            END IF;
        
        ELSEIF NEW.consultation_type = 'Group Consultation' THEN
            SELECT COUNT(*) INTO individual_count
            FROM appointments 
            WHERE counselor_preference = NEW.counselor_preference 
            AND preferred_date = NEW.preferred_date 
            AND preferred_time = NEW.preferred_time 
            AND status IN ('pending', 'approved')
            AND consultation_type = 'Individual Consultation'
            AND counselor_preference != 'No preference'
            AND id != NEW.id;
            
            IF individual_count > 0 THEN
                SIGNAL SQLSTATE '45000' 
                SET MESSAGE_TEXT = 'This time slot is already booked for individual consultation. Group consultations cannot share time slots with individual consultations.';
            END IF;
            
            SELECT COUNT(*) INTO group_count
            FROM appointments 
            WHERE counselor_preference = NEW.counselor_preference 
            AND preferred_date = NEW.preferred_date 
            AND preferred_time = NEW.preferred_time 
            AND status IN ('pending', 'approved')
            AND consultation_type = 'Group Consultation'
            AND counselor_preference != 'No preference'
            AND id != NEW.id;
            
            IF group_count >= 5 THEN
                SIGNAL SQLSTATE '45000' 
                SET MESSAGE_TEXT = 'Group consultation slots are full for this time slot (maximum 5 participants).';
            END IF;
        END IF;
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `ci_sessions`
--

DROP TABLE IF EXISTS `ci_sessions`;
CREATE TABLE IF NOT EXISTS `ci_sessions` (
  `id` varchar(128) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `data` blob NOT NULL,
  PRIMARY KEY (`id`),
  KEY `timestamp` (`timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `counselors`
--

DROP TABLE IF EXISTS `counselors`;
CREATE TABLE IF NOT EXISTS `counselors` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `counselor_id` varchar(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `degree` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `contact_number` varchar(20) NOT NULL,
  `address` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `civil_status` varchar(20) DEFAULT NULL,
  `sex` varchar(10) DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `counselor_ibfk_1` (`counselor_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `counselors`
--

INSERT INTO `counselors` (`id`, `counselor_id`, `name`, `degree`, `email`, `contact_number`, `address`, `created_at`, `updated_at`, `civil_status`, `sex`, `birthdate`) VALUES
(1, '2023307088', 'Emeliza A Borres', 'PH, D.', 'borresemeliza@gmail.com', '09922984103', 'Tuganay, Santa Cruz, Claveria, Misamis Oriental, Northern Mindanao', '2026-04-16 09:29:38', '2026-04-16 09:29:38', 'Single', 'Female', '2005-04-03');

-- --------------------------------------------------------

--
-- Table structure for table `counselor_availability`
--

DROP TABLE IF EXISTS `counselor_availability`;
CREATE TABLE IF NOT EXISTS `counselor_availability` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `counselor_id` varchar(10) NOT NULL,
  `available_days` enum('Monday','Tuesday','Wednesday','Thursday','Friday') NOT NULL,
  `time_scheduled` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `counselor_id` (`counselor_id`),
  KEY `idx_counselor_availability_day` (`counselor_id`,`available_days`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `counselor_availability`
--

INSERT INTO `counselor_availability` (`id`, `counselor_id`, `available_days`, `time_scheduled`, `created_at`) VALUES
(1, '2023307088', 'Monday', '7:00 AM-10:00 AM', '2026-04-16 09:30:43'),
(2, '2023307088', 'Tuesday', '7:00 AM-10:00 AM', '2026-04-16 09:30:43'),
(3, '2023307088', 'Wednesday', '7:00 AM-10:00 AM', '2026-04-16 09:30:43'),
(4, '2023307088', 'Thursday', '7:00 AM-10:00 AM', '2026-04-16 09:30:43');

-- --------------------------------------------------------

--
-- Table structure for table `daily_quotes`
--

DROP TABLE IF EXISTS `daily_quotes`;
CREATE TABLE IF NOT EXISTS `daily_quotes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `quote_text` text NOT NULL,
  `author_name` varchar(255) NOT NULL,
  `category` varchar(100) DEFAULT 'Inspirational',
  `source` varchar(255) DEFAULT NULL,
  `submitted_by_id` varchar(50) NOT NULL,
  `submitted_by_name` varchar(255) NOT NULL,
  `submitted_by_role` enum('counselor','admin') DEFAULT 'counselor',
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `moderated_by` varchar(50) DEFAULT NULL,
  `moderated_at` timestamp NULL DEFAULT NULL,
  `rejection_reason` text DEFAULT NULL,
  `times_displayed` int(11) DEFAULT 0,
  `last_displayed_date` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_status` (`status`),
  KEY `idx_category` (`category`),
  KEY `idx_submitted_by` (`submitted_by_id`),
  KEY `idx_last_displayed` (`last_displayed_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

DROP TABLE IF EXISTS `events`;
CREATE TABLE IF NOT EXISTS `events` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `location` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `feedback_questions`
--

DROP TABLE IF EXISTS `feedback_questions`;
CREATE TABLE IF NOT EXISTS `feedback_questions` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `question_number` int(11) UNSIGNED NOT NULL,
  `question_text` text NOT NULL,
  `field_name` varchar(100) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback_questions`
--

INSERT INTO `feedback_questions` (`id`, `question_number`, `question_text`, `field_name`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 1, 'Are you okay?', 'q1_ease_of_use', 1, 1, NULL, '2026-04-21 09:39:37'),
(2, 2, 'How satisfied are you with the overall counseling experience?', 'q2_satisfaction', 1, 2, NULL, NULL),
(3, 3, 'How satisfied are you with the response time to your appointment request?', 'q3_timeliness', 1, 3, NULL, NULL),
(4, 4, 'How clear was the information provided about counseling services?', 'q4_information_clarity', 1, 4, NULL, NULL),
(5, 5, 'How helpful was the counseling staff in addressing your concerns?', 'q5_staff_helpfulness', 1, 5, NULL, NULL),
(6, 6, 'How reliable was the technology used for online consultations?', 'q6_technology_reliability', 1, 6, NULL, NULL),
(7, 7, 'How confident do you feel about the privacy of your personal information?', 'q7_privacy_confidence', 1, 7, NULL, NULL),
(8, 8, 'How likely are you to recommend our counseling services to others?', 'q8_recommendation', 1, 8, NULL, NULL),
(9, 9, 'hello po', 'q9_overall_experience', 1, 9, NULL, '2026-04-20 13:19:55'),
(10, 10, 'Are you okay?', 'q10_future_use', 1, 10, NULL, '2026-04-17 17:28:39');

-- --------------------------------------------------------

--
-- Table structure for table `follow_up_appointments`
--

DROP TABLE IF EXISTS `follow_up_appointments`;
CREATE TABLE IF NOT EXISTS `follow_up_appointments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `counselor_id` varchar(10) NOT NULL,
  `student_id` varchar(100) NOT NULL,
  `parent_appointment_id` int(11) DEFAULT NULL COMMENT 'References the initial appointment or previous follow-up',
  `preferred_date` date NOT NULL,
  `preferred_time` varchar(50) NOT NULL,
  `consultation_type` varchar(50) NOT NULL,
  `follow_up_sequence` int(11) NOT NULL DEFAULT 1 COMMENT 'Track the sequence: 1st follow-up, 2nd follow-up, etc.',
  `description` text DEFAULT NULL,
  `reason` text DEFAULT NULL,
  `status` enum('pending','rejected','completed','cancelled') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_counselor` (`counselor_id`),
  KEY `idx_student` (`student_id`),
  KEY `idx_parent_appointment` (`parent_appointment_id`),
  KEY `idx_status` (`status`),
  KEY `idx_followup_parent_sequence` (`parent_appointment_id`,`follow_up_sequence`),
  KEY `idx_counselor_id` (`counselor_id`)
) ;

--
-- Dumping data for table `follow_up_appointments`
--

INSERT INTO `follow_up_appointments` (`id`, `counselor_id`, `student_id`, `parent_appointment_id`, `preferred_date`, `preferred_time`, `consultation_type`, `follow_up_sequence`, `description`, `reason`, `status`, `created_at`, `updated_at`) VALUES
(1, '2023307088', '2023305013', 3, '2026-04-20', '7:30 AM - 8:00 AM', 'Individual Counseling', 1, 'd', 'd', 'completed', '2026-04-17 04:30:59', '2026-04-17 04:31:10');

--
-- Triggers `follow_up_appointments`
--
DROP TRIGGER IF EXISTS `maintain_followup_sequence`;
DELIMITER $$
CREATE TRIGGER `maintain_followup_sequence` BEFORE INSERT ON `follow_up_appointments` FOR EACH ROW BEGIN
                IF NEW.parent_appointment_id IS NOT NULL THEN
                    SET NEW.follow_up_sequence = (
                        SELECT COALESCE(MAX(follow_up_sequence), 0) + 1 
                        FROM follow_up_appointments 
                        WHERE parent_appointment_id = NEW.parent_appointment_id
                    );
                END IF;
            END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

DROP TABLE IF EXISTS `messages`;
CREATE TABLE IF NOT EXISTS `messages` (
  `message_id` int(11) NOT NULL AUTO_INCREMENT,
  `sender_id` varchar(10) DEFAULT NULL,
  `receiver_id` varchar(10) DEFAULT NULL,
  `message_text` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`message_id`),
  KEY `idx_created_at` (`created_at`),
  KEY `messages_ibfk_1` (`sender_id`),
  KEY `messages_ibfk_2` (`receiver_id`),
  KEY `idx_sender_id` (`sender_id`),
  KEY `idx_receiver_id` (`receiver_id`),
  KEY `idx_sender_receiver` (`sender_id`,`receiver_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`message_id`, `sender_id`, `receiver_id`, `message_text`, `is_read`, `created_at`) VALUES
(1, '2023305013', '2023307088', 'hello', 1, '2026-04-17 03:42:22');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `version` varchar(255) NOT NULL,
  `class` varchar(255) NOT NULL,
  `group` varchar(255) NOT NULL,
  `namespace` varchar(255) NOT NULL,
  `time` int(11) NOT NULL,
  `batch` int(11) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `version`, `class`, `group`, `namespace`, `time`, `batch`) VALUES
(1, '2024_01_01_000001', 'App\\Database\\Migrations\\FixForeignKeyConstraints', 'default', 'App', 1776418005, 1),
(2, '2024_01_01_000002', 'App\\Database\\Migrations\\AddBusinessRuleTriggers', 'default', 'App', 1776418005, 1),
(3, '2024_01_01_000003', 'App\\Database\\Migrations\\ConfigureACIDSettings', 'default', 'App', 1776418005, 1),
(4, '2025-09-23-160820', 'App\\Database\\Migrations\\AddNotificationsTable', 'default', 'App', 1776418005, 1),
(5, '2025-09-23-160918', 'App\\Database\\Migrations\\CreateCiSessionsTable', 'default', 'App', 1776418005, 1),
(6, '2025-09-23-160926', 'App\\Database\\Migrations\\AlterNotificationsTableUserIdField', 'default', 'App', 1776418005, 1),
(7, '2025-09-23-163630', 'App\\Database\\Migrations\\AddVerificationToUsers', 'default', 'App', 1776418005, 1),
(8, '2025-09-23-174254', 'App\\Database\\Migrations\\AddResetTokenExpirationToUsers', 'default', 'App', 1776418005, 1),
(9, '2026-04-07-090000', 'App\\Database\\Migrations\\FixAppointmentStatusEnum', 'default', 'App', 1776418006, 1),
(10, '2026-04-17-170000', 'App\\Database\\Migrations\\CreateFeedbackQuestionsTable', 'default', 'App', 1776418006, 1),
(11, '2026_04_20_000001', 'App\\Database\\Migrations\\AddPerformanceIndexes', 'default', 'App', 1776677621, 2),
(12, '2026_04_20_000002', 'App\\Database\\Migrations\\AddPasswordHistory', 'default', 'App', 1776678006, 3),
(13, '2026-04-27-120000', 'App\\Database\\Migrations\\AddSentimentFieldsToFeedback', 'default', 'App', 1777292403, 4);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
CREATE TABLE IF NOT EXISTS `notifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `type` varchar(50) NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `related_id` int(11) DEFAULT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `event_date` datetime DEFAULT NULL,
  `appointment_date` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_is_read` (`is_read`),
  KEY `idx_created_at` (`created_at`),
  KEY `idx_user_read` (`user_id`,`is_read`)
) ENGINE=InnoDB AUTO_INCREMENT=55 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `type`, `title`, `message`, `related_id`, `is_read`, `event_date`, `appointment_date`, `created_at`) VALUES
(7, 2023307088, 'appointment', 'New Appointment Request', 'Student 2023305013 has requested a Individual Consultation appointment on April 20, 2026 at 9:30 AM - 10:00 AM.', 0, 1, NULL, NULL, '2026-04-17 03:53:06'),
(10, 2023307088, 'appointment', 'New Appointment Request', 'Student 2023305013 has requested a Individual Consultation appointment on April 20, 2026 at 7:00 AM - 7:30 AM.', 0, 1, NULL, NULL, '2026-04-17 04:04:27'),
(12, 2023305013, 'appointment', 'Appointment Completed', 'Your appointment on April 20, 2026 at 7:00 AM - 7:30 AM with Counselor Emeliza A Borres has been completed. Please provide feedback to help us improve our services.', 4, 1, NULL, NULL, '2026-04-17 04:04:58'),
(14, 2023307088, 'appointment', 'New Appointment Request', 'Student 2023305013 has requested a Individual Consultation appointment on April 20, 2026 at 7:00 AM - 7:30 AM.', 0, 1, NULL, NULL, '2026-04-17 04:32:43'),
(15, 2023305013, 'appointment', 'Appointment Approved', 'Congratulations! Your appointment on April 20, 2026 at 7:00 AM - 7:30 AM with Counselor Emeliza A Borres has been approved. Please check your scheduled appointments for details.', 5, 1, NULL, NULL, '2026-04-17 04:33:02'),
(16, 2023305013, 'appointment', 'Appointment Completed', 'Your appointment on April 20, 2026 at 7:00 AM - 7:30 AM with Counselor Emeliza A Borres has been completed. Please provide feedback to help us improve our services.', 5, 1, NULL, NULL, '2026-04-17 04:35:04'),
(17, 2023307088, 'appointment', 'New Appointment Request', 'Student 2023305013 has requested a Individual Consultation appointment on April 20, 2026 at 7:30 AM - 8:00 AM.', 0, 1, NULL, NULL, '2026-04-17 05:51:53'),
(18, 2023305013, 'appointment', 'Appointment Approved', 'Congratulations! Your appointment on April 20, 2026 at 7:30 AM - 8:00 AM with Counselor Emeliza A Borres has been approved. Please check your scheduled appointments for details.', 6, 1, NULL, NULL, '2026-04-17 05:52:05'),
(19, 2023305013, 'appointment', 'Appointment Completed', 'Your appointment on April 20, 2026 at 7:30 AM - 8:00 AM with Counselor Emeliza A Borres has been completed. Please provide feedback to help us improve our services.', 6, 1, NULL, NULL, '2026-04-17 06:02:48'),
(20, 2023307088, 'appointment', 'New Appointment Request', 'Student 2023305013 has requested a Individual Consultation appointment on April 20, 2026 at 7:30 AM - 8:00 AM.', 0, 1, NULL, NULL, '2026-04-17 06:08:22'),
(21, 2023305013, 'appointment', 'Appointment Completed', 'Your appointment on April 20, 2026 at 7:30 AM - 8:00 AM with Counselor Emeliza A Borres has been completed. Please provide feedback to help us improve our services.', 7, 1, NULL, NULL, '2026-04-17 06:08:30'),
(22, 2023305013, 'appointment', 'Appointment Completed', 'Your appointment on April 20, 2026 at 7:30 AM - 8:00 AM with Counselor Emeliza A Borres has been completed. Please provide feedback to help us improve our services.', 7, 1, NULL, NULL, '2026-04-17 06:08:46'),
(23, 2023307088, 'appointment', 'New Appointment Request', 'Student 2023305013 has requested a Group Consultation appointment on April 20, 2026 at 7:30 AM - 8:00 AM.', 0, 1, NULL, NULL, '2026-04-17 07:32:41'),
(24, 2023305013, 'appointment', 'Appointment Completed', 'Your appointment on April 20, 2026 at 7:30 AM - 8:00 AM with Counselor Emeliza A Borres has been completed. Please provide feedback to help us improve our services.', 8, 1, NULL, NULL, '2026-04-17 07:32:49'),
(25, 2023305013, 'appointment', 'Appointment Completed', 'Your appointment on April 20, 2026 at 7:30 AM - 8:00 AM with Counselor Emeliza A Borres has been completed. Please provide feedback to help us improve our services.', 8, 1, NULL, NULL, '2026-04-17 07:33:03'),
(26, 2023307088, 'appointment', 'New Appointment Request', 'Student 2023305013 has requested a Individual Consultation appointment on April 23, 2026 at 7:00 AM - 7:30 AM.', 0, 1, NULL, NULL, '2026-04-21 00:00:32'),
(27, 2023305013, 'appointment', 'Appointment Completed', 'Your appointment on April 23, 2026 at 7:00 AM - 7:30 AM with Counselor Emeliza A Borres has been completed. Please provide feedback to help us improve our services.', 9, 1, NULL, NULL, '2026-04-21 00:16:41'),
(28, 2023305013, 'appointment', 'Appointment Rescheduled', 'Your appointment on 2026-04-23 has been rescheduled to 2026-04-21 at 7:00 AM. Reason: ....', NULL, 1, NULL, NULL, '2026-04-21 00:17:21'),
(29, 2023305013, 'appointment', 'Appointment Completed', 'Your appointment on April 21, 2026 at 7:00 AM with Counselor Emeliza A Borres has been completed. Please provide feedback to help us improve our services.', 9, 1, NULL, NULL, '2026-04-21 00:17:36'),
(30, 2023305013, 'appointment', 'Appointment Completed', 'Your appointment on April 21, 2026 at 7:00 AM with Counselor Emeliza A Borres has been completed. Please provide feedback to help us improve our services.', 9, 1, NULL, NULL, '2026-04-21 00:21:53'),
(31, 2023307088, 'appointment', 'New Appointment Request', 'Student Acierto, Sebastian Anthony has requested a Individual Consultation appointment on April 22, 2026 at 7:00 AM - 7:30 AM.', 0, 1, NULL, NULL, '2026-04-21 00:32:58'),
(32, 2023305013, 'appointment', 'Appointment Rescheduled', 'Your appointment on 2026-04-22 has been rescheduled to 2026-04-21 at 7:00 AM. Reason: ..', NULL, 1, NULL, NULL, '2026-04-21 00:33:29'),
(33, 2023305013, 'appointment', 'Appointment Completed', 'Your appointment on April 21, 2026 at 7:00 AM with Counselor Emeliza A Borres has been completed. Please provide feedback to help us improve our services.', 10, 1, NULL, NULL, '2026-04-21 00:33:41'),
(34, 2023305013, 'appointment', 'Appointment Completed', 'Your appointment on April 21, 2026 at 7:00 AM with Counselor Emeliza A Borres has been completed. Please provide feedback to help us improve our services.', 10, 1, NULL, NULL, '2026-04-21 00:34:18'),
(35, 2023307088, 'appointment', 'New Appointment Request', 'Student 2023304958 has requested a Individual Consultation appointment on April 22, 2026 at 7:00 AM - 7:30 AM.', 0, 1, NULL, NULL, '2026-04-21 04:56:57'),
(36, 2023304958, 'appointment', 'Appointment Rescheduled', 'Your appointment on 2026-04-22 has been rescheduled to 2026-04-21 at 7:00 AM. Reason: ....', NULL, 0, NULL, NULL, '2026-04-21 05:01:43'),
(37, 2023304958, 'appointment', 'Appointment Completed', 'Your appointment on April 21, 2026 at 7:00 AM with Counselor Emeliza A Borres has been completed. Please provide feedback to help us improve our services.', 11, 0, NULL, NULL, '2026-04-21 05:02:03'),
(38, 2023304958, 'appointment', 'Appointment Completed', 'Your appointment on April 21, 2026 at 7:00 AM with Counselor Emeliza A Borres has been completed. Please provide feedback to help us improve our services.', 11, 0, NULL, NULL, '2026-04-21 05:02:14'),
(39, 2023307088, 'appointment', 'New Appointment Request', 'Student 2023307080 has requested a Individual Consultation appointment on April 22, 2026 at 7:00 AM - 7:30 AM.', 0, 1, NULL, NULL, '2026-04-21 05:10:26'),
(40, 2023307080, 'appointment', 'Appointment Rescheduled', 'Your appointment on 2026-04-22 has been rescheduled to 2026-04-21 at 7:00 AM. Reason: .', NULL, 0, NULL, NULL, '2026-04-21 05:11:25'),
(41, 2023307080, 'appointment', 'Appointment Completed', 'Your appointment on April 21, 2026 at 7:00 AM with Counselor Emeliza A Borres has been completed. Please provide feedback to help us improve our services.', 12, 0, NULL, NULL, '2026-04-21 05:11:35'),
(42, 2023307080, 'appointment', 'Appointment Completed', 'Your appointment on April 21, 2026 at 7:00 AM with Counselor Emeliza A Borres has been completed. Please provide feedback to help us improve our services.', 12, 1, NULL, NULL, '2026-04-21 05:12:02'),
(43, 2023307088, 'feedback', 'New Student Feedback', 'Uzi has submitted feedback for their appointment on April 21, 2026', 11, 1, NULL, NULL, '2026-04-21 05:53:39'),
(44, 2023307088, 'appointment', 'New Appointment Request', 'Student Acierto, Sebastian Anthony has requested a Individual Consultation appointment on April 22, 2026 at 7:30 AM - 8:00 AM.', 0, 1, NULL, NULL, '2026-04-21 05:57:28'),
(45, 2023305013, 'appointment', 'Appointment Completed', 'Your appointment on April 22, 2026 at 7:30 AM - 8:00 AM with Counselor Emeliza A Borres has been completed. Please provide feedback to help us improve our services.', 13, 1, NULL, NULL, '2026-04-21 06:00:25'),
(46, 2023305013, 'appointment', 'Appointment Rescheduled', 'Your appointment on 2026-04-22 has been rescheduled to 2026-04-21 at 9:00 AM. Reason: ....', NULL, 1, NULL, NULL, '2026-04-21 06:00:37'),
(47, 2023305013, 'appointment', 'Appointment Completed', 'Your appointment on April 21, 2026 at 9:00 AM with Counselor Emeliza A Borres has been completed. Please provide feedback to help us improve our services.', 13, 1, NULL, NULL, '2026-04-21 06:00:50'),
(48, 2023305013, 'appointment', 'Appointment Completed', 'Your appointment on April 21, 2026 at 9:00 AM with Counselor Emeliza A Borres has been completed. Please provide feedback to help us improve our services.', 13, 1, NULL, NULL, '2026-04-21 06:01:06'),
(49, 2023307088, 'feedback', 'New Student Feedback', 'Acierto, Sebastian Anthony has submitted feedback for their appointment on April 21, 2026', 13, 1, NULL, NULL, '2026-04-21 06:01:38'),
(50, 2023307088, 'appointment', 'New Appointment Request', 'Student Acierto, Sebastian Anthony has requested a Individual Consultation appointment on April 23, 2026 at 7:30 AM - 8:00 AM.', 0, 1, NULL, NULL, '2026-04-21 06:18:26'),
(51, 2023305013, 'appointment', 'Appointment Completed', 'Your appointment on April 23, 2026 at 7:30 AM - 8:00 AM with Counselor Emeliza A Borres has been completed. Please provide feedback to help us improve our services.', 14, 0, NULL, NULL, '2026-04-27 12:17:23'),
(52, 2023305013, 'appointment', 'Appointment Completed', 'Your appointment on April 23, 2026 at 7:30 AM - 8:00 AM with Counselor Emeliza A Borres has been completed. Please provide feedback to help us improve our services.', 14, 0, NULL, NULL, '2026-04-27 12:17:42'),
(53, 2023307088, 'feedback', 'New Student Feedback', 'Acierto, Sebastian Anthony has submitted feedback for their appointment on April 23, 2026', 14, 0, NULL, NULL, '2026-04-27 12:19:43'),
(54, 2023307088, 'feedback', 'New Student Feedback', 'Acierto, Sebastian Anthony has submitted feedback for their appointment on April 23, 2026', 14, 0, NULL, NULL, '2026-04-27 13:00:12');

-- --------------------------------------------------------

--
-- Table structure for table `notification_reads`
--

DROP TABLE IF EXISTS `notification_reads`;
CREATE TABLE IF NOT EXISTS `notification_reads` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` varchar(50) NOT NULL,
  `notification_type` enum('event','announcement') NOT NULL,
  `related_id` int(11) NOT NULL,
  `read_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_user_notification` (`user_id`,`notification_type`,`related_id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_notification_type` (`notification_type`),
  KEY `idx_related_id` (`related_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE IF NOT EXISTS `password_resets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` varchar(10) NOT NULL,
  `reset_code` varchar(10) NOT NULL,
  `reset_expires_at` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_reset_code` (`reset_code`),
  KEY `password_resets_fk2` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `resources`
--

DROP TABLE IF EXISTS `resources`;
CREATE TABLE IF NOT EXISTS `resources` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `resource_type` enum('file','link') NOT NULL,
  `file_name` varchar(255) DEFAULT NULL,
  `file_path` varchar(500) DEFAULT NULL,
  `file_type` varchar(100) DEFAULT NULL,
  `file_size` bigint(20) DEFAULT NULL,
  `external_url` varchar(1000) DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `tags` text DEFAULT NULL,
  `uploaded_by` varchar(10) NOT NULL,
  `visibility` enum('all','students','counselors') DEFAULT 'all',
  `is_active` tinyint(1) DEFAULT 1,
  `view_count` int(11) DEFAULT 0,
  `download_count` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `uploaded_by` (`uploaded_by`),
  KEY `idx_category` (`category`),
  KEY `idx_resource_type` (`resource_type`),
  KEY `idx_visibility` (`visibility`),
  KEY `idx_is_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `student_academic_info`
--

DROP TABLE IF EXISTS `student_academic_info`;
CREATE TABLE IF NOT EXISTS `student_academic_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` varchar(10) NOT NULL,
  `course` varchar(50) NOT NULL,
  `year_level` varchar(10) NOT NULL,
  `major_or_strand` varchar(50) NOT NULL,
  `academic_status` varchar(50) NOT NULL,
  `school_last_attended` varchar(255) DEFAULT NULL,
  `location_of_school` varchar(255) DEFAULT NULL,
  `previous_course_grade` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `student_id` (`student_id`),
  KEY `idx_academic_course` (`course`,`year_level`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_academic_info`
--

INSERT INTO `student_academic_info` (`id`, `student_id`, `course`, `year_level`, `major_or_strand`, `academic_status`, `school_last_attended`, `location_of_school`, `previous_course_grade`, `created_at`, `updated_at`) VALUES
(1, '2023305013', 'BSIT', 'I', '', 'New Student', 'n/a', 'n/a', 'n/a', '2026-04-21 00:27:32', '2026-04-21 00:27:53'),
(2, '2023307089', 'BSIT', 'III', '', 'Continuing/Old', 'DGSMNHS', 'Claveria, Misamis Oriental', '3', '2026-04-21 05:29:34', '2026-04-21 05:29:44');

-- --------------------------------------------------------

--
-- Table structure for table `student_address_info`
--

DROP TABLE IF EXISTS `student_address_info`;
CREATE TABLE IF NOT EXISTS `student_address_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` varchar(10) NOT NULL,
  `permanent_zone` varchar(50) DEFAULT NULL,
  `permanent_barangay` varchar(100) DEFAULT NULL,
  `permanent_city` varchar(100) DEFAULT NULL,
  `permanent_province` varchar(100) DEFAULT NULL,
  `present_zone` varchar(50) DEFAULT NULL,
  `present_barangay` varchar(100) DEFAULT NULL,
  `present_city` varchar(100) DEFAULT NULL,
  `present_province` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `student_id` (`student_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_address_info`
--

INSERT INTO `student_address_info` (`id`, `student_id`, `permanent_zone`, `permanent_barangay`, `permanent_city`, `permanent_province`, `present_zone`, `present_barangay`, `present_city`, `present_province`, `created_at`, `updated_at`) VALUES
(1, '2023305013', 'Zone 7', 'Lanise', 'Claveria', 'Misamis Oriental', 'Zone 7', 'Lanise', 'Claveria', 'Misamis Oriental', '2026-04-21 00:27:32', '2026-04-21 00:27:53'),
(2, '2023307089', 'Zone 4', 'Santa Cruz', 'Claveria', 'Misamis Oriental', 'Sitio Hipona, 0008 Patrocenio', 'N/A', 'Claveria', 'Misamis Oriental', '2026-04-21 05:29:34', '2026-04-21 05:29:44');

-- --------------------------------------------------------

--
-- Table structure for table `student_awards`
--

DROP TABLE IF EXISTS `student_awards`;
CREATE TABLE IF NOT EXISTS `student_awards` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` varchar(10) NOT NULL,
  `award_name` varchar(255) NOT NULL,
  `school_organization` varchar(255) NOT NULL,
  `year_received` varchar(4) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_student_awards` (`student_id`),
  KEY `idx_awards_student_year` (`student_id`,`year_received`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_awards`
--

INSERT INTO `student_awards` (`id`, `student_id`, `award_name`, `school_organization`, `year_received`, `created_at`, `updated_at`) VALUES
(5, '2023307089', 'Dean List', 'Ustp Claveria', '2026', '2026-04-21 05:29:44', '2026-04-21 05:29:44');

-- --------------------------------------------------------

--
-- Table structure for table `student_family_info`
--

DROP TABLE IF EXISTS `student_family_info`;
CREATE TABLE IF NOT EXISTS `student_family_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` varchar(10) NOT NULL,
  `father_name` varchar(255) DEFAULT NULL,
  `father_occupation` varchar(100) DEFAULT NULL,
  `father_educational_attainment` varchar(100) DEFAULT NULL,
  `father_age` int(3) DEFAULT NULL,
  `father_contact_number` varchar(20) DEFAULT NULL,
  `mother_name` varchar(255) DEFAULT NULL,
  `mother_occupation` varchar(100) DEFAULT NULL,
  `mother_educational_attainment` varchar(100) DEFAULT NULL,
  `mother_age` int(3) DEFAULT NULL,
  `mother_contact_number` varchar(20) DEFAULT NULL,
  `parents_permanent_address` text DEFAULT NULL,
  `parents_contact_number` varchar(20) DEFAULT NULL,
  `spouse` varchar(255) DEFAULT NULL,
  `spouse_occupation` varchar(100) DEFAULT NULL,
  `spouse_educational_attainment` varchar(100) DEFAULT NULL,
  `guardian_name` varchar(255) DEFAULT NULL,
  `guardian_age` int(3) DEFAULT NULL,
  `guardian_occupation` varchar(100) DEFAULT NULL,
  `guardian_contact_number` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `student_id` (`student_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_family_info`
--

INSERT INTO `student_family_info` (`id`, `student_id`, `father_name`, `father_occupation`, `father_educational_attainment`, `father_age`, `father_contact_number`, `mother_name`, `mother_occupation`, `mother_educational_attainment`, `mother_age`, `mother_contact_number`, `parents_permanent_address`, `parents_contact_number`, `spouse`, `spouse_occupation`, `spouse_educational_attainment`, `guardian_name`, `guardian_age`, `guardian_occupation`, `guardian_contact_number`, `created_at`, `updated_at`) VALUES
(1, '2023305013', 'N/A', 'N/A', 'N/A', NULL, NULL, 'N/A', 'N/A', 'N/A', NULL, NULL, 'N/A', NULL, 'N/A', 'N/A', 'N/A', 'N/A', NULL, 'N/A', NULL, '2026-04-21 00:27:32', '2026-04-21 00:27:32'),
(2, '2023307089', 'Rodel D. Borres', 'Driver', 'Elementary', 48, '09922984103', 'Eliza A. Borres', 'Laborer', 'Elementary', 48, '09922984103', 'Zone 4 Santa Cruz Claveria Misamis Oriental', '09922984103', 'N/A', 'N/A', 'N/A', 'Eliza Borres', 48, 'Laborer', '09922984103', '2026-04-21 05:29:34', '2026-04-21 05:29:34');

-- --------------------------------------------------------

--
-- Table structure for table `student_feedback`
--

DROP TABLE IF EXISTS `student_feedback`;
CREATE TABLE IF NOT EXISTS `student_feedback` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `appointment_id` int(11) NOT NULL,
  `student_id` varchar(10) NOT NULL,
  `counselor_id` varchar(10) DEFAULT NULL,
  `q1_ease_of_use` int(11) NOT NULL COMMENT 'How easy was it to navigate the appointment scheduling system?',
  `q2_satisfaction` int(11) NOT NULL COMMENT 'How satisfied are you with the overall counseling experience?',
  `q3_timeliness` int(11) NOT NULL COMMENT 'How satisfied are you with the response time to your appointment request?',
  `q4_information_clarity` int(11) NOT NULL COMMENT 'How clear was the information provided about counseling services?',
  `q5_staff_helpfulness` int(11) NOT NULL COMMENT 'How helpful was the counseling staff in addressing your concerns?',
  `q6_technology_reliability` int(11) NOT NULL COMMENT 'How reliable was the technology used for online consultations?',
  `q7_privacy_confidence` int(11) NOT NULL COMMENT 'How confident do you feel about the privacy of your personal information?',
  `q8_recommendation` int(11) NOT NULL COMMENT 'How likely are you to recommend our counseling services to others?',
  `q9_overall_experience` int(11) NOT NULL COMMENT 'How would you rate your overall experience with the counseling system?',
  `q10_future_use` int(11) NOT NULL COMMENT 'How likely are you to use our counseling services again in the future?',
  `additional_comments` text DEFAULT NULL,
  `sentiment_score` int(11) DEFAULT 0 COMMENT 'Sentiment score from -100 (very negative) to 100 (very positive)',
  `sentiment_label` varchar(20) DEFAULT 'neutral' COMMENT 'Sentiment label: positive, negative, or neutral',
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('pending','submitted') NOT NULL DEFAULT 'pending',
  PRIMARY KEY (`id`),
  KEY `appointment_id` (`appointment_id`),
  KEY `student_id` (`student_id`),
  KEY `counselor_id` (`counselor_id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_feedback`
--

INSERT INTO `student_feedback` (`id`, `appointment_id`, `student_id`, `counselor_id`, `q1_ease_of_use`, `q2_satisfaction`, `q3_timeliness`, `q4_information_clarity`, `q5_staff_helpfulness`, `q6_technology_reliability`, `q7_privacy_confidence`, `q8_recommendation`, `q9_overall_experience`, `q10_future_use`, `additional_comments`, `sentiment_score`, `sentiment_label`, `submitted_at`, `status`) VALUES
(1, 1, '2023305013', '2023307088', 3, 2, 4, 3, 3, 4, 3, 3, 4, 3, 'no comment', 0, 'neutral', '2026-04-16 09:34:25', 'submitted'),
(2, 2, '2023305013', '2023307088', 3, 2, 4, 3, 2, 4, 3, 3, 5, 3, 'no', 0, 'neutral', '2026-04-16 09:37:52', 'submitted'),
(3, 3, '2023305013', '2023307088', 3, 4, 3, 2, 4, 3, 3, 4, 5, 5, '', 0, 'neutral', '2026-04-17 03:56:29', 'submitted'),
(4, 4, '2023305013', '2023307088', 4, 3, 5, 5, 5, 5, 3, 3, 3, 5, '', 0, 'neutral', '2026-04-17 04:05:35', 'submitted'),
(5, 5, '2023305013', '2023307088', 3, 4, 3, 4, 3, 3, 4, 3, 4, 3, '', 0, 'neutral', '2026-04-17 05:50:55', 'submitted'),
(6, 6, '2023305013', '2023307088', 3, 4, 3, 4, 3, 4, 5, 3, 3, 3, 'For a counselor email feature, use clear action-based button names like “Send Email”, “Send Message”, or “Send Counseling Email”. Avoid generic labels like “Submit.” The button should clearly show that it sends an email to students. This improves usability, professionalism, and reduces confusion in your counseling system.', 0, 'neutral', '2026-04-17 06:07:38', 'submitted'),
(7, 7, '2023305013', '2023307088', 3, 4, 3, 4, 2, 5, 5, 5, 5, 4, '', 0, 'neutral', '2026-04-17 07:28:31', 'submitted'),
(8, 8, '2023305013', '2023307088', 3, 4, 3, 3, 3, 5, 4, 5, 4, 5, '', 0, 'neutral', '2026-04-20 10:16:59', 'submitted'),
(9, 9, '2023305013', '2023307088', 3, 4, 3, 5, 4, 4, 5, 4, 4, 5, '', 0, 'neutral', '2026-04-21 00:31:16', 'submitted'),
(10, 12, '2023307080', '2023307088', 3, 3, 3, 1, 3, 1, 1, 1, 1, 1, '', 0, 'neutral', '2026-04-21 05:16:58', 'submitted'),
(11, 10, '2023305013', '2023307088', 1, 1, 1, 1, 1, 5, 5, 5, 5, 5, '', 0, 'neutral', '2026-04-21 05:40:01', 'submitted'),
(12, 11, '2023304958', '2023307088', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, '', 0, 'neutral', '2026-04-21 05:53:39', 'submitted'),
(13, 13, '2023305013', '2023307088', 3, 4, 3, 4, 4, 5, 4, 5, 4, 5, '......', 0, 'neutral', '2026-04-21 06:01:38', 'submitted'),
(14, 14, '2023305013', '2023307088', 3, 4, 3, 3, 4, 3, 4, 3, 4, 3, 'Thank you for all the things you say to motivate me.', 100, 'positive', '2026-04-27 13:00:12', 'submitted');

-- --------------------------------------------------------

--
-- Table structure for table `student_gcs_activities`
--

DROP TABLE IF EXISTS `student_gcs_activities`;
CREATE TABLE IF NOT EXISTS `student_gcs_activities` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` varchar(10) NOT NULL,
  `activity_type` enum('adjustment','building_self_confidence','developing_communication_skills','study_habits','time_management','tutorial_with_peers','other') NOT NULL,
  `other_specify` varchar(255) DEFAULT NULL,
  `tutorial_subjects` text DEFAULT NULL COMMENT 'For tutorial_with_peers type',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_student_activity_type` (`student_id`,`activity_type`),
  KEY `idx_student_activities` (`student_id`,`activity_type`),
  KEY `idx_gcs_activities_student` (`student_id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_gcs_activities`
--

INSERT INTO `student_gcs_activities` (`id`, `student_id`, `activity_type`, `other_specify`, `tutorial_subjects`, `created_at`) VALUES
(25, '2023307089', 'adjustment', NULL, NULL, '2026-04-21 05:29:44'),
(26, '2023307089', 'building_self_confidence', NULL, NULL, '2026-04-21 05:29:44'),
(27, '2023307089', 'developing_communication_skills', NULL, NULL, '2026-04-21 05:29:44'),
(28, '2023307089', 'study_habits', NULL, NULL, '2026-04-21 05:29:44'),
(29, '2023307089', 'time_management', NULL, NULL, '2026-04-21 05:29:44'),
(30, '2023307089', 'tutorial_with_peers', NULL, '', '2026-04-21 05:29:44');

-- --------------------------------------------------------

--
-- Table structure for table `student_other_info`
--

DROP TABLE IF EXISTS `student_other_info`;
CREATE TABLE IF NOT EXISTS `student_other_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` varchar(10) NOT NULL,
  `course_choice_reason` text DEFAULT NULL,
  `family_description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'Array of: harmonious, conflict, separated_parents, parents_working_abroad, other' CHECK (json_valid(`family_description`)),
  `family_description_other` varchar(255) DEFAULT NULL,
  `living_condition` enum('good_environment','not_good_environment') DEFAULT NULL,
  `physical_health_condition` enum('No','Yes') DEFAULT 'No',
  `physical_health_condition_specify` text DEFAULT NULL,
  `psych_treatment` enum('No','Yes') DEFAULT 'No',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `student_id` (`student_id`),
  KEY `idx_student_other_info` (`student_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_other_info`
--

INSERT INTO `student_other_info` (`id`, `student_id`, `course_choice_reason`, `family_description`, `family_description_other`, `living_condition`, `physical_health_condition`, `physical_health_condition_specify`, `psych_treatment`, `created_at`, `updated_at`) VALUES
(1, '2023305013', NULL, '[]', NULL, NULL, 'No', 'N/A', 'No', '2026-04-21 00:27:32', '2026-04-21 00:27:53'),
(2, '2023307089', 'Because I chose it. ', '[\"conflict\"]', NULL, 'not_good_environment', 'Yes', 'N/A', 'No', '2026-04-21 05:29:34', '2026-04-21 05:29:44');

-- --------------------------------------------------------

--
-- Table structure for table `student_personal_info`
--

DROP TABLE IF EXISTS `student_personal_info`;
CREATE TABLE IF NOT EXISTS `student_personal_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` varchar(10) NOT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `first_name` varchar(100) DEFAULT NULL,
  `middle_name` varchar(100) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `place_of_birth` varchar(255) DEFAULT NULL,
  `age` int(3) DEFAULT NULL,
  `sex` enum('Male','Female') DEFAULT NULL,
  `civil_status` enum('Single','Married','Widowed','Legally Separated','Annulled') DEFAULT NULL,
  `religion` varchar(100) DEFAULT NULL,
  `contact_number` varchar(20) DEFAULT NULL,
  `fb_account_name` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `student_id` (`student_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_personal_info`
--

INSERT INTO `student_personal_info` (`id`, `student_id`, `last_name`, `first_name`, `middle_name`, `date_of_birth`, `place_of_birth`, `age`, `sex`, `civil_status`, `religion`, `contact_number`, `fb_account_name`, `created_at`, `updated_at`) VALUES
(1, '2023305013', 'Acierto', 'Sebastian Anthony', 'Gurro', '2004-10-27', 'Digos City, Davao Del Sur', 21, 'Male', 'Single', 'Roman Catholic', '09620894571', 'Sebastian Anthony Acierto', '2026-04-21 00:27:32', '2026-04-21 00:27:53'),
(2, '2023307089', 'Borres', 'Emeliza', 'Akut', '2005-04-03', 'Tuganay Santa Cruz Claveria Misamis Oriental', 21, 'Female', 'Single', 'Roman Catholic', '09922984103', 'Emeliza Borres', '2026-04-21 05:29:34', '2026-04-21 05:29:44');

-- --------------------------------------------------------

--
-- Table structure for table `student_residence_info`
--

DROP TABLE IF EXISTS `student_residence_info`;
CREATE TABLE IF NOT EXISTS `student_residence_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` varchar(10) NOT NULL,
  `residence_type` enum('at home','boarding house','USTP-Claveria Dormitory','relatives','friends','other') DEFAULT NULL,
  `residence_other_specify` varchar(255) DEFAULT NULL,
  `has_consent` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `student_id` (`student_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_residence_info`
--

INSERT INTO `student_residence_info` (`id`, `student_id`, `residence_type`, `residence_other_specify`, `has_consent`, `created_at`, `updated_at`) VALUES
(1, '2023305013', 'at home', 'N/A', 1, '2026-04-21 00:27:32', '2026-04-21 00:27:53'),
(2, '2023307089', 'at home', 'N/A', 1, '2026-04-21 05:29:34', '2026-04-21 05:29:44');

-- --------------------------------------------------------

--
-- Table structure for table `student_services_availed`
--

DROP TABLE IF EXISTS `student_services_availed`;
CREATE TABLE IF NOT EXISTS `student_services_availed` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` varchar(10) NOT NULL,
  `service_type` enum('counseling','insurance','special_lanes','safe_learning','equal_access','other') NOT NULL,
  `other_specify` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_student_service_type` (`student_id`,`service_type`),
  KEY `student_id` (`student_id`),
  KEY `idx_user_services_availed` (`student_id`,`service_type`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_services_availed`
--

INSERT INTO `student_services_availed` (`id`, `student_id`, `service_type`, `other_specify`, `created_at`) VALUES
(13, '2023307089', 'counseling', NULL, '2026-04-21 05:29:44'),
(14, '2023307089', 'insurance', NULL, '2026-04-21 05:29:44'),
(15, '2023307089', 'special_lanes', NULL, '2026-04-21 05:29:44');

-- --------------------------------------------------------

--
-- Table structure for table `student_services_needed`
--

DROP TABLE IF EXISTS `student_services_needed`;
CREATE TABLE IF NOT EXISTS `student_services_needed` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` varchar(10) NOT NULL,
  `service_type` enum('counseling','insurance','special_lanes','safe_learning','equal_access','other') NOT NULL,
  `other_specify` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_student_service_needed_type` (`student_id`,`service_type`),
  KEY `student_id` (`student_id`),
  KEY `idx_user_services_needed` (`student_id`,`service_type`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_services_needed`
--

INSERT INTO `student_services_needed` (`id`, `student_id`, `service_type`, `other_specify`, `created_at`) VALUES
(21, '2023307089', 'counseling', NULL, '2026-04-21 05:29:44'),
(22, '2023307089', 'insurance', NULL, '2026-04-21 05:29:44'),
(23, '2023307089', 'special_lanes', NULL, '2026-04-21 05:29:44'),
(24, '2023307089', 'safe_learning', NULL, '2026-04-21 05:29:44'),
(25, '2023307089', 'equal_access', NULL, '2026-04-21 05:29:44');

-- --------------------------------------------------------

--
-- Table structure for table `student_special_circumstances`
--

DROP TABLE IF EXISTS `student_special_circumstances`;
CREATE TABLE IF NOT EXISTS `student_special_circumstances` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` varchar(10) NOT NULL,
  `is_solo_parent` enum('Yes','No') DEFAULT NULL,
  `is_indigenous` enum('Yes','No') DEFAULT NULL,
  `is_breastfeeding` enum('Yes','No','N/A') DEFAULT NULL,
  `is_pwd` enum('Yes','No','Other') DEFAULT NULL,
  `pwd_disability_type` varchar(255) DEFAULT NULL,
  `pwd_proof_file` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `student_id` (`student_id`),
  KEY `idx_pwd_status` (`is_pwd`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_special_circumstances`
--

INSERT INTO `student_special_circumstances` (`id`, `student_id`, `is_solo_parent`, `is_indigenous`, `is_breastfeeding`, `is_pwd`, `pwd_disability_type`, `pwd_proof_file`, `created_at`, `updated_at`) VALUES
(1, '2023305013', 'No', 'No', 'N/A', 'No', 'N/A', 'N/A', '2026-04-21 00:27:32', '2026-04-21 00:27:53'),
(2, '2023307089', 'No', 'No', 'No', 'Yes', 'Visual Disability', 'Photos/pwd_proofs/pwd_proof_2023307089_1776749384.jpg', '2026-04-21 05:29:34', '2026-04-21 05:29:44');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` varchar(10) NOT NULL,
  `username` varchar(100) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `verification_token` varchar(6) DEFAULT NULL,
  `reset_expires_at` datetime DEFAULT NULL,
  `is_verified` tinyint(1) DEFAULT 0,
  `role` enum('student','admin','counselor') NOT NULL DEFAULT 'student',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `profile_picture` varchar(255) DEFAULT NULL,
  `last_login` timestamp NULL DEFAULT NULL,
  `logout_time` timestamp NULL DEFAULT NULL,
  `last_activity` timestamp NULL DEFAULT NULL,
  `last_active_at` timestamp NULL DEFAULT NULL,
  `last_inactive_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `user_id`, `username`, `email`, `password`, `verification_token`, `reset_expires_at`, `is_verified`, `role`, `created_at`, `profile_picture`, `last_login`, `logout_time`, `last_activity`, `last_active_at`, `last_inactive_at`) VALUES
(1, '1111111111', 'Admin', 'Counselign2025@gmail.com', '$2y$10$LBL7naddD7utU7JmOS6JOuVEs9s6HAtWKuGjCwPYGEro3vZdMgEVS', NULL, NULL, 1, 'admin', '2026-04-16 09:23:41', 'http://localhost/Counselign/public/Photos/profile.png', '2026-04-27 12:28:41', '2026-04-27 12:15:01', '2026-04-27 12:28:42', '2026-04-27 12:28:42', '2026-04-27 12:15:01'),
(2, '2023305013', 'Seb', 'sebastiananthonyacierto@gmail.com', '$2y$10$/oPteumkoKrTQbPl/rFL7e11zgcJ6xWjR7USgBpa0/gpzNc.9gOOG', NULL, NULL, 1, 'student', '2026-04-16 09:27:09', 'Photos/profile_pictures/student_2023305013_1776406965.png', '2026-04-27 12:59:32', '2026-04-27 13:00:50', '2026-04-27 13:00:50', '2026-04-27 13:00:50', '2026-04-27 13:00:50'),
(3, '2023307088', 'Liza', 'borresemeliza@gmail.com', '$2y$10$D4luzNYWLsbdw8Buw0Ct6OpbKDdKkHOIzJTxxupw7g6jv3LDHstya', NULL, NULL, 1, 'counselor', '2026-04-16 09:28:26', 'Photos/profile_pictures/counselor_2023307088_1776406942.png', '2026-04-27 13:01:03', '2026-04-27 12:59:15', '2026-04-27 13:15:53', '2026-04-27 13:15:53', '2026-04-27 12:59:15'),
(4, '2023307099', 'Sweet', 'emeliza1borres@gmail.com', '$2y$10$xliGtpYq4tRWJ3YoSnzxwuML3hD/LwThToEIlMFojLn.Snf9u6LyW', NULL, NULL, 1, 'student', '2026-04-21 04:43:41', 'http://172.16.86.30/Counselign/public/Photos/profile.png', NULL, NULL, NULL, NULL, NULL),
(6, '2023304958', 'Uzi', 'arrubio.milwaukee123@gmail.com', '$2y$10$xksv764pEzwzvXC.F4h4EertIC8Taq665L6B3Zox9msZRxSzxvGqq', NULL, NULL, 1, 'student', '2026-04-21 04:54:37', 'http://172.16.86.30/Counselign/public/Photos/profile.png', '2026-04-21 04:55:33', NULL, '2026-04-21 05:53:19', '2026-04-21 05:53:19', NULL),
(7, '2023307089', 'Sweet Sweet', 'borresemeliza1@gmail.com', '$2y$10$aQAjm4yFQHvvIFfv7MMw/ur2OUFlQSAX8LGWXpaTVCewmixXC7GLy', NULL, NULL, 1, 'student', '2026-04-21 04:58:30', 'Photos/profile_pictures/student_2023307089_1776748760.jpg', '2026-04-21 05:31:18', '2026-04-21 05:15:55', '2026-04-21 05:31:19', '2026-04-21 05:31:19', '2026-04-21 05:15:55'),
(8, '2023307080', 'Jean Rose', 'emelizaborres@gmail.com', '$2y$10$gk381ALONL7bV4B8dztwd.JosmHmcCMdUX6naor8BI8xHWuQ3Ntsa', NULL, NULL, 1, 'student', '2026-04-21 05:06:54', 'Photos/profile_pictures/student_2023307080_1776748713.jpg', '2026-04-21 05:30:54', '2026-04-21 05:31:03', '2026-04-21 05:31:03', '2026-04-21 05:31:03', '2026-04-21 05:31:03');

--
-- Constraints for dumped tables
--

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `appointments_ibfk_1` (`student_id`) USING BTREE,
  ADD KEY `idx_appointment_counselor_date_status` (`counselor_preference`,`preferred_date`,`status`),
  ADD KEY `idx_appointment_student_status` (`student_id`,`status`),
  ADD KEY `idx_student_id` (`student_id`),
  ADD KEY `idx_status` (`status`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `appointments_fk2` FOREIGN KEY (`counselor_preference`) REFERENCES `counselors` (`counselor_id`),
  ADD CONSTRAINT `appointments_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
--
-- Constraints for table `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `appointments_fk2` FOREIGN KEY (`counselor_preference`) REFERENCES `counselors` (`counselor_id`),
  ADD CONSTRAINT `appointments_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `counselors`
--
ALTER TABLE `counselors`
  ADD CONSTRAINT `counselor_ibfk_1` FOREIGN KEY (`counselor_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `counselor_availability`
--
ALTER TABLE `counselor_availability`
  ADD CONSTRAINT `counselor_availability_ibfk_1` FOREIGN KEY (`counselor_id`) REFERENCES `counselors` (`counselor_id`) ON DELETE CASCADE;

--
-- Constraints for table `follow_up_appointments`
--
ALTER TABLE `follow_up_appointments`
  ADD CONSTRAINT `fk_parent_appointment` FOREIGN KEY (`parent_appointment_id`) REFERENCES `appointments` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_student` FOREIGN KEY (`student_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `follow_up_appointments_ibfk_1` FOREIGN KEY (`counselor_id`) REFERENCES `counselors` (`counselor_id`) ON DELETE CASCADE;

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`sender_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD CONSTRAINT `password_resets_fk2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `resources`
--
ALTER TABLE `resources`
  ADD CONSTRAINT `resources_ibfk_1` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `student_academic_info`
--
ALTER TABLE `student_academic_info`
  ADD CONSTRAINT `student_academic_info_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `student_address_info`
--
ALTER TABLE `student_address_info`
  ADD CONSTRAINT `student_address_info_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `student_awards`
--
ALTER TABLE `student_awards`
  ADD CONSTRAINT `student_awards_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `student_family_info`
--
ALTER TABLE `student_family_info`
  ADD CONSTRAINT `student_family_info_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `student_feedback`
--
ALTER TABLE `student_feedback`
  ADD CONSTRAINT `student_feedback_ibfk_1` FOREIGN KEY (`appointment_id`) REFERENCES `appointments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `student_feedback_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `student_feedback_ibfk_3` FOREIGN KEY (`counselor_id`) REFERENCES `counselors` (`counselor_id`) ON DELETE SET NULL;

--
-- Constraints for table `student_gcs_activities`
--
ALTER TABLE `student_gcs_activities`
  ADD CONSTRAINT `student_gcs_activities_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `student_other_info`
--
ALTER TABLE `student_other_info`
  ADD CONSTRAINT `student_other_info_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `student_personal_info`
--
ALTER TABLE `student_personal_info`
  ADD CONSTRAINT `student_personal_info_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `student_residence_info`
--
ALTER TABLE `student_residence_info`
  ADD CONSTRAINT `student_residence_info_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `student_services_availed`
--
ALTER TABLE `student_services_availed`
  ADD CONSTRAINT `student_services_availed_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `student_services_needed`
--
ALTER TABLE `student_services_needed`
  ADD CONSTRAINT `student_services_needed_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `student_special_circumstances`
--
ALTER TABLE `student_special_circumstances`
  ADD CONSTRAINT `student_special_circumstances_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
