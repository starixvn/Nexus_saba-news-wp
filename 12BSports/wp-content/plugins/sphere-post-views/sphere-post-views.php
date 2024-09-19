<?php
/**
 * Plugin Name: Sphere Post Views
 * Description: Post views counters for ThemeSphere themes, slightly based on WP Popular Posts.
 * Plugin URI: https://theme-sphere.com
 * Author: ThemeSphere, asadkn, Hector Cabrera
 * Author URI: https://theme-sphere.com
 * Version: 1.0.1
 * License: GPLv2 or later
 * Requires at least: 5.5
 * Requires PHP: 7.1
 */
defined('ABSPATH') || exit;

/**
 * Launch the plugin.
 */
require_once __DIR__ . '/inc/plugin.php';

$plugin = \Sphere\PostViews\Plugin::get_instance();
$plugin->plugin_file = __FILE__;
$plugin->setup();

/**
 * Register activation and deactivation hooks.
 */
register_activation_hook(__FILE__, ['\Sphere\PostViews\Activator', 'activate']);
register_deactivation_hook(__FILE__, ['\Sphere\PostViews\Deactivator', 'deactivate']);
