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

defined( 'WP_HOME' ) || define( 'WP_HOME', 'https://new.lighttn.com' );
defined( 'WP_SITEURL' ) || define( 'WP_SITEURL', 'https://new.lighttn.com' );

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
define( 'AUTH_KEY',          'brPJ7{!<b--*5r-}0vQh{/4]eJ,QeNz5a2drnP;ufZqb-y,t:K]iD|8Aa1JAI}jt' );
define( 'SECURE_AUTH_KEY',   ',*-I/~ud.ncq>LkuU;: 1@[:;5|Ex?/5-(U(2U;UDYP@{@)rk$bd}2,fso|1.H4?' );
define( 'LOGGED_IN_KEY',     '(o.|G#;TeokP&i|NOBf({P;UXAWk-id;~3~HKYyQC{b<J2j?l7sbaD1=rlz|iuiL' );
define( 'NONCE_KEY',         '!`Son5Ae!l08nNe#H}zVE hW}GQ0P+0)]]ZdpPH~zZ-Js}#cVs~@h/GaxPhmWsqI' );
define( 'AUTH_SALT',         'H^8@%=F)}#yWsCPcxRj8Hd{;0,p_Za%Z)cZ(Viqf/E8nceo[+XZ/S@(OA2e>v]TP' );
define( 'SECURE_AUTH_SALT',  'nszwQYGnz +hli6SZ>L`?` *hhFhgB>v{%n5m)(~-yk@k^|~[+4J?dZu?|d<OE|K' );
define( 'LOGGED_IN_SALT',    '~FF.%9p,ftf[Vr61=FuL3#tv%RB/.wne:>9ZT}@|P~^ol}Y22Je/iPBtUB[Wi!ry' );
define( 'NONCE_SALT',        '{3YnjK)puCHHF^Y6m9UvvMG1@fok;w>9p1v%@,VWY@W09&Dq>$a}Z-WPMjDgX${s' );
define( 'WP_CACHE_KEY_SALT', ';NrJVGg;BGDo88>Rmi.RD4j[kJxPVc3/`!VbMqD^#F#w]-rJr)#`>5!78tn}$GG4' );


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
