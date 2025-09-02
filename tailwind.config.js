// tailwind.config.js
import forms from '@tailwindcss/forms'
import typography from '@tailwindcss/typography'

export default {
  darkMode: 'class',
  content: [
    './resources/views/**/*.blade.php',
    './resources/js/**/*.js',
  ],
  theme: {
    extend: {
      colors: {
        primary: {
          50:'#eff6ff',100:'#dbeafe',200:'#bfdbfe',300:'#93c5fd',400:'#60a5fa',
          500:'#3b82f6',600:'#2563eb',700:'#1d4ed8',800:'#1e40af',900:'#1e3a8a'
        },
        accent: {
          50:'#f5f3ff',100:'#ede9fe',200:'#ddd6fe',300:'#c4b5fd',400:'#a78bfa',
          500:'#8b5cf6',600:'#7c3aed',700:'#6d28d9',800:'#5b21b6',900:'#4c1d95'
        }
      },
      boxShadow: {
        soft: '0 4px 20px rgba(0,0,0,0.06)',
        softlg: '0 8px 32px rgba(0,0,0,0.08)',
        inset: 'inset 0 1px 3px rgba(0,0,0,0.08)',
      },
      borderRadius: { xl2: '1.25rem' },
    }
  },
  plugins: [forms, typography],
}
