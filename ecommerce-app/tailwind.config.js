import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: { // ここを "colors" に変更
                brand: {
                    50:  '#F4EEFF', // 一番明るい
                    100: '#DCD6F7',
                    200: '#A6B1E1',
                    900: '#424874', 
                    999: '#222831'// 一番濃い
                },
            }
        },
    },

    plugins: [forms],
};
