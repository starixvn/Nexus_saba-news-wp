<?php
ini_set('memory_limit', '256M');
define( 'WP_CACHE', true );
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
 * * ABSPATH
 *
 * @link https://wordpress.org/documentation/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'nova88' );

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
define( 'AUTH_KEY',         'jGzGDR!ep Nll~ X.rMS9osqSK`-v(,fuJhifKmX<cn3: TMw:sZI`Ygne3c-S~|' );
define( 'SECURE_AUTH_KEY',  '%PrOX~6tG@i<K9Lv0!na+5U$_M:21u|Y8*2h}CPB,(M8dl=!kq[,L{A+!;t`*ZXS' );
define( 'LOGGED_IN_KEY',    '(~0C?^Vu54t[Lu:mSFZ@cJ?={n7MhI(yG<W?P_p&~)p7@7KFKv}[)#_H$%TPE0lB' );
define( 'NONCE_KEY',        '9e|q5sV/&Mqb4FX3M^ZTB|87PGR5z1{~_s:n9X?UO|<SO/98pWdEGn7.NVw/E#FO' );
define( 'AUTH_SALT',        'S/BR5h<uAZ]&T[.MQ|]$M>~sllAY]%fS[ii<&dB#,:e9vapxzF.hF8RwRAI-,zBy' );
define( 'SECURE_AUTH_SALT', 'iR[( .24G=p1SnguzcieYQ{#p4FD%f}l7hLsTA&4(/775;sUt[OU.P``SuDmG`^5' );
define( 'LOGGED_IN_SALT',   '6~@8.an(/C?rc6AMK32@v(!GJZk2K`rOg*M3&tQoH7`~1zODK)FD6WqN&(MqNwkf' );
define( 'NONCE_SALT',       '8f)&7FazTzK|B~(no4ja8%BC-3k3-q)3YX Mbu,6|Wvl{XT=$*j`(&#R<Dhzd#PT' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

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
 * @link https://wordpress.org/documentation/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */

/* 網址設定 */
define('WP_HOME','https://newsnova88vn.com');
define('WP_SITEURL','https://newsnova88vn.com');


if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) &&  $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') {
	$_SERVER['HTTPS']='on';
} 
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
