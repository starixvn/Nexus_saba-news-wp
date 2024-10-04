<?php

namespace Bunyad\Elementor;
use Elementor\Controls_Manager;

/**
 * Extra containers.
 */
class ExtendSection
{
	protected $type = 'section';

	public function __construct()
	{
		// Add section query section after layout section.
		add_action('elementor/element/section/section_layout/after_section_end', [$this, 'add_query_controls'], 10, 2);

		// Add extra sections after specific existing sections.
		add_action('elementor/element/after_section_end', [$this, 'add_options_sections'], 10, 2);

		// Add extra controls for the section element, in several sections.
		add_action('elementor/element/before_section_end', [$this, 'add_options_controls'], 10, 2);

		// Add render attributes for gutter.
		add_action('elementor/frontend/section/before_render', [$this, 'add_gutter_attr']);

		// Modify JS template for section
		add_action('elementor/section/print_template', [$this, 'modify_template']);

		/**
		 * Extend Column Element.
		 */
		// Extend the section in layout section.
		add_action('elementor/element/column/layout/after_section_start', [$this, 'add_column_controls']);
	}

	/**
	 * Add the "section/container query" controls.
	 *
	 * @param \Elementor\Includes\Elements\Container $section
	 * @param array $args
	 * @return void
	 */
	public function add_query_controls($section, $args)
	{
		// This is required class.
		if (!class_exists('\Bunyad\Blocks\Base\LoopOptions')) {
			return;
		}

		$options_obj = new SectionOptions;
		$sections    = [
			'sec-query' => [
				'label' => esc_html__('Section Query', 'bunyad-admin'),
				'tab'   => $args['tab']
			]
		];

		LoopWidget::do_register_controls($section, $options_obj, $sections);
	}

	public function add_column_controls($section) {

		// $section->add_control(
		// 	'ts_sticky_col',
		// 	[
		// 		'label'       => esc_html__('Sticky Column', 'bunyad-admin'),
		// 		'type'        => \Elementor\Controls_Manager::SWITCHER,
		// 		'description' => esc_html__('Makes sticky using browser native sticky.', 'bunyad-admin'),
		// 		//'separator'   => 'before',
		// 		'default'      => '',
		// 		// 'prefix_class' => '',
		// 		// 'return_value' => 'ts-sticky-col',
		// 	]
		// );
		$section->add_control(
			'ts_sticky_col',
			[
				'label'       => esc_html__('Sticky Column', 'bunyad-admin'),
				'type'        => \Elementor\Controls_Manager::SELECT,
				'description' => esc_html__('Makes column sticky while scrolling. Native sticky is faster on slower laptops, but smart sticky has better UX.', 'bunyad-admin'),
				'separator'   => 'after',
				'options'     => [
					'' => esc_html__('Disabled', 'bunyad-admin'),
					'ts-sticky-native' => esc_html__('Native Sticky', 'bunyad-admin'),
					'ts-sticky-col'    => esc_html__('Smart Sticky', 'bunyad-admin'),
				],
				'default'      => '',
				'prefix_class' => '',
			]
		);

		$section->add_control(
			'is_sidebar',
			[
				'label'       => esc_html__('Is Sidebar?', 'bunyad-admin'),
				'type'        => \Elementor\Controls_Manager::SWITCHER,
				'description' => esc_html__('Check if this column is a sidebar.', 'bunyad-admin'),
				//'separator'   => 'before',
				'default'      => '',
				'prefix_class' => '',
				'return_value' => 'main-sidebar',
				'condition'    => ['is_main' => '']
			]
		);

		$section->add_control(
			'sidebar_sticky',
			[
				'label'       => esc_html__('Sticky Sidebar', 'bunyad-admin'),
				'type'        => \Elementor\Controls_Manager::SWITCHER,
				'description' => esc_html__('Check if this column is a sidebar.', 'bunyad-admin'),
				//'separator'   => 'before',
				'default'      => '',
				'prefix_class' => '',
				'return_value' => 'main-sidebar ts-sticky-col',
				'condition'    => ['is_sidebar!' => '', 'ts_sticky_col' => '']
			]
		);

		$section->add_control(
			'is_main',
			[
				'label'       => esc_html__('Is Main Column', 'bunyad-admin'),
				'type'        => \Elementor\Controls_Manager::SWITCHER,
				'description' => esc_html__('Check if this column is adjacent to a sidebar.', 'bunyad-admin'),
				//'separator'   => 'before',
				'default'      => '',
				'prefix_class' => '',
				'return_value' => 'main-content',
				'condition'    => ['is_sidebar' => '']
			]
		);

		$section->add_control(
			'add_separator',
			[
				'label'       => esc_html__('Add Separator', 'bunyad-admin'),
				'type'        => \Elementor\Controls_Manager::SWITCHER,
				'description' => esc_html__('Add a separator line.', 'bunyad-admin'),
				//'separator'   => 'before',
				'default'      => '',
				'prefix_class' => '',
				'return_value' => 'el-col-sep',
			]
		);


		$section->add_responsive_control(
			'column_order',
			[
				'label'       => esc_html__('Column Order', 'bunyad-admin'),
				'type'        => \Elementor\Controls_Manager::NUMBER,
				'description' => esc_html__('Rearrange columns by setting order for each.', 'bunyad-admin'),
				'default'      => '',
				'selectors'    => [
					'{{WRAPPER}}' => 'order: {{VALUE}};'
				]
			]
		);
	}

