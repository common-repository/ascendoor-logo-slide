<?php
/**
 * Plugin activate/deactivate definition.
 *
 * @since 1.0.0
 *
 * @package Ascendoor_Logo_Slide
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Plugin Activate and Deactivate.
 *
 * @since 1.0.0
 */
class Ascendoor_Logo_Slide_Activate_Deactivate {

	/**
	 * Plugin activated.
	 *
	 * @since 1.0.0
	 */
	public static function activate() {
		if ( function_exists( 'phpversion' ) ) {
			if ( ! version_compare( phpversion(), '7.0', '>=' ) ) {
				deactivate_plugins( plugin_basename( ASCENDOOR_LOGO_SLIDE_DIR_PATH . 'ascendoor-logo-slide.php' ) );
				wp_die(
					/* translators: 1. PHP Version. */
					sprintf( esc_html__( 'Ascendoor Logo Slide plugin requires PHP version %s or higher.', 'ascendoor-logo-slide' ), '7.0' ),
					'Plugin Activation PHP Version Error',
					array(
						'response'  => 200,
						'back_link' => true,
					)
				);
			}
		}

		global $wp_version;
		if ( ! version_compare( $wp_version, '5.5', '>=' ) ) {
			deactivate_plugins( plugin_basename( ASCENDOOR_LOGO_SLIDE_DIR_PATH . 'ascendoor-logo-slide.php' ) );
			wp_die(
				/* translators: 1. WordPress Version. */
				sprintf( esc_html__( 'Ascendoor Logo Slide plugin requires WordPress version %s or higher.', 'ascendoor-logo-slide' ), '5.5' ),
				'Plugin Activation WordPress Version Error',
				array(
					'response'  => 200,
					'back_link' => true,
				)
			);
		}
	}

	/**
	 * Plugin deactivated.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {

	}

}
