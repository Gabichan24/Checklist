const defaultTheme = require("tailwindcss/defaultTheme");

/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
    "./vendor/laravel/jetstream/**/*.blade.php",
    "./storage/framework/views/*.php",
    "./resources/views/**/*.blade.php",
    "./resources/js/**/*.js",
    "./resources/css/**/*.css",
  ],

  theme: {
    extend: {
      // Fuentes elegantes tipo dashboard
      fontFamily: {
        sans: ["Inter", ...defaultTheme.fontFamily.sans],
        rounded: ["Nunito", ...defaultTheme.fontFamily.sans],
        display: ["Montserrat", ...defaultTheme.fontFamily.sans],
      },

      // Colores suaves tipo Checklisto
      colors: {
        primary: {
          DEFAULT: "#16a34a", // verde suave
          light: "#dcfce7",
          dark: "#15803d",
        },
        sidebar: "#ffffff",
        softgray: "#f3f4f6",
        bordergray: "#e5e7eb",
      },

      // Sombra moderna
      boxShadow: {
        soft: "0 4px 20px rgba(0,0,0,0.05)",
        card: "0 3px 10px rgba(0,0,0,0.07)",
      },

      // Bordes redondeados grandes
      borderRadius: {
        xl2: "1rem",
        xl3: "1.35rem",
      },

      zIndex: {
        1: "1",
        n1: "-1",
      },

      screens: {
        "food-sm": "966px",
      },
    },
  },

  plugins: [
    require("@tailwindcss/forms"),
    require("@tailwindcss/typography"),
  ],
};

