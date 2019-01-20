<?php 
// social buttons?
	
// slider package
	- slider js
	- block

// seo package
	- seo.php

// register & lost pass
	- register.php
	- lostpassword.php
	
	/* 
	* LOGIN / REGISTRATION / LOST PASSWORD 
	*/
	
	// Change email and from
	//add_filter ('wp_mail_from_name', 'Picantina');
	//add_filter ('wp_mail_from', 'info@picantina.bg');

	// Change address returned by wp_lostpassword_url()
	//add_filter( 'lostpassword_url', [$this,'lostpasswordUrl', 10, 2 );

	// Change address returned by wp_registration_url()
	//add_filter( 'register_url', [$this,'registerUrl'], 10, 1 );		
	
	// Change <a> for login displayed in registration shortcode
	//add_filter( 'tat_login_a', [$this,'aLogin'], 10, 1 );	

	// Change address returned by wp_lostpassword_url()
	public function lostpasswordUrl( $lostpassword_url, $redirect ) {
	    return home_url( '/zabravena-parola/?redirect_to=' . $redirect );
	}
	
	// Change address returned by wp_registration_url()
	public function registerUrl( $register_url ) {
	    return home_url( '/registratsia/' );
	}
	
	// Change <a> for login displayed in registration shortcode
	public function aLogin( $login_url ) {
	    return '<a href="#" class="modal-show button" data-modal="login">Вход</a>';
	}
	
	// action listener for shortcode actions
	add_action('wp', 'tat_action_listener');
	function tat_action_listener() {
		if ( isset($_POST['tat-action']) ) {
			$theme_options = get_option('tat_general_options');
			$action = sanitize_text_field($_POST['tat-action']); 
			switch ($action) {
				case 'lostpass':
					if( !empty( $theme_options['lostpass'] ) ) {
						$tat_lostpass = TatPassword::get_instance();			
					}
					break;
				case 'register':
					if( !empty( $theme_options['register'] ) ) {
						$tab_register = TatRegister::get_instance();
					}			
					break;				
			}		
		}
	}


// theme settings
	settings.php


// cookie law 
	- texts from data atr
	- separate from tat.js 