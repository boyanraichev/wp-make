<?php

return [
	// add to acme taxonomy
	'acme' => [ 
		// text meta
		[
			'type' => 'text',
			'name' => 'products_subtitle',
			'label' => __('Subtitle','theme'),
			'options' => [ 'translate' => true ],
		],
		// text meta
		[
			'type' => 'image',
			'create' => false,
			'name' => 'products_icon',
			'label' => __('Icon','theme'),
		],
	],
	
];

