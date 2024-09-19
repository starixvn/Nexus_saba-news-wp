<?php

namespace Bunyad\Elementor;

use Elementor\Controls_Manager;

/**
 * Extra controls for widgets.
 */
class ExtendWidgets
{
	public function __construct()
	{
		add_action('elementor/element/after_section_end', [$this, 'extend_column_sections'], 9999, 2);
		add_action('elementor/element/after_section_end', [$this, 'extend_widgets_sections'], 9999, 2);
		add_action('elementor/element/before_section_end', [$this, 'extend_controls'], 9999, 2);

		// Add social links from global if missing.
		add_action('elementor/widget/before_render_content', [$this, 'add_social_links']);
	}

	/**
	 * Extend columns controls.
	 *
	 * @param \Elementor\Element_Base $section
	 * @param string $section_id
	 * @return void
	 */
	public function extend_column_sections($element, $section_id)
	{
		if (!method_exists($element, 'get_type') || $element->get_type() !== 'column') {
			return;
		}

		/**
		 * Dark Background section.
		 */
		if ($section_id === 'section_style') {

			$element->start_controls_section(
				'section_background_sd',
				[
					'label' => esc_html__('Dark Mode: Background', 'bunyad-admin'),
					'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				]
			);

			$element->add_group_control(
				\Elementor\Group_Control_Background::get_type(),
				[
					'name'  => 'background_sd',
					'types' => ['classic', 'gradient'],
					'fields_options' => [
						'background' => [
							'frontend_available' => true,
						],
					],
					'selector' => '.s-dark {{WRAPPER}} > .elementor-widget-wrap',
				]
			);

			$element->end_controls_section();
		}

		/**
		 * Dark Border section.
		 */
		if ($section_id === 'section_border') {

			$element->start_controls_section(
				'section_border_sd',
				[
					'label' => esc_html__('Dark Mode: Border', 'bunyad-admin'),
					'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				]
			);

			$element->add_group_control(
				\Elementor\Group_Control_Border::get_type(),
				[
					'name'     => 'border_sd',
					'selector' => '.s-dark {{WRAPPER}} > .elementor-element-populated',
				]
			);

			$element->end_controls_section();
		}
	}

	/**
	 * Extend widget sections.
	 *
	 * @param \Elementor\Element_Base $section
	 * @param string $section_id
	 * @return void
	 */
	public function extend_widgets_sections($element, $section_id)
	{
		if (!method_exists($element, 'get_type') || in_array($element->get_type(), ['column', 'section'])) {
			return;
		}

		/**
		 * Dark Background section.
		 */
		if ($section_id === '_section_background') {

			$element->start_controls_section(
				'_section_background_sd',
				[
					'label' => esc_html__('Dark Mode: Background', 'bunyad-admin'),
					'tab' => \Elementor\Controls_Manager::TAB_ADVANCED,
				]
			);

			$element->add_group_control(
				\Elementor\Group_Control_Background::get_type(),
				[
					'name'  => '_background_sd',
					'types' => ['classic', 'gradient'],
					'fields_options' => [
						'background' => [
							'frontend_available' => true,
						],
					],
					'selector' => '.s-dark {{WRAPPER}} > .elementor-widget-container',
				]
			);

			$element->end_controls_section();
		}

		/**
		 * Dark Border section.
		 */
		if ($section_id === '_section_border') {

			$element->start_controls_section(
				'_section_border_sd',
				[
					'label' => esc_html__('Dark Mode: Border', 'bunyad-admin'),
					'tab' => \Elementor\Controls_Manager::TAB_ADVANCED,
				]
			);

			$element->add_group_control(
				\Elementor\Group_Control_Border::get_type(),
				[
					'name'     => '_border_sd',
					'selector' => '.s-dark {{WRAPPER}} > .elementor-widget-container',
				]
			);

			$element->end_controls_section();
		}
	}


	/**
	 * Add extra controls to the editor widgets.
	 *
	 * @param \Elementor\Element_Base $section
	 * @param string $section_id
	 * @return void
	 */
	public function extend_controls($element, $section_id)
	{
		if (!method_exists($element, 'get_name')) {
			return;
		}

		switch ($element->get_name())
		{
			case 'text-editor':
				$this->text_editor_controls($element, $section_id);
				break;

			case 'divider':
				$this->divider_controls($element, $section_id);
				break;

			case 'icon-list':
				$this->icon_list_controls($element, $section_id);
				break;
		}

		$advanced = [
			// Columns and sections advanced section.
			'section_advanced',
			// Widgets advanced section
			'_section_style'
		];

		if (in_array($section_id, $advanced)) {
			$this->dark_mode_controls($element, $section_id);
		}
	}

	/**
	 * Add controls for dark mode to hide / show.
	 *
	 * @return void
	 */
	public function dark_mode_controls($element, $section_id)
	{
		$element->add_control(
			'ts_hide_s_dark',
			[
				'label' => esc_html__('Hide In Dark Mode', 'bunyad-admin'),
				'type' => Controls_Manager::SWITCHER,
				'default' => '',
				'prefix_class' => 'ts-el-',
				'label_on' => 'Hide',
				'label_off' => 'Show',
				'separator' => 'before',
				'return_value' => 'hide-s-dark',
			]
		);

		$element->add_control(
			'ts_hide_s_light',
			[
				'label' => esc_html__('Hide In Light Mode', 'bunyad-admin'),
				'type' => Controls_Manager::SWITCHER,
				'default' => '',
				'prefix_class' => 'ts-el-',
				'label_on' => 'Hide',
				'label_off' => 'Show',
				'return_value' => 'hide-s-light',
			]
		);
	}

	/**
	 * Add extra controls to the text editor widget.
	 *
	 * @param \Elementor\Element_Base $section
	 * @param string $section_id
	 * @return void
	 */
	public function text_editor_controls($element, $section_id)
	{
		if ($section_id === 'section_editor') {
			$element->add_control(
				'theme_content_style',
				[
					'label'       => esc_html__('Theme Content Style', 'bunyad-admin'),
					'description' => '',
					'type'        => Controls_Manager::SWITCHER,
					'default'      => '',
					'prefix_class' => '',
					'return_value' => 'post-content',
				],
				[
					'position' => [
						'at' => 'after',
						'of' => 'editor'
					]
				]
			);
		}

		if ($section_id === 'section_style') {
			$element->add_control(
				'text_color_sd',
				[
					'label' => esc_html__('Dark: Text Color', 'bunyad-admin'),
					'type' => Controls_Manager::COLOR,
					'default' => '',
					'selectors' => [
						'.s-dark {{WRAPPER}}' => 'color: {{VALUE}};',
					],
				],
				[
					'position' => [
						'at' => 'after',
						'of' => 'text_color'
					]
				]
			);	
		}
	}

	/**
	 * Divider widget.
	 */
	public function divider_controls($element, $section_id)
	{
		if ($section_id === 'section_divider_style') {
			$element->add_control(
				'color_sd',
				[
					'label'       => esc_html__('Dark: Color', 'bunyad-admin'),
					'type'        => Controls_Manager::COLOR,
					'default'     => '',
					'render_type' => 'template',
					'selectors'   => [
						'.s-dark {{WRAPPER}}' => '--divider-color: {{VALUE}}',
					],
				],
				[
					'position' => [
						'at' => 'after',
						'of' => 'color'
					]
				]
			);
		}
	}

	/**
	 * Icon list widget.
	 */
	public function icon_list_controls($element, $section_id)
	{
		if ($section_id === 'section_icon_list') {
			$element->update_control('divider_color', ['default' => 'var(--c-separator2)']);
			$element->add_control(
				'divider_color_sd',
				[
					'label'   => esc_html__('Dark: Color', 'bunyad-admin'),
					'type'    => Controls_Manager::COLOR,
					'default' => '',
					'condition' => ['divider' => 'yes'],
					'selectors' => [
						'.s-dark {{WRAPPER}} .elementor-icon-list-item:not(:last-child):after' => 'border-color: {{VALUE}}',
					],
				],
				['position' => ['at' => 'after', 'of' => 'divider_color']]
			);
		}

		if ($section_id === 'section_icon_style') {
			$element->add_control(
				'icon_color_sd',
				[
					'label' => esc_html__('Dark: Color', 'bunyad-admin'),
					'type' => Controls_Manager::COLOR,
					'default' => '',
					'selectors' => [
						'.s-dark {{WRAPPER}} .elementor-icon-list-icon i' => 'color: {{VALUE}};',
						'.s-dark {{WRAPPER}} .elementor-icon-list-icon svg' => 'fill: {{VALUE}};',
					],
					'condition' => ['icon_color!' => '']
				],
				['position' => ['at' => 'after', 'of' => 'icon_color']]
			);
		}

		if ($section_id === 'section_text_style') {
			$element->add_control(
				'text_color_sd',
				[
					'label' => esc_html__('Dark: Color', 'bunyad-admin'),
					'type' => Controls_Manager::COLOR,
					'default' => '',
					'selectors' => [
						'.s-dark {{WRAPPER}} .elementor-icon-list-text' => 'color: {{VALUE}};',
					],
					'condition' => ['text_color!' => '']
				],
				['position' => ['at' => 'after', 'of' => 'text_color']]
			);
		}
	}

	/**
	 * Automatically add global social media profiles to social icons widget, if the 
	 * link is missing in widget settings.
	 *
	 * @param \Elementor\Widget_Base $element
	 * @return void
	 */
	public function add_social_links($element)
	{
		if (!class_exists('\Bunyad') || !\Bunyad::options()) {
			return;
		}

		if (!method_exists($element, 'get_name') || $element->get_name() !== 'social-icons') {
			return;
		}

		$settings = $element->get_settings();
		if (empty($settings['social_icon_list'])) {
			return;
		}

		$social_profiles = \Bunyad::options()->get('social_profiles');
		$changed = false;
		foreach ($settings['social_icon_list'] as $key => $icon) {
			if (!empty($icon['link']['url']) || empty($icon['social_icon']['value'])) {
				continue;
			}

			preg_match('/fa-([a-z]+)(\s|$)/', $icon['social_icon']['value'], $match);
			if (!$match || !$match[1]) {
				continue;
			}

			$value = $social_profiles[$match[1]] ?? '';
			if ($value) {
				$icon['link']['url'] = $value;
				$settings['social_icon_list'][$key] = $icon;

				$changed = true;
			}
		}

		if ($changed) {
			$element->set_settings($settings);
		}
	}
}