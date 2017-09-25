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
define('DB_NAME', 'aliencyb_wp685');

/** MySQL database username */
define('DB_USER', 'aliencyb_wp685');

/** MySQL database password */
define('DB_PASSWORD', '6Pb7S3]2@y');

/** MySQL hostname */
define('DB_HOST', 'localhost');

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
define('AUTH_KEY',         'rde6zustcvafnprxqn79ufs60efsg4zfivnpxa956cbufreideiie08r3ku8dtwo');
define('SECURE_AUTH_KEY',  '7zttldax4hwr2xyaguxnr7zvegayixfjdzrzizunzdv7pngpnximsbmfxayxp6mq');
define('LOGGED_IN_KEY',    'pvkqk1ea9phmgklguctsvitmf7i7drzcspupcsqiqbotiinrz3yqbeyxmpfgja6p');
define('NONCE_KEY',        '9sedujz1hhqvww9knpl1gizy58wyuty5jjs0ocxm4w4yab2bxhozoom9vgm8x3ey');
define('AUTH_SALT',        'c1vygykt1b2lhq18yjtoiusdvzavdmgmrykmw7b6y0ruqfvh6jdqpzsbsxnyxkwy');
define('SECURE_AUTH_SALT', 'agi9ftllyqcvl9tiaiogjdxirjjpi5ywwnq1smlrljpxyothyoamrb8wwyldy4ap');
define('LOGGED_IN_SALT',   'ivg2fupu0rc4izxo0ix5wsewwliucpbt1oo0tq8ekc8ggblbd8efz9r8mmdam2xo');
define('NONCE_SALT',       'ig4tqzrklobcjncpmenmq36pazzv2irormej1bfnzda1sg7hhx6f5z6o691r9gzu');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp685_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', true);
//define('WP_DEBUG_DISPLAY', true);
//define('WP_DEBUG_LOG', true);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
