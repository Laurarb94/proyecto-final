/** @type {import('tailwindcss').Config} */
export default {
    content: [
      "./index.html", // Si tienes este archivo
      "./src/**/*.{vue,js,ts,jsx,tsx}" // Asegúrate de que Tailwind procese archivos .vue y .js en tu carpeta src
    ],
    theme: {
      extend: {},
    },
    plugins: [],
  };
  