<?php
/**
 * A walker to add custom menu fields
 */
class Bunyad_MenuWalkerEdit extends Walker_Nav_Menu_Edit 
{
	public $locations = [];
	public $current_menu;
	
	public function __construct() 
	{
		// Create an [3] => ['main', 'header'] type of array
		$locations = (array) get_nav_menu_locations();
		foreach ($locations as $key => $id) {
			
			if (!isset($this->locations[$id])) {
				$this->locations[$id] = [];
			}
			
			array_push($this->locations[$id], $key);
		}

		// Only adds to Appearance > Menus. For customizer, the hook needed is:
		// wp_nav_menu_item_custom_fields_customize_template
		add_action('wp_nav_menu_item_custom_fields', [$this, 'the_custom_fields'], 10, 3);
	}
	
	/**
	 * Identify the current menu and add custom fields if needed.
	 */
	public function start_el(&$output, $item, $depth = 0, $args = [], $id = 0) 
	{
		// get current menu id
		if (!$this->current_menu) {
			$menu = wp_get_post_terms($item->ID, 'nav_menu');
			
			if (!empty($menu[0])) {
				$this->current_menu = $menu[0]->term_id;
			}
			
			if (!$this->current_menu && $_REQUEST['menu']) {
				$this->current_menu = $_REQUEST['menu'];
			}
		}
		
		$item_output = '';
				
		parent::start_el($item_output, $item, $depth, $args, $id);

		/**
		 * Only needed for older versions. Handled by native hook wp_nav_menu_item_custom_fields for later.
		 */
		if (!did_action('wp_nav_menu_item_custom_fields')) {
            
			ob_start();
            do_action('wp_nav_menu_item_custom_fields', $item->ID, $item, $depth, $args);
			$fields = ob_get_clean();
			
			// Add new fields before <div class="menu-item-actions description-wide submitbox">
            if ($fields) {
                $item_output = preg_replace('/(?=<div[^>]+class="[^"]*submitbox)/', $fields, $item_output);
            }
		}
		
		$output .= $item_output;
	}
	
	/**
	 * Action Callback: Output the custom fields.
	 */
	public function the_custom_fields($item_id, $item, $depth = 0) 
	{
		echo $this->get_custom_fields($item, $depth);
	}
	
	/**
	 * Get custom fields for this menu item.
	 */
	public function get_custom_fields($item, $depth = 0)
	{
		$fields = apply_filters('bunyad_custom_menu_fields', []);
		$output = '';
		
		foreach ($fields as $key => $field) {
			
			// Parent menu field only?
			if (!empty($field['parent_only']) && $depth > 0) {
				continue;
			}
			
			// Only applies to a specific location?
			if (!empty($field['locations']) && !empty($this->locations[ $this->current_menu ]) 
				&& !array_intersect($this->locations[ $this->current_menu ], $field['locations'])
			) {
					continue;
			}
			
			// Relevant field values.
			$name = 'menu-item-' . esc_attr($key) . '[' . $item->ID . ']';
			$value = esc_attr($item->{$key});
			
			// Use renderer or a template?
			if (is_array($field['element'])) {
				
				/** @var $renderer Bunyad_Admin_OptionRenderer */
				$renderer = Bunyad::factory('admin/option-renderer');
				
				if ($field['element']['type'] == 'select') {
					$template = $renderer->render_select(
						array_merge(
							[
								'name'  => $name, 
								'value' => $value
							], 
							$field['element']
						)
					);
				}
			}
			else {
				// String template.
				$template = str_replace(
					['%id%', '%name%', '%value%'], 
					[$item->ID, $name, $value], 
					$field['element']
				);
			}
			
			$output .= '
			<p class="field-custom description description-wide">
				<label for="edit-menu-item-subtitle-' . esc_attr($item->ID) . '">
					' . $field['label'] . '<br />' . $template  . '
				</label>
			</p>';

			// Add nonce for security.
			$output .= sprintf(
				'<input type="hidden" name="bunyad_menu_fields_nonce" value="%s">',
				wp_create_nonce('bunyad_menu_fields_nonce')
			);
		}
		
		return $output;
	}
}
