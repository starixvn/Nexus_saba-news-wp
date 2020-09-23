<?php
/**
 * Plugin support: Essential Grid
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
if ( !function_exists( 'trx_addons_exists_essential-grid' ) ) {
	function trx_addons_exists_essential_grid() {
		return defined('EG_PLUGIN_PATH');
	}
}


// Demo data install
//----------------------------------------------------------------------------

// One-click import support
if ( is_admin() ) {
	require_once TRX_ADDONS_PLUGIN_DIR . TRX_ADDONS_PLUGIN_API . 'essential-grid/essential-grid-demo-importer.php';
}

// OCDI support
if ( is_admin() && trx_addons_exists_essential_grid() && trx_addons_exists_ocdi() ) {
	require_once TRX_ADDONS_PLUGIN_DIR . TRX_ADDONS_PLUGIN_API . 'essential-grid/essential-grid-demo-ocdi.php';
}
