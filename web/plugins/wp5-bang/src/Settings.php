<?php
namespace Plugin;

if (!defined('ABSPATH')) die;

class Settings {

	/**
	 * Holds the values to be used in the fields callbacks
	 */
	public $options = [];

	/** @var The single instance of the class */
	protected static $_instance = null;	
	
	// Don't load more than one instance of the class
	public static function instance() {
		if ( !isset(static::$_instance) ) {
			static::$_instance = new static;
		}
		return static::$_instance;
	}

	/**
	 * Construct
	 */
	public function __construct() {
		add_action( 'admin_menu', [ $this, 'add_theme_menu' ] );
		add_action( 'admin_init', [ $this, 'page_init' ] );
	}

	/**
	 * Add options page
	 */
	public function add_theme_menu() {
		add_theme_page(
			__('Theme Settings', 'wp5-bang'), 
			__('Theme Settings', 'wp5-bang'), 
			'manage_options', 
			'wp5bang_options', 
			[ $this, 'create_theme_page']
		);
	}

	/**
	 * Options page callback
	 */
	public function create_theme_page() {

		?>
		<div class="wrap">
			<h2><?php _e('Theme Settings', 'wp5-bang'); ?></h2>
			<!-- Make a call to the WordPress function for rendering errors when settings are saved. -->
			<?php settings_errors(); ?>
			<?php 	$active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'options'; ?>
			<h2 class="nav-tab-wrapper">
				<a href="themes.php?page=wp5bang_options&tab=options" class="nav-tab <?php echo $active_tab == 'options' ? 'nav-tab-active' : ''; ?>"><?php _e('Configuration', 'wp5-bang'); ?></a>
				<a href="themes.php?page=wp5bang_options&tab=social_options" class="nav-tab <?php echo $active_tab == 'social_options' ? 'nav-tab-active' : ''; ?>"><?php _e('Social Media', 'wp5-bang'); ?></a>
				<?php do_action('wp5bang_settings_tabs',$active_tab); ?>
			</h2>
			<!-- Create the form that will be used to render our options -->
			<form method="post" action="options.php">
				 <?php
				switch($active_tab) {
					case 'options':
						 $this->options['wp5bang_general_options'] = get_option( 'wp5bang_general_options' );
						$this->options['wp5bang_maintenance'] = get_option( 'wp5bang_maintenance' );
						settings_fields( 'wp5bang_general_options_group' );
						do_settings_sections( 'wp5bang_general_options' );
						break;
					case 'social_options':	
						$this->options['wp5bang_social_options'] = get_option( 'wp5bang_social_options' );
						settings_fields( 'wp5bang_social_options_group' );
						do_settings_sections( 'wp5bang_social_options' );
						break;
				}
				do_action('wp5bang_settings_form',$active_tab);
				submit_button();
				?>
			</form>
		</div><!-- /.wrap -->
		<?php
	}

	/**
	 * Register and add settings
	 */
	public function page_init() {    
		
		register_setting(
			'wp5bang_general_options_group',
			'wp5bang_general_options',
			[ $this, 'sanitize' ]
		);
		register_setting(
			'wp5bang_general_options_group',
			'wp5bang_maintenance',
			[ $this, 'sanitize' ]
		);
		register_setting(
			'wp5bang_social_options_group',
			'wp5bang_social_options',
			[ $this, 'sanitize' ]
		);
		
		add_settings_section(
			'wp5bang_settings_general',
			__('Basic site settings', 'wp5-bang'),
			[ $this, 'empty_section_info' ],
			'wp5bang_general_options'
		);
		
		add_settings_section(
			'wp5bang_settings_social',			
			__('Social media', 'wp5-bang'),
			[ $this, 'empty_section_info' ],	
			'wp5bang_social_options'			
		);
		
		// register settings FIELDS per section and page
		
		// GENERAL
		add_settings_field( 
			'wp5bang_maintanance_mode',	 // ID
			__('Maintenance', 'wp5-bang'),	 // Title
			[ $this, 'input_checkbox' ],	// Callback
			'wp5bang_general_options',	 // Page
			'wp5bang_settings_general',	 // Section	
			[
				'descr' => __('Check to temporarily hide the website', 'wp5-bang'),
				'name'	=> 'wp5bang_maintenance',
			]
		); 
		 
		// SOCIAL
		add_settings_field( 
			'wp5bang_facebook_page',
			__('Facebook page', 'wp5-bang'),	
			[ $this, 'input_text' ],
			'wp5bang_social_options',	 
			'wp5bang_settings_social',	 
			[
				'descr' => __('Full url', 'wp5-bang'),
				'name'	=> 'wp5bang_social_options',
				'key'	=> 'facebook_page',
			]
		 );
		 add_settings_field( 
			'wp5bang_twitter_page',
			__('Twitter page', 'wp5-bang'),
			[ $this, 'input_text' ],
			'wp5bang_social_options',	 
			'wp5bang_settings_social',	 
			[
				'descr' => __('Full url', 'wp5-bang'),
				'name'	=> 'wp5bang_social_options',
				'key'	=> 'twitter_page',
			]
		 );
		 add_settings_field( 
			'wp5bang_linkedin_page',	
			__('LinkedIn page', 'wp5-bang'),
			[ $this, 'input_text' ],
			'wp5bang_social_options',	 
			'wp5bang_settings_social',	 
			[
				'descr' => __('Full url', 'wp5-bang'),
				'name'	=> 'wp5bang_social_options',
				'key'	=> 'linkedin_page',
			]
		 );
		 
	}

