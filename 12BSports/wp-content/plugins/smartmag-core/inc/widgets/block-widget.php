<?php
namespace Bunyad\Widgets;
use \Bunyad;

/**
 * Base widget class for block widgets.
 */
class BlockWidget extends \WP_Widget
{
	public $conf = [];

	/**
	 * Options object
	 */
	public $options;
	public $block_class;
	public $has_css = false;

	protected $block_options;
	protected $block_data;
	protected $block_id;

	protected $is_loop;

	/** 
	 * @var \Bunyad_Admin_OptionRenderer
	 */
	private $option_renderer;

	public function __construct()
	{
		/**
		 * The name of this class is supposed to match: \Bunyad\Widgets\Grid_Block
		 * which will be converted \Bunyad\Blocks\Loops\Grid for loops namespace.
		 * Note: The excessive slashing is tricky but required. 
		 */
		$class = get_called_class();
		if (strpos($class, '\Loops') !== false) {
			$this->block_class = preg_replace('/.+?([a-z]+)\_Block$/i', 'Bunyad\Blocks\Loops\\\\$1', $class);
			$this->is_loop     = true;
		}
		else {
			$this->block_class = preg_replace('/.+?([a-z]+)\_Block$/i', 'Bunyad\Blocks\\\\$1', $class);
		}
		
		$this->block_id = $this->get_block_id();
		$data = $this->conf;

		if (!$data) {
			return;
		}

		parent::__construct(
			'smartmag-block-' . $this->block_id,
			esc_html($data['title']),
			[
				'description' => isset($data['description']) ? $data['description'] : 'SmartMag posts listings block.', // pre-escaped 
				'classname'   => 'ts-block-widget smartmag-widget-' . $this->block_id
			]
		);
	}

	/**
	 * Get the block options.
	 */
	public function init_options() 
	{
		if (!$this->options) {

			$class = $this->block_class . '_Options';

			$this->options = new $class;
			$this->options->init('widget');
		}
	}

	public function get_options()
	{
		if (!$this->options) {
			$this->init_options();
		}

		/**
		 * Specific changes for some options.
		 */
		$this->options->remove_options([
			'filters',
			'filters_tags',
			'filters_terms',
		]);

		$options = $this->options->get_all();

		// Only custom option.
		if (isset($options['sec-filter']['query_type'])) {
			$options['sec-filter']['query_type']['options'] = [
				'custom'  => esc_html__('Custom', 'bunyad-admin'),
			];
		}

		if (isset($options['sec-layout']['space_below'])) {
			$options['sec-layout']['space_below']['default'] = 'none';
		}

		foreach (['tags', 'exclude_tags'] as $id) {
			if (!isset($options['sec-filter'][$id])) {
				continue;
			}

			$options['sec-filter'][$id] = array_replace(
				$options['sec-filter'][$id],
				[
					'type'        => 'text',
					'options'     => '',
					'description' => esc_html__('Enter a tag slug or multiple slugs separated by comma.', 'bunyad-admin')
				]
			);
		}

		return $options;
	}

	/**
	 * Deduce block id from class name
	 */
	public function get_block_id() 
	{
		$class = explode('\\', $this->block_class);
		$id    = end($class);

		// Convert FooBar to Foo-Bar and FooBar3 to Foo-Bar-3
		$id    = preg_replace('/(.)(?=[A-Z])/u', '$1-', $id);
		$id    = preg_replace('/(.)(?=[0-9])/u', '$1-', $id);

		return strtolower($id);
	}

	/**
	 * The widget render function.
	 * 
	 * Note: This may also be called manually by legacy widgets.
	 */
	public function widget($args, $instance)
	{
		$settings = $instance + [
			'is_sc_call' => true
		];

		// Default to custom query.
		if (!isset($settings['query_type'])) {
			$settings['query_type'] = 'custom';
		}

		?>

		<?php 
		
		echo $args['before_widget'];

		// Widget title if it exists.
		if (!empty($instance['widget_title'])) {
			echo $args['before_title'] . $instance['widget_title'] . $args['after_title'];
		}

		?>
		
		<div class="block">
			<?php 
				/** @var \Bunyad\Blocks\Base\LoopBlock $block */
				$block = Bunyad::blocks()->load(
					str_replace('Bunyad\Blocks\\', '', $this->block_class),
					$settings
				);

				// Only for loop blocks.
				if ($this->is_loop) {
					// Process first to get right heading etc.
					$block->process();

					// Use sidebar default heading.
					if (empty($block->props['heading_type']) && !empty($block->data['heading'])) {
						$block->data['heading_custom'] = $args['before_title'] . $block->data['heading'] . $args['after_title'];
					}
				}

				$block->render();
	
				if (!empty($instance['has_css'])) {
					$this->render_css(
						'.block-sc[data-id="' . $block->data['unique_id'] . '"]', 
						$instance
					);
				}
			?>
		</div>

		<?php

		echo $args['after_widget'];
	}

