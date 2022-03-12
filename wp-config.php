<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
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
define( 'DB_NAME', 'yourpfb9_live' );

/** MySQL database username */
define( 'DB_USER', 'yourpfb9_live' );

/** MySQL database password */
define( 'DB_PASSWORD', 'c$7@^fzIbQ=g' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

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
define('AUTH_KEY',         'y&UaI@PR5wsu4C.4Ft{+R#F`[6N+B$.0+VG-?RD(Vd84|np0# {Xk17W;*ff+v&g');
define('SECURE_AUTH_KEY',  '3->9]gY6:z>7LBws,,-+>-V~S_Lg.L}sS1XHnE_LQnVj+$>r_}:-V[J&c-S11`&[');
define('LOGGED_IN_KEY',    ')%#r]k|j.ZLnFV9BhSUvp}Q&3jF[=XaPLW6DoP0o13 p{d/W.Q0wSVge_J*/a[Jv');
define('NONCE_KEY',        'mH*UEx?c?el:Ymu1l]W2nl4SR  <}e+.C* 4t&K),am@v|6<u^E&T`t`k|v?/Vl~');
define('AUTH_SALT',        '?[P,kMW4WQdo}.UT(/RBB<KNW%m]]N]N9xXKH+l|[hX8lK7h+Tl+OJ+G>):-,|}b');
define('SECURE_AUTH_SALT', 'wEt2X|+B?9|]&~U[;Jzx!!$->rc>$h` O|9rG,HD;9ql|q**dWrobOj-3|,pt2Gs');
define('LOGGED_IN_SALT',   '{twdi%!9K_ +-yJCe+Yn{q7Qv@6?%k#Akn#l:fnP)bZ%0%XR@%~-myd#!]BAT,ho');
define('NONCE_SALT',       '=O<AG/.%HejB-!y=yyLd/Yg4h{|k>NBc5(opCM6kTEig :mb^{)4zV|h McX%/rq');

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
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
