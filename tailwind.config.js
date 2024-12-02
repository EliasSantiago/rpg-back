/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./resources/**/*.{blade.php,js,vue}", // Adiciona todos os arquivos Blade, JS e Vue
    "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
  ],
  theme: {
    extend: {},
  },
  plugins: [],
};
