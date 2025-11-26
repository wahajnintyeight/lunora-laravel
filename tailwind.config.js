/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
        "./resources/js/**/*.js",
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', 'ui-sans-serif', 'system-ui'],
            },
            // Icon alignment utilities
            spacing: {
                'icon-xs': '0.75rem',
                'icon-sm': '1rem',
                'icon-md': '1.25rem',
                'icon-lg': '1.5rem',
            },
            // Colors are now defined in CSS @theme block (Tailwind v4)
            // See resources/css/app.css for color definitions
        },
    },
    plugins: [],
}