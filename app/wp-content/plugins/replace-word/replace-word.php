<?php
/*
Plugin Name: Replace Word
Version: 2.0
Plugin URI: http://www.wpbuilderweb.com/product/replace-word/
Description: This plugin is use to replace word from site whithout touch it's code structure. 
Author: Crish
Author URI: http://www.wpbuilderweb.com/
*/
/**
 * 
 * @access      public
 * @since       1.2
 * @return      $content
*/
if ( ! defined( 'ABSPATH' ) ) { 
    exit; // Exit if accessed directly
}
define( 'RWORD_VERSION', '1.2' );

define( 'RWORD_PLUGIN', __FILE__ );

define( 'RWORD_PLUGIN_BASENAME', plugin_basename( RWORD_PLUGIN ) );

define( 'RWORD_PLUGIN_NAME', trim( dirname( RWORD_PLUGIN_BASENAME ), '/' ) );

define( 'RWORD_PLUGIN_DIR', untrailingslashit( dirname( RWORD_PLUGIN ) ) );

define( 'RWORD_PLUGIN_CSS_DIR', RWORD_PLUGIN_DIR . '/css' );

require_once (dirname(__FILE__) . '/rword-admin-settings.php');

function rw_admin_set_scripts(){ // these scripts print on the admin page
	wp_enqueue_script('rw_ui_js', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js' , array('jquery'));
	wp_enqueue_script('rw_customized_js', plugins_url('assets/js/rw_customized_js.js', __FILE__ ) , array('jquery'));
	wp_enqueue_style('rw_styles', plugins_url('assets/css/rw-style.css', __FILE__ ));
}
require_once (dirname(__FILE__) . '/rword-front-action.php');
?>