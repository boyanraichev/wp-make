<?php
/* TALKING ABOUT BLANK - framework for theme development
/*
/* Shortcode for lost password page */

if (!defined('ABSPATH')) die;



add_shortcode( 'TAT_LOST_PASSWORD', [ 'TatPassword','print_form' ] );

/*
* Singleton class - use get_instance() to only instantiate once per session.
*
* This class handles a user password reset
*/
class TatPassword {

	/** @var The single instance of the class */
	private static $_instance = null;	
	
	public $success = null;
	
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
		
		$this->parse_POST();
		
	}

	public function parse_POST() {
		
		// Password reset enquiry has been sent

		if ( 'POST' == $_SERVER['REQUEST_METHOD'] && isset($_POST['tat-action']) AND $_POST['tat-action']=='lostpass'  AND isset( $_POST['tat-nonce'] ) AND wp_verify_nonce( $_POST['tat-nonce'], 'lost-pass' ) ) {
			// SAVE NEW PASSWORD
			if (isset($_POST['rp_key']) AND isset($_POST['rp_login'])) {

				$rp_key = $_POST['rp_key'];
		        $rp_login = $_POST['rp_login'];
		 
		        $user = check_password_reset_key( $rp_key, $rp_login );
		 
		        if ( ! $user || is_wp_error( $user ) ) { 
		            if ( $user && $user->get_error_code() === 'expired_key' ) {
		                $this->errors->add('expired', __('This link has expired. Please generate a new link.','tablank') ); 
		            } else {
		                $this->errors->add( 'expired',__('This link is not valid. Please generate a new link.','tablank') );
		            }
		        } else {		 
			        if ( empty( $_POST['pass1'] ) ) { 			
		            	$this->errors->add( 'password', __('You need to enter a password.','tablank') );
		            } else {
						 if ( $_POST['pass1'] != $_POST['pass2'] ) {	
						 	// Passwords don't match
			                $this->errors->add( 'password', __('The passwords do not match.','tablank') );
			             } else {
			             	if (strlen($_POST['pass1']) < 8) {
					        	$this->errors->add( 'password', __('The password needs to be at least 8 symbols.','tablank') );
						    } elseif (!preg_match("#[0-9]+#", $_POST['pass1'])) {
						        $this->errors->add( 'password', __('The password should contain at least one number.','tablank') );
						    }		             
			             }            
		            }
		        }
	            if (!$this->errors->get_error_messages()) {
		            reset_password( $user, $_POST['pass1'] );
		            $this->success = __('The password has been changed successfully!','tablank');
		        }
		 
			// OR GENERATE KEY
			} else {
				// check email
				$user_email = ( isset($_POST['user_login']) ? sanitize_email($_POST['user_login']) : '' );
				if ( !$user_email ) {
					$this->errors->add( 'email', __('Please enter an email address.','tablank') );
				} else {
					if (! is_email($user_email) ) {
						$this->errors->add( 'email', __('Please enter a valid email address.','tablank') );		
					} else {
						if ( !email_exists($user_email) ) {
							$this->errors->add( 'email', __('There is no valid account for this email address.','tablank') );
						}
					}
				}
				    
			    $redirect = ( !empty($_POST['redirect']) ? wp_validate_redirect($_POST['redirect'],'') : false );
			    
			    
			    // if no errors - generate link
				if (!$this->errors->get_error_messages('email')) {
					/*
					//$retrieve = retrieve_password();
					
					if ($retrieve) {
						$this->success = __('Изпратихме ви връзка за въстановяване на паролата по имейл!','tablank');
					} else {
						$this->errors->add( 'email', __('Не можахме да генерираме връзка за възстановяване на паролата за този имейл адрес.','tablank') );
					}
					*/
					
					// get user by email
					$user = get_user_by( 'email', $user_email );
					$key = get_password_reset_key( $user );
					
					if ( !is_wp_error( $key ) ) {
						
						$reset_url = home_url( strtok($_SERVER["REQUEST_URI"],'?').'?key='.$key.'&login='.rawurlencode( $user->user_login ) );
				        
				        /* from retrieve_password() */
					        $message = __('Someone has requested a password reset for the following account:') . "\r\n\r\n";
						    $message .= network_home_url( '/' ) . "\r\n\r\n";
						    $message .= sprintf(__('Username: %s'), $user->user_login) . "\r\n\r\n";
						    $message .= __('If this was a mistake, just ignore this email and nothing will happen.') . "\r\n\r\n";
						    $message .= __('To reset your password, visit the following address:') . "\r\n\r\n";
						    $message .= '<' . $reset_url . ">\r\n";
						 
						    if ( is_multisite() )
						        $blogname = $GLOBALS['current_site']->site_name;
						    else
						        $blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
						 
						    $title = sprintf( __('[%s] Password Reset'), $blogname );
						 	$title = apply_filters( 'retrieve_password_title', $title, $user->user_login, $user );
						 	
						 	$message = apply_filters( 'retrieve_password_message', $message, $key, $user->user_login, $user );
						 	
						    if ( $message && !wp_mail( $user_email, wp_specialchars_decode( $title ), $message ) )
						        wp_die( __('The email could not be sent.') . "<br />\n" . __('Possible reason: your host may have disabled the mail() function.') );
					 	// end
						
				        // set message
				        $this->success = __('We have sent an email with an email reset link!','tablank');				        
				    	
					} else {
						// no key was generated - probably wrong user?
						$this->errors->add( 'email', __('We could not generate an email reset link for this email.','tablank') );
					}
				}		
			}
		} 
		
	}
	
	public static function print_form($attributes, $content = null) { 
		
		$self = self::get_instance();
		
		$default_attributes = array(
			'redirect'	=> home_url(),
			'key'		=> '',
			'login'		=> '',
			);
	    $attributes = shortcode_atts( $default_attributes, $attributes );
		
		if ( isset( $_GET['redirect_to'] ) ) {
	        $attributes['redirect'] = wp_validate_redirect( $_GET['redirect_to'], $attributes['redirect'] );
	    }
	    if ( isset( $_GET['login'] ) AND isset( $_GET['key'] ) ) {
            $attributes['login'] = $_GET['login'];
            $attributes['key'] = $_GET['key'];
        }
        if ( isset( $_POST['rp_login'] ) AND isset( $_POST['rp_key'] ) ) {
            $attributes['login'] = $_POST['rp_login'];
            $attributes['key'] = $_POST['rp_key'];
        }
        
		// Show email request form or password reset form?
		$show_request_form = true;
		
		// Password reset key has been provided
		if ( 'GET' == $_SERVER['REQUEST_METHOD'] AND isset($_GET['key']) AND isset($_GET['login']) ) {
		
			$user = check_password_reset_key( $_GET['key'], $_GET['login'] );
	        if ( ! $user OR is_wp_error( $user ) ) {
	            if ( $user && $user->get_error_code() === 'expired_key' ) {
		            $self->errors->add( 'expired', __('This link has expired. Please generate a new link.','tablank') ); 
	            } else {
	                $self->errors->add( 'expired',__('This link is not valid. Please generate a new link.','tablank') ); 
	            }
	        } else {
	        	$show_request_form = false;
	        }
	    }
        
        if ( 'POST' == $_SERVER['REQUEST_METHOD'] AND isset($_POST['rp_key']) AND isset($_POST['rp_login']) ) {
			$show_request_form = false;
		}
        // start output
		ob_start();
		
		// show the request form
		if ($show_request_form) {
							
			if ( empty($self->success) ) {	
				if ( is_user_logged_in() ) { 
					_e('You have logged in to your account. You need to log out before you can reset your password.','tablank');
					echo '<br /><a href="'. wp_logout_url( $_SERVER["REQUEST_URI"] ).'" class="logout" title="'.__('Logout','tablank').'" rel="nofollow">'.__('Logout','tablank').'</a>';
				} else {
					?>
					<form method="post" id="lost-password-form" action="<?php echo strtok($_SERVER["REQUEST_URI"],'?'); ?>"> 
						<?php
						$errors = $self->errors->get_error_messages();
						if (!empty($errors) and is_array($errors)) {
							echo '<p class="errpr">';
								$i= 0;
								foreach ( $errors as $error ) {
									if ($i>0) echo '<br />'; $i++;
									echo $error;
								}
							echo '</p>';
						} else {
							echo '<p>'.__('Did you forget your password? Enter your email address and we will send you a password reset link.','tablank').'</p>';
						}
						?>
						<fieldset>
							<label for="user_login"><?php _e('Email','tablank'); ?></label>
							<input name="user_login" id="user_login" type="text" class="add-user-email <?php if ($self->errors->get_error_messages('email')) { echo 'input-error'; } ?>" value="<?php if ( isset($_POST['user_login']) ) echo stripslashes(esc_attr($_POST['user_login'])); ?>" placeholder="<?php _e('Email','tablank'); ?>" >
						</fieldset>
										
						<button type="submit" class="lostpass-button button"><?php _e( 'Reset Password', 'tablank' ) ?></button>
						<input type="hidden" name="redirect" value="<?php echo esc_url($attributes['redirect']); ?>">
						<input type="hidden" name="tat-action" value="lostpass">
						<?php wp_nonce_field( 'lost-pass', 'tat-nonce') ?>
		
					</form>
					<?php 		
				}			
			} else {
				echo '<div class="success">'.$self->success.'</div>';
			}

		} else { 
		// show the new password form		
			if ( empty($self->success) ) {
				$errors = $self->errors->get_error_messages();
				if ($errors) {
					echo '<div class="errpr">';
						$i= 0;
						foreach ( $errors as $error ) {
							if ($i>0) echo '<br />'; $i++;
							echo $error;
						}
					echo '</div>';
				} else {
					echo '<div class="tips">'.__('Choose a strong password with a minimum of 8 characters, including at least one number.','tablank').'</div>';
				}

				?>
				<form method="post" id="reset-password-form" action="<?php echo strtok($_SERVER["REQUEST_URI"],'?'); ?>" autocomplete="off">
			        
			        <fieldset>
			        	<div>
				            <label for="pass1"><?php _e( 'New Password', 'tablank' ) ?></label>
				            <input type="password" name="pass1" id="pass1" class="input-password" size="20" value="" autocomplete="off" />
			            </div>
			            <div>
				            <label for="pass2"><?php _e( 'Repeat Password', 'tablank' ) ?></label>
				            <input type="password" name="pass2" id="pass2" class="input-password" size="20" value="" autocomplete="off" />
			            </div>
			        </fieldset>
			         
			        <div class="description"><?php // echo wp_get_password_hint(); ?></div>
			         
			        <button type="submit" class="resetpass-button button"><?php _e('Save','tablank'); ?></button>
			        
			        <input type="hidden" id="user_login" name="rp_login" value="<?php echo esc_attr( $attributes['login'] ); ?>" autocomplete="off" />
			        <input type="hidden" name="rp_key" value="<?php echo esc_attr( $attributes['key'] ); ?>" />
			        <input type="hidden" name="redirect" value="<?php echo esc_url($attributes['redirect']); ?>" />
					<input type="hidden" name="tat-action" value="lostpass" />
					<?php wp_nonce_field( 'lost-pass', 'tat-nonce') ?>			        
			    </form>
			    <?php
		// show the login form
			} else {
				echo '<div class="success">'.$self->success.'</div>';
				$args = array( 'redirect' => $attributes['redirect'], 'label_username' => __( 'Email', 'tablank' ) );
				wp_login_form($args);
			}	
		}
		$output = ob_get_contents();
		ob_end_clean();
		return $output;
	}

}