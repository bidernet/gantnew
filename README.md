# 📦 bidernet v2.3.3-php - חבילת התקנה מלאה

חבילה מלאה לתיקייה `/public_html/app.bidernet.co.il/` בשרת ClickPress.

---

## 📂 הקבצים בחבילה

| קובץ | תיאור | פעולה |
|---|---|---|
| `index.html` | דף האתר | העלה לשרת |
| `app.js` | קוד האפליקציה (459KB compiled) | העלה לשרת |
| `api.php` | Backend API | העלה לשרת |
| `config.php` | הגדרות DB | **ערוך** ואז העלה |
| `schema.sql` | יצירת טבלאות | **הרץ** ב-phpMyAdmin |

---

## 🚀 שלבי התקנה (בסדר!)

### שלב 1: קבלת פרטי DB מחברת השרתים

חברת השרתים שלחה לך:
- שם מסד הנתונים (כמו `biderne1_bidernet`)
- שם משתמש DB (כמו `biderne1_admin`)
- סיסמת DB

### שלב 2: עריכת config.php

פתח את `config.php` בעורך טקסט (Notepad, VSCode וכו') והחלף 3 שורות:

```php
$DB_NAME = 'biderne1_bidernet';     // ← הכנס את שם ה-DB שקיבלת
$DB_USER = 'biderne1_admin';        // ← הכנס את שם המשתמש שקיבלת
$DB_PASS = 'YOUR_PASSWORD_HERE';    // ← הכנס את הסיסמה שקיבלת
```

**שמור את הקובץ.**

### שלב 3: העלאה ל-cPanel

1. כנס ל-**cPanel → File Manager**
2. נווט ל-`/public_html/app.bidernet.co.il/`
3. **מחק** את הקבצים הישנים (index.html, assets/ וכו')
4. העלה את **כל 5 הקבצים** האלה לאותה תיקייה:
   - `index.html`
   - `app.js`
   - `api.php`
   - `config.php` (אחרי שערכת אותו!)
   - `schema.sql`

### שלב 4: יצירת הטבלאות ב-phpMyAdmin

1. ב-cPanel → **phpMyAdmin**
2. בעמודה שמאל, בחר את ה-DB שלך (`biderne1_bidernet`)
3. למעלה לחץ על **SQL**
4. פתח את `schema.sql` בעורך טקסט, **העתק את כל התוכן**
5. **הדבק** ב-SQL ב-phpMyAdmin
6. לחץ **Go** / **בצע**

אמור להופיע ✅ עם הודעה שכל הטבלאות נוצרו.

### שלב 5: בדיקה

1. פתח `https://app.bidernet.co.il/` בדפדפן
2. רענון חזק: **Ctrl+Shift+R**
3. F12 → **Console** - אמור לראות:
   ```
   🎯 bidernet Content Calendar v2.3.3-php
   ✨ Server-backed via /api.php (MySQL on ClickPress)
   ```
4. הרץ ב-Console: `apiPing()` - אמור לחזור:
   ```json
   {ok: true, message: "pong", db: "connected", version: "v2.3.3-php"}
   ```

---

## 🔑 התחברות ראשונה

- שם משתמש: `admin`
- סיסמה: `admin123`

⚠️ **החלף את הסיסמה מיד אחרי ההתחברות הראשונה!**

---

## 🆘 פתרון בעיות

### "Database connection failed"
- ✅ ודא שערכת נכון את `config.php`
- ✅ ודא שיש לך **לעשות** את הסיסמה (case-sensitive!)
- ✅ ודא שהמשתמש קיבל **ALL PRIVILEGES** על ה-DB

### "Missing config.php"
- העלית את `config.php` לאותה תיקייה כמו `api.php`?

### "Unknown action"
- העלית את ה-`api.php` החדש (v2.3.3)? היקפים את הקובץ.

### Console מציג שגיאות "Supabase"
- שלא העלית גרסה ישנה? ודא שזה `app.js` החדש.
- רענון חזק: Ctrl+Shift+R

### תמונות לא נשמרות
- ודא שהרצת את `schema.sql` המלא (יש עמודה `mediaData`)
- בדוק ב-phpMyAdmin שהעמודה קיימת בטבלת `posts`
- בדוק שהשרת מאפשר גודל קבצים גדול (php.ini → `upload_max_filesize` = `10M`)

---

## 🎯 מה כלול בגרסה v2.3.3-php

- ✅ Backend ב-PHP/MySQL טהור (בלי Supabase!)
- ✅ אימות עם סיסמאות
- ✅ ניהול לקוחות ועסקים
- ✅ העלאת תמונות וסרטונים
- ✅ צ'אט פנים-פוסט עם היסטוריה
- ✅ צ'אט צוות פנימי
- ✅ אישורי גנט (gantt approvals)
- ✅ לינקי שיתוף קצרים
- ✅ ניהול חבילה ומדיה
- ✅ קטגוריות פוסטים
- ✅ מובייל-responsive
- ✅ branding מלא
