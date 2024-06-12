<?php
define('WP_CACHE', true); // Added by W3 Total Cache
$home_url = '';
$upload_dir = '';
$db_name = '';
$project = '';
$cdn_host = '';
define("TIMEOUT_CACHE_TERMS",true);

if ( strpos($_SERVER['HTTP_HOST'], 'salons-reno.rw.loc.env') !== false ) : 
	$home_url = 'http://salons-reno.rw.loc.env/';
	$upload_dir = 'wp-content/uploads/salons-reno';
	$db_name = 'salonsreno';
	$project = 'SALONRENO';
elseif ( strpos($_SERVER['HTTP_HOST'], 'salons-deco.rw.loc.env') !== false ) : 
	$home_url = 'http://salons-deco.rw.loc.env/';
	$upload_dir = 'wp-content/uploads/salons-deco';
	$db_name = 'salonsdecodb';
	$project = 'SALONDECO';
elseif ( strpos($_SERVER['HTTP_HOST'], 'salons-pms.rw.loc.env') !== false ) : 
	$home_url = 'http://salons-pms.rw.loc.env/';
    $cdn_host = 'salon-pms.rw.webpick.info';
	$upload_dir = 'wp-content/uploads/salons-pms';
	$db_name = 'salonpms';
	$project = 'SALONPMS';
elseif ( strpos($_SERVER['HTTP_HOST'], 'salons-infini_par.rw.loc.env') !== false ) : 
	$home_url = 'http://salons-infini_par.rw.loc.env/';
	$cdn_host = 'paris-infinideco.rw.webpick.info';
	$upload_dir = 'wp-content/uploads/salons-deco';
	$db_name = 'infinideco_par';
	$project = 'INFINIDECO';
elseif ( strpos($_SERVER['HTTP_HOST'], 'salons-infini_dau.rw.loc.env') !== false ) : 
	$home_url = 'http://salons-infini_dau.rw.loc.env/';
	$cdn_host = 'paris-infinideco.rw.webpick.info';
	$upload_dir = 'wp-content/uploads/salons-deco';
	$db_name = 'infinideco_dau';
	$project = 'INFINIDECO';
endif;

$db_pass = 'password';
$db_host = 'mysql';
$db_user = 'root';



define( 'WP_HOME', $home_url );
define( 'WP_SITEURL', $home_url );
define( 'CDN_HOST', $cdn_host );

define('UPLOADS', $upload_dir );

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', $db_name);

/** MySQL database username */
define('DB_USER', $db_user);

/** MySQL database password */
define('DB_PASSWORD', $db_pass);

/** MySQL hostname */
define('DB_HOST', $db_host);

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');


/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '0d5f558732ae485e4717d155190814d9c1d988a6');
define('SECURE_AUTH_KEY',  'ed2c3b6e24e1ca2e15cff370f6960e847490f4c1');
define('LOGGED_IN_KEY',    '41b53fd32ac16f795d5dcb595f1a9c7df7350af8');
define('NONCE_KEY',        '851678b27d65b9c935e69fb726022fb51a2c0e18');
define('AUTH_SALT',        '67b4467f1a0e39a783a181d7164045e397a1e6b0');
define('SECURE_AUTH_SALT', 'f5b88cf95080ef7bd6d1f2bf844e0aba26a57223');
define('LOGGED_IN_SALT',   'd52afbdd2d9627ff83eb62a6fb1c6f0f8019f364');
define('NONCE_SALT',       '008bf57aef43610f37e1bf4ad46b5c0b74b68a04');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

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
//define('WP_DEBUG', false);

define('WP_DEBUG', isset($_GET['debug_mode']) );

// If we're behind a proxy server and using HTTPS, we need to alert Wordpress of that fact
// see also http://codex.wordpress.org/Administration_Over_SSL#Using_a_Reverse_Proxy
/* if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
	$_SERVER['HTTPS'] = 'on';
} */
error_log(0);
/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'detect-mobile.php');
require_once(ABSPATH . 'wp-settings.php');
