<?php
/*
Plugin Name: Bunyad Demo Import
Plugin URI: https://theme-sphere.com
Description: Modified (fork) version of "One Click Demo Import"
Version: 2.6.3
Author: ThemeSphere & ProteusThemes
Author URI: https://theme-sphere.com
License: GPL3
License URI: http://www.gnu.org/licenses/gpl.html
Text Domain: pt-ocdi
*/

/**
 * This fork was created due to underlying difference between our idea of
 * how the UI should function vs the one in the original plugin.
 * 
 * Main Changes:
 *  - Different UI in class-ocdi-main
 *  - A different AJAX implementation to handle failures better
 *  - Improvements to new_ajax_request_maybe()
 *  - WXRImporter::process_attachment() improvement for skipping redownloads
 *  - Importer.php improvements to add missing xml import logs.
 */

// Exit if accessed directly
if (!defined('ABSPATH')) exit;

/**
 * Main plugin class with initialization tasks.
 */
class Bunyad_Demo_Import_Plugin {
	/**
	 * Constructor for this class.
	 */
	public function __construct() {
		/**
		 * Display admin error message if PHP version is older than 5.3.2.
		 * Otherwise execute the main plugin class.
		 */
		if ( version_compare( phpversion(), '5.3.2', '<' ) ) {
			add_action( 'admin_notices', array( $this, 'old_php_admin_error_notice' ) );
		}
		else {
			// Set plugin constants.
			$this->set_plugin_constants();

			// Composer autoloader.
			require_once PT_OCDI_PATH . 'vendor/autoload.php';

			// Instantiate the main plugin class *Singleton*.
			$pt_one_click_demo_import = OCDI\OneClickDemoImport::get_instance();


			// +EDIT: Menu importer.
			require PT_OCDI_PATH . 'inc/menus.php';
			// Backward compatibility.
			class_alias('OCDI\OneClickDemoImport', 'Bunyad_Demo_Import');

			// Register WP CLI commands
			// +EDIT: Disabled for PHP8.0 issues.
			// if ( defined( 'WP_CLI' ) && WP_CLI ) {
				// WP_CLI::add_command( 'ocdi list', array( 'OCDI\WPCLICommands', 'list_predefined' ) );
				// WP_CLI::add_command( 'ocdi import', array( 'OCDI\WPCLICommands', 'import' ) );
			// }
		}
	}

	/**
	 * Display an admin error notice when PHP is older the version 5.3.2.
	 * Hook it to the 'admin_notices' action.
	 */
	public function old_php_admin_error_notice() {
		$message = sprintf( esc_html__( 'The %2$sOne Click Demo Import%3$s plugin requires %2$sPHP 5.3.2+%3$s to run properly. Please contact your hosting company and ask them to update the PHP version of your site to at least PHP 5.3.2.%4$s Your current version of PHP: %2$s%1$s%3$s', 'pt-ocdi' ), phpversion(), '<strong>', '</strong>', '<br>' );

		printf( '<div class="notice notice-error"><p>%1$s</p></div>', wp_kses_post( $message ) );
	}


	/**
	 * Set plugin constants.
	 *
	 * Path/URL to root of this plugin, with trailing slash and plugin version.
	 */
	private function set_plugin_constants() {
		// Path/URL to root of this plugin, with trailing slash.
		if ( ! defined( 'PT_OCDI_PATH' ) ) {
			define( 'PT_OCDI_PATH', plugin_dir_path( __FILE__ ) );
		}
		if ( ! defined( 'PT_OCDI_URL' ) ) {
			define( 'PT_OCDI_URL', plugin_dir_url( __FILE__ ) );
		}

		// Action hook to set the plugin version constant.
		add_action( 'admin_init', array( $this, 'set_plugin_version_constant' ) );
	}


	/**
	 * Set plugin version constant -> PT_OCDI_VERSION.
	 */
	public function set_plugin_version_constant() {
		if ( ! defined( 'PT_OCDI_VERSION' ) ) {
			$plugin_data = get_plugin_data( __FILE__ );
			define( 'PT_OCDI_VERSION', $plugin_data['Version'] );
		}
	}
}

if (!function_exists('bunyad_demo_import_init')) {

	/**
	 * Detect plugin clash or initialize.
	 */
	function bunyad_demo_import_init() {
		if (
			class_exists('PT_One_Click_Demo_Import') 
			|| class_exists('OCDI_Plugin', false) 
			|| defined('PT_OCDI_PATH')
		) {			
			add_action('admin_notices', function() {
				echo '<div class="notice notice-error"><h2>Plugin Conflict</h2><p>Please de-activate the plugin "One Click Demo Import" as it conflicts with Bunyad Demo Import.</p></div>';
			});
			return;
		}

		// Instantiate the plugin class.
		new Bunyad_Demo_Import_Plugin;
	}
}

add_action('plugins_loaded', 'bunyad_demo_import_init');
