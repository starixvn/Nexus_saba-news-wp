<?php
/**
 * A very fast custom endpoint for AJAX logging.
 * 
 * Note: Plugin hooks will not work as it uses SHORTINIT.
 */

use Sphere\PostViews\Admin\OptionsData;
use Sphere\PostViews\Endpoint;
use Sphere\PostViews\Options;
use Sphere\PostViews\Plugin;

define('DOING_AJAX', true);
define('SHORTINIT', true);

$wp_load = '../../../wp-load.php';

// Send 500 if WordPress cannot be loaded.
if (!file_exists($wp_load)) {
	http_response_code(500);
	exit;
}

require_once $wp_load;

// Required for nonces in the endpoint. See Helper::create_token()
if (!function_exists('wp_hash')) {
	require ABSPATH . WPINC . '/pluggable.php';
}

// Not really needed in the endpoint, but just a polyfill as plugin_dir_url() uses it
// but it's not available in SHORTINIT.
if (!function_exists('plugins_url')) {
	function plugins_url($path = '', $plugin = '') {
		return '';
	}
}

/**
 * Launch the plugin.
 */
require_once __DIR__ . '/inc/plugin.php';

$plugin = Plugin::get_instance();
$plugin->plugin_file = __FILE__;
$plugin->register_autoloader();

$endpoint = new Endpoint(
	new Options(OptionsData::OPTIONS_KEY)
);

// Test to check if 500 or success. Ensures everything loaded.
if (isset($_GET['test'])) {
	die('Successful');
}

$endpoint->update_views();