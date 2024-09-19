<?php
/**
 * All the theme options are loaded and fetched via this class.
 */
class Bunyad_Options
{
	protected $configs    = [];
	protected $cache      = [];
	protected $short_load = true;

	/**
	 * @var array Values changed via the API instead of init.
	 */
	protected $mutated = [];

	/**
	 * @var array A multi-dimensional array of keys and option elements.
	 */
	public $defaults = [];
	
	/**
	 * Initialize options.
	 * 
	 * @param bool $short When true, a short list of options are loaded. Otherwise a
	 *                    full options tree load is done. See load_elements().
	 * @return void
	 */
	public function init($short = true)
	{
		// Save flag for whether it was a short load or not.
		$this->short_load = $short;

		/**
		 * Filter 'bunyad_theme_options' can be used to provide extra options that are added 
		 * both on normal run and to the customizer.
		 * 
		 * Handled by code below via running 'bunyad_theme_options' for both:
		 *  bunyad_options_tree and bunyad_options_elements
		 */
		// On full load/customizer, add via 'bunyad_options_tree' filter.
		add_filter('bunyad_options_tree', function($tree) {
			return apply_filters('bunyad_theme_options', $tree, 'tree');
		}, 2);

		// For short load/frontend, add the elements from 'bunyad_theme_options' filter.
		add_filter('bunyad_options_elements', function($options, $short) {

			// Only needed for short load. Full load in tree above.
			if (!$short) {
				return $options;
			}

			$options_tree = apply_filters('bunyad_theme_options', [], 'short');

			if (!empty($options_tree)) {
				// Flatten the extra options.
				$options = array_merge(
					$options,
					Bunyad::factory('admin/options')->get_elements_from_tree($options_tree)
				);
			}

			return $options;
		}, 2, 2);

		// Flattened key => element pair of elments from options tree.
		$this->defaults = $this->load_elements($short);

		// Get our options
		$options = (array) get_option($this->configs['theme_prefix'] . '_theme_options');

		// Remove options that have unmet context conditions, essentially reset to default.
		$options = Bunyad::factory('admin/options')->remove_disabled_contexts(
			$options, 
			$this->defaults
		);

		if (is_array($options) && $options) {
			$this->cache = $options;
		}

		do_action('bunyad_options_initialized');
	}

	/**
	 * A reinit preserves currently set options, instead of a clean init.
	 * Useful in case something was overridden via API by a plugin / dev.
	 */
	public function reinit($short = true)
	{
		$current = $this->cache;
		$this->init($short);

		// Add back all the values that were previously changed using the API, in this instance.
		foreach ($current as $key => $value) {
			if (in_array($key, $this->mutated)) {
				$this->cache[$key] = $value;
			}
		}
	}
	
	/**
	 * Loads options tree, or short options, and returns a flattened key => element pair. 
	 * 
	 * @uses Bunyad_Admin_Options::get_elements_from_tree()
	 * 
	 * @param boolean $short Short loads a pre-generated array for performance reasons.
	 * @return array
	 */
	public function load_elements($short = true)
	{
		// Short loads a pre-generated array with memory and speed optimizations.
		if ($short === true) {
			$options = include get_template_directory() . '/admin/options-short.php';

		}
		else {

			$options_tree = $this->load_options_tree();

			// Process tree to add group pseudo-options and defaults.
			$options_tree = Bunyad::get('customizer')->process_options($options_tree, false);

			// Flatten the options tree.
			$options = Bunyad::factory('admin/options')->get_elements_from_tree($options_tree);
		}

		/**
		 * Filter the final key => element pair.
		 */
		return apply_filters(
			'bunyad_options_elements',
			$options,
			$short
		);
	}

	/**
	 * Load the options tree from file and apply filters.
	 * 
	 * @return array Raw options tree.
	 */
	public function load_options_tree()
	{
		// Get the main full options data.
		$options_tree = include get_template_directory() . '/admin/options.php';

		/**
		 * Filter for the raw options tree. 
		 * 
		 * This is used in Customizer so any options that have to be added to 
		 * customizer can be added via this filter.
		 * 
		 * Note: If the options have non-empty default values, they should also 
		 * be added via the bunyad_options_elements filter.
		 * 
		 * Alternatively, 'bunyad_theme_options' filter will add to both tree and short.
		 */
		$options_tree = apply_filters('bunyad_options_tree', $options_tree);

		return $options_tree;
	}

