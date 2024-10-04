<?php
/**
 * Base class for handling common meta methods.
 */
abstract class Bunyad_Admin_MetaBase
{
	protected $options = [];

	/**
	 * Using _ underscore hides it in custom metabox fields.
	 */
	protected $option_prefix = '_bunyad_';

	/**
	 * Save meta for the object post or taxonomy.
	 * 
	 * @param integer $object_id
	 */
	public function save_meta($object_id)
	{
		do_action('bunyad_meta_before_save', $object_id);

		$options = $this->options;

		// Save all meta data with the right prefix
		foreach ($_POST as $key => $value) {
			
			// Not a meta value with our desired prefix? Skip.
			if (strpos($key, $this->option_prefix) !== 0) {
				continue;
			}

			// Current value
			$meta = $this->get_meta($object_id, $key, true);

			if ($value == '_default') {
				$value = '';
			}

			// The string "0" is valid value, but false/integer 0 aren't.
			$is_empty    = ($value == '');
			$is_default  = false;

			// Remove empty for arrays.
			if (is_array($value)) {
				$value = array_filter($value);
				$is_empty = !count($value);
			}

			// Check if value matches default in options array. The default is in key 'value'.
			if (!empty($options[$key]) && array_key_exists('value', $options[$key])) {
				$is_default = ($value == $options[$key]['value']);
			}
			
			// Add or update metadata
			if (!$is_default && !$is_empty && $meta != $value) {
				
				// allowed_html available for this value in options?
				if (!empty($options[$key]) && array_key_exists('allowed_html', $options[$key])) {
					if (!is_array($value)) {
						$filtered_value = addslashes(
							wp_kses(stripslashes($value), $options[$key]['allowed_html'])
						);
					}
					else {
						$filtered_value = map_deep(
							$value,
							function($value) use ($options, $key) {
								return addslashes(
									wp_kses(stripslashes($value), $options[$key]['allowed_html'])
								);
							}
						);
					}
				}
				else {
					// Default filtered values
					$filtered_value = (current_user_can('unfiltered_html') ? $value : wp_kses_post_deep($value));
				}
				
				// filtered_value is expected to have slashes
				$this->update_meta($object_id, $key, $filtered_value);

			}
			else {

				// get_post_meta() returns '' when it can't find a record.
				$meta_exists = ($meta !== '');

				// Remove empty or default values
				if ($meta_exists && ($is_empty || $is_default)) {
					$this->delete_meta($object_id, $key);
				}
			}
		}
	}

	/**
	 * Delete a meta field.
	 */
	abstract public function delete_meta($object_id, $key);

	/**
	 * Update a meta field.
	 */
	abstract public function update_meta($object_id, $key, $value);
	
	/**
	 * Get meta field value.
	 */
	abstract public function get_meta($object_id, $key, $single = false);
}