	/**
	 * Render dynamic CSS needed for this widget.
	 */
	protected function render_css($wrapper, array $instance)
	{
		// $this->init_options();

		$options  = $this->get_options();
		$css      = [];

		foreach ($options as $section) {
			foreach ($section as $key => $option) {

				if (empty($instance[$key]) || empty($option['selectors'])) {
					continue;
				}
				
				$css[] = $this->create_option_css(
					(array) $option['selectors'],
					$wrapper, 
					$instance[$key]
				);
			}
		}

		$css = array_filter($css);
		if (!empty($css)) {
			printf(
				'<style>%s</style>',
				join("\n", $css)
			);
		}
	}

	/**
	 * Create CSS given selectors, wrapper id and a value.
	 */
	private function create_option_css(array $selectors, $wrapper, $value = '') 
	{
		$css = [];
		foreach ($selectors as $selector => $rule) {
			$selector = str_replace('{{WRAPPER}}', $wrapper, $selector);
			
			// Replacement variables.
			$rule    = str_replace('{{VALUE}}', $value, $rule);
			$rule    = str_replace('{{UNIT}}', 'px', $rule);
			$rule    = str_replace('{{SIZE}}', $value, $rule);

			$css[]    = "{$selector} { {$rule} }";
		}

		return join("\n", $css);
	}

	/**
	 * @inheritDoc
	 */
	public function update($new, $old)
	{

		$this->init_options();
		$options = $this->options->get_all(true);

		// Sanitize all.
		array_walk_recursive($new, function(&$value, $key) use ($options) {
			$sanitizer = 'wp_kses_post';

			if (isset($options[$key]['sanitize_callback'])) {
				$sanitizer = $options[$key]['sanitize_callback'];
			}

			// Add back sanitizer if current user can't use unfiltered html.
			if (!$sanitizer && !current_user_can('unfiltered_html')) {
				$sanitizer = 'wp_kses_post';
			}

			if ($sanitizer) {
				$value = call_user_func($sanitizer, $value);
			}
		});

		return $new;
	}

	protected function init_editor() 
	{
		$this->init_options();

		// Init editor.
		$this->options->init_editor();
	}

	/**
	 * Backend form
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form($instance) 
	{
		$this->init_editor();
		$this->option_renderer = Bunyad::factory('admin/option-renderer');

		$sections = $this->options->get_sections();
		$options  = $this->get_options();

		// Wrapper.
		printf('<div id="bunyad-widget-%1$s">', esc_attr($this->id));

		// We want heading option first at top.
		if (isset($options['sec-heading'])) {
			$heading = $options['sec-heading']['heading'];
			unset($options['sec-heading']['heading']);

			$heading_value = isset($instance['heading']) ? $instance['heading'] : '';
			$this->_render_option('heading', $heading, $heading_value);
		}

		/**
		 * Render all section headings and options.
		 */
		$count   = 0;
		foreach ($sections as $sec_key => $data) {
			$count++;

			// Options cannot be empty.
			if (empty($options[$sec_key])) {
				continue;
			}

			ob_start();
			foreach ($options[$sec_key] as $key => $option) {
				$this->_render_option(
					$key, 
					$option,
					isset($instance[$key]) ? $instance[$key] : null
				);
			}

			$rendered_options = ob_get_clean();
			$rendered_options = trim($rendered_options);

			// Skip empty groups.
			if (!$rendered_options) {
				continue;
			}

			if (empty($data['widget_no_group'])) {
				printf(
					'<details class="bunyad-widget-tab"%1$s>
						<summary class="heading"><span>%2$s</span></summary>
						%3$s
					</details>',
					($count === 1 ? ' open' : ''),
					esc_html($data['label']),
					$rendered_options // Escaped by _render_option()
				);
			}
			else {
				printf(
					'<div>%s</div>',
					$rendered_options  // Escaped by _render_option()
				);
			}
		}

		// Close bunyad-widget id wrapper.
		echo '</div>'; 

