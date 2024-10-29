<?php
/**
 * The admin-specific settings/options functionality of the plugin.
 *
 * @since    1.0.0
 *
 * @package    Ascendoor_Logo_Slide
 */

/**
 * Admin settings page for the logo slide settings.
 *
 * @since 1.0.0
 */
class Ascendoor_Logo_Slide_Admin_Settings {
	/**
	 * Plugin REST API Namespace.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	private $namespace = 'ascendoor-logo-slide/';

	/**
	 * Plugin REST API Version.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	private $rest_version = 'v1';

	/**
	 * Plugin Name.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	private $plugin_name;

	/**
	 * Plugin Version.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	private $plugin_version;

	/**
	 * Logo_Slide_Admin_Settings class constructor.
	 *
	 * @since 1.0.0
	 * @param string $plugin_name Plugin name.
	 * @param string $plugin_version Plugin versions.
	 */
	public function __construct( $plugin_name, $plugin_version ) {
		$this->plugin_name    = $plugin_name;
		$this->plugin_version = $plugin_version;

		add_action( 'admin_menu', array( $this, 'plugin_settings_page' ) );

		add_action( 'admin_enqueue_scripts', array( $this, 'setting_scripts' ) );

		add_action( 'rest_api_init', array( $this, 'api_init' ) );
	}

	/**
	 * Callback for the admin_menu action.
	 * Register plugin settings page as subpage under "Settings" menu.
	 *
	 * @since 1.0.0
	 * @access  public
	 */
	public function plugin_settings_page() {
		add_menu_page( // phpcs:ignore
			esc_html__( 'Ascendoor Logo Slide', 'ascendoor-logo-slide' ),
			esc_html__( 'Ascendoor Logo Slide', 'ascendoor-logo-slide' ),
			'manage_options',
			'ascendoor-logo-slide',
			array( $this, 'settings_logo_slide_cb' ),
			''
		);
	}

	/**
	 * Callback function for "ascendoor-logo-slide" menu.
	 *
	 * @since 1.0.0
	 */
	public function settings_logo_slide_cb() {
		?>
		<div id="ascendoor-logo-slide"></div>
		<?php
	}

	/**
	 * Setting admin section scripts.
	 *
	 * @since 1.0.0
	 */
	public function setting_scripts() {
		// Get current screen.
		$screen              = get_current_screen();
		$admin_scripts_bases = 'toplevel_page_ascendoor-logo-slide';

		// Load scripts on the plugin page only.
		if ( isset( $screen->base ) && ( $screen->base === $admin_scripts_bases ) ) {
			wp_enqueue_style( "{$this->plugin_name}-admin-backend", ASCENDOOR_LOGO_SLIDE_DIR_URL . 'admin/resources/style/backend.css', array( 'wp-components' ), $this->plugin_version );
			wp_enqueue_style( "{$this->plugin_name}-preview", ASCENDOOR_LOGO_SLIDE_DIR_URL . 'admin/resources/build/index.css', array( "{$this->plugin_name}-admin-backend" ), $this->plugin_version );

			wp_enqueue_script( "{$this->plugin_name}-admin-preview", ASCENDOOR_LOGO_SLIDE_DIR_URL . 'admin/js/ascendoor-logo-slide-admin-preview.js', array( 'jquery' ), $this->plugin_version, true );

			// Enqueue media upload scripts if not loaded already.
			if ( ! did_action( 'wp_enqueue_media' ) ) {
				wp_enqueue_media();
			}

			// Scripts dependencies with WordPress React elements and components.
			$dependency = array(
				'lodash',
				'wp-api-fetch',
				'wp-i18n',
				'wp-components',
				'wp-element',
				'wp-editor',
				'wp-data',
				"{$this->plugin_name}-admin-preview",
			);

			wp_enqueue_script( "{$this->plugin_name}-admin-react", ASCENDOOR_LOGO_SLIDE_DIR_URL . 'admin/resources/build/index.js', $dependency, $this->plugin_version, true );
			wp_localize_script( "{$this->plugin_name}-admin-react", 'als', array( 'admin' => admin_url() ) );
		}
	}

