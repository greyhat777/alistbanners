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
define('WP_CACHE', true); //Added by WP-Cache Manager
define( 'WPCACHEHOME', '/home/alistbanners/public_html/alistbanners.com/wp-content/plugins/wp-super-cache/' ); //Added by WP-Cache Manager
define('DB_NAME', 'alistbanners');

/** MySQL database username */
define('DB_USER', 'alistbanner');

/** MySQL database password */
define('DB_PASSWORD', 'banners559');

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
define('AUTH_KEY',         'Yfud4UbZoUK7BFzvwKq1jXlekoYiwVGcZrcDbOtXjkfUqfsPzWu9IGvJiecIL6Aq');
define('SECURE_AUTH_KEY',  '9ynOfUc6YRvubOJtHkikmle96W1XVK5PVWaWtZv81KUoHsxnTF2p1vxwwSiR2GsH');
define('LOGGED_IN_KEY',    '44SSMzO38OREitwCBnFkrUlEEfzZ4HdfL4zyEfPfh28m4xCkjPyMGGs9KR1stp3I');
define('NONCE_KEY',        'AOQjOfXKXNrJ5W6b7OMJ71IuuWO0SIYYwt4xYI19HLUFsPbnIfu11CaQvP5jnEf8');
define('AUTH_SALT',        'ITnTewtBxjuuf4kXyOldKdY7dfHhgzVgBhVGiMf9Z1uUaC9bMzacI6axIpgowY7p');
define('SECURE_AUTH_SALT', 'mjo3PHHDCrMwFlgCiLCnznpJYHs5QgbPHVm6pZgFonvnUhPeibi8S0iybD5Kn49n');
define('LOGGED_IN_SALT',   'eRTugwXVruuOR8r2lK4QqkXpeFdQrDXEwCfQMSQe0npAEFHK4L9Ar2wFg14cU9K8');
define('NONCE_SALT',       'xoLVAgfzjKwlbyWgjGyjDU7nt7VWFemqVOvteN4xnpBztnzxiUJ1Poh2jiNA00r3');

/**
 * Other customizations.
 */
define('FS_METHOD','direct');define('FS_CHMOD_DIR',0755);define('FS_CHMOD_FILE',0644);
/**define('WP_TEMP_DIR',dirname(__FILE__).'/wp-content/uploads');

/**
 * Turn off automatic updates since these are managed upstream.
 */
define('AUTOMATIC_UPDATER_DISABLED', true);


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
define(‘WP_MEMORY_LIMIT’, ‘256M’);
