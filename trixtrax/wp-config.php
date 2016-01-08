<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'wp_trixtrax');

/** MySQL database username */
define('DB_USER', 'wproot');

/** MySQL database password */
define('DB_PASSWORD', 'iAAVE*f|49_&');

/** MySQL hostname */
define('DB_HOST', 'wptrixtrax.co45x9lr3rcx.us-east-1.rds.amazonaws.com');

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
define('AUTH_KEY',         'McDRceJ1`$|o E]SNywFd.O?pCBw<i|]|QPL:op8_D 3p qaYOIs?9J+x=#Ww}Hn');
define('SECURE_AUTH_KEY',  'dU6V:lye:Q3WQ@QW0dLUzW_K1XV%3d)3i5?M|Kzkp!E$ah7;_{|eYu5=tZ#XY|y&');
define('LOGGED_IN_KEY',    '!5{++!YKzKjURbvJqqoBkDKh,G-`3C74{k-4]FmA^y[c2b;|//o$hS,:Qr*T/{6.');
define('NONCE_KEY',        'o==-s.<w?w<]}d~k.e@e6yZ*VW)7H3`JoKy?#r3OAV7^h$x-P.qK68ZwO+UT/n?v');
define('AUTH_SALT',        'N=$7##1pg<t+Y1KeFem(f+9!Uil-6NhTgl2N*UQZ-L/&l1<6<lz TEjzfj90U9(d');
define('SECURE_AUTH_SALT', 'gs-+}p>.?GpxnR-23L_W8e]xe`:Hx_leXbs%!EgRZ w_M!vn7y3/D?gr>v13EXG?');
define('LOGGED_IN_SALT',   'EHas!|tOku+b3D vh..!zhU&w:C*|tzA/1:eQf}wn|HNxTrJxd~yClQVDdMYzQ*K');
define('NONCE_SALT',       '`O|UDwXgqD),mt[H1t vedug4vNRyY/VS7ePvM9Y^{N?ab{bVyP<pVa1<dMGcm[A');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', 'es_ES');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */
define('FS_METHOD', 'direct');
/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
