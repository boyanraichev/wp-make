const { mix } = require('laravel-mix');

mix.js('web/content/themes/wp5-blank/resources/js/theme.js', 'web/content/themes/wp5-blank/assets/js')
	.styles([
   		'web/content/themes/wp5-blank/resources/css/normalise.css',
   		'web/content/themes/wp5-blank/resources/css/tat.css',
   		'web/content/themes/wp5-blank/resources/css/theme.css'
   	], 'web/content/themes/wp5-blank/assets/css/theme.css');