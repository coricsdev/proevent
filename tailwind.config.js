/* minimal tailwind config, just enough to keep purge working correctly */

module.exports = {
    content: [
      "./*.php",
      "./**/*.php",
      "./assets/js/**/*.js"
    ],
    theme: {
      extend: {
        // we’ll probably wire brand color from settings later
      }
    },
    plugins: []
  };
  