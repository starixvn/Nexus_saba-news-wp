<?php
/**
 * Plugin support: Uber Menu
 *
 * @package WordPress
 * @subpackage ThemeREX Addons
 * @since v1.5
 */

// Don't load directly
if ( ! defined( 'TRX_ADDONS_VERSION' ) ) {
	die( '-1' );
}

// Check if plugin installed and activated
if ( !function_exists( 'trx_addons_exists_ubermenu' ) ) {
	function trx_addons_exists_ubermenu() {
		return class_exists('UberMenu');
	}
}
	

// Return true if theme location assigned to UberMenu
if ( !function_exists( 'trx_addons_ubermenu_check_location' ) ) {
	function trx_addons_ubermenu_check_location($loc) {
		$rez = false;
		if (trx_addons_exists_ubermenu()) {
			$theme_loc = ubermenu_op( 'auto_theme_location', 'main' );
			$rez = !empty($theme_loc[$loc]);
		}
		return $rez;
	}
}


// Demo data install
//----------------------------------------------------------------------------

// One-click import support
if ( is_admin() ) {
	require_once TRX_ADDONS_PLUGIN_DIR . TRX_ADDONS_PLUGIN_API . 'ubermenu/ubermenu-demo-importer.php';
}

// OCDI support
if ( is_admin() && trx_addons_exists_ubermenu() && trx_addons_exists_ocdi() ) {
	require_once TRX_ADDONS_PLUGIN_DIR . TRX_ADDONS_PLUGIN_API . 'ubermenu/ubermenu-demo-ocdi.php';
}
