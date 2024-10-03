<?php

namespace Bunyad\Blocks\Base;

/**
 * Base Blocks Class.
 */
abstract class Block 
{
	/**
	 * @var array Properties/attributes for the block.
	 */
	public $props = [];

	/**
	 * @var array Original props, after filtering by setup_props().
	 */
	protected $orig_props = [];

	/**
	 * @var string Identifier of the block - may also be used for block view.
	 */
	public $id  = '';

	/**
	 * @param array $props
	 */
	public function __construct($props = [])
	{
        if (!is_array($props)) {
			$props = [];
		}
		
		$props = $this->setup_props($props);

		// Save originally provided props, after unknowns are removed.
		$this->orig_props = $props;

		// Override default props with processed props (by setup_props()).
		$this->props = array_replace(
			$this->get_default_props(),
			apply_filters('bunyad_blocks_setup_props', $props, $this->id)
		);

		$this->init();
	}

	public function init() {}

	/**
	 * Get default props for this block.
	 *
	 * @return array
	 */
	abstract public static function get_default_props();

	/**
	 * Render and print the output, such as HTML.
	 */
	abstract public function render();

	/**
	 * Setup / process props before overriding the defaults with provided.
	 * 
	 * By default, removes:
	 * 
	 * 1. Unrecognized props (not in defaults array).
	 * 2. When the value is _default (forces to use default prop).
	 * 3. Props with same value as default.
	 * 
	 * To be used to extend properties for this block based on provided props, or when
	 * some computation is needed that's not available to the method get_default_props().
	 *
	 * @param array $props
	 * @return array
	 */
	public function setup_props(array $props) 
	{
		$defaults = $this->get_default_props();
		foreach ($props as $key => $value) {

			// Remove if unrecognized prop or has _default value.
            if (!array_key_exists($key, $defaults) || $value === '_default') {
				unset($props[$key]);
				continue;
			}

			/**
			 * If the default prop value is an array, the expected value is an array, so 
			 * strings should be split by comma separation.
			 */
			if (is_array($defaults[$key])) {
				
				$orig_is_array = true;

				// If it's an alias, check if the original is an array.
				if (array_key_exists('alias', $defaults[$key])) {
					$ref = $defaults[$key]['alias'];

					if (!is_array($defaults[$ref])) {
						$orig_is_array = false;
					}
				}
				
				// Nothing to do anymore.
				if (!$orig_is_array) {
					continue;
				}

				// Not empty string but an array is expected.
				if ($value && is_string($value)) {
					$value = array_map(
						'trim',
						explode(',', $value)
					);
				}

				// An array is expected for this key, cast it if not empty.
				$props[$key] = $value ? (array) $value : [];
			}
		}

		/**
		 * Removes defaults for external non-programmatic calls. For internal calls, we generally 
		 * wish to use exactly what is specified in props. Otherwise, default props will be 
		 * removed and set to global props via map_global_props().
		 */
		if (!empty($props['is_sc_call'])) {
			$props = $this->remove_defaults($props, $defaults);
		}

		// ThemeSphere theme active, add in the defaults.
		if (class_exists('\Bunyad', false) && \Bunyad::get('theme')) {
			$props = $this->map_global_props($props);
		}

		return $props;
	}

	/**
	 * Remove props with same value as default. 
	 * 
	 * Can help if correct global/default value is not set. Also required so global default 
	 * options can be added via self::map_global_props().
	 *
	 * @param array $props
	 * @param array $defaults
	 * @return array
	 */
	protected function remove_defaults(array $props, $defaults = [])
	{
		foreach ($props as $key => $value) {
			
			/**
			 * We don't do strict matching in all cases for removal. Based on default prop type:
			 * 
			 * - Strict comparison for null type.
			 * - Following are considered equal:
			 * 
			 *  0     == ''
			 *  false == '0'
			 *  false == ''
			 *  true  == '1'
			 *  true  == 1
			 *  10    == '10'
			 *  []    == ''
			 * 
			 * - Loose comparison done for boolean, integer, float (double).
			 * - Strict comparison with cast for string type.
			 * - Arrays strict comparison to allow all order sort, with cast.
			 */
			$remove  = false;
			$default = $defaults[$key];
			
			switch (gettype($default)) {

				case 'boolean':
				case 'integer':
				case 'double':
					$remove = $default == $value;
					break;

				case 'string':
					if (!is_array($value)) {
						// Everything but arrays. Good to cast to string first.
						$remove = (string) $default === (string) $value;
					}

					break;

				case 'array':
					$remove = (array) $default === (array) $value;
					break;

				default:
					$remove = $default === $value;
					break;
			}

			if ($remove) {
				unset($props[$key]);
			}
		}

		return $props;
	}

	/**
	 * Add in global props as specified. Only invoked when a ThemeSphere theme is active.
	 * 
	 * Called via setup_props() method after removing default props. Only overrides are 
	 * preserved.
	 * 
	 * Proper Usage:
	 *  1. Defaults/global options should be overridden by provided props.
	 *  <code>return array_replace($global_options, $props);</code>
	 *
	 * @param array $props Props after processed by self::setup_props().
	 * @return array
	 */
	public function map_global_props($props)
	{
		return $props;
	}

	/**
	 * Get all the props.
	 *
	 * @return array
	 */
	public function get_props()
	{
		return $this->props;
	}

	/**
	 * Magic method for print / echo.
	 */
	public function __toString() 
	{
		ob_start();
		$this->render();
		
		return ob_get_clean();
	}

}