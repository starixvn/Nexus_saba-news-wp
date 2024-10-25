<?php
/**
 * Theme Options handler on admin side
 */
class Bunyad_Admin_Options
{
	public $option_key;
	public $options;
	
	public function __construct()
	{
		$this->option_key = Bunyad::options()->get_config('theme_prefix') .'_theme_options';

		// Initialize.
		add_action('admin_init', [$this, 'init']);
		add_action('after_switch_theme', [$this, 'check_version']);
		
		// Cleanup defaults. Caps will be pre-checked.
		add_action('customize_save_after', [$this, 'customizer_save_process']);
	}
	
	/**
	 * Initialize at admin_init hook.
	 */
	public function init()
	{
		// Current user should have sufficient priveleges to continue.
		if (!current_user_can('edit_theme_options')) {
			return;
		}

		// Check for a changed version in non-AJAX only.
		if (!wp_doing_ajax()) {
			$this->check_version();
		}
	}
	
	/**
	 * Check current theme version and run an update hook if necessary
	 */
	public function check_version()
	{
		$option = Bunyad::options()->get_config('theme_prefix') . '_theme_version';

		// Stored version info
		$version_info   = (array) get_option($option);
		$stored_version = !empty($version_info['current']) ? $version_info['current'] : null;

		// Legacy compat: Get from options.
		if (!$stored_version && Bunyad::options()->theme_version) {
			$stored_version = Bunyad::options()->theme_version;
		}
		
		// Update version if necessary.
		if (!version_compare($stored_version, Bunyad::options()->get_config('theme_version'), '==')) {
			
			// This shouldn't happen, but just in case the previous version is known and we're in customizer. 
			// We can't update while in customizer preview. 
			if ($stored_version && function_exists('is_customize_preview') && is_customize_preview()) {
				return wp_die(
					'The theme has a pending update that may cause fatal errors on customizer. Please go back to your WordPress admin area first and then open Customizer again.',
					'Pending Update!'
				);
			}

			// Fire up the hook.
			do_action('bunyad_theme_version_change', $stored_version);

			// Can be filtered to stop the version update in db.
			if (!apply_filters('bunyad_theme_version_update_done', true)) {
				return;
			}
			
			// Update the theme version.
			$version_info['current'] = Bunyad::options()->get_config('theme_version');

			// Add in first install data if missing.
			$version_info['first_install'] = $version_info['first_install'] ?? time();

			if ($stored_version) {
				$version_info['previous'] = $stored_version;
			}
			
			// Update changes in database.
			update_option($option, array_filter($version_info));

			/**
			 * Force recheck plugin updates to get update prompts for required plugins.
			 */
			$transient = (object) get_site_transient('update_plugins');
			$transient->last_checked = time() - DAY_IN_SECONDS;
			set_site_transient('update_plugins', $transient);

			do_action('wp_update_plugins');

			/**
			 * Refresh TGMPA notices to alert of new plugin updates.
			 */
			$id = Bunyad::options()->get_config('theme_prefix') . '_tgmpa';
			delete_metadata('user', null, 'tgmpa_dismissed_notice_' . $id, null, true);
		}
	}
	
	/**
	 * Load options locally for the class
	 * 
	 * @deprecated No longer in use.
	 */
	public function set_options($options = null)
	{
		if ($options) {
			$this->options = $options;
		}
		else if (!$this->options) { 
			
			// Get default options if empty
			$this->options = include get_template_directory() . '/admin/options.php';
		}
		
		return $this;
	}

	/**
	 * Extract elements/fields from the options tree.
	 * 
	 * @param array   $options  Options tree.
	 * @param boolean $sub      Deprecated. Add partial sub-elements in the list.
	 * @param string  $tab_id   Filter using a tab id.
	 */
	public function get_elements_from_tree(array $options, $sub = false, $tab_id = null)
	{
		$elements = [];
		
		foreach ($options as $tab) {
			
			if ($tab_id != null && $tab['id'] !== $tab_id) {
				continue;
			}

			if (empty($tab['sections'])) {
				continue;
			}
			
			foreach ($tab['sections'] as $section) {
				foreach ($section['fields'] as $element) {
					// Pseudo element?
					if (empty($element['name'])) {
						continue;
					}
					
					$elements[$element['name']] = $element;					
				}
			}
		}
		
		return $elements;
	}

	/**
	 * Post-process Save customizer options.
	 * 
	 * This is needed to fix the defaults on customizer as it saves values in DB even when default is used.
	 * 
	 * @todo Refactor to Bunyad_Options::update()
	 */
	public function customizer_save_process()
	{
		// The save options (Just saved by Customizer before this hook)
		$options  = get_option($this->option_key);

		if (empty($options)) {
			return;
		}

		$options = $this->remove_defaults($options);

		// Remove dependencies disabled via context.
		// @deprecated Remove on runtime instead to preserve entered data in customizer.
		// $options = $this->remove_disabled_contexts($options, $elements);

		// Save the updated options
		update_option($this->option_key, $options);
	}

