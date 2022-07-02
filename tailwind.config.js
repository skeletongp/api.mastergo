const defaultTheme = require("tailwindcss/defaultTheme");

module.exports = {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.css",
    ],
    theme: {
        extend: {
            fontFamily: {
                bank: ["Merchant Copy"],
            },
        },
        theme: {},
    },
    plugins: [require("flowbite/plugin")],
    darkMode: 'class',

};
