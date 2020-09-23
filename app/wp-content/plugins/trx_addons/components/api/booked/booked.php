<?php
/**
 * Plugin support: Booked Appointments
 *
 * @package WordPress
 * @subpackage ThemeREX Addons
 * @since v1.5
 */

// Don't load directly
if ( ! defined( 'TRX_ADDONS_VERSION' ) ) {
	die( '-1' );
}

// Check if plugin is installed and activated
if ( !function_exists( 'trx_addons_exists_booked' ) ) {
	function trx_addons_exists_booked() {
		return class_exists( 'booked_plugin' );
	}
}


// Add shortcodes
//----------------------------------------------------------------------------

// Add shortcodes to Elementor
if ( trx_addons_exists_booked() && trx_addons_exists_elementor() ) {
	require_once TRX_ADDONS_PLUGIN_DIR . TRX_ADDONS_PLUGIN_API . 'booked/booked-sc-elementor.php';
}

// Add shortcodes to VC
if ( trx_addons_exists_booked() && trx_addons_exists_vc() ) {
	require_once TRX_ADDONS_PLUGIN_DIR . TRX_ADDONS_PLUGIN_API . 'booked/booked-sc-vc.php';
}


// Demo data install
//----------------------------------------------------------------------------

// One-click import support
if ( is_admin() ) {
	require_once TRX_ADDONS_PLUGIN_DIR . TRX_ADDONS_PLUGIN_API . 'booked/booked-demo-importer.php';
}

// OCDI support
if ( is_admin() && trx_addons_exists_booked() && trx_addons_exists_ocdi() ) {
	require_once TRX_ADDONS_PLUGIN_DIR . TRX_ADDONS_PLUGIN_API . 'booked/booked-demo-ocdi.php';
}
