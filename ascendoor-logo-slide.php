<?php
/**
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @since             1.0.0
 * @package           Ascendoor_Logo_Slide
 *
 * @wordpress-plugin
 * Plugin Name:       Ascendoor Logo Slide
 * Plugin URI:        
 * Description:       Simple logo slide with CSS transition effects.
 * Version:           1.0.0
 * Author:            Ascendoor
 * Author URI:        https://ascendoor.com/
 * License:           GNU General Public License v3.0
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:       ascendoor-logo-slide
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Current plugin version.
 *
 * @since   1.0.0
 */
define( 'ASCENDOOR_LOGO_SLIDE_VERSION', '1.0.0' );

/**
 * Current plugin constants.
 *
 * @since   1.0.0
 */
define( 'ASCENDOOR_LOGO_SLIDE_DIR_URL', plugin_dir_url( __FILE__ ) );
define( 'ASCENDOOR_LOGO_SLIDE_DIR_PATH', plugin_dir_path( __FILE__ ) );

/**
 * Plugin activation.
 *
 * @since   1.0.0
 * @return  void
 */
function ascendoor_logo_slide_activate() {
	if ( ! class_exists( 'Ascendoor_Logo_Slide_Activate_Deactivate' ) ) {
		require_once ASCENDOOR_LOGO_SLIDE_DIR_PATH . 'includes/class-ascendoor-logo-slide-activate-deactivate.php'; // phpcs:ignore
	}

	Ascendoor_Logo_Slide_Activate_Deactivate::activate();
}
register_activation_hook( __FILE__, 'ascendoor_logo_slide_activate' );

/**
 * Plugin deactivation.
 *
 * @since   1.0.0
 * @return  void
 */
function ascendoor_logo_slide_deactivate() {
	if ( ! class_exists( 'Ascendoor_Logo_Slide_Activate_Deactivate' ) ) {
		require_once ASCENDOOR_LOGO_SLIDE_DIR_PATH . 'includes/class-ascendoor-logo-slide-activate-deactivate.php'; // phpcs:ignore
	}

	Ascendoor_Logo_Slide_Activate_Deactivate::deactivate();
}
register_deactivation_hook( __FILE__, 'ascendoor_logo_slide_deactivate' );

/**
 * Begins execution of the plugin.
 *
 * @since   1.0.0
 * @return  void
 */
function ascendoor_logo_slide_plugin_init_callback() {
	/**
	 * The plugin core class.
	 */
	require_once ASCENDOOR_LOGO_SLIDE_DIR_PATH . 'includes/class-ascendoor-logo-slide.php'; // phpcs:ignore

	/**
	 * Begins execution of the plugin.
	 */
	Ascendoor_Logo_Slide::get_instance();
}
add_action( 'ascendoor_logo_slide_plugin_init', 'ascendoor_logo_slide_plugin_init_callback' );


/**
 * Init and run the plugin.
 */
do_action( 'ascendoor_logo_slide_plugin_init' );


