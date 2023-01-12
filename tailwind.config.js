const plugin = require('tailwindcss/plugin')

/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
      "./resources/**/*.blade.php",
      "./resources/**/*.svg",
      "./resources/**/*.js",
  ],
  theme: {
    extend: {},
  },
  plugins: [
      plugin(function({ addUtilities, addVariant, addComponents, e, config }) {
          addUtilities({
              '.bg-radial-gradient': {
                  backgroundImage: 'radial-gradient(ellipse farthest-side at center bottom, var(--tw-gradient-stops))',
              }
          })
      }),
      plugin(function({ addUtilities, addVariant, addComponents, e, config }) {
          addVariant('peer-unchecked', '*:not(:checked)~&')
          addVariant('has-checked', '&:has(:checked)')
          addVariant('has-unchecked', '&:has(:not(:checked))')
      }),
  ],
}
