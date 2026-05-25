# 🔧 תיקון: תמונות נעלמות + הגדלה ל-50MB

## ❓ הבעיה שהיתה
1. **תמונות וסרטונים נעלמו אחרי רענון** - הקוד שלח `mediaData` (Base64) ל-`api.php` אבל api.php הישן ידע לשמור רק `mediaUrl` ולא שמר את התמונה
2. **הגבלה של 4MB** - גם בקוד וגם בהגדרות PHP בשרת

## ✅ מה שתוקן בחבילה הזו

| קובץ | מה השתנה |
|---|---|
| `app.js` | הגבלת ההעלאה הועלתה מ-4MB ל-**50MB** |
| `api.php` | מקבל ושומר `mediaData` (Base64) ב-DB |
| `schema.sql` | כולל עמודות חדשות `mediaData`, `mediaName` |
| `php.ini` | **חדש!** מגדיל את הגבלות PHP ל-60MB |

## 🚀 התקנה - 4 צעדים

### 1️⃣ העלה את כל הקבצים החדשים ל-`/public_html/app.bidernet.co.il/`

⚠️ **חשוב להעלות גם את `php.ini`** - הוא מגדיל את הגבלת PHP בשרת!

קבצים להעלאה (5 קבצים):
- `index.html`
- `app.js` (חדש - עם 50MB)
- `api.php` (חדש - עם תמיכה ב-mediaData)
- `php.ini` (**חדש!** - חובה!)
- `schema.sql` (אם עוד לא הרצת)

### 2️⃣ הרץ את `schema.sql` ב-phpMyAdmin (אם עוד לא הרצת)

זה יוסיף את העמודות החסרות:
- `mediaData` LONGTEXT
- `mediaName` VARCHAR(255)
- `category` VARCHAR(100)
- `publishedTo` TEXT
- וגם טבלת `gantt_approvals` חדשה

### 3️⃣ ודא ש-config.php עדיין יש עם הסיסמה הנכונה

אל **תחליף** את `config.php` הקיים שלך! רק אם זו התקנה ראשונה.

### 4️⃣ בדיקה

1. רענון חזק: **Ctrl+Shift+R**
2. F12 → Console → אמור לראות: `🎯 bidernet Content Calendar v2.3.3-php`
3. נסה להעלות תמונה (עד 50MB) ושמור פוסט
4. רענן את הדף - **התמונה אמורה להישאר!** ✅

## ⚠️ אם php.ini לא עובד

חלק מהשרתים לא מאפשרים `php.ini` בכל תיקייה. אם זה לא עובד:

**פנה לחברת השרתים ובקש:**
> "אנא העלו את הגבלות PHP בתיקיית `/public_html/app.bidernet.co.il/`:
> - `upload_max_filesize` = 60M
> - `post_max_size` = 65M
> - `memory_limit` = 128M"

או בקש מהם להפעיל את `php.ini` המקומי.

## 🧪 בדיקה ש-PHP מקבל קבצים גדולים

צור קובץ `info.php` בתיקייה עם:
```php
<?php phpinfo(); ?>
```

פתח אותו בדפדפן וחפש:
- `upload_max_filesize` → אמור להראות `60M`
- `post_max_size` → אמור להראות `65M`

⚠️ **אחרי הבדיקה - מחק את `info.php` מסיבות אבטחה!**
