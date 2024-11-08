<?php
/**
 * On plugin uninstall.
 *
 * @since 1.0.0
 *
 * @package Ascendoor_Logo_Slide
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

// If current user cannot uninstall plugin, exit.
if ( ! current_user_can( 'install_plugins' ) ) {
	exit;
}

delete_option( '_ascendoor_logo_slide_style' );
delete_option( '_ascendoor_logo_slide' );
