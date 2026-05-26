# 🔧 bidernet v2.4.11-php - תיקון יצירת קישור

## 🐛 הבעיה שתוקנה

ב-v2.4.10 הייתה שגיאת קוד: `setUsers is not defined` - מנעה יצירת קישורים חדשים.

## ✅ ה-fix

עכשיו הקוד משתמש ב-`saveUsers` (פונקציה תקינה שמועברת כ-prop) במקום ב-`setUsers` שלא קיים.

## 🚀 התקנה

ל-`/public_html/app.bidernet.co.il/`:
- ✅ **`app.js`** ⭐

## 🧪 בדיקה

**Ctrl+Shift+R**

ב-Console:
```
🎯 bidernet Content Calendar v2.4.11-php
🔧 FIX: setUsers reference error in share link
```

עכשיו תוכל ליצור קישור שיתוף לכל הלקוחות בלי שגיאה!
