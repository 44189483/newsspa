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
define('DB_NAME', 'microuni_eg');

/** MySQL database username */
define('DB_USER', 'root');//microuni_newsspa

/** MySQL database password */
define('DB_PASSWORD', '');//bgm=S-]$d5KJ

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
define('AUTH_KEY',         '17jqc,nh*$_UByM9P^}Ql35IKzi;j$_-9j]5&;%FVE/RWD;wV} sKA}bZ[DblZ4r');
define('SECURE_AUTH_KEY',  '@#Y)Z>,PT|)MMcN>!~G9wfG]?ArAbBPn4A~Y,:5j:kd8VraS5d7B7?#&=55Xw2N.');
define('LOGGED_IN_KEY',    ']IxQ`!$cIyXn?$Fiu) ^>ACwBS9FF, DtVNpL,-QB4[{=L>E#npqs^1u`w)C^3d$');
define('NONCE_KEY',        'US*D1#&^~:^M 9&US{~mFZ|uo{zw{DBBC3fp<-$*^`fD4,gUyT}TXkU~6NAz}*8o');
define('AUTH_SALT',        'hPYV}S-fEXoayv<PeafmmM|?;&-b5mNzzyY/GfTkHD/6M&Wz-6p.*!WQHi9~)9aH');
define('SECURE_AUTH_SALT', '{xpOuV]ChpJ6Eq4vj;L2wwTwxtTGTKBw&@-P<Oh.P:-rm6VKl9]Ck`(HXNysj-za');
define('LOGGED_IN_SALT',   'iC:lA71JKFtFJ}J}=1R.2i!d8BW7VXQ`T>*Zy6>wuW$Jiw-I%a>`oI-*q)&Y!KfJ');
define('NONCE_SALT',       'zMjJ~Ur=w}c~Yowq*]U[oO13-9_Up)KJh(wrg8hM=@ibfjPcMgk0{yQTn:-w6LN,');

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
