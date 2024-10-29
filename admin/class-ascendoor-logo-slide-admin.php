<?php
/**
 * Plugin admin (backend) definition.
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
 * Admin side class.
 *
 * @since 1.0.0
 */
class Ascendoor_Logo_Slide_Admin {

	/**
	 * Plugin name.
	 *
	 * @since 1.0.0
	 * @var string $plugin_name Plugin Name.
	 */
	private $plugin_name;

	/**
	 * Plugin version.
	 *
	 * @since 1.0.0
	 * @var string $plugin_version Plugin Version.
	 */
	private $plugin_version;

	/**
	 * Plugin Ascendoor_Logo_Slide_Admin constructor.
	 *
	 * @since 1.0.0
	 *
	 * @param string $plugin_name Plugin name.
	 * @param string $plugin_version Plugin version.
	 * @return void
	 */
	public function __construct( $plugin_name, $plugin_version ) {
		$this->plugin_name    = $plugin_name;
		$this->plugin_version = $plugin_version;

		$this->load_dependencies();
		$this->add_hooks();
	}

	/**
	 * Load admin dependent files.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	private function load_dependencies() {
		require_once ASCENDOOR_LOGO_SLIDE_DIR_PATH . 'admin/includes/class-ascendoor-logo-slide-admin-settings.php'; //phpcs:ignore
		new Ascendoor_Logo_Slide_Admin_Settings( $this->plugin_name, $this->plugin_version );
	}

	/**
	 * Request enqueue admin facing styles and scripts.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	private function add_hooks() {
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
	}

	/**
	 * Enqueue admin styles and scripts.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function enqueue_scripts() {
		// Enqueue style.
		wp_enqueue_style( "{$this->plugin_name}-admin", ASCENDOOR_LOGO_SLIDE_DIR_URL . 'admin/css/ascendoor-logo-slide-admin.css', array(), $this->plugin_version );

		// Enqueue script.
		wp_enqueue_script( "{$this->plugin_name}-admin", ASCENDOOR_LOGO_SLIDE_DIR_URL . 'admin/js/ascendoor-logo-slide-admin.js', array( 'jquery' ), $this->plugin_version, true );
	}
}
