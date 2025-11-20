module.exports = {
  content: [
      "./**/*.php",
      "./assets/js/**/*.js",
      "./assets/css/**/*.css"
  ],
  theme: {
      extend: {
          colors: {
              primary: "var(--proevent-brand)",   // our dynamic theme color
          }
      }
  },
  plugins: []
}
