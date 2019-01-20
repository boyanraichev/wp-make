<?php
/* TALKING ABOUT BLANK - framework for theme development
/*
/* Shortcode for lost password page */

if (!defined('ABSPATH')) die;



add_shortcode( 'TAT_REGISTER', array( 'TatRegister','print_form') );

/*
* Singleton class - use get_instance() to only instantiate once per session.
*
* This class handles a user password reset
*/
class TatRegister {

	/** @var The single instance of the class */
	private static $_instance = null;	
	
	public $success = null;
	
	public $register_fields = [];
	
	// Loads just one instance of the class.
	public static function get_instance() {
		if ( null == self::$_instance ) {
            self::$_instance = new self;
        }
        return self::$_instance;
    }
    
	// Construct
	function __construct() {
	
		$this->errors = new WP_Error();
		
		$this->register_fields = [
			'user_email' => [
				'type' 	=> 'email',
				'label'	=> __('Email','tablank'),
				'id'	=> 'register-email',
				'class' => 'input-1',
				'placeholder'	=> __('Email','tablank'),
				'required'		=> true,
			],
			'first_name' => [
				'type' 	=> 'text',
				'label'	=> __('First Name','tablank'),
				'id'	=> 'register-firstname',
				'class' => 'input-1',
				'placeholder'	=> __('First Name','tablank'),
				'required'		=> true,
				'error' => __('You need to fill-in your first name.','tablank'),
			],
			'last_name' => [
				'type' 	=> 'text',
				'label'	=> __('Last Name','tablank'),
				'id'	=> 'register-lastname',
				'class' => 'input-1',
				'placeholder'	=> __('Last Name','tablank'),
				'required'		=> true,
				'error' => __('You need to fill-in your last name.','tablank'),
			],	
		];
		
		$this->register_fields = apply_filters('tat_register_fields',$this->register_fields );
		
		$this->parse_POST();
		
	}

	public function parse_POST() {
		
		$this->user = [];
			
		// Check if data is being sent
		if ( 'POST' == $_SERVER['REQUEST_METHOD'] && isset($_POST['tat-action']) AND $_POST['tat-action']=='register'  AND isset( $_POST['tat-nonce'] ) AND wp_verify_nonce( $_POST['tat-nonce'], 'user-register' ) ) {
			
			// check email
			if (isset($_POST['user_email'])) { 
				$this->user['user_email'] = sanitize_email($_POST['user_email']); 
			}
			
			if ( !$this->user['user_email'] ) {
				$this->errors->add('user_email',__('Please, enter an email address','tablank'));
			} else {
				if (! is_email($this->user['user_email']) ) {
					$this->errors->add('user_email',__('Please, enter a valid email address','tablank'));		
				} else {
					if ( email_exists($this->user['user_email']) OR username_exists($this->user['user_email'])) {
						$this->errors->add('user_email',__('This email has already been registered on our site','tablank'));
					} else {
						$this->user['user_login'] = $this->user['user_email'];
					}
				}
			}
						
			// check names
			$this->user['first_name'] = ( isset($_POST['first_name']) ? sanitize_text_field( $_POST['first_name'] ) : '' );
			if (!empty($this->register_fields['first_name']['required']) AND empty($this->user['first_name'])) {
				$error_msg = ( isset($this->register_fields['first_name']['error']) ? $this->register_fields['first_name']['error'] : __('This is a required field.','tablank') );
				$this->errors->add('first_name',$error_msg);
			}
			
			$this->user['last_name'] = ( isset($_POST['last_name']) ? sanitize_text_field( $_POST['last_name'] ) : '' );			
		    if (!empty($this->register_fields['last_name']['required']) AND empty($this->user['last_name'])) {
				$error_msg = ( isset($this->register_fields['last_name']['error']) ? $this->register_fields['last_name']['error'] : __('This is a required field.','tablank') );
				$this->errors->add('last_name',$error_msg);
			}   
			
		    $redirect = ( !empty($_POST['redirect']) ? wp_validate_redirect($_POST['redirect'],'') : '' );
		    		    
		    // if no errors - register new user
			if (1 > count($this->errors->get_error_messages()) ) {
				
				// prepare password
				$this->user['user_pass'] = wp_generate_password( 10, false );
				
				// add user to db
				$new_user = wp_insert_user( $this->user );

				if( !is_wp_error($new_user) ) {
										 
					// send email
					$this->wp_new_user_notification( $new_user, $this->user['user_pass'] );
					
					$this->success = true;
					
					if ( $redirect ) {
						wp_redirect( $redirect ); exit;
					}
					
					
				}	
			}
		}
		
	}
	
