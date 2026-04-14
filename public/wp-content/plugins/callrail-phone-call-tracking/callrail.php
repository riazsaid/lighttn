<?php
/*
Plugin Name: CallRail Phone Call Tracking
Plugin URI: http://www.callrail.com/docs/web-integration/wordpress-plugin/
Description: Dynamically swap CallRail tracking phone numbers based on the visitor's referring source.
Author: CallRail, Inc.
Version: 0.5.3
Author URI: http://www.callrail.com
*/

add_action( 'admin_menu', 'callrail_menu' );
add_action( 'admin_notices', 'callrail_admin_notice' );
add_action( 'wp_footer', 'callrail_footer' );

add_action( 'rest_api_init', 'calltrk_register_routes' );

add_shortcode( 'callrail_form', 'callrail_form_shortcode_handler' );

add_filter( 'sanitize_option_masked_id_and_access_key', 'callrail_sanitize_masked_id', 10, 2 );

function callrail_wp_api_key() {
	$key = trim( get_option( 'masked_id_and_access_key' ) );
	return str_replace( 'x', '/', $key );
}

function calltrk_register_routes() {
	register_rest_route(
		'calltrk/v1',
		'/store',
		array(
			'methods'             => 'POST',
			'callback'            => 'calltrk_set_cookie',
			'permission_callback' => '__return_true',
		)
	);
}

function calltrk_set_cookie( WP_REST_Request $request ) {
	$response = new WP_REST_Response( array() );
	$response->set_status( 204 );

	$params = $request->get_json_params();

	$domain   = $params['domain'];
	$duration = $params['duration'];

	$duration = isset( $duration ) ? time() + $duration : time() + 3600;

	$keys = array( 'calltrk_referrer', 'calltrk_landing', 'calltrk_session_id' );
	foreach ( $keys as $key ) {
		if ( array_key_exists( $key, $params ) ) {
			setcookie( $key, $params[ $key ], $duration, '/', $domain );
		}
	}

	return $response;
}

function callrail_menu() {
	add_options_page( 'CallRail Options', 'CallRail', 'manage_options', 'callrail', 'callrail_options' );
}

function callrail_admin_notice() {
	$api_key = callrail_wp_api_key();

	$is_plugins_page = ( substr( $_SERVER['PHP_SELF'], -11 ) == 'plugins.php' );

	if ( $is_plugins_page && ! $api_key && function_exists( 'admin_url' ) ) {
		echo '<div class="error"><p><strong>' .
		sprintf(
			__( '<a href="%s">Enter your WordPress Plugin Key</a> to enable dynamic tracking number insertion.', 'callrail' ),
			admin_url( 'options-general.php?page=callrail' )
		) .
		   '</strong></p></div>';
	}
}

// Sanitizes values to fit a masked id (9 digits) and guard against XSS
function callrail_sanitize_masked_id( $value, $option ) {
	return substr( trim( sanitize_text_field( $value ) ), 0, 9 );
}

function callrail_options() {
	// must check that the user has the required capability
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}

	// Read in existing option value from database
	$masked_id_and_access_key = get_option( 'masked_id_and_access_key' );

	// Check that:
	// 1. The user has posted us some information
	// If they did, this hidden field will be set to 'Y'
	// 2. Verify the nonce to prevent a CSRF attack
	if (
	isset( $_POST['callrail_hidden_field'] ) &&
	$_POST['callrail_hidden_field'] == 'Y' &&
	wp_verify_nonce( $_POST['callrail_nonce'], 'callrail-options' )
	) {

		// Read their posted value
		$masked_id_and_access_key = trim( sanitize_text_field( $_POST['masked_id_and_access_key'] ) );

		// Change the delimiter from x to /
		// x allows double clicking to copy and paste
		$masked_id_and_access_key = str_replace( 'x', '/', $masked_id_and_access_key );

		// Save the posted value in the database
		update_option( 'masked_id_and_access_key', $masked_id_and_access_key );

		// Read back the sanitized value
		$masked_id_and_access_key = get_option( 'masked_id_and_access_key' );

		// Put an settings updated message on the screen
		echo '<div class="updated"><p><strong>Your CallRail settings were saved successfully.</strong></p></div>';
	}

	// Before showing it back to the user, change the delimeter from / to x
	$masked_id_and_access_key = str_replace( '/', 'x', $masked_id_and_access_key );
	?>
	<div class="wrap">
	  <h2>CallRail Settings</h2>
	  <p>Dynamically swap CallRail phone numbers based on the referring source.</p>
	  <form method="POST" action="">
		<input type="hidden" name="callrail_hidden_field" value="Y">
		<?php wp_nonce_field( 'callrail-options', 'callrail_nonce' ); ?>
		<table class="form-table" cellpadding="0" cellspacing="0">
		  <tr valign="top">
			<th scope="row" style="padding-left: 0px">
			  <label for="masked_id_and_access_key">CallRail WordPress Plugin Key</label>
			</th>
			<td>
			  <input name="masked_id_and_access_key" type="text" id="masked_id_and_access_key"
					 class="regular-text code" size="20" value="<?php echo esc_attr($masked_id_and_access_key); ?>" />
			</td>
		  </tr>
		  <tr valign="top">
			<td colspan="2" style="padding-left: 0px">
			  <span class="description">You can find this value in your
				<a href="http://app.callrail.com/wordpress" target="_blank">CallRail account</a>.
			  </span>
			</td>
		  </tr>
		</table>
		<p class="submit">
		  <input type="submit" name="Submit" class="button-primary" value="Save Changes" />
		</p>
	  </form>
	</div>
	<?php
}

function callrail_footer() {
	$api_key = callrail_wp_api_key();

	if ( ! $api_key ) {
		return;
	}

	echo "\r\n<!-- CallRail WordPress Integration -->\r\n";
	echo '<script type="text/javascript">window.crwpVer = 1;</script>';
	$escaped_api_key = esc_js($api_key);
	wp_enqueue_script( 'swapjs', "//cdn.callrail.com/companies/$escaped_api_key/wp-0-5-3/swap.js" );
}

function callrail_form_shortcode_handler( $attributes ) {
	$form_id = esc_attr($attributes['form_id']);

	if ( ! $form_id ) {
		return '';
	}

	return "<div id=\"cr-form-$form_id\"></div>";
}
