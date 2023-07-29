const defaultTheme = require('tailwindcss/defaultTheme');

/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },

            colors: {
                'custom-blue': '#03243D',
                'custom-indigo': '#031726',
                'custom-lightindigo': '#031926',
                'custom-lightblue': '#239DFA',
                'custom-green': '#3CFA7A',
                'custom-white': '#eeeeee',
                'custom-gray': '#D7D7D7',
                'custom-sky': '#239DFA',
                'custom-darksky': '#0B4C73',
                'custom-orange': '#FA702F',
                'new-white': '#2200ff',
            },

            dropShadow: {
                'wxl': '0 25px 25px rgb(244 244 244 / 84%)',
                '4xl': [
                    '0 35px 35px rgba(0, 0, 0, 0.25)',
                    '0 45px 65px rgba(0, 0, 0, 0.15)'
                ]
            },
        },
    },

    plugins: [
        require('@tailwindcss/forms'),
        require('@tailwindcss/typography'),
    ],
};
