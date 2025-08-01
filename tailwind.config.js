/** @type {import('tailwindcss').Config} */
export default {
  // Avec Tailwind v4 et le plugin Vite, le content est géré dans vite.config.js
  // Mais on peut garder cette config pour d'éventuelles personnalisations
  theme: {
    extend: {
      fontFamily: {
        sans: ['Figtree', 'sans-serif'],
      },
    },
  },
  plugins: [],
};