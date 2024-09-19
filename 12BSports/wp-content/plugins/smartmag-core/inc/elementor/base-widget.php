<?php

namespace Bunyad\Elementor;

use Elementor\Controls_Manager;
use Bunyad;

/**
 * The base widget for all Elementor Widgets.
 */
abstract class BaseWidget extends \Elementor\Widget_Base 
{
	/**
	 * Options object
	 */
	public $options;
	public $block_class;

	/**
	 * @inheritDoc
	 */
	public function __construct($data = [], $args = null)
	{
		// The name of this class is supposed to match: \Bunyad\Blocks\Loops\Grid_Elementor
		// which will be converted \Bunyad\Blocks\Loops\Grid
		$this->block_class = str_replace('_Elementor', '', get_class($this));

		parent::__construct($data, $args);
	}

	/**
	 * Get the options class for this widget.
	 */
	public function get_options($init_editor = false) 
	{
		if (!$this->options) {

			$class = $this->block_class . '_Options';

			$this->options = new $class;
			$this->options->init();
		}

		if ($init_editor) {
			$this->options->init_editor();
		}
		
		return $this->options;
	}

	public function get_name()
	{
		$name = explode('\\', $this->block_class);
		return 'smartmag-' . str_replace('\\', '_', strtolower(end($name)));
	}

	public function get_title()
	{
		return $this->get_block_config('title');
	}

	public function get_icon() 
	{
		return $this->get_block_config('icon');
	}

	public function get_categories()
	{
		return $this->get_block_config('categories');
	}

	/**
	 * Get elementor configs from the block options object.
	 */
	public function get_block_config($key)
	{
		return $this->get_options()->elementor_conf[ $key ];
	}

	/**
	 * Render Widget HTML
	 */
	protected function render()
	{
		if (class_exists('\SmartMag_Core') && empty(\SmartMag_Core::instance()->theme_supports['blocks'])) {
			return;
		}

		$settings = $this->_process_settings();

		// DEBUG the difference between two:
		// print_r($this->get_data('settings'));
		// print_r($settings);

		/** @var \Bunyad\Blocks\Base\Block $block */
		$block = Bunyad::blocks()->load(
			str_replace('Bunyad\Blocks\\', '', $this->block_class),
			$settings
		);

		$block->render();
	}

	/**
	 * Process and return settings for block.
	 *
	 * @return array
	 */
	protected function _process_settings()
	{
		// Elementor mostly only really saves the changed props (except few strays).
		// Available via $this->get_data('settings')
		// In editor mode, $this->get_data('settings') gets all settings, but on frontend
		// only the changed data. So we're using get_settings_for_display() instead.

		$settings = array_replace(
			$this->get_settings_for_display(),
			[
				'is_sc_call' => true
			]
		);

		/** 
		 * Convert responsive values to local recognized.
		 * 
		 * Example: column_mobile to column_small.
		 */
		$map = [
			'desktop' => 'main', 
			'tablet'  => 'medium',
			'mobile'  => 'small'
		];

        foreach ($settings as $key => $value) {

			$suffix = substr(strrchr($key, '_'), 1);
			if ($suffix && isset($map[$suffix])) { 
				$to = preg_replace('/'. $suffix .'$/', $map[$suffix], $key);
				$settings[$to] = $value;
			}
		}

		return $settings;
	}

	/**
	 * Register the controls for this elementor widget.
	 * 
	 * Called on both front and backend.
	 */
	protected function register_controls()
	{
		$options = $this->get_options(self::is_elementor());

		if (!is_object($options)) {
			return;
		}

		// Get the sections array
		$section_data = $options->get_sections();

		static::do_register_controls($this, $options, $section_data);
	}

