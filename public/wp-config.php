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
 * * Localized language
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

if ( ! function_exists( 'load_env_file' ) ) {
	/**
	 * Load simple KEY=VALUE pairs from a .env file.
	 */
	function load_env_file( $path ) {
		if ( ! is_readable( $path ) ) {
			return;
		}

		$lines = file( $path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES );

		if ( false === $lines ) {
			return;
		}

		foreach ( $lines as $line ) {
			$line = trim( $line );

			if ( '' === $line || '#' === $line[0] || false === strpos( $line, '=' ) ) {
				continue;
			}

			list( $key, $value ) = array_map( 'trim', explode( '=', $line, 2 ) );

			if ( '' === $key ) {
				continue;
			}

			$length = strlen( $value );
			if ( $length >= 2 ) {
				$first_char = $value[0];
				$last_char  = $value[ $length - 1 ];

				if ( ( '"' === $first_char && '"' === $last_char ) || ( "'" === $first_char && "'" === $last_char ) ) {
					$value = substr( $value, 1, -1 );
				}
			}

			if ( false === getenv( $key ) ) {
				putenv( "{$key}={$value}" );
				$_ENV[ $key ]    = $value;
				$_SERVER[ $key ] = $value;
			}
		}
	}
}

load_env_file( dirname( __DIR__ ) . '/.env' );
load_env_file( __DIR__ . '/.env' );

// ** Database settings - You can get this info from your web host ** //
if ( file_exists( __DIR__ . '/wp-config-ddev.php' ) ) {
	require_once __DIR__ . '/wp-config-ddev.php';
}

/** The name of the database for WordPress */
defined( 'DB_NAME' ) || define( 'DB_NAME', getenv( 'DB_NAME' ) ?: '' );

/** Database username */
defined( 'DB_USER' ) || define( 'DB_USER', getenv( 'DB_USER' ) ?: '' );

/** Database password */
defined( 'DB_PASSWORD' ) || define( 'DB_PASSWORD', getenv( 'DB_PASSWORD' ) ?: '' );

/** Database hostname */
defined( 'DB_HOST' ) || define( 'DB_HOST', getenv( 'DB_HOST' ) ?: 'localhost' );

$wp_home    = getenv( 'WP_HOME' ) ?: '';
$wp_siteurl = getenv( 'WP_SITEURL' ) ?: $wp_home;

if ( '' !== $wp_home ) {
	defined( 'WP_HOME' ) || define( 'WP_HOME', $wp_home );
}

if ( '' !== $wp_siteurl ) {
	defined( 'WP_SITEURL' ) || define( 'WP_SITEURL', $wp_siteurl );
}

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
define( 'AUTH_KEY',          '/JAIWTmNzMuh8+gKDsJau5GI8kzMAZK8xTrfZdyfCR1gx/Zbf4FbERTiQa+2r8T3' );
define( 'SECURE_AUTH_KEY',   '4b//BGvLyUEK8awGpgPcUyhVzoHBTtcqNOtmyOL4nx85UvlOBOoL0ZMt/V00G05y' );
define( 'LOGGED_IN_KEY',     '2+6twTGhIq4uUQclOoxtdMDhUsic7Fqg5wsOeFSVyKnPIWvR98bgTvqoW7QKKreP' );
define( 'NONCE_KEY',         'gsbSPqdoRzFUASYDYyFbm3wyCy3IwKejJ+DYYYHYyBJJDx167z8EwLNT0WNceF5m' );
define( 'AUTH_SALT',         'rX3c41Zn9cTSwg9lKzGy9B68sz2gbu6cB3zp/HNMeGiu55jn/DNASGS26Lth6B/X' );
define( 'SECURE_AUTH_SALT',  'jvfdIVqc8MZwpIuSiZZrTubWQ9RqJlB1vkBlAT79McnQSKhW7yJnRHbIFOBw2Tiw' );
define( 'LOGGED_IN_SALT',    'JYe/LD7yQtgw966DWfnwUE3bSi7KsWTAwzOCkeJ/2ebZRM3kohjgwyRunt0ftn3t' );
define( 'NONCE_SALT',        'p4YnTcDxxxm5zK6KycDVrAQozFNJGTZ2/lFNNkFzq+1Mq6rcsZJpG2NqoLFoJcDH' );
define( 'WP_CACHE_KEY_SALT', 'lighttn.local' );


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';


/* Add any custom values between this line and the "stop editing" line. */

// Load the ACF Pro license from the environment so it is not committed to git.
$acf_pro_license = getenv( 'ACF_PRO_LICENSE' ) ?: '';
if ( '' !== $acf_pro_license && ! defined( 'ACF_PRO_LICENSE' ) ) {
	define( 'ACF_PRO_LICENSE', $acf_pro_license );
}

// Enable WP_DEBUG on local development only.
defined( 'WP_DEBUG' ) || define( 'WP_DEBUG', true );
defined( 'WP_DEBUG_LOG' ) || define( 'WP_DEBUG_LOG', true );
defined( 'WP_DEBUG_DISPLAY' ) || define( 'WP_DEBUG_DISPLAY', false );

define( 'WP_MEMORY_LIMIT', '256M' );
define( 'WP_MAX_MEMORY_LIMIT', '512M' );
define( 'WP_POST_REVISIONS', true );



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
// WP_DEBUG is set above in the custom values section.

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
