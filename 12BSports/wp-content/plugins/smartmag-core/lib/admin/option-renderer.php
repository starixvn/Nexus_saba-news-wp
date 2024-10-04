<?php

class Bunyad_Admin_OptionRenderer
{
	public $default_values = array();
	
	/**
	 * Initialize the template file
	 * 
	 * @param array $options
	 * @param string $file
	 * @param array $populate  default form values for elements
	 * @param array $data 
	 */
	public function template($options, $file, $populate = [], $data = [])
	{
		$this->default_values = (array) $populate;
		extract($data);

		require_once $file;
	}
	
	/**
	 * Render and output an element.
	 */
	public function render($element)
	{
		// Defaults.
		$element = array_replace([
			'name'  => null, 
			'label' => null
		], $element);
		
		// Set default value if available.
		if (isset($this->default_values[$element['name']])) {
			$element['value'] = $this->default_values[$element['name']];
		}

		$output = $this->render_element($element);
	
		// Decorate it.
		if ($output && empty($element['no_wrap'])) {
			$output = '<label class="element-title">'. $element['label'] . '</label>'
					. '<div class="element-control">' . $output . (isset($element['html_post_output']) ? $element['html_post_output'] : '') . '</div>';
		}
		
		return $output;
	}

	/**
	 * Render an element with the element data specified.
	 * 
	 * @param array $element
	 * @return string
	 */
	public function render_element($element)
	{
		$output = '';

		switch ($element['type']) {

			case 'select':
				$output = $this->render_select($element);
				break;
			
			case 'checkbox':
				$output = $this->render_checkbox($element);
				break;
				
			case 'text':
				$output = $this->render_text($element);
				break;
				
			case 'number':
				$output = $this->render_text(array_replace([
					'input_type'  => 'number', 
					'input_size'  => '4', 
					'input_class' => 'small'
					],
					$element
				));
				break;

			case 'hidden':
				$output = $this->render_text(array_merge(array('input_type' => 'hidden'), $element));
				break;
				
			case 'textarea':
				$output = $this->render_textarea($element);
				break;
				
			case 'radio':
				$output = $this->render_radio($element);
				break;
				
			case 'color':
				$output = $this->render_color_picker($element);
				break;
				
			case 'bg_image':
				$output = $this->render_bg_image($element);
				break;
				
			case 'upload':
				$output = $this->render_upload($element);
				break;
				
			case 'html':
				$output = $element['html'];
				break;
				
			case 'file':
				$output = include locate_template($element['render']);
				break;
				
			case 'multiple':
				$output = $this->render_multiple($element);
				break;
				
			default:
				break;
		}

		return $output;
	}
	
	/**
	 * Render a select element.
	 */
	public function render_select($element)
	{
		$element = array_replace([
			'value'    => '',
			'multiple' => false,
			'class'    => '',
		], $element);

		// To treat as array.
		if ($element['multiple']) {
			$element['name'] .= '[]';
		}

		$output = sprintf(
			'<select name="%1$s" class="%2$s"%3$s>',
			esc_attr($element['name']),
			$element['class'] ? esc_attr($element['class']) : '',
			$element['multiple'] ? ' multiple' : ''
		);
		
		foreach ((array) $element['options'] as $key => $option) 
		{
			if (is_array($option)) {
				$output .= '<optgroup label="' . esc_attr($key) . '">' . $this->_render_options($option, $element['value']) . '</optgroup>';
			}
			else {
				$output .= $this->_render_options(array($key => $option), $element['value']);
			}
		}
		
		return $output . '</select>';
	}
	
	/**
	 * Helper for: render_select()
	 */
	private function _render_options($options, $selected = '') 
	{
		$output = '';
		
		foreach ($options as $key => $option) {

			if (is_array($selected)) {
				$is_selected = in_array($key, $selected) ? ' selected' : '';
			}
			else {
				$is_selected = selected((string) $selected, $key, false);
			}

			$output .= '<option value="'. esc_attr($key) .'"'. $is_selected .'>' . esc_html($option) . '</option>';
		}
		
		return $output;
	}
	
	/**
	 * Render a single checkbox or a group of multiple checkboxes
	 * 
	 * @param array $element
	 */
	public function render_checkbox($element)
	{
		$output = '';
		
		// multiple checkboxes?
		if (!empty($element['multiple'])) 
		{
			$element['value'] = (array) $element['value'];
			foreach ((array) $element['multiple'] as $key => $option) 
			{
				$value = isset($element['value'][$key]) ? $element['value'][$key] : '';
				
				$sub_element = array_merge($element, array(
					'name'  => $element['name'] . '[' . $key . ']',
					'label' => $option,
					'value' => $value,
				));
				
				$output .= '<div class="checkbox"> ' . $this->_render_checkbox($sub_element) . '</div>';
			}
		}
		else {
			return $this->_render_checkbox($element);
		}
						
		return $output;
	}
	