	/**
	 * Register the controls on the provided element/widget.
	 *
	 * @param \Elementor\Controls_Stack $element
	 * @param \Bunyad\Blocks\Base\LoopOptions $options_obj
	 * @param array $section_data
	 * @return void
	 */
	public static function do_register_controls($element, $options_obj, $section_data = [])
	{
		$options = $options_obj->get_all();
		/**
		 * Go through the sections and options
		 */
		foreach ($options as $section_key => $section_opts) {

			$element->start_controls_section($section_key, $section_data[$section_key]);

			foreach ($section_opts as $option_key => $option) {

				// If options are a callback, that means the processing is done only when in 
				// elementor edit mode.
				// if (static::is_elementor()) {
				// 	$option = $options_obj->do_option_callbacks($option);
				// }

				$options = static::_map_control($option);

				if ($options['type'] === 'group') {
					$element->add_group_control(
						$options['group_type'], 
						['name' => $option_key] + $options
					);
				}
				else if (isset($options['devices'])) {
					if (!is_array($options['devices'])) {
						unset($options['devices']);
					}

					$element->add_responsive_control($option_key, $options);
				}
				else {
					$element->add_control($option_key, $options);
				}
			}

			$element->end_controls_section();
		}
	}

	/**
	 * Convert the option to an elementor compatible control array.
	 */
	protected static function _map_control($option)
	{
		// Group type.
		if ($option['type'] === 'group') {
			switch ($option['group_type']) {
				case 'typography':
					$option['group_type'] = \Elementor\Group_Control_Typography::get_type();
					break;

				case 'background':
					$option['group_type'] = \Elementor\Group_Control_Background::get_type();
					break;

				case 'box-shadow':
					$option['group_type'] = \Elementor\Group_Control_Box_Shadow::get_type();
					break;

				case 'bunyad-border':
					$option['group_type'] = Controls\Groups\Border::get_type();
					break;
			}
		}

		// Know types map with elementor
		$types = [
			'text'     => Controls_Manager::TEXT,
			'textarea' => Controls_Manager::TEXTAREA,
			'richtext' => Controls_Manager::WYSIWYG,
			'number'   => Controls_Manager::NUMBER,
			'select'   => Controls_Manager::SELECT,
			'choose'   => Controls_Manager::CHOOSE,
			'switcher' => Controls_Manager::SWITCHER,
			'slider'   => Controls_Manager::SLIDER,
			'color'    => Controls_Manager::COLOR,
			'icon'     => Controls_Manager::ICON,
			'media'    => Controls_Manager::MEDIA,
			'html'     => Controls_Manager::RAW_HTML,
			'heading'  => Controls_Manager::HEADING,
			'hidden'   => Controls_Manager::HIDDEN,
			'dimensions' => Controls_Manager::DIMENSIONS,
		];

		$mapped = $option;

		if (array_key_exists($option['type'], $types)) {
			$mapped = array_replace($option, [
				'type' => $types[ $option['type'] ]
			]);
		}

		if (isset($option['input_attrs'])) {
			$mapped += (array) $option['input_attrs'];
		}

		// For color control, description is missing in Elementor.
		if ($mapped['type'] === 'color' && isset($mapped['description'])) {
			$mapped['label'] .= sprintf(
				'<p class="elementor-control-field-description" style="max-width: 160px;">%s</p>',
				$mapped['description']
			);
			unset($mapped['description']);
		}

		// Add devices.
		// Removed in v9.2. Not changing it means all default elementor breakpoints will be added instead.
		// if (isset($option['devices']) && !is_array($option['devices'])) {
		// 	$mapped['devices'] = ['desktop', 'tablet', 'mobile'];
		// }

		// Keys not needed
		unset($mapped['section']);

		// Remove callables if not already removed.
		unset($mapped['editor_callback']);
		if (isset($mapped['options']) && is_callable($mapped['options'])) {
			$mapped['options'] = [];
		}

		return $mapped;
	}

	/**
	 * Create an array with sections as keys.
	 * 
	 * @deprecated No longer used
	 */
	protected function _map_sections($options)
	{
		$new = [];

		foreach ($options as $key => $data) {
			if (empty($data['section'])) {
				$data['section'] = 'general';
			}

			if (empty($new[$data['section']])) {
				$new[$data['section']] = [];
			}

			$new[$data['section']][$key] = $data;
		}

		return $new;
	}

	/**
	 * Check if we are in the Elementor editor.
	 * 
	 * @return bool True if in Elementor Edit Mode
	 */
	public static function is_elementor()
	{
		if (!class_exists('\Elementor\Plugin', false)) {
			return false;
		}
		
		// Elmentor may use AJAX to get widget configs etc.
		if (isset($_REQUEST['action']) && $_REQUEST['action'] === 'elementor_ajax') {
			return true;
		}
		
		if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
			return true;
		}

		return false;
	}
}