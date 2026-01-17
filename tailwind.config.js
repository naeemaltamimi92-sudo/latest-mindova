/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      colors: {
        // Primary colors - Purple (#5A3DEB)
        primary: {
          50: 'var(--color-primary-50, #EDE9FD)',
          100: 'var(--color-primary-100, #D4CBFA)',
          200: 'var(--color-primary-200, #B5A6F6)',
          300: 'var(--color-primary-300, #9681F2)',
          400: 'var(--color-primary-400, #775FEE)',
          500: 'var(--color-primary-500, #5A3DEB)',
          600: 'var(--color-primary-600, #4B32C9)',
          700: 'var(--color-primary-700, #3C28A7)',
          800: 'var(--color-primary-800, #2D1E85)',
          900: 'var(--color-primary-900, #1E1463)',
          DEFAULT: 'var(--color-primary, #5A3DEB)',
        },
        // Secondary colors - Gray (#6B7280)
        secondary: {
          50: 'var(--color-secondary-50, #F9FAFB)',
          100: 'var(--color-secondary-100, #F3F4F6)',
          200: 'var(--color-secondary-200, #E5E7EB)',
          300: 'var(--color-secondary-300, #D1D5DB)',
          400: 'var(--color-secondary-400, #9CA3AF)',
          500: 'var(--color-secondary-500, #6B7280)',
          600: 'var(--color-secondary-600, #4B5563)',
          700: 'var(--color-secondary-700, #374151)',
          800: 'var(--color-secondary-800, #1F2937)',
          900: 'var(--color-secondary-900, #111827)',
          DEFAULT: 'var(--color-secondary, #6B7280)',
        },
        // Accent alias for primary (purple)
        accent: {
          50: 'var(--color-accent-50, #EDE9FD)',
          100: 'var(--color-accent-100, #D4CBFA)',
          200: 'var(--color-accent-200, #B5A6F6)',
          300: 'var(--color-accent-300, #9681F2)',
          400: 'var(--color-accent-400, #775FEE)',
          500: 'var(--color-accent-500, #5A3DEB)',
          600: 'var(--color-accent-600, #4B32C9)',
          700: 'var(--color-accent-700, #3C28A7)',
          DEFAULT: 'var(--color-accent, #5A3DEB)',
        },
        // Neutral scale (formerly Lavender)
        lavender: {
          50: 'var(--color-lavender-50, #FAFAFA)',
          100: 'var(--color-lavender-100, #F5F5F5)',
          200: 'var(--color-lavender-200, #EEEEEE)',
          300: 'var(--color-lavender-300, #E0E0E0)',
          400: 'var(--color-lavender-400, #BDBDBD)',
          500: 'var(--color-lavender-500, #9E9E9E)',
          DEFAULT: 'var(--color-lavender-200, #EEEEEE)',
        },
        // Semantic colors (now mapped to primary/secondary)
        success: {
          DEFAULT: 'var(--color-success, #5A3DEB)',
          light: 'var(--color-success-light, #775FEE)',
          dark: 'var(--color-success-dark, #4B32C9)',
        },
        warning: {
          DEFAULT: 'var(--color-warning, #9681F2)',
          light: 'var(--color-warning-light, #B5A6F6)',
          dark: 'var(--color-warning-dark, #775FEE)',
        },
        danger: {
          DEFAULT: 'var(--color-danger, #3C28A7)',
          light: 'var(--color-danger-light, #4B32C9)',
          dark: 'var(--color-danger-dark, #2D1E85)',
        },
        info: {
          DEFAULT: 'var(--color-info, #6B7280)',
          light: 'var(--color-info-light, #9CA3AF)',
          dark: 'var(--color-info-dark, #4B5563)',
        },
      },
    },
  },
  plugins: [],
}