	/**
	 * Register REST API route.
	 *
	 * @since 1.0.0
	 */
	public function api_init() {
		// Get plugin settings.
		register_rest_route(
			"{$this->namespace}{$this->rest_version}",
			'/logos',
			array(
				array(
					'methods'             => \WP_REST_Server::READABLE, // phpcs:ignore
					'callback'            => array( $this, 'get_logos' ),
					'permission_callback' => function () { // phpcs:ignore
						return current_user_can( 'manage_options' );
					},
				),
			)
		);

		// Save plugin settings.
		register_rest_route(
			"{$this->namespace}{$this->rest_version}",
			'/save',
			array(
				array(
					'methods'             => \WP_REST_Server::EDITABLE, // phpcs:ignore
					'callback'            => array( $this, 'save_logos' ),
					'permission_callback' => function () { // phpcs:ignore
						return current_user_can( 'manage_options' );
					},
				),
			)
		);
	}

	/**
	 * Get Logo Slide Options for the REST API.
	 *
	 * @since 1.0.0
	 * @return WP_ERROR|WP_REST_Request
	 */
	public function get_logos() {
		// Default options.
		$default_options = array(
			'logos'          => array(),
			'clone'          => false,
			'rows'           => 1,
			'cols'           => 5,
			'gap'            => 10,
			'slideType'      => 'slideup',
			'slideRandom'    => false,
			'slideTime'      => 3000,
			'pauseHover'     => false,
			'containerStyle' => array(
				'margin'         => '0px 0px 0px 0px',
				'marginUnit'     => 'px',
				'padding'        => '0px 0px 0px 0px',
				'paddingUnit'    => 'px',
				'slideClassName' => '',
				'slideID'        => '',
				'background'     => array(
					'type'       => 'none',
					'color'      => '',
					'image'      => '',
					'position'   => 'center center',
					'size'       => 'cover',
					'repeat'     => 'no-repeat',
					'attachment' => 'scroll',
				),
			),
			'logoStyle'      => array(
				'height'         => 0,
				'padding'        => '0px 0px 0px 0px',
				'paddingUnit'    => 'px',
				'slideClassName' => '',
				'slideID'        => '',
			),
			'responsive'     => array(
				'tablet' => array(
					'containerStyle' => array(),
					'logoStyle'      => array(),
				),
				'mobile' => array(
					'containerStyle' => array(),
					'logoStyle'      => array(),
				),
			),
		);

		$options = get_option( '_ascendoor_logo_slide', $default_options );

		$options = $this->recursive_parse_args( $options, $default_options );

		return rest_ensure_response( $options );
	}

	/**
	 * Save Logo Slide Options from REST API.
	 *
	 * @since 1.0.0
	 * @param \WP_REST_Request $request WP_REST_Request Object.
	 * @return array
	 */
	public function save_logos( \WP_REST_Request $request ) { // phpcs:ignore
		$params = $request->get_params();

		if ( is_array( $params ) && isset( $params['settings'] ) ) {
			$settings = $this->recursive_sanitize_settings( $params['settings'] );

			update_option( '_ascendoor_logo_slide', $settings );

			$this->generate_style( $settings );
		}

		$options = get_option( '_ascendoor_logo_slide', array() );

		if ( ! is_array( $options ) ) {
			$options = array();
		}

		return $options;
	}

