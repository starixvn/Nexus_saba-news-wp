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
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'sabasports');

/** MySQL database username */
define( 'DB_USER', 'sabasports');

/** MySQL database password */
define( 'DB_PASSWORD', 'sabasports1234AA');

/** MySQL hostname */
define( 'DB_HOST', '10.99.99.66:33061');

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '');


/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         '0d5a255f9806feebb547120c0772e5630c120637');
define( 'SECURE_AUTH_KEY',  'cbe44c4aeb803660379656bfaf96d5d675babc9c');
define( 'LOGGED_IN_KEY',    'aa5387846101f96b4f37d919c20a2014c83c234f');
define( 'NONCE_KEY',        '6ad7a1f974f4399e3ea9b08290d2e089b9a63ce2');
define( 'AUTH_SALT',        '0341eb698a23b55034305d788195e11a51eac2a2');
define( 'SECURE_AUTH_SALT', '38abddef49ad1d4cdae551170a228cde5f1d05c2');
define( 'LOGGED_IN_SALT',   '4204e62d65ff9b1d10ad7466f33726f5f9abafbb');
define( 'NONCE_SALT',       '661d4fa46fffc06434e6daf69bfef5423feaf043');

/**#@-*/

/**
 * WordPress Database Table prefix.
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
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

// If we're behind a proxy server and using HTTPS, we need to alert WordPress of that fact
// see also http://codex.wordpress.org/Administration_Over_SSL#Using_a_Reverse_Proxy
if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
	$_SERVER['HTTPS'] = 'on';
}

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';