	/**
	 * Whether current options loaded were via short load.
	 *
	 * @return boolean
	 */
	public function is_short_load() 
	{
		return $this->short_load;
	}
	
	/**
	 * Get an option from the database (cached) or the default value provided 
	 * by the options setup. 
	 * 
	 * Note: Fallback to default value of the related element unless fallbacks are
	 * defined explicity in arguments.
	 * 
	 * @param string|array $key   An option key to get value of.
	 * @param mixed ...$fallbacks Fallback option keys, if the main key is not set.
	 * @return mixed|null
	 */
	public function get($key, ...$fallbacks)
	{
		// Multiple keys can be provided to use the other if one's not set.
		if ($fallbacks && !isset($this->cache[$key])) {

			foreach ($fallbacks as $option) {
				if (isset($this->cache[$option])) {
					return $this->cache[$option];
				}
			}

			// Nothing found yet, use the last key as fallback.
			$fallback = end($fallbacks);
			return $this->get($fallback);
		}
		
		if (isset($this->cache[$key])) {
			return $this->cache[$key];
		}
		
		if (isset($this->defaults[$key]['value'])) {
			return $this->defaults[$key]['value'];
		}
		
		return null;
	}
	
	/**
	 * Get value with a fallback default value specified manually.
	 * 
	 * @param string
	 * @param mixed
	 * @return mixed Either the option value or default fallback.
	 */
	public function get_or($key, $default = '')
	{
		if (isset($this->cache[$key])) {
			return $this->cache[$key];
		}
		
		return $default;
	}

	public function __set($key, $value)
	{
		return $this->set($key, $value);
	}

	public function __get($key)
	{
		return $this->get($key);
	}
	
	/**
	 * Remove all cache options - USE WITH CARE!
	 * 
	 * It will destroy changes made via meta to categories if it's used before saving options. 
	 */
	public function clear()
	{
		$this->cache = array();
		return $this;
	}
	
	/**
	 * Get all the options with non-default values (that were saved in the DB/storage).
	 * 
	 * @param null|string $prefix Prefix to limit by
	 */
	public function get_all($prefix = null) 
	{
		if ($prefix) {
			$options = array();
			
			foreach ($this->cache as $key => $value) {
				if (preg_match('/^' . preg_quote($prefix) . '/', $key)) {
					$options[$key] = $value;
				}
			}
			
			return $options;
		}
		
		return $this->cache;
	}
	
	/**
	 * Overwrite all options in cache.
	 * 
	 * @param array $options
	 */
	public function set_all(array $options)
	{
		$this->cache = $options;
		return $this;
	}
	
	/**
	 * Updates local cache - DOES NOT saves to DB. Use update() to save.
	 * 
	 * @param string|array $key
	 * @param mixed $value  a value of null will unset the option
	 * @return Bunyad_Options
	 */
	public function set($key, $value = null)
	{
		// Arrays have to be merged.
		if (is_array($key)) {
			$this->cache = array_replace($this->cache, $key);
			array_push($this->mutated, ...array_keys($key));
			return $this;
		}

		array_push($this->mutated, $key);
		
		if ($value === null) {
			unset($this->cache[$key]);
		}
		else {
			$this->cache[$key] = $value;
		}
		
		return $this;
	}
	
	/**
	 * Updates the options from local cache to the database.
	 * 
	 * @param null|mixed $key
	 * @param null|mixed $value
	 */
	public function update($key = null, $value = null) 
	{
		if ($key != null && $value != null) {
			$this->set($key, $value);
		}
		
		unset($this->cache['shortcodes']);
		
		return update_option($this->configs['theme_prefix'] . '_theme_options', (array) $this->cache);
	}
	
	/**
	 * Set local configurations.
	 * 
	 * @param string|array $key
	 * @param mixed  $value
	 */
	public function set_config($key, $value = '')
	{
		if (is_array($key)) {
			$this->configs = array_merge($this->configs, $key);
			return $this;
		}
		
		$this->configs[$key] = $value;
		return $this;
	}
	
	/**
	 * Get local configurations.
	 */
	public function get_config($key)
	{
		return isset($this->configs[$key]) ? $this->configs[$key] : null;
	}
}