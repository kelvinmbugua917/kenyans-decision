-- ==========================================================
-- Kenyans Decision - Database Schema (MySQL 8.0+ / MariaDB)
-- ==========================================================

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS `admin_audit_logs`;
DROP TABLE IF EXISTS `reports`;
DROP TABLE IF EXISTS `comments`;
DROP TABLE IF EXISTS `discussions`;
DROP TABLE IF EXISTS `votes`;
DROP TABLE IF EXISTS `poll_options`;
DROP TABLE IF EXISTS `polls`;
DROP TABLE IF EXISTS `users`;
DROP TABLE IF EXISTS `analytics`;
SET FOREIGN_KEY_CHECKS = 1;

-- Users Table
CREATE TABLE `users` (
  `id` VARCHAR(64) NOT NULL,
  `email` VARCHAR(255) NOT NULL UNIQUE,
  `password_hash` VARCHAR(255) NOT NULL,
  `display_name` VARCHAR(255) NOT NULL,
  `role` ENUM('admin', 'user') NOT NULL DEFAULT 'user',
  `county` VARCHAR(100) DEFAULT 'Nairobi',
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Polls Table
CREATE TABLE `polls` (
  `id` VARCHAR(128) NOT NULL,
  `slug` VARCHAR(128) NOT NULL UNIQUE,
  `title` VARCHAR(255) NOT NULL,
  `description` TEXT NOT NULL,
  `category` VARCHAR(100) NOT NULL DEFAULT 'General Kenya',
  `creator_type` ENUM('official', 'community') NOT NULL DEFAULT 'community',
  `creator_name` VARCHAR(255) DEFAULT 'Kenyans Decision',
  `creator_id` VARCHAR(64) DEFAULT NULL,
  `allow_vote_change` TINYINT(1) NOT NULL DEFAULT 1,
  `closing_date` DATETIME DEFAULT NULL,
  `status` ENUM('active', 'closed') NOT NULL DEFAULT 'active',
  `is_featured` TINYINT(1) NOT NULL DEFAULT 0,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_slug` (`slug`),
  INDEX `idx_status_featured` (`status`, `is_featured`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Poll Options Table
CREATE TABLE `poll_options` (
  `id` VARCHAR(128) NOT NULL,
  `poll_id` VARCHAR(128) NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `party` VARCHAR(255) NOT NULL,
  `party_short` VARCHAR(100) DEFAULT NULL,
  `avatar_color` VARCHAR(50) DEFAULT '#16a34a',
  `photo_url` TEXT DEFAULT NULL,
  `sort_order` INT NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  INDEX `idx_poll_id` (`poll_id`),
  CONSTRAINT `fk_poll_options_poll` FOREIGN KEY (`poll_id`) REFERENCES `polls` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Votes Table (Anonymous Duplicate Mitigation Engine)
CREATE TABLE `votes` (
  `id` VARCHAR(128) NOT NULL,
  `poll_id` VARCHAR(128) NOT NULL,
  `option_id` VARCHAR(128) NOT NULL,
  `voter_hash` VARCHAR(128) NOT NULL,
  `ip_hmac` VARCHAR(128) NOT NULL,
  `device_token` VARCHAR(128) DEFAULT NULL,
  `user_id` VARCHAR(64) DEFAULT NULL,
  `county` VARCHAR(100) DEFAULT 'Nairobi',
  `age_group` VARCHAR(50) DEFAULT '25-34',
  `risk_score` ENUM('trusted', 'normal', 'suspicious', 'blocked') NOT NULL DEFAULT 'normal',
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_poll_voter` (`poll_id`, `voter_hash`),
  INDEX `idx_poll_id_risk` (`poll_id`, `risk_score`),
  INDEX `idx_ip_hmac` (`ip_hmac`),
  INDEX `idx_poll_county` (`poll_id`, `county`),
  CONSTRAINT `fk_votes_poll` FOREIGN KEY (`poll_id`) REFERENCES `polls` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Discussions Table
CREATE TABLE `discussions` (
  `id` VARCHAR(128) NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `content` TEXT NOT NULL,
  `category` VARCHAR(100) NOT NULL DEFAULT 'General Kenya',
  `author_id` VARCHAR(64) NOT NULL,
  `author_name` VARCHAR(255) NOT NULL,
  `likes_count` INT NOT NULL DEFAULT 0,
  `comments_count` INT NOT NULL DEFAULT 0,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_category` (`category`),
  INDEX `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Comments Table
CREATE TABLE `comments` (
  `id` VARCHAR(128) NOT NULL,
  `discussion_id` VARCHAR(128) NOT NULL,
  `author_id` VARCHAR(64) NOT NULL,
  `author_name` VARCHAR(255) NOT NULL,
  `content` TEXT NOT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_discussion_id` (`discussion_id`),
  CONSTRAINT `fk_comments_discussion` FOREIGN KEY (`discussion_id`) REFERENCES `discussions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Content Reports Table
CREATE TABLE `reports` (
  `id` VARCHAR(128) NOT NULL,
  `target_type` ENUM('post', 'comment', 'vote') NOT NULL,
  `target_id` VARCHAR(128) NOT NULL,
  `reason` VARCHAR(255) NOT NULL,
  `reporter_id` VARCHAR(64) DEFAULT NULL,
  `status` ENUM('pending', 'reviewed', 'dismissed') NOT NULL DEFAULT 'pending',
  `details` TEXT DEFAULT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Append-Only Admin Audit Logs Table
CREATE TABLE `admin_audit_logs` (
  `id` VARCHAR(128) NOT NULL,
  `admin_email` VARCHAR(255) NOT NULL,
  `action` VARCHAR(100) NOT NULL,
  `target` VARCHAR(255) NOT NULL,
  `before_state` LONGTEXT DEFAULT NULL,
  `after_state` LONGTEXT DEFAULT NULL,
  `prev_hash` VARCHAR(128) DEFAULT NULL,
  `log_hash` VARCHAR(128) DEFAULT NULL,
  `timestamp` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_timestamp` (`timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Platform Analytics
CREATE TABLE `analytics` (
  `id` INT NOT NULL DEFAULT 1,
  `total_visitors` INT NOT NULL DEFAULT 4820,
  `total_shares` INT NOT NULL DEFAULT 310,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `analytics` (`id`, `total_visitors`, `total_shares`) VALUES (1, 4820, 310)
ON DUPLICATE KEY UPDATE `id` = 1;
