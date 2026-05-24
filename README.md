# bidernet Content Calendar v2.3.3-php

מערכת לוח שנה לתוכן עבור bidernet group - גרסת PHP/MySQL לשרתי ClickPress/cPanel.

## 🆕 מה חדש ב-v2.3.3-php (לעומת v2.2.0)

כל השיפורים שעשינו ב-v2.3.2 (Supabase) הועברו לגרסת PHP:

### תיקוני באגים:
- ✅ תיקון תצוגת תמונות וסרטונים (mediaData/mediaName)
- ✅ תיקון התנגשות לוגו במובייל (mobile-responsive header)
- ✅ לוגו בכותרת הפך לכפתור חזרה לראשי
- ✅ מיפוי שדות מלא: chat_messages↔messages, edit_history↔history

### פיצ'רים חדשים:
- ✅ לינקי שיתוף קצרים (#share=TOKEN במקום payload ארוך)
- ✅ סנכרון אישורי גנט (gantt_approvals) דרך השרת
- ✅ מיפוי קטגוריות, חבילה (package_size, package_period)
- ✅ פרסום מרובה פלטפורמות (published_to)
- ✅ תאריך עריכה צ'אט צוות (edited_at)

## ⚠️ דרישות שדרוג ב-MySQL

הגרסה הזו מצפה שטבלאות MySQL יכילו עמודות חדשות. הרץ את הסקריפט הבא:

```sql
-- עדכון טבלת users
ALTER TABLE users ADD COLUMN IF NOT EXISTS package_size VARCHAR(50);
ALTER TABLE users ADD COLUMN IF NOT EXISTS package_period VARCHAR(50);
ALTER TABLE users ADD COLUMN IF NOT EXISTS logo_name VARCHAR(255);
ALTER TABLE users ADD COLUMN IF NOT EXISTS share_token VARCHAR(64);
CREATE INDEX IF NOT EXISTS idx_users_share_token ON users(share_token);

-- עדכון טבלת posts
ALTER TABLE posts ADD COLUMN IF NOT EXISTS media_data LONGTEXT;
ALTER TABLE posts ADD COLUMN IF NOT EXISTS media_name VARCHAR(255);
ALTER TABLE posts ADD COLUMN IF NOT EXISTS category VARCHAR(100);
ALTER TABLE posts ADD COLUMN IF NOT EXISTS published_to JSON;
ALTER TABLE posts ADD COLUMN IF NOT EXISTS published_at DATETIME;
ALTER TABLE posts ADD COLUMN IF NOT EXISTS publish_status VARCHAR(50);
ALTER TABLE posts ADD COLUMN IF NOT EXISTS chat_messages JSON;
ALTER TABLE posts ADD COLUMN IF NOT EXISTS edit_history JSON;
ALTER TABLE posts ADD COLUMN IF NOT EXISTS client_approval JSON;

-- עדכון טבלת team_chat
ALTER TABLE team_chat ADD COLUMN IF NOT EXISTS edited_at DATETIME;

-- טבלה חדשה: gantt_approvals
CREATE TABLE IF NOT EXISTS gantt_approvals (
  business_name VARCHAR(255) PRIMARY KEY,
  status VARCHAR(50),
  comment TEXT,
  approved_at DATETIME,
  approved_by VARCHAR(255),
  post_count INT,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

## ⚠️ דרישות שדרוג ב-api.php

ה-api.php צריך לתמוך בפעולות הבאות:

### פעולות חדשות נדרשות:
- `?action=gantt_approvals` - GET/POST/DELETE
- `?action=share&token=XXX` - GET (חיפוש משתמש לפי share_token)
- `?action=ping` - GET (כבר קיים)

### פעולות קיימות שדורשות תמיכה בשדות חדשים:
- `?action=posts` - יקבל ויחזיר media_data, media_name, category, published_to, chat_messages, edit_history וכו'
- `?action=users` - יקבל ויחזיר package_size, package_period, logo_name, share_token
- `?action=team_chat` - יקבל ויחזיר edited_at

## 🚀 התקנה ב-ClickPress / cPanel

1. **בנה את הפרויקט:**
   ```bash
   npm install
   npm run build
   ```

2. **העלה את התיקייה `dist/` ל-public_html של השרת**

3. **ודא ש-api.php קיים בשרת ותומך בכל הפעולות לעיל**

4. **הרץ את ה-SQL לעדכון מסד הנתונים**

5. **בדוק שהכל עובד:**
   - פתח את האתר → F12 → Console
   - אמור לראות: `🎯 bidernet Content Calendar v2.3.3-php`
   - הרץ `apiPing()` → אמור לחזור `{ok: true, ...}`

## 🔑 משתמש ברירת מחדל
- שם משתמש: `admin`
- סיסמה: `admin123`
- **חשוב להחליף מיד אחרי התקנה!**

## 📞 תמיכה
לבעיות פנה לחברת השרתים לוודא ש-api.php עודכן לכל הפיצ'רים החדשים.
