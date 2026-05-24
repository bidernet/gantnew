# 📅 bidernet group - מערכת ניהול גאנטי תוכן

מערכת ניהול תוכן וגאנטי לסוכנויות פרסום דיגיטליות, בנויה עם React ו-Tailwind CSS.

## ✨ פיצ'רים עיקריים

- 🎨 ניהול מרובה לקוחות עם מיתוג מותאם אישי
- 📝 3 תצוגות פוסטים: ציר זמן, לוח שנה, וגאנט חודשי
- ✅ מערכת אישורים דו-צדדית (לקוח-מנהל)
- 💬 צ'אט פנימי בין מנהלים + צ'אט פוסט עם לקוח
- 🤖 עוזר AI לכתיבת תוכן (Anthropic API)
- 📤 מסך פרסום מרכזי ברשתות חברתיות
- 📊 עקיבה אחרי חבילות לקוחות (שבועי/חודשי)
- 🔗 לינקים לשיתוף עבור לקוחות (ללא סיסמה)
- 📜 היסטוריית שינויים מלאה לכל פוסט

## 🛠 טכנולוגיות

- **Frontend**: React 18 + Vite + Tailwind CSS
- **Storage**: localStorage (בגרסת הדמו) / MySQL (בגרסת הייצור)
- **UI**: lucide-react icons
- **AI**: Anthropic Claude API

## 🚀 התקנה וקריאה לפעולה

### מתאפשרות:

```bash
# התקן תלויות
npm install

# הרץ בתצוגה ופיתוח
npm run dev

# בנה לייצור
npm run build

# הצגה מקומית של הגרסה המובנית
npm run preview
```

### גישה למערכת:

1. פתח http://localhost:5173 בדפדפן
2. התחבר עם:
   - **שם משתמש**: `admin`
   - **סיסמה**: `admin123`

## 📁 מבנה הפרויקט

```
bidernet-content-calendar/
├── App.jsx                 # קומפוננטה ראשית (כל המערכת בקובץ אחד)
├── index.html             # דף HTML
├── main.jsx               # נקודת כניסה
├── index.css              # סגנונות Tailwind
├── package.json           # תלויות ורקומנדות
├── vite.config.js         # הגדרות Vite
├── tailwind.config.js     # הגדרות Tailwind
├── postcss.config.js      # הגדרות PostCSS
└── README.md              # קובץ זה
```

## 🔑 פרטי כניסה ראשונים

| Role | Username | Password |
|------|----------|----------|
| Admin | admin | admin123 |

## 🌍 פריסה לייצור

### דרישות השרת:

- Node.js 16+
- npm או yarn

### שלבי פריסה:

```bash
# 1. בנה את הפרויקט
npm run build

# 2. העלה את תיקיית /dist לשרת שלך
# 3. אם משתמש ב-Vercel/Netlify - רק חבר את ה-repo וזה יבנה אוטומטית

# או - בנה בשרת שלך:
npm install
npm run build
# שרת את קבצי /dist עם שרת סטטי (nginx, Apache, וכו')
```

## 🔐 הערות אבטחה

**גרסת הדמו הנוכחית:**
- משתמשת ב-localStorage (בדפדפן)
- כל הנתונים נשמרים ב-state של React

⚠️ **לייצור**, כל הנתונים חייבים להיות:
- מאוחסנים במסד נתונים בצד השרת (MySQL/PostgreSQL)
- מקובלים דרך API מאובטח
- עם אימות כראוי וסיסמאות מוצפנות

## 📞 תמיכה

לשאלות או בעיות, צור issue ב-GitHub repository.

## 📄 רישיון

© 2026 bidernet group. כל הזכויות שמורות.
