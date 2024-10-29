<?php
/**
 * Plugin shortcode definition.
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
 * Plugin shortcodes.
 *
 * @since 1.0.0
 */
class Ascendoor_Logo_Slide_Shortcodes {

	/**
	 * Ascendoor_Logo_Slide_Shortcodes instance holder.
	 *
	 * @since 1.0.0
	 * @var Ascendoor_Logo_Slide_Shortcodes
	 */
	private static $instance;

	/**
	 * Initialize Ascendoor_Logo_Slide_Shortcodes instance.
	 *
	 * @since 1.0.0
	 * @return Ascendoor_Logo_Slide_Shortcodes   Ascendoor_Logo_Slide_Shortcodes instance.
	 */
	public static function get_instance() {
		if ( ! self::$instance instanceof self ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Ascendoor_Logo_Slide_Shortcodes constructor.
	 *
	 * @since 1.0.0
	 */
	private function __construct() {
		add_shortcode( 'ascendoor_logo_slide', array( $this, 'ascendoor_logo_slide_callback' ) ); // phpcs:ignore
	}


	/**
	 * Shortcode tag 'ascendoor_logo_slide' callback function.
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function ascendoor_logo_slide_callback() {
		$options = get_option( '_ascendoor_logo_slide', array() );

		if ( ! is_array( $options ) || empty( $options ) ) {
			return '';
		}

		ob_start();
		if ( isset( $options['logos'] ) && is_array( $options['logos'] ) && ! empty( $options['logos'] ) ) {
			
			$js_options = array();

			if ( isset( $options['containerStyle'] ) && is_array( $options['containerStyle'] ) && isset( $options['containerStyle']['id'] ) ) {
				$js_options['containerID'] = $options['containerStyle']['id'];
			}

			if ( isset( $options['containerStyle'] ) && is_array( $options['containerStyle'] ) && isset( $options['containerStyle']['className'] ) ) {
				$js_options['containerClass'] = $options['containerStyle']['className'];
			}

			if ( isset( $options['clone'] ) && $options['clone'] ) {
				$js_options['clone'] = $options['clone'];
			}

			if ( isset( $options['cols'] ) && $options['cols'] ) {
				$js_options['cols'] = intval( $options['cols'] );
			}

			if ( isset( $options['gap'] ) && $options['gap'] ) {
				$js_options['gap'] = intval( $options['gap'] );
			}

			if ( isset( $options['slideType'] ) && $options['slideType'] ) {
				$js_options['type'] = $options['slideType'];
			}

			if ( isset( $options['slideRandom'] ) ) {
				$js_options['random'] = $options['slideRandom'];
			}

			if ( isset( $options['slideTime'] ) && $options['slideTime'] ) {
				$js_options['interval'] = $options['slideTime'];
			}

			if ( isset( $options['pauseHover'] ) ) {
				$js_options['pauseHover'] = $options['pauseHover'];
			}

			if ( isset( $options['logoStyle'] ) && is_array( $options['logoStyle'] ) ) {
				if ( isset( $options['logoStyle']['className'] ) ) {
					$js_options['itemClass'] = $options['logoStyle']['className'];
				}
			}

			if ( isset( $options['responsive'] ) && is_array( $options['responsive'] ) ) {
				foreach ( $options['responsive'] as $device => $responsive ) {
					if ( isset( $responsive['cols'] ) ) {
						$js_options['responsive'][ $device ]['cols'] = intval( $responsive['cols'] );
					}

					if ( isset( $responsive['gap'] ) ) {
						$js_options['responsive'][ $device ]['gap'] = intval( $responsive['gap'] );
					}
				}
			}

			?>
			<div class="ascendoor-logo-slide" data-als='<?php echo wp_json_encode( $js_options ); ?>'>
				<?php
				foreach ( $options['logos'] as $logo ) {
					$alt    = $logo['alt'];
					$title  = $logo['title'];
					$url    = $logo['url'];
					$target = $logo['target'];
					$link   = isset( $logo['link'] ) ? $logo['link'] : '';

					if ( $link ) {
						?>
						<a href="<?php echo esc_url( $link ); ?>" target="<?php echo esc_attr( $target ); ?>" title="<?php echo esc_attr( $title ); ?>">
							<img src="<?php echo esc_url( $url ); ?>" alt="<?php echo esc_attr( $alt ); ?>" />
						</a>
					<?php } else { ?>
						<figure title="<?php echo esc_attr( $title ); ?>">
							<img src="<?php echo esc_url( $url ); ?>" alt="<?php echo esc_attr( $alt ); ?>" />
						</figure>
						<?php
					}
				}
				?>
			</div>
			<?php
		}

		return ob_get_clean();
	}
}

// Initialize shortcodes.
Ascendoor_Logo_Slide_Shortcodes::get_instance();