	/**
	 * Add extra sections after other sections.
	 *
	 * @param \Elementor\Element_Base $section
	 * @param string $section_id
	 * @return void
	 */
	public function add_options_sections($section, $section_id)
	{
		if (!method_exists($section, 'get_type') || $section->get_type() !== $this->type) {
			return;
		}

		/**
		 * Dark Background section.
		 */
		if ($section_id === 'section_background') {

			$section->start_controls_section(
				'section_background_sd',
				[
					'label' => esc_html__('Dark Mode: Background', 'bunyad-admin'),
					'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				]
			);

			$section->add_group_control(
				\Elementor\Group_Control_Background::get_type(),
				[
					'name'  => 'background_sd',
					'types' => ['classic', 'gradient'],
					'fields_options' => [
						'background' => [
							'frontend_available' => true,
						],
					],
					'selector' => '.s-dark {{WRAPPER}}',
				]
			);

			$section->end_controls_section();
		}

		/**
		 * Dark Border section.
		 */
		if ($section_id === 'section_border') {

			$section->start_controls_section(
				'section_border_sd',
				[
					'label' => esc_html__('Dark Mode: Border', 'bunyad-admin'),
					'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				]
			);

			$section->add_group_control(
				\Elementor\Group_Control_Border::get_type(),
				[
					'name'     => 'border_sd',
					'selector' => '.s-dark {{WRAPPER}}',
				]
			);

			$section->end_controls_section();
		}
	}

	/**
	 * Add extra controls to the sections.
	 *
	 * @param \Elementor\Element_Base $section
	 * @param string $section_id
	 * @return void
	 */
	public function add_options_controls($section, $section_id)
	{
		if (!method_exists($section, 'get_type') || $section->get_type() !== $this->type) {
			return;
		}

		/**
		 * Layout section controls.
		 * 
		 * @todo Move to SectionsOptions class where possible.
		 */
		if ($section_id === 'section_layout') {
			$section->add_control(
				'is_full_xs',
				[
					'label'       => esc_html__('Full-width on Mobile?', 'bunyad-admin'),
					'description' => '',
					'type'        => \Elementor\Controls_Manager::SWITCHER,
					'default'      => '',
					'prefix_class' => '',
					'return_value' => 'is-full-xs',
				],
				[
					'position'    => [
						'at' => 'before',
						'of' => 'gap'
					]
				]
			);

			$section->add_control(
				'gutter',
				[
					'label'       => esc_html__('Space Between Columns (Vertical)', 'bunyad-admin'),
					'type'        => 'select',
					'description' => esc_html__('This is different from Gap as it only applies vertically between columns excluding the left-most and right-most column.. Gap applies in both directions and also at beginning and end..', 'bunyad-admin'),
					'options'     => [
						'default' => esc_html__('Default Spacing', 'bunyad-admin'),
						'none'    => esc_html__('None', 'bunyad-admin'),
						'sm'      => esc_html__('Small Spacing', 'bunyad-admin'),
						'lg'      => esc_html__('Large Spacing', 'bunyad-admin'),
						'xlg'     => esc_html__('Wide Spacing', 'bunyad-admin'),
					],
					'label_block' => true,
					//'separator'   => 'before',
	
					// Keeping default to none for elementor imports.
					'default'     => 'none',
				],
				[
					'position'    => [
						'at' => 'before', 
						'of' => 'gap'
					]
				]
			);
		}

		if ($section_id === 'section_background') {
			$section->add_control(
				'is_dark_section',
				[
					'label'       => esc_html__('Is Dark Section', 'bunyad-admin'),
					'description' => 'Set to auto-adjust all SmartMag block colors under this section.',
					'type'        => \Elementor\Controls_Manager::SWITCHER,
					'separator'   => 'before',
					'default'      => '',
					'prefix_class' => '',
					'return_value' => 's-dark',
				]
			);
		}
	}

	/**
	 * Hook Callback: Add gutter attributes for section
	 * 
	 * @param \Elementor\Element_Section $section
	 */
	public function add_gutter_attr($section) 
	{
		if (!is_callable([$section, 'add_render_attribute'])) {
			return;
		}

		$spacing = $section->get_settings('gutter');
		if ($spacing && $spacing !== 'none') {
			$section->add_render_attribute(
				'_wrapper',
				'class',
				[
					'has-el-gap',
					'el-gap-' . $spacing,
				]
			);
		}
	}

	/**
	 * Hook Callback: Modify the content_template output for section
	 */
	public function modify_template($content)
	{
		ob_start();
		?>
			<# 
			if (view && view.$el) {
				view.$el.removeClass('has-el-gap el-gap-default el-gap-sm el-gap-lg el-gap-xlg');

				if (settings.gutter && settings.gutter !== 'none') {
					view.$el.addClass('has-el-gap el-gap-' + settings.gutter);
				}
			}
			#>
		<?php

		return ob_get_clean() . $content;
	}
}