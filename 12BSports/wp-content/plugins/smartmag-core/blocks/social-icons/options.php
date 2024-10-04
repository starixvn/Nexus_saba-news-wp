<?php

namespace Bunyad\Blocks;

/**
 * Newsletter block options
 */
class SocialIcons_Options extends Base\Options
{
	public $block_id = 'SocialIcons';

	public function init($type = '') 
	{
		// Block name to be used by page builders
		$this->block_name = esc_html__('SmartMag Social Icons', 'bunyad-admin');

		$this->elementor_conf = [
			'title'      => $this->block_name,
			'icon'       => 'ts-ele-icon eicon-social-icons',
			'categories' => ['smart-mag-blocks'],
		];

		// Currently doesn't make sense without theme data.
		if (!class_exists('\Bunyad') || !is_callable([\Bunyad::core(), 'get_common_data'])) {
			return false;
		}

		$_common = \Bunyad::core()->get_common_data('options');

		/**
		 * General Options.
		 */
		$options['sec-general'] = [
			'style' => [
				'name'    => 'style',
				'label'   => esc_html__('Preset Style', 'bunyad-admin'),
				'type'    => 'select',
				'label_block' => true,
				'options' => [
					'a' => esc_html__('Base Icons', 'bunyad-admin'),
					'b' => esc_html__('Rounded Monochrome', 'bunyad-admin'),
					'c' => esc_html__('Squares with Background Color', 'bunyad-admin'),
				],
				'default' => 'a'
			],

			'brand_colors' => [
				'name'         => 'brand_colors',
				'label'        => esc_html__('Use Brand Colors', 'bunyad-admin'),
				'type'         => 'select',
				'options'      => [
					''       => esc_html__('Disabled', 'bunyad-admin'),
					'color'  => esc_html__('As Icons Color', 'bunyad-admin'),
					'bg'     => esc_html__('As Background Color', 'bunyad-admin'),
				],
				'condition' => ['style!' => 'c']
			],

			'services' => [
				'name'     => 'services',
				'label'    => esc_html__('Social Services', 'bunyad-admin'),
				'description' => 'URL will be used from global settings in Appearance > Customize > Social Profiles.',
				'type'     => 'bunyad-selectize',
				'multiple' => true,
				'options'  => $_common['social_services'],
				'label_block' => true,
			],
		];

		/**
		 * Style options.
		 */
		$options['sec-style'] = [
			'css_align' => [
				'label'   => esc_html__('Alignment', 'bunyad-admin'),
				'type'    => 'choose',
				'options' => [
					'left'    => [
						'title' => esc_html__('Left', 'bunyad-admin'),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__('Center', 'bunyad-admin'),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__('Right', 'bunyad-admin'),
						'icon' => 'eicon-text-align-right',
					],
				],
				'selectors_dictionary' => [
					'left'  => 'flex-start',
					'right' => 'flex-end',
				],
				'selectors' => [
					'{{WRAPPER}} .spc-social' => 'justify-content: {{VALUE}}',
				],
				'devices'   => true,
				// Intentional: 'selectors_dictionary' requires this to be a string or it will cause FATAL error.
				'default'   => '',
			],

			'css_icon_size' => [
				'label'       => esc_html__('Icon Size', 'bunyad-admin'),
				'type'        => 'slider',
				'devices'     => true,
				'size_units'  => ['%', 'px'],
				'selectors'   => [
					'{{WRAPPER}} .spc-social' => '--spc-social-fs: {{SIZE}}{{UNIT}}',
				],
				'default'     => [],
			],

			'css_spacing' => [
				'label'       => esc_html__('Spacing / Gap', 'bunyad-admin'),
				'type'        => 'slider',
				'devices'     => true,
				'size_units'  => ['%', 'px'],
				'selectors'   => [
					'{{WRAPPER}} .spc-social' => '--spc-social-space: {{SIZE}}{{UNIT}}',
				],
				'default'     => [],
			],

			'css_radius' => [
				'label'       => esc_html__('Roundness / Radius', 'bunyad-admin'),
				'type'        => 'number',
				'selectors'   => [
					'{{WRAPPER}} .service' => 'border-radius: {{VALUE}}px;'
				],
				'default'     => '',
			],

			'css_box_size' => [
				'label'       => esc_html__('Box Size', 'bunyad-admin'),
				'type'        => 'slider',
				'devices'     => true,
				'size_units'  => ['%', 'px'],
				'selectors'   => [
					'{{WRAPPER}} .spc-social' => '--spc-social-size: {{SIZE}}{{UNIT}}',
				],
				'default'     => [],
				'condition'   => ['style' => ['b', 'c']]
			],

			'css_colors' => [
				'label'       => esc_html__('Custom Colors', 'bunyad-admin'),
				'type'        => 'color',
				'separator'   => 'before',
				'selectors'   => [
					'{{WRAPPER}} .spc-social' => '--c-spc-social: {{VALUE}}',
				],
				'default'     => '',
			],
			'css_colors_sd' => [
				'label'       => esc_html__('Dark: Custom Colors', 'bunyad-admin'),
				'type'        => 'color',
				'selectors'   => [
					'.s-dark {{WRAPPER}} .spc-social' => '--c-spc-social: {{VALUE}}',
				],
				'default'     => '',
				'condition'   => ['css_colors!' => '']
			],

			'css_hov_colors' => [
				'label'       => esc_html__('Hover Colors', 'bunyad-admin'),
				'type'        => 'color',
				'selectors'   => [
					'{{WRAPPER}} .service:hover' => '--c-spc-social: {{VALUE}}',
				],
				'default'     => '',
			],

			'css_bg' => [
				'label'       => esc_html__('Custom Background', 'bunyad-admin'),
				'type'        => 'color',
				'selectors'   => [
					'{{WRAPPER}} .service' => '--c-spcs-bg: {{VALUE}}',
				],
				'default'     => '',
			],
			'css_bg_sd' => [
				'label'       => esc_html__('Dark: Custom BG', 'bunyad-admin'),
				'type'        => 'color',
				'selectors'   => [
					'.s-dark {{WRAPPER}} .service' => '--c-spcs-bg: {{VALUE}}',
				],
				'default'     => '',
				'condition'   => ['css_bg!' => '']
			],

			'css_padding' => [
				'label'       => esc_html__('Padding', 'bunyad-admin'),
				'type'        => 'dimensions',
				'size_units'  => ['px'],
				'devices'     => true,
				'separator'   => 'before',
				'selectors'   => [
					'{{WRAPPER}} .service' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
				'default'     => [],
			],
		];

		$this->options = $options;
		$this->_add_defaults();

		/**
		 * Widget only options.
		 */
		if ($type === 'widget') {
			$this->options['sec-general'] += [
				'widget_title' => [
					'name'     => 'widget_title',
					'label'    => esc_html__('Widget Title (Optional)', 'bunyad-admin'),
					'description' => esc_html__('Not recommended except for minimal style.', 'bunyad-admin'),
					'type'     => 'text',
					'position' => [
						'of' => 'headline',
						'at' => 'before'
					]
				]
			];
		}
	}

	public function get_sections()
	{
		return [
			'sec-general' => [
				'label' => esc_html__('General', 'bunyad-admin')
			],
			'sec-style'   => [
				'label' => esc_html__('Style', 'bunyad-admin'),
				'tab'   => 'style'
			],
		];
	}
}