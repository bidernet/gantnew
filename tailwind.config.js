/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./index.html",
    "./src/**/*.{js,ts,jsx,tsx}",
    "./App.jsx",
    "./main.jsx"
  ],
  theme: {
    extend: {
      fontFamily: {
        sans: ['PingHL', 'Heebo', 'system-ui', 'sans-serif']
      }
    }
  },
  plugins: []
}
