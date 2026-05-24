-- ============================================
-- bidernet group - Database Schema (v2.3.3-php)
-- ============================================
-- כולל את כל הטבלאות והשדות הדרושים
-- הרץ את הקובץ הזה ב-phpMyAdmin פעם אחת
-- ============================================

SET NAMES utf8mb4;
SET CHARACTER SET utf8mb4;

-- ========== USERS TABLE ==========
CREATE TABLE IF NOT EXISTS `users` (
  `id` VARCHAR(50) PRIMARY KEY,
  `username` VARCHAR(100) UNIQUE NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `name` VARCHAR(200) NOT NULL,
  `email` VARCHAR(200),
  `phone` VARCHAR(50),
  `role` ENUM('admin','client') NOT NULL DEFAULT 'client',
  `businessName` VARCHAR(200),
  `logoData` LONGTEXT,
  `logoName` VARCHAR(255),
  `shareToken` VARCHAR(100),
  `packageData` TEXT,
  `packageSize` VARCHAR(50),
  `packagePeriod` VARCHAR(50),
  `createdAt` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX `idx_username` (`username`),
  INDEX `idx_shareToken` (`shareToken`),
  INDEX `idx_businessName` (`businessName`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========== POSTS TABLE ==========
CREATE TABLE IF NOT EXISTS `posts` (
  `id` VARCHAR(50) PRIMARY KEY,
  `businessName` VARCHAR(200) NOT NULL,
  `title` VARCHAR(500),
  `content` LONGTEXT,
  `date` DATE,
  `time` VARCHAR(10),
  `platforms` TEXT,
  `mediaUrl` LONGTEXT,
  `mediaData` LONGTEXT COMMENT 'Base64 of uploaded image/video',
  `mediaName` VARCHAR(255),
  `mediaType` VARCHAR(20),
  `category` VARCHAR(100),
  `status` VARCHAR(50) DEFAULT 'draft',
  `clientApproval` TEXT,
  `publishStatus` VARCHAR(50),
  `publishedAt` DATETIME,
  `publishedTo` TEXT COMMENT 'JSON array of platforms',
  `chatMessages` LONGTEXT,
  `editHistory` LONGTEXT,
  `createdBy` VARCHAR(100),
  `createdAt` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX `idx_businessName` (`businessName`),
  INDEX `idx_date` (`date`),
  INDEX `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========== BRANDING TABLE (single row) ==========
CREATE TABLE IF NOT EXISTS `branding` (
  `id` INT PRIMARY KEY DEFAULT 1,
  `companyName` VARCHAR(200),
  `tagline` VARCHAR(500),
  `logoData` LONGTEXT,
  `primaryColor` VARCHAR(20),
  `secondaryColor` VARCHAR(20),
  `loginWelcome` VARCHAR(500),
  `theme` VARCHAR(50),
  `extraData` LONGTEXT,
  `updatedAt` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========== TEAM CHAT (admin internal chat) ==========
CREATE TABLE IF NOT EXISTS `team_chat` (
  `id` VARCHAR(50) PRIMARY KEY,
  `senderUsername` VARCHAR(100) NOT NULL,
  `senderName` VARCHAR(200),
  `message` TEXT,
  `editedAt` DATETIME NULL,
  `createdAt` DATETIME DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_createdAt` (`createdAt`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========== TEMPLATES ==========
CREATE TABLE IF NOT EXISTS `templates` (
  `id` VARCHAR(50) PRIMARY KEY,
  `name` VARCHAR(200) NOT NULL,
  `content` LONGTEXT,
  `platforms` TEXT,
  `createdAt` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========== GANTT APPROVALS (חדש!) ==========
CREATE TABLE IF NOT EXISTS `gantt_approvals` (
  `businessName` VARCHAR(200) PRIMARY KEY,
  `status` VARCHAR(50),
  `comment` TEXT,
  `approvedAt` DATETIME,
  `approvedBy` VARCHAR(200),
  `postCount` INT,
  `createdAt` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========== DEFAULT ADMIN USER ==========
-- Username: admin / Password: admin123
INSERT INTO `users` (`id`, `username`, `password`, `name`, `role`)
VALUES ('admin_default', 'admin', 'admin123', 'מנהל ראשי', 'admin')
ON DUPLICATE KEY UPDATE `id`=`id`;

-- ========== DEFAULT BRANDING ==========
INSERT INTO `branding` (`id`, `companyName`, `tagline`, `primaryColor`, `secondaryColor`, `loginWelcome`, `theme`)
VALUES (1, 'bidernet group', 'משרד פרסום דיגיטלי', '#deeece', '#0a0a0a', 'ברוכים הבאים', 'light')
ON DUPLICATE KEY UPDATE `id`=`id`;

-- ============================================
-- סיום! 7 טבלאות נוצרו.
-- ============================================
