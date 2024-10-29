<?php
/**
 * Plugin public (frontend) definition.
 *
 * @since      1.0.0
 *
 * @package    Ascendoor_Logo_Slide
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Public side class.
 *
 * @since 1.0.0
 */
class Ascendoor_Logo_Slide_Public {
	/**
	 * Plugin name.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	private $plugin_name;

	/**
	 * Plugin version.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	private $plugin_version;

	/**
	 * Plugin Ascendoor_Logo_Slide_Public constructor.
	 *
	 * @since 1.0.0
	 *
	 * @param string $plugin_name    Plugin name.
	 * @param string $plugin_version Plugin version.
	 */
	public function __construct( $plugin_name, $plugin_version ) {
		$this->plugin_name    = $plugin_name;
		$this->plugin_version = $plugin_version;

		$this->load_dependencies();
		$this->add_hooks();
	}

	/**
	 * Load public dependent files.
	 *
	 * @since 1.0.0
	 */
	private function load_dependencies() {}

	/**
	 * Request enqueue public facing styles and scripts.
	 *
	 * @since 1.0.0
	 */
	private function add_hooks() {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
	}

	/**
	 * Enqueue public styles and scripts.
	 *
	 * @since 1.0.0
	 */
	public function enqueue_scripts() {
		// Enqueue style.
		wp_enqueue_style( "{$this->plugin_name}-public", ASCENDOOR_LOGO_SLIDE_DIR_URL . 'admin/resources/build/index.css', array(), $this->plugin_version );
		$option_style = get_option( '_ascendoor_logo_slide_style', '' );
		if ( $option_style ) {
			wp_add_inline_style( "{$this->plugin_name}-public", $option_style );
		}

		// Enqueue script.
		wp_enqueue_script( "{$this->plugin_name}-public", ASCENDOOR_LOGO_SLIDE_DIR_URL . 'public/js/ascendoor-logo-slide-public.js', array( 'jquery' ), $this->plugin_version, true );
	}
}
