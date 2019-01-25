<?php
/*
Plugin Name: 	Genesis Credit Text
Plugin URI: 	https://rickrduncan.com/pro/wordpress/plugins/genesis-credit-text
Description: 	Customize the credits text without touching functions.php file.
Version: 		1.0.0
Author: 		B3Marketing, LLC
Author URI: 	https://rickrduncan.com
License:		GPL2
License URI:  	https://www.gnu.org/licenses/gpl-2.0.html
*/


/**
 * Exit if accessed directly
 *
 * @since 1.0.0
 */
if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * Check to make sure a Genesis child theme is active
 *
 * @since 1.0.0
 */
register_activation_hook( __FILE__, 'gct_require_genesis' );
function gct_require_genesis() {
	
	if ( 'genesis' != basename( TEMPLATEPATH ) ) {
		deactivate_plugins( plugin_basename( __FILE__ ) );
		wp_die( sprintf( __( 'Sorry, but to activate this plugin the <a target="_blank" rel="nofollow" href="%s">Genesis Framework</a> is required.'), 'https://rickrduncan.com/go/get-genesis' ) );
	}
	
}


/**
 * Add plugin 'Settings' link to the Plugins page
 *
 * @since 1.0.0
 */
add_filter( "plugin_action_links_$plugin", 'gct_add_settings_link' );
function gct_add_settings_link( $links ) {
	$plugin = plugin_basename( __FILE__ );
    $settings_link = '<a href="admin.php?page=genesis">' . __( 'Settings' ) . '</a>';
    array_unshift($links, $settings_link);
  	return $links;
}


/**
 * Confirm Genesis is still running
 *
 * Since this is a Genesis specific plugin we only run our code on genesis_init
 * Plugin is installed and at that time we knew that Genesis was installed. Now, before we run any
 * Genesis specific code we test to make certain that Genesis is still running/activated, otherwise we might trigger fatal error.
 *
 * @since 1.0.0
 */
add_action( 'genesis_init', 'gct_init' );
function gct_init() {
	add_action( 'after_setup_theme', 'gct_footer_customizations_after_setup_theme' );
}


/**
 * Now that we know Genesis is still running, execute our footer code
 *
 * @since 1.0.0
 */
function gct_footer_customizations_after_setup_theme(){
	add_filter( 'genesis_theme_settings_defaults', 'gct_custom_footer_defaults' );
	add_action( 'genesis_settings_sanitizer_init', 'gct_sanitization_filters' );
	add_action('genesis_theme_settings_metaboxes', 'gct_footer_settings_box');
	add_action('after_setup_theme', 'gct_remove_footer_filters' );
	add_filter('genesis_footer_output', 'gct_footer_creds_text', 10, 3);
}


/**
 * Register default settings using Genesis shortcodes
 *
 * @since 1.0.0
 */
function gct_custom_footer_defaults( $defaults ) {
 
	$defaults['gct_footer_creds'] = 'Copyright [footer_copyright] [footer_childtheme_link] &amp;middot; [footer_genesis_link] [footer_studiopress_link] &amp;middot; [footer_wordpress_link] &amp;middot; [footer_loginout]';
 
	return $defaults;
}


/**
 * Sanitize input
 *
 * @since 1.0.0
 */
function gct_sanitization_filters() {
	genesis_add_option_filter( 'safe_html', GENESIS_SETTINGS_FIELD, array( 'gct_footer_creds' ) );
}


/**
 * Register metabox
 *
 * @since 1.0.0
 */
function gct_footer_settings_box( $_genesis_theme_settings_pagehook ) {
	add_meta_box( 'gct-genesis-settings', __( 'Genesis Credit Text' ), 'gct_footer_box', $_genesis_theme_settings_pagehook, 'main', 'high' );
}


/**
 * Create input field
 *
 * @since 1.0.0
 */
function gct_footer_box() {
	?>
	<p><?php _e("Enter your custom credits text, including HTML if desired.", 'gct_footer'); ?></p>
	<label>Genesis Credit Text:</label>
	<textarea id="gct_footer_creds" class="large-text" name="<?php echo GENESIS_SETTINGS_FIELD; ?>[gct_footer_creds]" cols="78" rows="8" /><?php echo htmlspecialchars( genesis_get_option('gct_footer_creds') ); ?></textarea>
    <p><?php echo ( '<strong>Default Text:</strong><br /><br /> <code>Copyright [footer_copyright] [footer_childtheme_link] &amp;middot; [footer_genesis_link] [footer_studiopress_link] &amp;middot; [footer_wordpress_link] &amp;middot; [footer_loginout]</code>' ); ?></p>
	<?php
}


/**
 * Remove other filters if they exist. Someone could have already customized credits text inside of functions.php
 *
 * @since 1.0.0
 */
function gct_remove_footer_filters() {
    remove_all_filters( 'genesis_footer_creds_text' );
}


/**
 * And finally, display our custom credits text
 *
 * @since 1.0.0
 */
function gct_footer_creds_text($creds) {
	$custom_creds = '<p class="genesis-credit-txt">' . genesis_get_option('gct_footer_creds') . '</p>';
	if ($custom_creds) {
		return $custom_creds;
	}
	else {
		return $creds;
	}
}