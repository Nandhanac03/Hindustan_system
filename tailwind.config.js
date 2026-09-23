import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', 'ui-sans-serif', 'system-ui', 'sans-serif', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: {
                    DEFAULT: '#a38c29',
                    50:  '#fdfbf0',
                    100: '#f9f5dc',
                    200: '#f0e6b3',
                    300: '#e3d183',
                    400: '#d0b855',
                    500: '#b8a43d',
                    600: '#a38c29',
                    700: '#8d7923',
                    800: '#6b5d1c',
                    900: '#4a4014',
                    950: '#2e2810',
                },
                surface: {
                    DEFAULT: '#FAF9F6',
                    card:    '#FFFFFF',
                    border:  '#E5E0D4',
                },
                slate: {
                    50: '#f6f5f4',
                    100: '#eceae6',
                    200: '#d7d4ce',
                    300: '#bebab0',
                    400: '#a59d92',
                    500: '#8b8377',
                    600: '#6c665d',
                    700: '#534e47',
                    800: '#3c3933',
                    850: '#292724',
                    900: '#191816',
                    950: '#0f0e0d',
                }
            },
            boxShadow: {
                'soft': '0 2px 15px -3px rgba(0, 0, 0, 0.07), 0 10px 20px -2px rgba(0, 0, 0, 0.04)',
                'glow': '0 0 15px rgba(163, 140, 41, 0.3)',
            }
        },
    },

    plugins: [forms],
};

