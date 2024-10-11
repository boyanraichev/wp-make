const mix = require('laravel-mix');

mix.js('web/themes/wp5-blank/resources/js/theme.js', 'web/themes/wp5-blank/assets/js');
mix.js('web/themes/wp5-blank/resources/js/blocks.js', 'web/themes/wp5-blank/assets/js').react();
mix.js('vendor/boyo/wp5-bang/resources/bang.meta.js', 'web/themes/wp5-blank/assets/vendor/wp5-bang/js');

mix.options({
    clearConsole: false,
    terser: {
        extractComments: (astNode, comment) => false,
        terserOptions: {
            format: {
                comments: false,
            }
        }
    },
    postCss: [
        require('autoprefixer')({
            overrideBrowserslist: [
                '> 0.5%',
                'last 4 versions',
                'Firefox ESR',
                'ie 11'
            ]
        })
    ]
});

mix.sass('web/themes/wp5-blank/resources/sass/theme.scss', 'web/themes/wp5-blank/assets/css/theme.css')
mix.sass('web/themes/wp5-blank/resources/sass/blocks.scss', 'web/themes/wp5-blank/assets/css/blocks.css');