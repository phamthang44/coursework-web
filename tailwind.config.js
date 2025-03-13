/** @type {import('tailwindcss').Config} */
export default {
  content: ["./src/**/*.{html,js,php}"],
  theme: {
    extend: {
      colors: {
        darkmode: "#262626",
        darkmode2: "#131313",
      },
    },
  },
  darkMode: "class", // Enable dark mode using a class
  plugins: [],
};
