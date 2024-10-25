<?php
/**
 * Navigation menus and mega menu functionality.
 */
class Bunyad_Theme_Navigation
{
	public function __construct()
	{
		add_action('bunyad_theme_init', array($this, 'init'));	
	}
	
	public function init()
	{
		/**
		 * Mega menu support
		 */
		add_filter('bunyad_mega_menu_end_lvl', array($this, 'attach_mega_menu'));
	}

	/**
	 * Filter Callback: Add our custom mega-menus
	 *
	 * @param array $args
	 */
	public function attach_mega_menu($args)
	{
		extract($args);

		// Have a mega menu?
		if (empty($item->mega_menu)) {
			return $sub_menu;
		}
		
		ob_start();

		switch ($item->mega_menu) {
			case 'category-a':
				$template = 'category-a';
				break;

			// Legacy 'category'
			case 'category':
				$template = 'category-b';
				break;

			default:
				$template = 'links';
				break;
		}
		
		// Get our partial
		Bunyad::core()->partial(
			'partials/header/mega-menu/' . $template,
			compact('item', 'sub_menu', 'sub_items', 'args')
		);
		
		// Return template output
		return ob_get_clean();
	}
	
}


// init and make available in Bunyad::get('navigation')
Bunyad::register('navigation', array(
	'class' => 'Bunyad_Theme_Navigation',
	'init' => true
));