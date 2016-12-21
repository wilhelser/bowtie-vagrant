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
define('DB_NAME', 'wordpress');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'vagrant');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

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
define('AUTH_KEY',         'dh(;*iE`qembbLI=w$z!mGHn ~JAQq~j*<Me}Lx(<djoH,M0N3KM9XAf@d FW<-h');
define('SECURE_AUTH_KEY',  'hcqLD?$E,%5Ck#{8*NS(Du.f4kE4PSuL8sJ#NQ,ZJQ|=}o]QkgKjhdJ73[;@OT0B');
define('LOGGED_IN_KEY',    'IGtqpKhWB*Mk_/Y<m5E>C)0RRC8I>2LVJm=]>|g$2MP8h::KT%JD~e0tR.P?qAMI');
define('NONCE_KEY',        'hsqQ7DEraNzZOs88S/jR~xTF*e@>>8|u]]932KR!Gm2+uj#[h_v$]DRu#H#%F_yJ');
define('AUTH_SALT',        'pO+;ndc2-&aROv@GIzn826o=]Un|sW&@t7Umi3nS{{:3?&q9|}Q7 ebu|R4))}{f');
define('SECURE_AUTH_SALT', 't/O]v.=OYjqub,f@mYa<Ok&aq,C*lD4yq*ZO&$mp-4SaHQO7Jz}<a/h}M@L/L9`?');
define('LOGGED_IN_SALT',   'O8fT:0YkyEHyydd;;C;5.=-1;}%or~ki-gj4Q1VJ*54SSkt+>6-C,McV8mr&TWHt');
define('NONCE_SALT',       'Y6>B4m3RsG$G1hw!BGCHR1(yC]%YK+J6U)(~FA4ei3IX+l@+3sH|TGu{-S0jA@Xx');

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
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