	// helper for: render_checkbox()
	public function _render_checkbox($element) {
		
		$element = array_merge(array('value' => null), $element);
		$element['options'] = array_merge(
			array('checked' => esc_html('Yes', 'bunyad-admin'), 'unchecked' => esc_html('No', 'bunyad-admin')), 
			!empty($element['options']) ? $element['options'] : array()
		);
		
		$output = '<input type="hidden" name="'. esc_attr($element['name']) .'" value="0" />' // always send in POST - even when empty
				. '<input type="checkbox" name="'. esc_attr($element['name']) .'" value="1" id="'. esc_attr($element['name']) .'"'
				. checked($element['value'], 1, false) . ' data-yes="'. esc_attr($element['options']['checked']) .'" data-no="'. esc_attr($element['options']['unchecked']) .'" />
				<label for="'. esc_attr($element['name']) .'">' . $element['label'] . '</label>
				';
				
		return $output;
	}
	
	/**
	 * Renders a text input field
	 */
	public function render_text($element)
	{
		$element = array_replace([
			'value'       => '', 
			'input_class' => '', 
			'input_type'  => 'text', 
			'input_size'  => '', 
			'input_attrs' => [],
			'placeholder' => ''
		], $element);

		$args = array_filter(
			$element['input_attrs'] + [
				'type'  => $element['input_type'],
				'name'  => $element['name'],
				'value' => $element['value'],
				'class' => ['input', $element['input_class']],
				'size'  => $element['input_size'],
				'placeholder' => $element['placeholder']
			]
		);

		$attribs = Bunyad::markup()->attribs(
			'element-' . $element['name'],
			$args,
			array('echo' => false)
		);

		$output = '<input ' . $attribs . ' />';

		return $output;
	}
	
	public function render_html($element)
	{
		return $element['html'];
	}
	
	/**
	 * Renders a textarea element 
	 */
	public function render_textarea($element)
	{
		// defaults
		$element = array_merge(array(
			'value' => null,
			'placeholder' => '',
			'options' => array('rows' => null, 'cols' => null)
		), $element);
		
		
		$attribs = array(
			'name'  => $element['name'],
			'placeholder' => $element['placeholder']
		);
		
		// Add rows and cols if set
		if ($element['options']['rows'] || $element['options']['cols']) {
			$attribs = array_merge($attribs, array(
				'rows' => intval($element['options']['rows']),
				'cols' => intval($element['options']['cols'])
			));
		}
		
		// Create attributes
		$attribs = Bunyad::markup()->attribs(
			'element-' . $element['name'], 
			array_filter($attribs),
			array('echo' => false)
		);
		
		$output = '<textarea '. $attribs . '>'. esc_html($element['value']) .'</textarea>';
		
		return $output;
	}
	
	public function render_radio($element)
	{
		$output = '';
		
		foreach ($element['options'] as $key => $option)
		{
			$output .= '<div class="radio-option"><label><input type="radio" name="'. esc_attr($element['name']) .'" value="'. esc_attr($key) . '"'
					.  checked($element['value'], $key, false) .' /><span>' . esc_html($option) . '</span></label></div>';
		}
				
		return $output;
	}
	
	public function render_color_picker($element)
	{
		$element = array_merge(array('value' => null), $element);
		
		$output = '<input type="text" class="bunyad-color-picker" name="'. esc_attr($element['name']) .'"'
				. ' value="' . esc_attr($element['value']) . '" /><div class="color-picker-element"></div>';
				
		return $output;
	}
	
	/**
	 * Render background image selector with options to select bg position
	 * 
	 * @param array $element
	 * @uses Bunyad_Admin_OptionRenderer::render_upload()
	 */
	public function render_bg_image($element)
	{
		// future themes on-need-basis implementation
	}
	
