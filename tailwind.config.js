import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
        './resources/js/**/*.jsx',
        './resources/js/**/*.js',
    ],

    theme: {
        extend: {
            colors: {
                gold: '#BB9C7C',
                blue: '#051532',
                black: '#000000',
                teal: '#95F2F2',
                white: '#FFFFFF',
                grey: '#EFEFEF',
                yellow: '#F7F8C6',
                pink: '#F5D6E1',
                lightBlue: '#C7EDF2',
                mint: '#D4F6D1',
                purple: '#D3B2E7',
                darkGrey: '#5A5A5A',
                red: '#FF0000',
                'brand-background': '#FFFFFF',
                'brand-text': '#1A1A1A',
                'brand-muted': '#4F4F4F',
                'brand-surface': '#FFF8F5',
                coral: '#FF6F61',
                'coral-light': '#FF8A75',
                'coral-dark': '#E65A50',
                'secondary-sky': '#6BCBFF',
                'secondary-mint': '#9AD5CA',
                'secondary-amber': '#FFC15E',
                'secondary-lavender': '#C9A4F6',
                'secondary-berry': '#FF8FA3',
            },
            fontFamily: {
                sans: ['open-sans', ...defaultTheme.fontFamily.sans],
            },
            boxShadow: {
                glow: '0 24px 60px -30px rgba(99, 102, 241, 0.6)',
            },
            backgroundImage: {
                'coral-glow': 'linear-gradient(135deg, #FF6F61 0%, #FF9478 100%)',
            },
        },
    },

    plugins: [forms],
};
