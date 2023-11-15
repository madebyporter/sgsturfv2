/** @type {import('tailwindcss').Config} */

module.exports = {
  content: [
    "./**/*.php",
    "./**/*.js",
  ],
  theme: {
    fontFamily: {
      'sans': ['Inter', 'Helvetica', 'ui-sans-serif', 'system-ui'],
    },
    screens: {
      'sm': '640px',
      // => @media (min-width: 640px) { ... }

      'md': '768px',
      // => @media (min-width: 768px) { ... }

      'lg': '1024px',
      // => @media (min-width: 1024px) { ... }

      'xl': '1280px',
      // => @media (min-width: 1280px) { ... }

      '2xl': '1536px',
      // => @media (min-width: 1536px) { ... }
    },
    extend: {
      colors: {
        'white': '#FFF',
        'gray-light': '#D9D9D9',
        'gray': '#A3A3A1',
        'gray-dark': '#666666',
        'black': '#242423',
        'graphite': '#292928',
        'green-pale': '#EFF0ED',
        'orange': '#FC9827',
        'green': '#72845E',
      },
      gridTemplateColumns: {
        // Simple 16 column grid
        '12': 'repeat(12, minmax(0, 1fr))'
      }
    },
  },
  variants: {},
  plugins: [
    require('@tailwindcss/typography'),
  ],
};
