/** @type {import('tailwindcss').Config} */
export default {
  content: ["./src/**/*.{html,js,php}"],
  theme: {
    extend: {
      colors: {
        darkmode: "#262626",
        darkmode2: "#131313",
        primary: {
          light: "#3f51b5",
          dark: "#b92b27", // Quora red
        },
        secondary: {
          light: "#f50057",
          dark: "#f50057",
        },
        background: {
          light: "#f5f5f5",
          dark: "#262626",
        },
        card: {
          light: "#ffffff",
          dark: "#1e1e1e",
        },
        border: {
          light: "#e0e0e0",
          dark: "#2e2e2e",
        },
        success: {
          light: "#4caf50",
          dark: "#4caf50",
        },
        warning: {
          light: "#ff9800",
          dark: "#ff9800",
        },
        danger: {
          light: "#f44336",
          dark: "#f44336",
        },
      },
    },
  },
  darkMode: "class", // Enable dark mode using a class
  plugins: [],
};