	/**
	 * Generate and save CSS rules from options.
	 *
	 * @since 1.0.0
	 * @param array $options Settings Options.
	 */
	private function generate_style( $options ) {
		$style = '';

		$container_id       = '';
		$container_class    = '';
		$logo_height        = '';
		$css_style_selector = '.logo-slide-wrapper ';

		if ( is_array( $options['containerStyle'] ) && isset( $options['containerStyle']['id'] ) && $options['containerStyle']['id'] ) {
			$container_id       = $options['containerStyle']['id'];
			$css_style_selector = "#{$container_id} ";
		}

		if ( is_array( $options['containerStyle'] ) && isset( $options['containerStyle']['className'] ) && $options['containerStyle']['className'] ) {
			$container_class     = $options['containerStyle']['className'];
			$css_style_selector .= ".{$container_class} ";
		}

		if ( isset( $options['containerStyle'] ) && is_array( $options['containerStyle'] ) ) {
			$container_style = '';
			$background      = isset( $options['containerStyle']['background'] ) ? $options['containerStyle']['background'] : null;

			if ( is_array( $background ) ) {
				$type = isset( $background['type'] ) ? $background['type'] : null;
				if ( 'color' === $type && isset( $background['color'] ) && is_array( $background['color'] ) ) {
					$rgba_color       = "rgba({$background['color']['r']}, {$background['color']['g']}, {$background['color']['b']}, {$background['color']['a']})";
					$container_style .= "background-color: {$rgba_color};";
				} elseif ( 'image' === $type ) {
					$image = isset( $background['image'] ) ? esc_url( $background['image'] ) : '';

					if ( $image ) {
						$container_style .= "background-image: url('{$image}') !important;";
					}

					if ( isset( $background['size'] ) ) {
						$container_style .= "background-size: {$background['size']} !important;";
					}

					if ( isset( $background['position'] ) ) {
						$container_style .= "background-position: {$background['position']} !important;";
					}

					if ( isset( $background['repeat'] ) ) {
						$container_style .= "background-repeat: {$background['repeat']} !important;";
					}

					if ( isset( $background['attachment'] ) ) {
						$container_style .= "background-attachment: {$background['attachment']} !important;";
					}
				}
			}

			if ( isset( $options['containerStyle']['margin'] ) ) {
				$container_style .= "margin: {$options['containerStyle']['margin']} !important;";
			}

			if ( isset( $options['containerStyle']['padding'] ) ) {
				$container_style .= "padding: {$options['containerStyle']['padding']} !important;";
			}

			if ( $container_style ) {
				$style = "{$css_style_selector} {{$container_style}}";
			}
		}

		if ( isset( $options['logoStyle'] ) && is_array( $options['logoStyle'] ) ) {
			$logo_style = '';

			if ( isset( $options['logoStyle']['padding'] ) && $options['logoStyle']['padding'] ) {
				$style .= "
					{$css_style_selector} .logo-slide-slides .logo-slide-effect a,
					{$css_style_selector} .logo-slide-slides .logo-slide-effect figure { 
						padding: {$options['logoStyle']['padding']} !important; 
					}
				";
			}

			if ( isset( $options['logoStyle']['height'] ) && 0 < intval( $options['logoStyle']['height'] ) ) {
				$logo_height = $options['logoStyle']['height'] . 'px';
				$logo_style .= "height: {$options['logoStyle']['height']}px !important;";
			}

			if ( $logo_style ) {
				$style .= "{$css_style_selector} .logo-slide-slides .logo-slide-effect {{$logo_style}}";
			}
		}

		if ( isset( $options['responsive'] ) && is_array( $options['responsive'] ) ) {
			$responsive = $options['responsive'];

			if ( isset( $responsive['tablet'] ) && isset( $responsive['tablet']['containerStyle'] ) ) {
				$tablet_container_style = '';
				$tablet_logo_style      = '';

				$responsive_container_style = isset( $responsive['tablet']['containerStyle'] ) ? $responsive['tablet']['containerStyle'] : null;

				$responsive_logo_style = isset( $responsive['tablet']['logoStyle'] ) ? $responsive['tablet']['logoStyle'] : null;

				if ( is_array( $responsive_container_style ) ) {
					if ( isset( $responsive_container_style['margin'] ) ) {
						$tablet_container_style .= "margin: {$responsive_container_style['margin']} !important;";
					}

					if ( isset( $responsive_container_style['padding'] ) ) {
						$tablet_container_style .= "padding: {$responsive_container_style['padding']} !important;";
					}

					if ( $tablet_container_style ) {
						$style .= "@media (max-width: 768px) {
							{$css_style_selector} {{$tablet_container_style}}
						}";
					}
				}

				if ( is_array( $responsive_logo_style ) ) {
					if ( isset( $responsive_logo_style['padding'] ) && $responsive_logo_style['padding'] ) {
						$style .= "@media (max-width: 768px) {
							{$css_style_selector} .logo-slide-slides .logo-slide-effect a,
							{$css_style_selector} .logo-slide-slides .logo-slide-effect figure { 
								padding: {$responsive_logo_style['padding']} !important; 
							}
						}";
					}

					if ( isset( $responsive_logo_style['height'] ) && 0 < intval( $responsive_logo_style['height'] ) ) {
						$tablet_logo_style .= "height: {$responsive_logo_style['height']}px !important;;";
					}

					if ( $tablet_logo_style ) {
						$style .= "@media (max-width: 768px) {
							{$css_style_selector} .logo-slide-slides .logo-slide-effect {{$tablet_logo_style}}
						}";
					}
				}
			}

			if ( isset( $responsive['mobile'] ) && isset( $responsive['mobile']['containerStyle'] ) ) {
				$mobile_container_style = '';
				$mobile_logo_style      = '';

				$responsive_container_style = isset( $responsive['mobile']['containerStyle'] ) ? $responsive['mobile']['containerStyle'] : null;

				$responsive_logo_style = isset( $responsive['mobile']['logoStyle'] ) ? $responsive['mobile']['logoStyle'] : null;

				if ( is_array( $responsive_container_style ) ) {
					if ( isset( $responsive_container_style['margin'] ) ) {
						$mobile_container_style .= "margin: {$responsive_container_style['margin']} !important;";
					}

					if ( isset( $responsive_container_style['padding'] ) ) {
						$mobile_container_style .= "padding: {$responsive_container_style['padding']} !important;";
					}

					if ( $mobile_container_style ) {
						$style .= "@media (max-width: 480px) {
							{$css_style_selector} {{$mobile_container_style}}
						}";
					}
				}

				if ( is_array( $responsive_logo_style ) ) {
					if ( isset( $responsive_logo_style['padding'] ) && $responsive_logo_style['padding'] ) {
						$style .= "@media (max-width: 480px) {
							{$css_style_selector} .logo-slide-slides .logo-slide-effect a,
							{$css_style_selector} .logo-slide-slides .logo-slide-effect figure { 
								padding: {$responsive_logo_style['padding']} !important; 
							}
						}";
					}

					if ( isset( $responsive_logo_style['height'] ) && 0 < intval( $responsive_logo_style['height'] ) ) {
						$mobile_logo_style .= "height: {$responsive_logo_style['height']}px !important;;";
					}

					if ( $mobile_logo_style ) {
						$style .= "@media (max-width: 480px) {
							{$css_style_selector} .logo-slide-slides .logo-slide-effect {{$mobile_logo_style}}
						}";
					}
				}
			}
		}

		update_option( '_ascendoor_logo_slide_style', sanitize_textarea_field( $style ) );
	}

	/**
	 * Multi-dimensional array parse.
	 *
	 * @since 1.0.0
	 *
	 * @param array $args Compare array.
	 * @param array $defaults Default array.
	 *
	 * @return array
	 */
	private function recursive_parse_args( $args, $defaults ) {
		$new_args = (array) $defaults;

		foreach ( $args as $key => $value ) {
			if ( is_array( $value ) && isset( $new_args[ $key ] ) ) {
				$new_args[ $key ] = $this->recursive_parse_args( $value, $new_args[ $key ] );
			} else {
				$new_args[ $key ] = $value;
			}
		}

		return $new_args;
	}

	/**
	 * Recursive sanitation for an array.
	 *
	 * @since 1.0.0
	 * @param array $array Array to sanitize.
	 *
	 * @return array
	 */
	private function recursive_sanitize_settings( $array ) {
		foreach ( $array as $key => &$value ) {
			if ( is_array( $value ) ) {
				$value = $this->recursive_sanitize_settings( $value );
			} else {
				if ( 'link' === $key || 'url' === $key || 'image' === $key ) {
					$value = esc_url_raw( $value );
				} elseif ( is_bool( $value ) ) {
					$value = $value;
				} elseif ( is_numeric( $value ) ) {
					if ( is_float( $value ) ) {
						$value = (float) $value;
					} else {
						$value = intval( $value );
					}
				} else {
					$value = sanitize_text_field( $value );
				}
			}
		}

		return $array;
	}

}
