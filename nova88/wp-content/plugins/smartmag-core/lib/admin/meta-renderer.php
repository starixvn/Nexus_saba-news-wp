<?php

class Bunyad_Admin_MetaRenderer extends Bunyad_Admin_OptionRenderer
{
	private $prefix;
	
	public function set_prefix($prefix)
	{
		$this->prefix = $prefix;
		return $this;
	}
	
	/**
	 * Renders a specific element adding in the default values.
	 * 
	 * @param array $element
	 */
	public function render($element)
	{
		// Set default value if available (the known value).
		if (isset($this->default_values[$element['name']])) {
			$default = $this->default_values[$element['name']];

			// Array? - possible messed up import.
			if (is_array($default) && isset($default[0])) {
				$default = $default[0];
			}

			$element['value'] = $default;
		}

		return $this->render_element($element);
	}

	/**
	 * Get compatible options - currently just adds prefix to name.
	 * 
	 * @param array $options multi-dimensional array of options
	 */
	public function options($options)
	{
		$new_options = array();
		foreach ($options as $key => $option) {
			$option['name']    = $this->prefix . $option['name'];
			$new_options[$key] = $option;
		}
		
		return $new_options;
	}
}