	/**
	 * Remove options that have default value based on provided elements tree.
	 * 
	 * @uses Bunyad::options()
	 * @param array $options   Key => value array.
	 * @param array $elements Optional options tree.
	 */
	public function remove_defaults($options, $elements = [])
	{
		if (!$elements) {
			// Get options and process them to add group pseudo-options and defaults.
			$elements = Bunyad::options()->load_elements(false);
		}

		// Remove defaults
		foreach ($options as $key => $value) {

			// Reset default as isset() is used later.
			unset($default);

			// Unrecognized element. Skip.
			// Note: Not unsetting as it might be a legacy value still needed in options storage.
			if (!isset($elements[$key])) {
				continue;
			}

			// Skip groups and other fields that should not have a value. This is also removed on runtime.
			if (isset($elements[$key]['type']) && in_array($elements[$key]['type'], ['group', 'message'])) {
				unset($options[$key]);
				continue;
			}

			// Default unspecified?
			if (isset($elements[$key]) && isset($elements[$key]['value'])) {
				$default = $elements[$key]['value'];
			}

			if (is_array($value)) {

				foreach ($value as $k => $v) {

					/**
			 		 * For special arrays that have keys in options as social_profile[facebook]
			 		 */
					$ele_key = "{$key}[{$k}]";
					if (isset($elements[$ele_key])) {
						$ele_default = $elements[$ele_key]['value'];

						if ($ele_default == $v) {
							unset($options[$key][$k]);
						}
					}
				}

				// Filter empty entries from devices array. If the default 'value' is known and available.
				if (!empty($elements[$key]['devices']) && isset($default)) {

					$filtered = array_filter($value);

					// If default value is empty, customizer module hasn't run process_options()
					// and there was no manual default value. Remove empty keys / use filtered.
					if (empty($default)) {
						$value = $filtered;
					}
					else {

						// If array is empty or the only value is limit, set to default / remove.
						if (!$filtered || (count($filtered) === 1 && isset($filtered['limit']))) {
							$value = $default;
						}
					}
				}

				// Empty arrays are removed only if the default value is empty too. (below)
				// Otherwise, selecting no checkboxes for example can be a problem.
			}
			
			// Remove default values.
			// Note: Arrays with same keys are equal as it's a loose match. Sortables shouldn't 
			// use string keys but dynamic integer keys.
			//
			// Caveat: Casting to int would mean, '#222' or 'e' == 0 in loose match, pre-php8.
			if (isset($default)) {
				
				// BC: PHP 8+ doesn't match 0 == '' anymore, so do int match.
				// Customizer often may save toggles as '' or 1. 
				// We're using floatval() so a value of 0.x won't be stripped when casted to int.
				// Using floatval() is non-locale-aware unlike (float) and works fine with non-decimals.
				$value = $default === 0 ? floatval($value) : $value;

				if ($default == $value) {
					unset($options[$key]);
				}
			}
		}

		return $options;
	}
	
	/**
	 * Remove disabled dependencies based on context.
	 *
	 * @param array $options
	 * @return array
	 */
	public function remove_disabled_contexts($options, $elements)
	{
		// Override default values on elements.
		foreach ($options as $key => $value) {
			if (isset($elements[$key])) {
				$elements[$key]['value'] = $value;

				// Remove groups and other fields that should not have a value.
				if (isset($elements[$key]['type']) && in_array($elements[$key]['type'], ['group', 'message'])) {
					unset($options[$key]);
				}
			}
		}

		// Note: Separate from previous loop as all values need to be made available beforehand
		// for the method is_context_active.
		foreach ($options as $key => $value) {

			if (!isset($elements[$key]) || !array_key_exists('context', $elements[$key])) {
				continue;
			}

			// Preserved flag - do not remove.
			if (!empty($elements[$key]['preserve'])) {
				continue;
			}

			$context = (array) $elements[$key]['context'];

			if (!$this->is_context_active($context, $elements)) {
				unset($options[$key]);
			}
		}

		return $options;
	}

	/**
	 * Check if an element is contextually active.
	 * 
	 * @param array $contexts  Context tests to conduct.
	 * @param array $elements  Elements expected with values overriden (not defaults).
	 * 
	 * @return boolean
	 */
	public function is_context_active($contexts, $elements)
	{
		$active = null;

		// Empty context means active.
		if (!$contexts) {
			return true;
		}

		foreach ($contexts as $data) {

			$data['relation'] = isset($data['relation']) ? $data['relation'] : 'AND';
			
			// Previous condition failed, continue no more with AND relation.
			if ($active === false && $data['relation'] !== 'OR') { 
				return false;
			}

			// Previous condition passed, stop with OR relation.
			if ($active === true && $data['relation'] === 'OR') {
				return true;
			}

			$expected = $data['value'];
			$value    = $elements[ $data['key'] ]['value'] ?? false;
			$compare  = isset($data['compare']) ? $data['compare'] : '';

			$active = $this->context_compare($value, $expected, $compare);
		}

		return $active;
	}

	/**
	 * Compare current with expected value.
	 * 
	 * @return boolean
	 */
	public function context_compare($value, $expected, $compare)
	{
		if (is_array($expected)) {
			$compare = $compare == '!=' ? 'not in' : 'in';
		}
		else {
			// BC: PHP 8+ doesn't match 0 == '' anymore, so int convert.
			$value    = $expected === 0 ? (int) $value : $value;
			$expected = $value === 0 ? (int) $expected : $expected;
		}

		switch ($compare) {
			case 'in':
			case 'not in':
				$return = in_array($value, $expected);
				return $compare == 'in' ? $return : !$return;

			case '!=':
				return $value != $expected;

			default:
				return $value == $expected;
		}
	}

	/**
	 * Delete / reset options - security checks done outside.
	 */
	public function delete_options($type = null)
	{
		// Empty it all.
		Bunyad::options()
			->set_all([])
			->update();
		
		do_action('bunyad_options_reset');

		return true;
	}
	
}