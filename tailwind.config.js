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
            colors: {
                transparent: 'transparent',
                current: 'currentColor',
                'white': '#ffffff',
                'black': '#000000',
                'gray': {
                    50: '#f9fafb',
                    100: '#f3f4f6',
                    200: '#e5e7eb',
                    300: '#d1d5db',
                    400: '#9ca3af',
                    500: '#6b7280',
                    600: '#4b5563',
                    700: '#374151',
                    800: '#1f2937',
                    900: '#111827',
                    950: '#030712',
                },
                // Custom brand colors based on logo
                'gold': {
                    50: '#fffbeb',
                    100: '#fef3c7',
                    200: '#fde68a',
                    300: '#fcd34d',
                    400: '#fbbf24',
                    500: '#f59e0b', // Primary gold
                    600: '#d97706',
                    700: '#b45309',
                    800: '#92400e',
                    900: '#78350f',
                    950: '#451a03',
                },
                'maroon': {
                    50: '#fdf2f2',
                    100: '#fce7e7',
                    200: '#fbd5d5',
                    300: '#f8b4b4',
                    400: '#f87171',
                    500: '#ef4444',
                    600: '#dc2626', // Primary maroon
                    700: '#b91c1c',
                    800: '#991b1b',
                    900: '#7f1d1d',
                    950: '#450a0a',
                },
            },
        },
    },
    plugins: [],
}