		// Add the has_css flag to be saved.
		if ($this->has_css) {
			printf(
				'<input type="hidden" name="%1$s" value="%2$s" />',
				$this->get_field_name('has_css'),
				(int) $this->has_css
			);
		}

		$this->render_form_js();
	}

	/**
	 * Render a field for the widget
	 *
	 * @param string $key    ID/name of the option.
	 * @param array $option  Option configs.
	 * @param mixed $value   Current value. If null, default is used.
	 */
	public function _render_option($key, $option, $value = null)
	{
		$show_label = true;

		// Unsupported type.
		if (in_array($option['type'], ['color', 'heading'])) {
			return;
		}

		$allowed_css = [
			'css_column_gap',
			'css_row_gap',

			// Media ratio can't be used until dependencies are properly removed.
			// 'media_ratio_custom',
			
			// Better handled in global only.
			// 'media_width',
		];

		// CSS values are mostly unsupported.
		if (isset($option['selectors']) || isset($option['selector'])) {
			if (!in_array($key, $allowed_css)) {
				return;
			}
			
			$this->has_css = true;
		}

		switch ($option['type']) {
			case 'text':
			case 'media':
				$option['type'] = 'text';
				$option['input_class'] = 'widefat';
				
				break;

			case 'select':
				$option['type']  = 'select';
				$option['class'] = 'widefat';

				break;

			case 'select2':
			case 'bunyad-selectize':
				$option = array_replace($option, [
					'type'    => 'select',
					'class'   => 'widefat bunyad-selectize'
				]);

				break;

			case 'slider':
				$option['type'] = 'number';
				break;

			case 'switcher':
			case 'checkbox':
				$option['type'] = 'checkbox';
				$option['label_block'] = true;

				// Checkbox renderer has a label built-in
				$show_label = false;

				break;
			
			case 'richtext':
				$option['type'] = 'textarea';
				break;

			// Unsupported.
			case 'html':
				return;

		}

		$default = isset($option['default']) ? $option['default'] : '';
		$option  = array_replace($option, [
			'name'    => $this->get_field_name($key),
			'value'   => $value !== null ? $value : $default,
			'no_wrap' => true
		]);

		// Not all pagination types are supported for widgets.
		if ($key === 'pagination_type') {
			$option['options'] = array_intersect_key(
				$option['options'], 
				array_flip([
					'numbers-ajax',
					'load-more'
				])
			);
		}

		/**
		 * Convert 'conditions' data to be valid with our JS context checker.
		 */
		$context = [];
		if (isset($option['condition'])) {
			foreach ($option['condition'] as $opt_key => $val) {
				$compare = '=';
				if (strpos($opt_key, '!') !== false) {
					$compare = '!=';
					$opt_key = str_replace('!', '', $opt_key);
				}

				$context[$opt_key] = [
					'value'   => $val,
					'compare' => $compare
				];
			}
		}

		$class = [
			'bunyad-widget-option'
		];

		if (empty($option['label_block'])) {
			$class[] = 'bunyad-widget-option-inline';
		}

		if ($option['type'] === 'hidden') {
			echo $this->option_renderer->render($option);
			return;	
		}

		$attribs = [
			'class'        => $class,
			'data-element' => $key,
			'data-type'    => $option['type'],
			'data-value'   => is_array($option['value']) ? json_encode($option['value']) : $option['value'],
			'data-context' => $context ? json_encode($context) : ''
		];

		if (isset($option['selectize_options'])) {
			$attribs['data-selectize-options'] = json_encode($option['selectize_options']);
		}

		?>

		<div <?php Bunyad::markup()->attribs('widget-' . $this->block_id, $attribs);?>>

			<?php if ($show_label): ?>
				
				<label for="<?php echo esc_attr($this->get_field_name($key)); ?>" class="label">
					<?php echo esc_html($option['label']); ?>
				</label>

			<?php endif; ?>

			<?php echo $this->option_renderer->render($option); ?>

			<?php if (!empty($option['description'])): ?> 

				<p class="small-desc"><?php echo esc_html($option['description']); ?></p>

			<?php endif; ?>

		</div>

		<?php
	}

	/**
	 * JS to initialize the widet form.
	 */
	public function render_form_js()
	{
		?>
		<script>
		jQuery(function($) {
			Bunyad.widgets.init('bunyad-widget-<?php echo esc_attr($this->id); ?>');
		});
		</script>
		<?php
	}
}