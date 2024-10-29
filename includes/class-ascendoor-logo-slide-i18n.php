<?php
/**
 * Plugin language definition.
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
 * Plugin language initialize.
 *
 * @since 1.0.0
 */
class Ascendoor_Logo_Slide_I18n {

	/**
	 * Ascendoor_Logo_Slide_I18n class constructor.
	 *
	 * @since   1.0.0
	 *
	 * @param   string $plugin_name        Name of the plugin.
	 * @param   string $plugin_version     Version of the plugin.
	 */
	public function __construct( $plugin_name, $plugin_version ) {
		add_action( 'init', array( $this, 'load_textdomain' ) );
	}

	/**
	 * Load plugin translation files.
	 *
	 * @since 1.0.0
	 */
	public function load_textdomain() {
		load_plugin_textdomain(
			'ascendoor-logo-slide',
			false,
			'ascendoor-logo-slide/languages/'
		);
	}

}
