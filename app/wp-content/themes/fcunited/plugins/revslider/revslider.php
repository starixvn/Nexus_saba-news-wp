<?php
/* Revolution Slider support functions
------------------------------------------------------------------------------- */

// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if ( ! function_exists( 'fcunited_revslider_theme_setup9' ) ) {
	add_action( 'after_setup_theme', 'fcunited_revslider_theme_setup9', 9 );
	function fcunited_revslider_theme_setup9() {
		if ( is_admin() ) {
			add_filter( 'fcunited_filter_tgmpa_required_plugins', 'fcunited_revslider_tgmpa_required_plugins' );
		}
	}
}

// Filter to add in the required plugins list
if ( ! function_exists( 'fcunited_revslider_tgmpa_required_plugins' ) ) {
	//Handler of the add_filter('fcunited_filter_tgmpa_required_plugins',	'fcunited_revslider_tgmpa_required_plugins');
	function fcunited_revslider_tgmpa_required_plugins( $list = array() ) {
		if ( fcunited_storage_isset( 'required_plugins', 'revslider' ) && fcunited_storage_get_array( 'required_plugins', 'revslider', 'install' ) !== false && fcunited_is_theme_activated() ) {
			$path = fcunited_get_plugin_source_path( 'plugins/revslider/revslider.zip' );
			if ( ! empty( $path ) || fcunited_get_theme_setting( 'tgmpa_upload' ) ) {
				$list[] = array(
					'name'     => fcunited_storage_get_array( 'required_plugins', 'revslider', 'title' ),
					'slug'     => 'revslider',
					'source'   => ! empty( $path ) ? $path : 'upload://revslider.zip',
					'required' => false,
				);
			}
		}
		return $list;
	}
}

// Check if RevSlider installed and activated
if ( ! function_exists( 'fcunited_exists_revslider' ) ) {
	function fcunited_exists_revslider() {
		return function_exists( 'rev_slider_shortcode' );
	}
}
