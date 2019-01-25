<?php
/**
 * Runs on Uninstall of Genesis Credit Text
 *
 * @package   Genesis Credit Text
 * @author    B3Marketing, LLC
 * @license   GPL-2.0+
 * @link      https://rickrduncan.com/pro/wordpress/plugins/genesis-credit-text
 */
 

/**
 * Are you allowed to be here?
 *
 * @since 1.0.0
 */
if ( !defined('ABSPATH') && !defined('WP_UNINSTALL_PLUGIN') ) {
    exit();
}


/**
 * Delete all the options we created with this plugin.
 *
 * @since 1.0.0
 */
$options = array(
	'gct_footer_creds',
	);

foreach ( $options as $option ) {
	if ( get_option( $option ) ) {
		delete_option( $option );
	}
}
?>