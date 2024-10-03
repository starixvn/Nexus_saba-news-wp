<?php

namespace Bunyad\Blocks\Base;
use Bunyad\Util;

/**
 * Block options for page builders, widgets and so on.
 */
abstract class Options 
{
	public $options = [];

	/**
	 * @var array Map of option_key => section_key
	 */
	private $options_map = [];

	/**
	 * @var array Commonly used options data.
	 */
	protected $common = [];

	/**
	 * Block name to be used for displaying.
	 */
	public $block_name;

	/**
	 * Configuration for Elementor Widget
	 */
	public $elementor_conf = [];

	/**
	 * Class name of the associated loop block
	 */
	public $block_class;

	/**
	 * Init and setup options.
	 */
	abstract public function init();

	/**
	 * Get option sections.
	 * 
	 * @return array
	 */
	abstract public function get_sections();

	/**
	 * Run only when we're in the page builder.
	 *
	 * @return void
	 */
	public function init_editor()
	{
		// Create a reference map and setup option callbacks.
        foreach ($this->options as $section_key => $section) {
            foreach ($section as $key => $option) {
				$this->options_map[$key] = $section_key;

				// Run the callback for the options, usually for in editor.
				$this->options[$section_key][$key] = $this->do_option_callbacks($option);
            }
		}
		
		// Re-position as needed.
		foreach ($this->options as $section_key => $section) {
            foreach ($section as $key => $option) {
                if (isset($option['position'])) {
                    $this->move_option(
                        $key,
                        $option['position']['of'],
                        $option['position']['at']
                    );
                }
            }
		}
	}

	/**
	 * Get the class name of the block these options are for
	 */
	public function get_block_class()
	{
		if (!$this->block_class) {

			$block_class       = explode('\\', get_class($this));
			$this->block_class = str_replace(
				'_Options',
				'',
				end($block_class)
			);

			// Special case: Loops.
			if ($block_class[ count($block_class) - 2 ] === 'Loops') {
				$this->block_class = 'Loops\\' . $this->block_class;
			}
		}

		$class = '\Bunyad\Blocks\\' . $this->block_class;

		// Fallback if doesn't exist.
		if (!class_exists($class)) {
			$class = '\Bunyad\Blocks\Base\\' . (
				strpos($class, 'Loops') !== false ? 'LoopBlock' : 'Block'
			);
		}

		return $class;
	}

	/**
	 * Add defaults from the relevant block to the options
	 */
	protected function _add_defaults($props = [])
	{
		if (!$props) {
			$class = $this->get_block_class();
			$props = $class::get_default_props();
		}

		foreach ($this->options as $section_key => $section) {
			foreach ($section as $key => $option) {

				// This default should be preserved (can't be overwritten).
				if (!empty($option['default_forced'])) {
					continue;
				}

				// Add default from props.
				if (array_key_exists($key, $props)) {

					$new_value = $props[$key];
                    if (isset($option['default'])) {
						$expected_type = gettype($option['default']);

						// Convert boolean and integers to string if string expected as default.
						if ($expected_type === 'string' && in_array(gettype($new_value), ['boolean', 'integer'])) {
							$new_value = (string) $new_value;
						}

						// Set empty value for switcher with falsey defaults. We want them to be always saved.
						// As they may inherit from global by default.
						// if (!$new_value && $option['type'] === 'switcher') {
						// 	$new_value = '';
						// }
                    }

					$this->options[$section_key][$key]['default'] = $new_value;
				}
			}
		}
	}

	/**
	 * Move an item in the options array.
	 *
	 * @uses \Bunyad\Util\array_insert()
	 * @param string $key      The option key to move. 
	 * @param string $target   Target option key to move before/after.
	 * @param string $position To move 'before' or 'after' the target.
	 * @return void
	 */
	public function move_option($key, $target, $position = 'before')
	{
		$section = $this->options_map[$key];
		$section_target = $this->options_map[$target];

		// Remove it first and update section in map.
		$value = $this->options[$section][$key];
		unset($this->options[$section][$key]);
		$this->options_map[$key] = $section_target;

        // Move it now.
        Util\array_insert(
            $this->options[$section_target],
            $target,
            [$key => $value],
            $position
		);
	}

	/**
	 * Get all the options.
	 * 
	 * @param boolean $key_value Whether to return key => value pairs
	 * @return array
	 */
	public function get_all($key_value = false)
	{
		if (!$this->options) {
			$this->init();
		}

		if (!$key_value) {
			return $this->options;
		}

		// Convert to key value pair.
		$options = [];
		foreach ($this->options as $section) {
			foreach ($section as $key => $option) {
				$options[$key] = $option;
			}
		}

		return $options;
	}

	/**
	 * Remove a registered option from the array
	 * 
	 * @param array $keys Array of keys to remove
	 */
	public function remove_options($keys)
	{
		$to_remove = (array) $keys;

		foreach ($this->options as $sec_key => $section) {
			foreach ($section as $key => $option) {
				if (in_array($key, $to_remove)) {
					unset($this->options[$sec_key][$key]);
				}
			}
		}
	}

	/**
	 * Update an existing option array.
	 *
	 * @param string $key
	 * @param array $value
	 * @return void
	 */
	public function change_option($key, $value)
	{
		$to_change = [$key => $value];

		foreach ($this->options as $sec_key => $section) {
			foreach ($section as $key => $option) {
				if (!isset($to_change[$key])) {
					continue;
				}

				if (is_array($to_change[$key])) {
					$this->options[$sec_key][$key] = array_replace(
						$this->options[$sec_key][$key],
						$to_change[$key]
					);
				} else {
					$this->options[$sec_key][$key] = $to_change[$key];
				}
			}
		}
	}

	/**
	 * Do the callbacks for a specified options. 
	 * 
	 * This will ensure it's called via correct scope and protected methods get called.
	 *
	 * @param array $option
	 * @return array
	 */
	public function do_option_callbacks(array $option)
	{
		if (isset($option['editor_callback']) && is_callable($option['editor_callback'])) {
			$option = call_user_func($option['editor_callback'], $option);
			unset($option['editor_callback']);
		}

		if (isset($option['options']) && is_callable($option['options'])) {
			$option['options'] = call_user_func($option['options']);
		}

		return $option;
	}

	public function get_common_data(array $options = [])
	{
		if (!$this->common) {
			$this->common = new OptionsData;

			// Replace common from theme.
			if (is_callable([\Bunyad::core(), 'get_common_data'])) {
				$options = array_replace($options, (array) \Bunyad::core()->get_common_data('options'));
			}
		}

		if (count($options)) {
			$this->common->append($options);
		}

		return $this->common;
	}
}