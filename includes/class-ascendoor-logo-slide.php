<?php
/**
 * Plugin core definition.
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
 * Plugin initializer.
 *
 * @since 1.0.0
 */
class Ascendoor_Logo_Slide {
	/**
	 * Ascendoor_Logo_Slide instance holder.
	 *
	 * @since 1.0.0
	 * @var Ascendoor_Logo_Slide
	 */
	private static $instance;

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
	 * Plugin public (frontend) Logo_Slide_Public instance holder.
	 *
	 * @since 1.0.0
	 * @var Ascendoor_Logo_Slide_Public
	 */
	public $plugin_public;

	/**
	 * Plugin admin (backend) Logo_Slide_Admin instance holder.
	 *
	 * @since 1.0.0
	 * @var Ascendoor_Logo_Slide_Admin
	 */
	public $plugin_admin;

	/**
	 * Initialize Ascendoor_Logo_Slide instance.
	 *
	 * @since 1.0.0
	 * @return  Ascendoor_Logo_Slide  Ascendoor_Logo_Slide instance.
	 */
	public static function get_instance() {
		if ( ! self::$instance instanceof self ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Ascendoor_Logo_Slide constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->plugin_name = 'ascendoor-logo-slide';

		if ( defined( 'ASCENDOOR_LOGO_SLIDE_VERSION' ) ) {
			$this->plugin_version = ASCENDOOR_LOGO_SLIDE_VERSION;
		} else {
			$this->plugin_version = '1.0.0';
		}

		$this->load_dependencies();
		$this->load_i18n();
		$this->load_public();
		$this->load_admin();
	}

	/**
	 * Load plugin dependent files.
	 *
	 * @since 1.0.0
	 */
	private function load_dependencies() {
		require_once ASCENDOOR_LOGO_SLIDE_DIR_PATH . 'includes/helper-ascendoor-logo-slide.php'; // phpcs:ignore
		require_once ASCENDOOR_LOGO_SLIDE_DIR_PATH . 'includes/extra/class-ascendoor-logo-slide-shortcodes.php'; // phpcs:ignore
	}

	/**
	 * Load plugin language files.
	 *
	 * @since 1.0.0
	 */
	private function load_i18n() {
		require_once ASCENDOOR_LOGO_SLIDE_DIR_PATH . 'includes/class-ascendoor-logo-slide-i18n.php'; // phpcs:ignore
		new Ascendoor_Logo_Slide_I18n( $this->plugin_name, $this->plugin_version );
	}


	/**
	 * Initialize plugin Public area.
	 *
	 * @since 1.0.0
	 */
	private function load_public() {
		require_once ASCENDOOR_LOGO_SLIDE_DIR_PATH . 'public/class-ascendoor-logo-slide-public.php'; // phpcs:ignore
		$this->plugin_public = new Ascendoor_Logo_Slide_Public( $this->plugin_name, $this->plugin_version );
	}

	/**
	 * Initialize plugin Admin area.
	 *
	 * @since 1.0.0
	 */
	private function load_admin() {
		// Initialize Admin only in admin area.
		require_once ASCENDOOR_LOGO_SLIDE_DIR_PATH . 'admin/class-ascendoor-logo-slide-admin.php'; // phpcs:ignore
		$this->plugin_admin = new Ascendoor_Logo_Slide_Admin( $this->plugin_name, $this->plugin_version );
	}
}
