-- ============================================================
-- bidernet Content Calendar v2.3.3-php - MySQL Schema Upgrade
-- ============================================================
-- הרץ את הסקריפט הזה במסד הנתונים שלך לאחר העלאת v2.3.3-php
-- ============================================================

-- עדכון טבלת users
ALTER TABLE users ADD COLUMN IF NOT EXISTS package_size VARCHAR(50) NULL;
ALTER TABLE users ADD COLUMN IF NOT EXISTS package_period VARCHAR(50) NULL;
ALTER TABLE users ADD COLUMN IF NOT EXISTS logo_name VARCHAR(255) NULL;
ALTER TABLE users ADD COLUMN IF NOT EXISTS share_token VARCHAR(64) NULL;

-- אינדקס מהיר לחיפוש לפי share_token
ALTER TABLE users ADD INDEX IF NOT EXISTS idx_users_share_token (share_token);

-- עדכון טבלת posts
ALTER TABLE posts ADD COLUMN IF NOT EXISTS media_data LONGTEXT NULL COMMENT 'Base64 of image/video';
ALTER TABLE posts ADD COLUMN IF NOT EXISTS media_name VARCHAR(255) NULL;
ALTER TABLE posts ADD COLUMN IF NOT EXISTS category VARCHAR(100) NULL;
ALTER TABLE posts ADD COLUMN IF NOT EXISTS published_to JSON NULL COMMENT 'Array of platforms post was published to';
ALTER TABLE posts ADD COLUMN IF NOT EXISTS published_at DATETIME NULL;
ALTER TABLE posts ADD COLUMN IF NOT EXISTS publish_status VARCHAR(50) NULL;
ALTER TABLE posts ADD COLUMN IF NOT EXISTS chat_messages JSON NULL COMMENT 'Per-post chat history';
ALTER TABLE posts ADD COLUMN IF NOT EXISTS edit_history JSON NULL COMMENT 'Edit log';
ALTER TABLE posts ADD COLUMN IF NOT EXISTS client_approval JSON NULL COMMENT 'Approval status + comment';

-- עדכון טבלת team_chat  
ALTER TABLE team_chat ADD COLUMN IF NOT EXISTS edited_at DATETIME NULL;

-- טבלה חדשה: gantt_approvals
CREATE TABLE IF NOT EXISTS gantt_approvals (
  business_name VARCHAR(255) PRIMARY KEY,
  status VARCHAR(50) NULL,
  comment TEXT NULL,
  approved_at DATETIME NULL,
  approved_by VARCHAR(255) NULL,
  post_count INT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- סיום - בדוק שהכל עבר ללא שגיאות
-- ============================================================
