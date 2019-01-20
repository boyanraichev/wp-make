var el = wp.element.createElement,
	__ = wp.i18n.__,
    blockStyle = { backgroundColor: '#eee', padding: '20px' };

wp.blocks.registerBlockType( 'wp5-blank/test-block', {
    title: 'Test Block',
    icon: 'carrot',
    category: 'layout',
    attributes: {
	    slides: {
			type: 'array',
			default: [],
			source: 'query',
			selector: '.glide__slide',
			query: {
				img: {
					source: 'attribute',
					selector: '.mw__image--image',
					attribute: 'data-src',
				},
			},
		},
	},
    edit: function( props ) {
	    
	    const { 
	        setAttributes, 
	        attributes,
	        focus
	    } = props;
	    
	    const {
			slides,
		} = attributes;
		
		function onSelectImage( media ) {

			slides.push({
				'img': media.sizes.full.url
			});

		    return setAttributes( {
		      slides: slides
		    } );
		    
		};
		
		var slidesEls = [];
		
		slides.forEach( ( slide ) => {

			if (slide.img !== undefined && slide.img.length > 0) {
				
				slidesEls.push(
					el(
						'li',
						{ className: 'newco-slides-preview-img' },
						el(
							'img',
							{ src: slide.img, className: 'newco-slides-preview-img-el' }
						)
					)	
				);
				
			}
			
		} );
		
		return [
			focus &&
			el(
				wp.blocks.BlockControls,
				{},
			),
			el( 
				'div', 
				{ }, 
				el(
					'ul',
					{ className: 'newco-slides-preview', style: blockStyle  },
					slidesEls
				),
				el(
					'div',
					{ style: { 'padding-top': '10px' } },				
					el( 
						wp.editor.MediaUpload, 
						{
					    	onSelect: onSelectImage,
							type: 'image',
							value: '',
							render: function( obj ) {
					        	return el( 
					        		wp.components.Button, 
					        		{
										className: 'button button-large',
										onClick: obj.open
									},
									__( 'Upload Image' )
								); 
							}
    					}
    				)
    			)
			)
		];
			
    },

    save: function( { attributes } ) {
	    
	    const {
			slides,
		} = attributes;
		
		var slidesEls = [];
		
		slides.forEach( ( slide ) => {

			if (slide.img !== undefined && slide.img.length > 0) {
				
				slidesEls.push(
					el(
						'li',
						{ className: 'glide__slide glide__slide--type-image' },
						el (
							'div',
							{ className: 'mw__base mw__image' },
							el(
								'div',
								{ 'data-src': slide.img, className: 'mw__image--image' }
							),
							el(
								'div',
								{ className: 'mw__image--content' }
							)
						)
					)	
				);
				
			}
			
		} );

		
        return el(
	        'div',
	        { className: 'wp-block-newco-sliders-image-slider newco-slider newco-slider-home', style: 'background-color: black;' },
	        el(
		        'div',
		    	{ className: 'list-unstyled glide__ghost--wrapper' },
	        ),
	        el(
		        'div',
		    	{ className: 'glide' },
		    	el(
			    	'div',
					{ className: 'glide__track', 'data-glide-el': 'track' },
					el(
						'ul',
						{ className: 'glide__slides' },	
						slidesEls
					),
			    ),
			    el(
				    'div',
				    { 'data-glide-el': 'controls' },
				    el(
					    'button',
					    { className: 'slider__arrow slider__arrow--prev glide__arrow glide__arrow--prev', 'data-glide-dir': "<" },
					    el(
						    'svg',
						    { xmlns: 'http://www.w3.org/2000/svg', width: '19.031', height: '28.031', 'viewBox': '0 0 19.031 28.031' },
						    el(
						    	'path',
						    	{ d: 'M180.908,857.261a0.919,0.919,0,0,0-1.28,0l-8.887,8.723-4.471,4.389a0.877,0.877,0,0,0,0,1.257l4.471,4.389,8.887,8.722a0.917,0.917,0,0,0,1.28,0l3.831-3.761a0.875,0.875,0,0,0,0-1.256l-8.247-8.094a0.878,0.878,0,0,1,0-1.257l8.247-8.094a0.875,0.875,0,0,0,0-1.256Z', transform: 'translate(-166 -857)', fill:'#ffffff' }
						    )
					    )
				    ),
				    el(
					    'button',
					    { className: 'slider__arrow slider__arrow--next glide__arrow glide__arrow--next', 'data-glide-dir': "" },
					    el(
						    'svg',
						    { xmlns: 'http://www.w3.org/2000/svg', width: '18.97', height: '28', 'viewBox': '0 0 18.97 28' },
						    el(
						    	'path',
						    	{ d: 'M2550.12,884.739a0.9,0.9,0,0,0,1.27,0l8.88-8.713,4.46-4.384a0.881,0.881,0,0,0,0-1.255L2560.27,866l-8.88-8.713a0.9,0.9,0,0,0-1.27,0l-3.83,3.756a0.881,0.881,0,0,0,0,1.255l8.23,8.086a0.864,0.864,0,0,1,0,1.255l-8.23,8.085a0.882,0.882,0,0,0,0,1.256Z', transform: 'translate(-2546.03 -857.031)', fill:'#ffffff' }
						    )
					    )
				    )
			    )
	        ),
	        el(
		        'div',
		        { className: 'glide__nav--wrapper' },
		        el(
			        'div',
			    	{ className: 'glide__nav--token' }    
		        )
	        )
	    );
	        
    },
} );