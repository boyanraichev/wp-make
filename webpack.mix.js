const { mix } = require('laravel-mix');

mix.js('web/content/themes/wp5-blank/resources/js/theme.js', 'web/content/themes/wp5-blank/assets/js')
   .js('web/content/themes/wp5-blank/resources/js/blocks.js', 'web/content/themes/wp5-blank/assets/js')
   .js('vendor/boyo/wp5-bang/resources/bang.meta.js', 'web/content/themes/wp5-blank/assets/vendor/wp5-bang/js')
   .options({
        postCss: [
            require('postcss-css-variables')(),
            require('autoprefixer')({
               overrideBrowserslist: [
                    '> 0.5%',
                    'last 4 versions',
                    'Firefox ESR',
                    'ie 11'
                ]})
        ]
   })
   .sass('web/content/themes/wp5-blank/resources/sass/theme.scss', 'web/content/themes/wp5-blank/assets/css/theme.css')
   .sass('web/content/themes/wp5-blank/resources/sass/blocks.scss', 'web/content/themes/wp5-blank/assets/css/blocks.css');