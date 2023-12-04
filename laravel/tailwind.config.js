import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */

export default {
    content: [
        "./index.html",
        "./src/**/*.{js,ts,jsx,tsx}",
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        "./src/**/*.{html,js}",
        "./node_modules/tw-elements/dist/plugin.cjs"
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
                gm_font: ['Pathway Extreme', ...defaultTheme.fontFamily.sans],
            },
            colors:{
                gm_bg1: '#1F2333',
                gm_bg2: '#2F3542',
                gm_textsub: '#546465',
                gm_text: '#EBEBEB',
                gm_emphasis: '#08C4B7',
                gm_alert: '#EF4B2B',
                gm_notice: '#F4A52F',
            },
        },
    },

    plugins: [
        forms
    ],
};




