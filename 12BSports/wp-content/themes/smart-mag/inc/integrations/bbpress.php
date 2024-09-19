<?php
namespace SmartMag\Integrations;
use \Bunyad;

/**
 * Setup BBPress compatibility for the theme.
 */
class Bbpress
{
	public function __construct()
	{	
		add_action('bunyad_theme_init', array($this, 'init'));	
	} 
	
	/**
	 * Setup bbPress hooks
	 */
	public function init()
	{
		// Register support
		add_theme_support('bbpress');

		add_action('wp', [$this, 'change_sidebar']);
	}

	public function change_sidebar()
	{
		if (!function_exists('is_bbpress') || !is_bbpress()) {
			return;
		}

		// Change sidebar for shop page.
		if (is_active_sidebar('smartmag-bbpress')) {
			Bunyad::registry()->sidebar = 'smartmag-bbpress';
		}
	}
}