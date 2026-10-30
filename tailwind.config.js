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
                sans: ['"Instrument Sans"', ...defaultTheme.fontFamily.sans],
                display: ['"Bricolage Grotesque"', '"Instrument Sans"', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                ink: {
                    DEFAULT: '#0B110D',
                    800: '#101712',
                    700: '#141B16',
                    600: '#1B271F',
                    500: '#1F2A23',
                    400: '#26322A',
                    300: '#2C3A31',
                    200: '#3A4A40',
                },
                brand: {
                    DEFAULT: '#B8FF65',
                    soft: '#D3FFA0',
                },
                paper: '#F4F5F1',
                stage: '#ECEFE7',
                placeholder: '#E3E7DB',
                line: {
                    DEFAULT: '#D5DACF',
                    strong: '#BFC7B8',
                    soft: '#E3E7DB',
                },
                muted: '#4B544D',
                body: '#2E3830',
                fog: '#A6ADA7',
                snow: '#F2F5F0',
                moss: '#2F4A22',
                rust: '#8A3B00',
                focus: '#5E9E2A',
            },
            borderRadius: {
                card: '4px',
            },
            keyframes: {
                float: {
                    '0%, 100%': { transform: 'translateY(0)' },
                    '50%': { transform: 'translateY(-12px)' },
                },
                rise: {
                    from: { opacity: '0', transform: 'translateY(14px)' },
                    to: { opacity: '1', transform: 'none' },
                },
                digitando: {
                    '0%, 60%, 100%': { opacity: '.4', transform: 'translateY(0)' },
                    '30%': { opacity: '1', transform: 'translateY(-4px)' },
                },
            },
            animation: {
                float: 'float 6s ease-in-out infinite',
                rise: 'rise .5s cubic-bezier(.2,.7,.2,1) both',
                'rise-late': 'rise .6s .2s cubic-bezier(.2,.7,.2,1) both',
                digitando: 'digitando 1.2s ease-in-out infinite',
            },
        },
    },

    plugins: [forms],
};
