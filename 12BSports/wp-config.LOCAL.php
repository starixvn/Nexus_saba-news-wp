<?php
define( 'WP_CACHE', false );
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * Localized language
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', '12B' );

/** Database username */
define( 'DB_USER', 'sabasports' );

/** Database password */
define( 'DB_PASSWORD', 'sabasports1234AA' );

/** Database hostname */
define( 'DB_HOST', '10.18.200.84:3336' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );


/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',          'U?K~-)A?Ti~,~7.&tSgE^#WA$zXcD6^ErNHe5x7.5i=VMH@145R2jGl=c0v&eH{#' );
define( 'SECURE_AUTH_KEY',   'B;l:8jD`87RX,.&}]+6X.W!N9ujuOPlqYCyVMEO$Kx~2r7//a$?cQ~$f&L2@cmjs' );
define( 'LOGGED_IN_KEY',     '1]xNMj(awY3n.7{;NI85O@%F,Lz`_z3*%G|^>Rs>HSTQiU |?*}QOmnO^H7:b].}' );
define( 'NONCE_KEY',         'DIr25$+saFqN/x(y:0f<&m?8qhuj*1!frOf(|)kU*?jff|U40o9Sl3P?XDM<|j8d' );
define( 'AUTH_SALT',         '!SA&|Z<J(8*W?4G&v)`eNO*X{136Y&u~UWzg#Pgq`u~i;uaR{8t1D0%:,J`.IxG0' );
define( 'SECURE_AUTH_SALT',  'hDy5;yLMhnHsNMH;v0!T_~EaiZ!4H:K&~Ep|fBS95#O_2SEgYU@b&y&5;1{l7nl0' );
define( 'LOGGED_IN_SALT',    'kt0Kj!,j`rrFNINeR4:l9XZ>ALCm7dZcyO1_:Gp3L_u)Zf0fz#larTk1b 6_J6rJ' );
define( 'NONCE_SALT',        '~b>9SH;#eTuCJ!(*4{)Ey50a->&+|O ^tpV6>mW=-B{p:tX=})BSD2h4l4fUc:G~' );
define( 'WP_CACHE_KEY_SALT', 'F};jnN;gbiqW3/V%-ax%Aq4Jx#YP_m!a&i*?!#|sH} 5t1*+(c=f!iOyh6zmn/B5' );


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';


/* Add any custom values between this line and the "stop editing" line. */



/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */

define( 'WP_DEBUG', true );
define( 'WP_DEBUG_LOG', true );
define( 'WP_DEBUG_DISPLAY', false );



define( 'FS_METHOD', 'direct' );
define( 'WP_AUTO_UPDATE_CORE', 'minor' );
define( 'POST_ID_REDIRECT', 19676 );
/* That's all, stop editing! Happy publishing. */


if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) &&  $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') {
	$_SERVER['HTTPS']='on';
} 


/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}
define( 'WP_ENABLE_GA', false );
/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
