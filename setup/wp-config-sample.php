<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'database_name_here' );

/** MySQL database username */
define( 'DB_USER', 'username_here' );

/** MySQL database password */
define( 'DB_PASSWORD', 'password_here' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'put your unique phrase here' );
define( 'SECURE_AUTH_KEY',  'put your unique phrase here' );
define( 'LOGGED_IN_KEY',    'put your unique phrase here' );
define( 'NONCE_KEY',        'put your unique phrase here' );
define( 'AUTH_SALT',        'put your unique phrase here' );
define( 'SECURE_AUTH_SALT', 'put your unique phrase here' );
define( 'LOGGED_IN_SALT',   'put your unique phrase here' );
define( 'NONCE_SALT',       'put your unique phrase here' );

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * Change the default wp_ prefix to improve security
 */
$table_prefix = 'xxx_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false );

// WP post revisions are filling the database quickly - limit them with a sensible number here or set as false to not save revisions
define('WP_POST_REVISIONS', 1);

// the default autosave interval is 60 seconds, which would quickly eat-up your post revisions. Set to a bigger number
define('AUTOSAVE_INTERVAL', 6000);

// setting the site url here, instead of the admin panel, allows you to setup different environments with the same database
define('WP_SITEURL', 'http://' . $_SERVER['HTTP_HOST'] . '/wp');
define('WP_HOME', 'http://' . $_SERVER['HTTP_HOST'] );

// remove the code editor from the WP admin
define( 'DISALLOW_FILE_EDIT', true );

// rename the content folder for avoiding some automated attacks
define('WP_CONTENT_FOLDERNAME', 'content');
define('WP_CONTENT_DIR', dirname( __FILE__ ) . '/' . WP_CONTENT_FOLDERNAME) ;
define('WP_CONTENT_URL', WP_HOME . '/' . WP_CONTENT_FOLDERNAME);

define( 'WP_PLUGIN_DIR', dirname( __FILE__ ) . '/' . 'plugins' );
define( 'WP_PLUGIN_URL', WP_HOME . '/' . 'plugins' );


// this is needed in some environments - if you have issues with FTP (installing plugins etc), uncomment this
// define('FS_METHOD', 'direct');

// this is usually needed with the Bitnami stack, if you have trouble updating wordpress set a temp folder manually
// define('WP_TEMP_DIR', '/Applications/mampstack-7.1.15-0/apps/xxxxxxx/tmp');

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once( ABSPATH . 'wp-settings.php' );

// move themes folder outside content
register_theme_directory( dirname( __FILE__ ) . '/' . 'themes' );

//  Disable pingback.ping xmlrpc method to prevent Wordpress from participating in DDoS attacks
//  More info at: https://docs.bitnami.com/?page=apps&name=wordpress&section=how-to-re-enable-the-xml-rpc-pingback-feature

// remove x-pingback HTTP header
add_filter('wp_headers', function($headers) {
    unset($headers['X-Pingback']);
    return $headers;
});
// disable pingbacks
add_filter( 'xmlrpc_methods', function( $methods ) {
        unset( $methods['pingback.ping'] );
        return $methods;
});
