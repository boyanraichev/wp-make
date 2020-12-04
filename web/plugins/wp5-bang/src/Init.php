<?php 
namespace Plugin;

if (!defined('ABSPATH')) die;

/**
 * The Plugin Init - this is for your own custom functionality.
 */
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
	
		// login through ajax
		// add_action( 'wp_ajax_nopriv_ajax_login', [$this,'ajaxLogin'] );
		
		// Disable the Admin Panel and admin bar, leave access to Admin Ajax for Ajax calls
		add_action( 'admin_init', [$this,'blockAdminAccess'], 1);
		add_action( 'init', [$this,'hideAdminBar'], 1);
	
		// theme settings (optional)
		if (is_admin()) {
			// $settings = Settings::instance();
		}	
	}
	
	/*
	* Hide admin bar
	*/	
	public function hideAdminBar() 
	{
		if (!current_user_can('administrator') AND !current_user_can('see_backend')) {

			show_admin_bar(false);
			
		}
	}
	
	/*
	* Block admin access
	*/	
	public function blockAdminAccess() 
	{
		
		// implement different logic if needed
		if (!current_user_can('administrator') AND !current_user_can('see_backend')) {
								
			$isAjax = (defined('DOING_AJAX') && true === DOING_AJAX) ? true : false;
		
			if(!$isAjax) {
				if (strpos(strtolower($_SERVER['REQUEST_URI']), '/wp-admin') !== false) {
					wp_redirect(get_option('home'));
				}
			}
			
		}
	    
	}
	
	/* 
	*
	* Ajax login
	*
	*/
	public function ajaxLogin() 
	{
		
		if (!is_user_logged_in()) {
			
			/*
			'action': 'ajaxlogin', //calls wp_ajax_nopriv_ajaxlogin
	        'username': , 
	        'password': , 
	        'remember': ,
	        'redirect': ,
	        'bang-security': 'das-login-nonce'
	        */
	        
		    check_ajax_referer( 'das-login-nonce', 'bang-security' );
		
		    $info = [];
		    $info['user_login'] = sanitize_user($_POST['username'],true);
		    
		    if (config('theme.email_login')) {
			    $user = get_user_by('email',$info['user_login']);
				if(!empty($user->user_login)) {
					$info['user_login'] = $user->user_login;
				}
			}
			
		    $info['user_password'] = $_POST['password'];
		    $info['remember'] = ( !empty($_POST['remember']) ? true : false );
		    
		    $redirect = ( !empty($_POST['redirect']) ? esc_url($_POST['redirect']) : false );
		
		    $ssl = is_ssl();
		    $user_signon = wp_signon( $info, $ssl );
		    
		    if ( is_wp_error($user_signon) ){
		        echo json_encode( [ 'success' => false, 'message' => '<span class="error">'.__('Wrong email or password.', 'wp5-bang').'</span>' ] );
		    } else {
		        echo json_encode( [ 'success' => true, 'message' => __('Logged in...', 'wp5-bang'), 'redirect' => $redirect ] );
		    }
		    
		}
	    die();
	    
	}
}