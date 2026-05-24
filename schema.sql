-- Database: `lostfound_db`

CREATE TABLE IF NOT EXISTS `Person` (
  `person_id` INT AUTO_INCREMENT PRIMARY KEY,
  `first_name` VARCHAR(100) NOT NULL,
  `last_name` VARCHAR(100) NOT NULL,
  `contact_email` VARCHAR(255) UNIQUE NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `Faculty_User` (
  `person_id` INT PRIMARY KEY,
  `employee_id` VARCHAR(50) UNIQUE NOT NULL,
  `password_hash` VARCHAR(255) NOT NULL,
  `department` VARCHAR(100) NOT NULL,
  FOREIGN KEY (`person_id`) REFERENCES `Person`(`person_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `Claimer` (
  `person_id` INT PRIMARY KEY,
  `identification_type` VARCHAR(50) NOT NULL,
  `identification_number` VARCHAR(100) UNIQUE NOT NULL,
  FOREIGN KEY (`person_id`) REFERENCES `Person`(`person_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `Item` (
  `item_id` INT AUTO_INCREMENT PRIMARY KEY,
  `logged_by_faculty_id` INT NOT NULL,
  `category` VARCHAR(100) NOT NULL,
  `date_reported` DATETIME NOT NULL,
  `location_found` VARCHAR(255) NOT NULL,
  `status` ENUM('Listed', 'Claim Pending', 'Returned') NOT NULL DEFAULT 'Listed',
  `item_type` ENUM('Identifiable', 'Unidentifiable') NOT NULL,
  FOREIGN KEY (`logged_by_faculty_id`) REFERENCES `Faculty_User`(`person_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `Identifiable_Item` (
  `item_id` INT PRIMARY KEY,
  `visible_name` VARCHAR(255) NOT NULL,
  `generalized_description` TEXT NOT NULL,
  FOREIGN KEY (`item_id`) REFERENCES `Item`(`item_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `Unidentifiable_Item` (
  `item_id` INT PRIMARY KEY,
  `generalized_description` TEXT NOT NULL,
  `hidden_description` TEXT NOT NULL,
  FOREIGN KEY (`item_id`) REFERENCES `Item`(`item_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `Claim` (
  `claim_id` INT AUTO_INCREMENT PRIMARY KEY,
  `item_id` INT NOT NULL,
  `claimer_id` INT NOT NULL,
  `ownership_proof` TEXT,
  `claim_status` ENUM('Pending Review', 'Approved', 'Rejected') NOT NULL DEFAULT 'Pending Review',
  `date_submitted` DATETIME NOT NULL,
  FOREIGN KEY (`item_id`) REFERENCES `Item`(`item_id`),
  FOREIGN KEY (`claimer_id`) REFERENCES `Claimer`(`person_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `Handoff` (
  `handoff_id` INT AUTO_INCREMENT PRIMARY KEY,
  `claim_id` INT NOT NULL UNIQUE,
  `faculty_user_id` INT NOT NULL,
  `handoff_date` DATETIME NOT NULL,
  `physical_id_verified` BOOLEAN NOT NULL DEFAULT FALSE,
  FOREIGN KEY (`claim_id`) REFERENCES `Claim`(`claim_id`),
  FOREIGN KEY (`faculty_user_id`) REFERENCES `Faculty_User`(`person_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
