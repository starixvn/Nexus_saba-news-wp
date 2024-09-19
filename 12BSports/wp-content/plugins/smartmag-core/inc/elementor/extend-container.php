<?php

namespace Bunyad\Elementor;

/**
 * Extra containers.
 */
class ExtendContainer extends ExtendSection
{
	protected $type = 'container';

	public function __construct()
	{
		// Add section query section after layout section.
		add_action('elementor/element/container/section_layout_container/after_section_end', [$this, 'add_query_controls'], 10, 2);

		// Add extra sections after specific existing sections.
		add_action('elementor/element/after_section_end', [$this, 'add_options_sections'], 10, 2);

		// Add extra controls for the section element, in several sections.
		add_action('elementor/element/before_section_end', [$this, 'add_options_controls'], 10, 2);

		// Add render attributes for gutter.
		// add_action('elementor/frontend/section/before_render', [$this, 'section_gutter_attr']);

		// Modify JS template for section
		// add_action('elementor/section/print_template', [$this, 'section_modify_template']);


		// Add some more controls to the beginning.
		add_action('elementor/element/container/section_layout_container/after_section_start', function($section) {
			$section->add_control(
				'ts_spacing',
				[
					'label'       => esc_html__('Match Theme Spacing', 'bunyad-admin'),
					'type'        => \Elementor\Controls_Manager::SWITCHER,
					'description' => esc_html__('Applies theme paddings and widths based on device. Only for top-level containers.', 'bunyad-admin'),
					//'separator'   => 'before',
					'default'      => '',
					'prefix_class' => '',
					'return_value' => 'ts-el-con',
					'condition'    => ['content_width' => 'boxed']
				]
			);
		});
	}

	public function add_controls($section) {

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
}