	/**
	 * Render an upload element
	 * 
	 * @param array $element
	 */
	public function render_upload($element)
	{
		$button_label = esc_html('Upload', 'bunyad-admin');
		if (!empty($element['options']['button_label'])) {
			$button_label = $element['options']['button_label'];
		}
		
		$element = array_merge(array('value' => null), $element);
		$element['options'] = array_replace(array(
				'editable'    => null, 
				'title'       => null, 
				'type'        => null,
				'media_type' => 'url'
			), 
			$element['options']
		);
		$element = $this->set_sub_values($element, array('bg_type'));
		
		$classes = $image = '';
		
		$output = '<input type="'. ($element['options']['editable'] ? 'text' : 'hidden') .'" name="'. esc_attr($element['name']) .'" class="element-upload" value="'
				. esc_attr($element['value']) .'" />'
				. '<input type="button" class="button upload-btn" value="'. esc_attr($button_label) .'"' 
				. ' data-insert-label="'. esc_attr($element['options']['insert_label']) .'"'
				. ' data-title="'. esc_attr($element['options']['title']) .'"'
				. ' data-title="'. esc_attr($element['options']['type']) .'"'
				. ' data-media-type="'. esc_attr($element['options']['media_type']) .'"'
				.' />';
		
		// For type image, we can have a preview.
		if ($element['options']['type'] == 'image') { 
			
			// Image exists / editing.
			if ($element['value']) {

				$media_src = $element['value'];
				if ($element['options']['media_type'] == 'id') {
					$media_src = wp_get_attachment_url($media_src);
				}

				$image   = '<img src="'. esc_url($media_src) .'" />';
				$classes = ' visible ';
			}
		
			// Background type.
			$type = '';
			if (isset($element['bg_type'])) {
				
				$type .= $this->render_select(array(
					'name' => $element['name'] . '_bg_type',
					'value' => $element['bg_type']['value'],
					'options' => array(
						'repeat'    => esc_html('Repeat Horizontal and Vertical - Pattern', 'bunyad-admin'),
						'cover'     => esc_html('Fully Cover Background - Photo', 'bunyad-admin'),
						'repeat-x'  => esc_html('Repeat Horizontal', 'bunyad-admin'),
						'repeat-y'  => esc_html('Repeat Vertical', 'bunyad-admin'),
						'no-repeat' => esc_html('No Repeat', 'bunyad-admin'),
					),
				));
			}
			
			$output .= '<a href="" class="remove-image button after-upload'. $classes .'">'. esc_html('Remove', 'bunyad-admin') .'</a>';			
			$output .= '<div class="image-upload'. $classes .'">'. $image . '</div>';
			
			$output .= '<div class="image-type after-upload'. $classes .'">' . $type . '</div>';
		}
				
		return $output;
	}
	
	/**
	 * Render multiple repeating fields - where multiple of the same can be dynamically added
	 */
	public function render_multiple($element)
	{
		$element = array_merge(
			array(
				'html'  => '',
				'value' => ''
			),
			$element
		);
		

		/**
		 * Print existing field groups while editing
		 */
		$fields = '';
		if (!empty($element['value'])) {
			
			$iterator = $element['value'];
			$first    = current($iterator);
			
			// possibly multi-dimensional array - created by multiple fields in a group
			if (is_array($first)) {
				$iterator = $first;
			}
			
			foreach ($iterator as $key => $value) {
				$fields .= $this->_render_multiple_fields($element, $key);
			}

		}
		else {
			// add an empty field group
			$fields = $this->_render_multiple_fields($element);	
		}
		
		$output = '<div class="element-multiple">' . $fields . '<a href="#">' . esc_html('Add More', 'bunyad-admin')  . '</a></div>';
		
		return $output;
	}
	
	/**
	 * Helper for render_multiple() to add a field
	 * 
	 * @param array   $element  the main element to render sub-elements from
	 * @param integer  $key      position to get the value from saved values, if any
	 */
	public function _render_multiple_fields($element, $key = null)
	{
		// no sub fields for this element?
		if (empty($element['sub_fields'])) {
			return '';
		}
		
		$fields = '';
		
		foreach ($element['sub_fields'] as $field) {

			// defaults
			$field = array_merge(array('label' => '', 'no_wrap' => 1), $field);
			
			/**
			 * If name is omitted, there's likely only one field. The field will become available in an array of type:
			 * 
			 * main_field[]  instead of  main_field['sub_field'][]
			 */

			if (!isset($field['name'])) {
				$field = array_merge($field, array(
					'value' => ($key !== null ? $element['value'][$key] : ''),
					'name'  => $element['name'] . '[]',
				));
			}
			else {
				$field = array_merge($field, array(
					'value' => ($key !== null ? $element['value'][ $field['name'] ][$key] : ''),
					'name'  => $element['name'] . '[' . $field['name'] . '][]'
				));
			}
			
			$label   = ($field['label'] ? '<label>' . $field['label'] . '</label>' : '');
			$fields .= '<div class="field">' . $label . $this->render($field) . '</div>';
			
		}
		
		return '<div class="fields'. ($key == null ? ' default' : '') .'">' . $fields . '<a href="#" class="remove">' . esc_html('Remove', 'bunyad-admin') . '</a></div>';
		
	}
	
	/**
	 * Set the default values for sub elements as specified
	 */
	public function set_sub_values($element, $sub = array())
	{
		foreach ($sub as $ele) 
		{
			$key = $element['name'] . '_' . $ele;
			
			// populate saved value if available
			if (isset($element[$ele]) && array_key_exists($key, $this->default_values)) {
				$element[$ele]['value'] = $this->default_values[$key];
			}
		}
		
		return $element;
	}
}