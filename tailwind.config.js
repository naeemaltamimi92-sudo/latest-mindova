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
        // Primary colors - Deep Indigo (#3E3B92)
        primary: {
          50: 'var(--color-primary-50, #EEEEF8)',
          100: 'var(--color-primary-100, #D5D4ED)',
          200: 'var(--color-primary-200, #AEACE0)',
          300: 'var(--color-primary-300, #8785D3)',
          400: 'var(--color-primary-400, #5F5DC6)',
          500: 'var(--color-primary-500, #3E3B92)',
          600: 'var(--color-primary-600, #322F75)',
          700: 'var(--color-primary-700, #262358)',
          800: 'var(--color-primary-800, #1A183B)',
          900: 'var(--color-primary-900, #0D0C1E)',
          DEFAULT: 'var(--color-primary, #3E3B92)',
        },
        // Secondary/Accent colors - Electric Cyan (#00D2FF)
        secondary: {
          50: 'var(--color-secondary-50, #E6FBFF)',
          100: 'var(--color-secondary-100, #B3F2FF)',
          200: 'var(--color-secondary-200, #80E9FF)',
          300: 'var(--color-secondary-300, #4DE0FF)',
          400: 'var(--color-secondary-400, #1AD7FF)',
          500: 'var(--color-secondary-500, #00D2FF)',
          600: 'var(--color-secondary-600, #00A8CC)',
          700: 'var(--color-secondary-700, #007E99)',
          800: 'var(--color-secondary-800, #005466)',
          900: 'var(--color-secondary-900, #002A33)',
          DEFAULT: 'var(--color-secondary, #00D2FF)',
        },
        // Accent alias for secondary
        accent: {
          50: 'var(--color-accent-50, #E6FBFF)',
          100: 'var(--color-accent-100, #B3F2FF)',
          200: 'var(--color-accent-200, #80E9FF)',
          300: 'var(--color-accent-300, #4DE0FF)',
          400: 'var(--color-accent-400, #1AD7FF)',
          500: 'var(--color-accent-500, #00D2FF)',
          600: 'var(--color-accent-600, #00A8CC)',
          700: 'var(--color-accent-700, #007E99)',
          DEFAULT: 'var(--color-accent, #00D2FF)',
        },
        // Lavender scale - Soft Lavender (#E6E6FA)
        lavender: {
          50: 'var(--color-lavender-50, #FAFAFF)',
          100: 'var(--color-lavender-100, #F5F5FF)',
          200: 'var(--color-lavender-200, #E6E6FA)',
          300: 'var(--color-lavender-300, #D4D4F0)',
          400: 'var(--color-lavender-400, #C2C2E6)',
          500: 'var(--color-lavender-500, #B0B0DC)',
          DEFAULT: 'var(--color-lavender-200, #E6E6FA)',
        },
        // Semantic colors (kept standard for universal understanding)
        success: {
          DEFAULT: 'var(--color-success, #10b981)',
          light: 'var(--color-success-light, #34d399)',
          dark: 'var(--color-success-dark, #059669)',
        },
        warning: {
          DEFAULT: 'var(--color-warning, #f59e0b)',
          light: 'var(--color-warning-light, #fbbf24)',
          dark: 'var(--color-warning-dark, #d97706)',
        },
        danger: {
          DEFAULT: 'var(--color-danger, #ef4444)',
          light: 'var(--color-danger-light, #f87171)',
          dark: 'var(--color-danger-dark, #dc2626)',
        },
        info: {
          DEFAULT: 'var(--color-info, #00D2FF)',
          light: 'var(--color-info-light, #4DE0FF)',
          dark: 'var(--color-info-dark, #00A8CC)',
        },
      },
    },
  },
  plugins: [],
}
