<?php
/**
 * Base migrations.
 */
class Bunyad_Theme_Admin_Migrations_Base 
{
	public $options = [];

	public function __construct($options = []) 
	{
		$this->options = $options;
		$this->begin();
	}

	public function begin() {}

	/**
	 * Unset if a key exists in the options array with same value
	 *
	 * @param string|array $key
	 * @param string|null $value
	 * @return void
	 */
	public function unset_if_match($key, $value = null)
	{
		if (is_array($key)) {
			foreach ($key as $k => $v) {
				$this->unset_if_match($k, $v);
			}
			return;
		}

		if (!isset($this->options[$key])) {
			return;
		}

		if ($this->options[$key] == $value) {
			unset($this->options[$key]);
		}
		else if (is_array($value)) {

			$opt = &$this->options[$key];

			/**
			 * Remove for arrays of type 
			 */
			foreach ($value as $k => $v) {
				if (!is_string($k)) {
					continue;
				}
				
				// Remove if same
				if (!empty($opt[$k]) && $opt[$k] == $v) {
					unset($opt[$k]);
				}
			}

			if (empty($opt)) {
				unset($opt);
			}
		}
	}

	/**
	 * Rename old options.
	 */
	public function rename_options($options) 
	{
		foreach ($options as $old => $new) {
			$this->rename_option($old, $new);
		}
	}

	/**
	 * Copy the value from an option to another, optionally adding a suffix.
	 */
	public function copy_option($old, $new, array $suffix_opt = [])
	{
		if (!isset($this->options[$old])) {
			return false;
		}

		if (count($suffix_opt)) {
			list($suffix_key, $suffix_default) = $suffix_opt;

			$new = $this->_option_suffix($new, $suffix_key, $suffix_default);
		}

		foreach ((array) $new as $key) {
			$this->options[ $key ] = $this->options[ $old ];
		}

		return true;
	}

	/**
	 * Rename an option and delete old.
	 *
	 * @param string $old Old option key.
	 * @param string|array $new New option key.
	 * @param array $suffix_opt Pair to be used as $check and $default params for _option_suffix.
	 * 
	 * @return void
	 */
	public function rename_option($old, $new, array $suffix_opt = []) {
		if ($this->copy_option($old, $new, $suffix_opt)) { 
			unset($this->options[ $old ]);
		}
	}

	/**
	 * Apply a suffix based on the value of an option, or use a default suffix.
	 *
	 * @param string $key Option key to add suffix to.
	 * @param callable|string $check
	 * @param string $default
	 * 
	 * @return string
	 */
	protected function _option_suffix($key, $check, $default = '')
	{
		
		if (is_string($check)) {
			$suffix = 
				isset($this->options[ $check ]) 
					? $this->options[ $check ]
					: $default;
		}

		if (is_callable($check)) {
			$suffix = call_user_func($check, $default);
		}

		return $key . '_' . $suffix;
	}

	/**
	 * Get widgets for a sidebar.
	 */
	protected function get_widgets_data($sidebar) 
	{
		global $wp_registered_widgets;
	
		$sidebar_widgets = get_option('sidebars_widgets', []);
		if (empty($sidebar_widgets[ $sidebar ])) {
			return [];
		}
	
		$widgets = $sidebar_widgets[ $sidebar ];
		$selected_widgets = [];
	
		foreach ($widgets as $id) {
	
			if (!isset($wp_registered_widgets[ $id ])) {
				continue;
			}
	
			$option_name = $wp_registered_widgets[ $id ]['callback'][0]->option_name;
			$widget_data = get_option($option_name);
			$key         = $wp_registered_widgets[ $id ]['params'][0]['number'];
	
			$selected_widgets[$id] = [
				'object'  => $wp_registered_widgets[ $id ],
				'options' => (array) $widget_data[ $key ],
				'option'  => [$key, $option_name]
			];
		}
	
		return $selected_widgets;
	}
}