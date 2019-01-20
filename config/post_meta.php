<?php

return [
	/*
	* What posts to show this meta for, valid values are:
	* 'page_on_front' 
	* 'cpt_xxxxxx' where xxxxxx = post type
	* 'page-template.php' for custom page templates
	*/
	'page-templates/templatename.php' => [ // 'page_on_front', 'cpt_xxxxxx', page template
		'metabox_id' => [ // metabox id
			'metabox_title' => '', // section title
			'metabox_intro' => '', // section intro text
			'sections' => [
				// text block
				[
					'type'		=> 'text',
					'title'		=> 'Optional title',
					'text'		=> 'Sample text',
				],
				// single meta field
				[
					'type'		=> 'single',
					'meta_name' => '',
					'meta_type' => 'text/textarea/textarea-xl/dropdown_pages/image/select/radio/checkbox',
					'label'		=> '',
					'placeholder'	=> '',								
				],
				// multiple input fields saved in a single meta field
				[
					'type'		=> 'multiple',
					'meta_name' => '',
					'title'		=> '', // optional section title
					'rows'		=> [
						[	
							'name' 		=> 'name', 
							'label'		=> 'URL',
							'placeholder'	=> 'URL', 
							'meta_type' => 'text/textarea/textarea-xl/dropdown_pages/image/select/radio/checkbox', 
							'options' 	=> [ 'value' => 'name' ],
						]
					]								
				],
				// table for repeating blocks of data, saved in a single meta field
				[
					'type'		=> 'table',
					'meta_name'	=> '',
					'title'		=> '', // table title
					'text'		=> '', // table instructions
					'table'		=> 'conf_speakers', // table id
					'rows'		=> [
						'speaker' => [ // row id
							[
								'name' 		=> 'name', 
								'title' 	=> 'Title', 
								'meta_type' => 'text/textarea/textarea-xl/dropdown_pages/image/select/radio/checkbox',
								'options' 	=> [ 'value' => 'name' ], 
							] 
						]	
					],
					'speaker'	=> 'Row name', //
				],
			],
		],	
	],	
					
];
