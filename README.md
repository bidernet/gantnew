# 🆕 bidernet v2.3.4-php - גרסה חדשה עם תיקונים

## 🐛 הבעיות שתוקנו

### 1. תמונות נעלמו אחרי רענון ❌➡️✅
api.php הישן לא ידע לשמור Base64 של תמונות. עכשיו זה עובד.

### 2. הגבלת 4MB ❌➡️✅ 50MB
הגבלה בקוד הועלתה ל-50MB + קובץ php.ini חדש.

## ✅ איך לבדוק שזו v2.3.4

F12 → Console אמור להראות:
```
🎯 bidernet Content Calendar v2.3.4-php
🔧 FIX: media uploads now persist + 50MB limit
```

אם אתה רואה v2.3.3 - הקבצים הישנים עוד שם או שיש cache.

## 🚀 התקנה - 3 צעדים

### 1️⃣ העלה ל-`/public_html/app.bidernet.co.il/`:

- `index.html` (חדש)
- `app.js` (חדש)
- `api.php` (חדש - תומך בתמונות)
- `php.ini` ⭐ **חדש - חובה!**
- `schema.sql` (אם עוד לא הרצת)

⚠️ **אל תחליף את `config.php`** - השאר את הסיסמה!

### 2️⃣ הרץ schema.sql ב-phpMyAdmin (אם עוד לא)

### 3️⃣ Ctrl+Shift+R לרענון חזק

## 🧪 בדיקה

1. F12 → Console → `v2.3.4-php` ✅
2. צור פוסט עם תמונה ושמור
3. רענן - **התמונה נשארת!** ✅

## ⚠️ אם php.ini לא עובד

פנה לחברת השרתים ובקש:
- upload_max_filesize = 60M
- post_max_size = 65M
- memory_limit = 128M