	public static function print_form($attributes, $content = null) { 
		
		$self = self::get_instance();
		
		$default_attributes = array( 
			'terms_url' => null,
			'redirect'	=> '',
			);
	    $attributes = shortcode_atts( $default_attributes, $attributes );
		
	    if ( isset( $_GET['redirect_to'] ) ) {
	        $attributes['redirect'] = wp_validate_redirect( $_GET['redirect_to'], $attributes['redirect'] );
	    }

		// start output
		ob_start();
		
								
		if ( empty($self->success) ) {	
			if ( is_user_logged_in() && (! current_user_can( 'create_users' ) ) ) { 
				echo '<p class="notification">'.__('You are already registered.','tablank').'</p>';
			} else {
				?>
				<form method="post" id="user-register-form" action="<?php echo strtok($_SERVER["REQUEST_URI"],'?'); ?>"> 
					<fieldset>
						<?php 
						foreach ($self->register_fields as $name => $field) {
							echo $self->prep_field($name,$field);
						}
						?>
					</fieldset>
					
					<?php if ( $attributes['terms_url'] ) { ?>		
						<fieldset>		
							<label for="terms"><input name="terms" id="terms" type="checkbox" value="1" required="required"> <?php _e('I agree to the','kosher'); ?> <a href="<?php echo esc_url($attributes['terms_url']); ?>" target="_blank" class="terms_url"><?php _e('terms of use','kosher'); ?></a></label>
							<?php echo ( ($self->errors->has_errors('terms')) ? $self->errors->errors('terms') : '<br />');  ?>
						</fieldset>
					<?php } ?>
					
					<p class="notification"><?php _e('Your password will be sent to your email address.','tablank'); ?></p>
					
					<button type="submit" class="register-button button"><?php if ( current_user_can( 'create_users' ) ) { _e('Add user','tablank');  } else { _e('Register','tablank'); } ?></button>

					<input type="hidden" name="redirect" value="<?php echo esc_url($attributes['redirect']); ?>">
					<input type="hidden" name="tat-action" value="register">
					<?php wp_nonce_field( 'user-register', 'tat-nonce') ?>
	
				</form><!-- #adduser -->
				<?php 		
			}			
		} else {
			$login_a = '<a href="'.wp_login_url( home_url() ).'" title="'. __('Login','tablank') . '">'. __('Login','tablank') . '</a>';
			$login_a = apply_filters('tat_login_a',$login_a );
			$login_redirect = home_url();
			$login_redirect = apply_filters('tat_login_redirect',$login_redirect );
			?>
			<p class="notification"><?php _e('Your password will be sent to your email address.','tablank'); ?> <?php echo $login_a; ?></p>
			<script>
				jQuery(document).ready(function() {
					if (jQuery('#redirect')) { 
						jQuery('#redirect').val('<?php echo $login_redirect; ?>');
					}
				
				});
			
			</script>
			<?php
			
		}
		$output = ob_get_contents();
		ob_end_clean();
		return $output;
	}
	
	public function prep_field($name,$field) {
		
		$self = self::get_instance();
		
		ob_start();
		
		switch ($field['type']) {
			case 'email':
				$error_msg = $self->errors->get_error_messages($name);
				if (!empty($field['label'])) {
					?><label for="<?php echo $field['id']; ?>"><?php echo $field['label']; ?></label><?php
				}
				?>
				<input type="email" id="<?php echo $field['id']; ?>" name="<?php echo $name; ?>" 
					class="<?php if (!empty($field['class'])) { echo esc_attr($field['class']); } if (!empty($error_msg)) { echo 'input-error'; } ?>"
					value="<?php if (!empty($self->user[$name])) { echo esc_attr($self->user[$name]); }?>"
					placeholder="<?php if (!empty($field['placeholder'])) { echo $field['placeholder']; }?>"
					<?php if (!empty($field['required'])) { echo ' required'; } ?>>
				<?php 
				if (!empty($error_msg)) {
					?><div class="error"><?php echo $error_msg[0]; ?></div><?php
				}
				break;
				
			case 'text':
				$error_msg = $self->errors->get_error_messages($name);
				if (!empty($field['label'])) {
					?><label for="<?php echo $field['id']; ?>"><?php echo $field['label']; ?></label><?php
				}
				?>
				<input type="text" id="<?php echo $field['id']; ?>" name="<?php echo $name; ?>" 
					class="<?php if (!empty($field['class'])) { echo esc_attr($field['class']); } if (!empty($error_msg)) { echo 'input-error'; } ?>"
					value="<?php if (!empty($self->user[$name])) { echo esc_attr($self->user[$name]); }?>"
					placeholder="<?php if (!empty($field['placeholder'])) { echo $field['placeholder']; }?>"
					<?php if (!empty($field['required'])) { echo ' required'; } ?>>
				<?php	
				if (!empty($error_msg)) {
					?><div class="error"><?php echo $error_msg[0]; ?></div><?php
				}		
				break;		
		}
		
		$output = ob_get_contents();
		ob_end_clean();
		return $output;
	
	}
	
	public function wp_new_user_notification( $user_id, $plain_pass ) {
	    
	    $user = get_userdata( $user_id );
	 	
	 	$message = __('Hello','tablank')."\r\n\r\n";
	 	$message .= sprintf(__('Your registration is complete. You can login at %s using the following:','tablank'),wp_login_url())."\r\n\r\n";
	    $message .= __('Username:','tablank').' '.$user->user_login."\r\n";
	    $message .= __('Password:','tablank').' '.$plain_pass."\r\n\r\n";
		$message .= __('Greetings','tablank')."\r\n\r\n";
		$message .= get_bloginfo('name');
		
	    wp_mail($user->user_email, __('Welcome!','tablank'), $message);

	}

}
