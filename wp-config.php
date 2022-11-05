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
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'genesis' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

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
define( 'AUTH_KEY',         '3d8/[zu8HWkBgZMw`-<&Tfop@B*3aUO96t=/j%5(fv&gV>EJTB#cf?F8XrWXmS2U' );
define( 'SECURE_AUTH_KEY',  'lk+KxrZJ33@Y6#J[Bw_#~>)oLM//*,*w]9UvDo2opT+*7ORyA>uzb;+<3><jR-um' );
define( 'LOGGED_IN_KEY',    ']fjVDDH>;wQ ;kH6VlL.^Z^w>6p>Y.EW`z&36jzDP!Xtr84I9{s}:Z76{My!6rzV' );
define( 'NONCE_KEY',        'A1!93XL?=Ky  )0aTvt:d? }(<{Bo3EbOZmvnMn$}mDS 7x/RE[4yRiIE,xE^P![' );
define( 'AUTH_SALT',        '<g@|=V U|T}GYK1E|?7dK&M*xx3Ac81[J,(:Zh+O+7O-vXw?K=m(Lp?XC/S)0Bit' );
define( 'SECURE_AUTH_SALT', 'S/Pme{u)I6P){{gG4P^qo3:LF42EOkTh>xOJWdb:TwyGucbAq>Vt9]/a([nkV}j4' );
define( 'LOGGED_IN_SALT',   'Oj{yduP}!-^{bl J?TfZP`>r5*Z7i1KvRK kl5K06#*H,$ c2jR@7fz~yE Gu<ui' );
define( 'NONCE_SALT',       'E5JobpJ!AIah1El^N3g U~YDFm?.t2eQyt1)2H;y -1N%F <hkF_UN/CXG:[hHc9' );

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