	/**
	 * Sanitize each setting field as needed
	 */
	public function sanitize($input)
	{ 
		// do stuff here
		return $input;
	}

	/** 
	 * Print the Section text
	 */
	public function empty_section_info() {
		echo '';
	}
	/** 
	 * Prints input type=text options
	 */
	public function input_text($args) {
		$option = $this->extract_option_data($args);
		// Render the output
		echo '<input type="text" id="'. $option['id'] .'" name="'. $option['name'] .'" value="'.stripslashes(esc_attr( $option['value'] )).'" class="regular-text" />';
		echo ( isset($args['descr']) ? '<br><small><label for="'.$option['id'].'">'. $args['descr'] .'</label></small>' : '' ); 
	}
	
	/** 
	 * Prints textarea options
	 */
	public function input_textarea($args) {
		$option = $this->extract_option_data($args);
		// Render the output
		echo '<textarea id="'. $option['id'] .'" name="'. $option['name'] .'" rows="6" cols="45" >'. stripslashes(esc_textarea($option['value'])) .'</textarea>';
		echo ( isset($args['descr']) ? '<br><small><label for="'.$option['id'].'">'. $args['descr'] .'</label></small>' : '' );
	}
	
	/** 
	 * Prints input type=text options
	 */
	public function input_number($args) {
		$option = $this->extract_option_data($args);
		$class = ( ( !isset($args['max']) OR $args['max'] > 99999 ) ? 'regular-text' : 'small-text' );
		// Render the output
		echo '<input type="number" id="'. $option['id'] .'" name="'. $option['name'] .'" value="'.stripslashes(esc_attr( $option['value'] )).'" min="'.$args['min'].'" max="'.$args['max'].'" step="'.$args['step'].'" class="'.$class.'" />';
		echo ( isset($args['descr']) ? '<br><small><label for="'.$option['id'].'">'. $args['descr'] .'</label></small>' : '' );
	}

	/** 
	 * Prints checkbox options
	 */
	public function input_checkbox($args) {
		$option = $this->extract_option_data($args);
		// Render the output
		echo '<label for="'. $option['id'] .'"><input type="checkbox" id="'. $option['id'] .'" name="'. $option['name'] .'" size="20" value="1" '.checked($option['value'],'1',false).' />'. $args['descr'] . '</label>';
	}
	
	/** 
	 * Prints radio options
	 */
	public function input_radio($args) {
		$option = $this->extract_option_data($args);
		$radio_array = $args['options'];
		// Render the output
		foreach ( $radio_array as $value => $name) {
			echo '<label for="'. $option['id'].'-'.$value .'"><input type="radio" id="'. $option['id'].'-'.$value .'" name='. $option['name'] .' value="'. $value .'" '.checked($option['value'],$value,false).' /> '. $name . '</label><br />'; 
		}  	
		echo ( isset($args['descr']) ? '<small><label for="'.$option['id'].'">'. $args['descr'] .'</label></small>' : '' );
	}
	
	/** 
	 * Prepares an $option array with name, id and value for this option
	 */
	public function extract_option_data($args) {
		$option = array();
		if ( !empty($args['key']) ) { 
			$option['name'] = $args['name'] . '[' . $args['key'] . ']'; 
			$option['id'] = $args['name'].'-'.$args['key']; 
			$settings = get_option($args['name'],'');
			$option['value'] = ( isset($this->options[$args['name']][$args['key']]) ? $this->options[$args['name']][$args['key']] : '');
		} else { 
			$option['name'] = $args['name']; 
			$option['id'] = $args['name'];
			$option['value'] = ( isset( $this->options[$args['name']] ) ? $this->options[$args['name']] : '');
		}
		return $option;
	}
	

	
	public function validate_options( $input ) {
		// Create our array for storing the validated options
		$output = array();
		// Loop through each of the incoming options
		foreach( $input as $key => $value ) {
			// Check to see if the current option has a value. If so, process it.
			if( isset( $input[$key] ) ) {
				// Strip all HTML and PHP tags and properly handle quoted strings
				$output[$key] = strip_tags( stripslashes( $input[ $key ] ) );
			} // end if
		} // end foreach
		// Return the array processing any additional functions filtered by this action
		return apply_filters( 'wp5bang_validate_options', $output, $input );
	}

}