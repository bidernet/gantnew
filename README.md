# 🎨 bidernet v2.3.9-php - תיקון אייקונים

## 🔧 מה תוקן בגרסה זו

**בעיה ב-v2.3.8**: האייקונים היו בתוך תיקייה `icons/` ולא הוצגו (שגיאת 404).

**הפתרון ב-v2.3.9**: האייקונים עכשיו **באותה תיקייה ראשית** של האפליקציה, ללא צורך בתיקיית משנה.

## 🆕 כל השינויים בגרסה (מצטבר מ-v2.3.7+):

### 🎨 מיתוג
- Favicon (10 גדלים)
- כותרת: "בידרנט מערכת גאנטים"  
- צבעי מותג: `#d7ff00` + `#013d19`

### 📱 פלטפורמות
- רק 3: Facebook, Instagram, TikTok
- אייקונים ירוקים מותאמים

### 📝 עריכת פוסט
- בחירת פלטפורמות - רק אייקונים, ללא טקסט
- שדה תוכן - 10 שורות (כפול)
- אזור העלאת קובץ - קומפקטי

## 🚀 התקנה - חשוב מאוד!

### העלה את כל הקבצים ל-`/public_html/app.bidernet.co.il/` (כולם באותה תיקייה!):

**קבצים ראשיים:**
- `app.js` ⭐
- `index.html` ⭐
- `api.php` ⭐

**אייקוני פלטפורמה (3 קבצים - באותה תיקייה!):**
- `platform-facebook.png` ⭐
- `platform-instagram.png` ⭐
- `platform-tiktok.png` ⭐

**Favicons (10 קבצים):**
- `favicon.ico`, `favicon.png`
- `favicon-16.png`, `favicon-32.png`, `favicon-48.png`, `favicon-64.png`
- `favicon-128.png`, `favicon-192.png`, `favicon-256.png`
- `apple-touch-icon.png`

⚠️ **אל תחליף את `config.php`** - השאר את הסיסמה הקיימת!

⚠️ **כל הקבצים באותה תיקייה ראשית** - אין צורך ביצירת תיקיות משנה!

### בדיקה אחרי העלאה

נסה לפתוח בדפדפן:
```
https://app.bidernet.co.il/platform-facebook.png
```

אמור להופיע האייקון. אם 404 - הקובץ לא הועלה.

### רענון

**Ctrl+Shift+R**

ב-Console (F12):
```
🎯 bidernet Content Calendar v2.3.9-php
🔧 FIX: Platform icons in root folder
```
