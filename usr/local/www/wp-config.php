<?php
// ** Database settings ** //
define( 'DB_NAME',     'insert_db_name' );
define( 'DB_USER',     'insert_db_user' );
define( 'DB_PASSWORD', 'insert_db_password' );
define( 'DB_HOST',     'insert_db_host' );
define( 'DB_CHARSET',  'insert_db_charset' );
define( 'DB_COLLATE',  'insert_db_collation' );

/**#@+
 * Authentication unique keys and salts.
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 */
define('AUTH_KEY',         'insert_auth_key');
define('SECURE_AUTH_KEY',  'insert_secure_auth_key');
define('LOGGED_IN_KEY',    'insert_logged_in_key');
define('NONCE_KEY',        'insert_nonce_key');
define('AUTH_SALT',        'insert_auth_salt');
define('SECURE_AUTH_SALT', 'insert_secure_auth_salt');
define('LOGGED_IN_SALT',   'insert_logged_in_salt');
define('NONCE_SALT',       'insert_nonce_salt');
/**#@-*/

/* WordPress database table prefix. */
$table_prefix = 'insert_db_prefix';

/* For developers: WordPress debugging mode. */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the 'stop editing' line. */
define( 'FS_METHOD', 'direct' );
define( 'DISALLOW_FILE_EDIT', true );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
    define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
