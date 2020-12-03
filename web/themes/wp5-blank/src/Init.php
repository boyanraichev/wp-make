<?php 
namespace Theme;
	
if (!defined('ABSPATH')) die;

class Init {
	
	/** @var The single instance of the class */
	private static $_instance = null;	
	
	// Don't load more than one instance of the class
	public static function instance() 
	{
		if ( null == self::$_instance ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    
    /**
     * Constructor
     */
    public function __construct() 
	{
    	
    	// after theme setup
    	add_action( 'after_setup_theme', [ $this, 'themeSetup' ] ); 
		
		// theme init 
    	add_action( 'init', [ $this, 'themeInit' ] );
    	
    	// register Gutenberg blocks
    	add_action( 'init', [ $this, 'enqueueBlocks' ] );
    	
    	// register scripts
    	add_action( 'wp_enqueue_scripts', [ $this, 'scripts' ] );
    	
    	// register styles
    	add_action( 'wp_enqueue_scripts', [ $this, 'styles' ] );
    	
    	// register admin only scripts
		add_action( 'admin_enqueue_scripts', [ $this, 'adminScripts' ] );

		// add browser classes
		add_filter( 'body_class', [$this,'bodyClass']);
		
		// allow svg
		add_filter('upload_mimes', [$this,'mimeTypes']);
		
    }
    
    /**
     * Add theme support
     */
    public function themeSetup() 
	{
	    
	    // setup language if not in english
		load_theme_textdomain( 'wp5-blank', get_template_directory().'/languages' );		
		
		// gutenberg custom color palette
		// add_theme_support( 'editor-color-palette', [] );
		
		// gutenberg custom font sizes
		// add_theme_support( 'editor-font-sizes', [] );
	}
	
    /**
     * Basic Theme settings
     */
    public function themeInit() 
	{
    
		/*
		* register all nav menus for your site here
		*/
		register_nav_menus( [ 
			'menu-main' => __( 'Header menu', 'wp5-blank'),
		] );
	
		/*
		*  set thumbnail sizes
		*/
		// set_post_thumbnail_size( 400, 400 ); // default Post Thumbnail dimensions  
		// add_image_size( 'slide', 1400, 600, true ); // i.e. home page slides

    	/*
	    * jpeg quality
	    */
		add_filter( 'jpeg_quality', function() { return 90; } );				
		
    }
    
    /*
	* Enqueue Gutenberg Blocks JS and CSS. Files include a sample block
	*/
    public function enqueueBlocks()
    {
	    
		wp_register_script(
	        'wp5-blank-blocks',
	        get_stylesheet_directory_uri() . '/assets/js/blocks.js',
			[ 'wp-blocks', 'wp-dom-ready', 'wp-edit-post', 'wp-block-library', 'wp-i18n', 'wp-element', 'wp-data' ],
	        THEME_VERSION
	    );
	    
		wp_register_style(
	        'wp5-blank-blocks',
	        get_stylesheet_directory_uri() . '/assets/css/blocks.css',
	        [ 'wp-edit-blocks' ],
	        THEME_VERSION
	    );

	    register_block_type( 'wp5-blank/test-block', [
	        'editor_script' => 'wp5-blank-blocks',
	        'editor_style' => 'wp5-blank-blocks',
	    ]);    		
		
	}
	
	/*
	* Enqueue JS files
	*/
	public function scripts() 
	{
		
		// register polyfill
		wp_register_script( 'polyfill-io', 'https://polyfill.io/v3/polyfill.min.js?features=es6%2Ces5%2Cdefault', false, false, false );
		wp_enqueue_script( 'polyfill-io' );
		
		// register theme script
		wp_register_script( 'themejs',  get_stylesheet_directory_uri() . '/assets/js/theme.js', [], ( WP_DEBUG ? time() : THEME_VERSION ), true );
	    wp_enqueue_script ('themejs');
		
		// localize theme script
		wp_localize_script( 'themejs', 'wp5_blank_obj', [ 
	        'ajaxurl' => admin_url( 'admin-ajax.php' ),
	        'redirecturl' => $_SERVER["REQUEST_URI"],
		] );
		
	}
	
	/*
	* Enqueue CSS files
	*/
	public function styles() 
	{
		
		// wp_dequeue_style( 'wp-block-library' ); 
		
		wp_enqueue_style( 'themecss', get_stylesheet_directory_uri() . '/assets/css/theme.css', [], ( WP_DEBUG ? time() : THEME_VERSION ), 'all' );
		
	}
	
	/*
	*  Admin scripts for custom blocks etc
	*/
	public function adminScripts() 
	{
		
	
	}
	
	/*
	*  Add to body classes
	*/
	public function bodyClass(array $classes) : array
	{
		
		$classes[] = 'js-cookie';	
					
		return $classes;

	}
	
	/*
	*  Allow SVG uploads
	*/
	public function mimeTypes(array $mimes) : array 
	{
		
		$mimes['svg'] = 'image/svg+xml';
		
		return $mimes;
		
	}
	
}
