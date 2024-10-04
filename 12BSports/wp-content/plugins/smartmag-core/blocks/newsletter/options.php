<?php

namespace Bunyad\Blocks;

/**
 * Newsletter block options
 */
class Newsletter_Options extends Base\Options
{
	public $block_id = 'Newsletter';

	public function init($type = '') 
	{
		// Block name to be used by page builders
		$this->block_name = esc_html__('Newsletter Form', 'bunyad-admin');

		$this->elementor_conf = [
			'title'      => $this->block_name,
			'icon'       => 'ts-ele-icon ts-ele-icon-newsletter',
			'categories' => ['smart-mag-blocks'],
		];

		/**
		 * General Options.
		 */
		$options['sec-general'] = [
			'headline' => [
				'name'    => 'headline',
				'label'   => esc_html__('Headline / CTA', 'bunyad-admin'),
				'type'    => 'text',
				'default' => ''
			],
			
			'service' => [
				'name'    => 'service',
				'label'   => esc_html__('Subscribe Service', 'bunyad-admin'),
				'type'    => 'select',
				'options' => [
					''          => esc_html__('Settings From Customizer', 'bunyad-admin'),
					'mailchimp' => esc_html__('MailChimp', 'bunyad-admin'),
					'custom'    => esc_html__('Others (HTML / Shortcode)', 'bunyad-admin'),
				],
				'label_block' => true,
			],

			'custom_form' => [
				'name'    => 'custom_form',
				'label'   => esc_html__('Form HTML / Shortcode', 'bunyad-admin'),
				'type'    => 'textarea',
				'label_block' => true,
				'default' => '',
				'condition' => ['service' => 'custom'],
				'sanitize_callback' => false
			],

			'message' => [
				'name'    => 'message',
				'label'   => esc_html__('Message', 'bunyad-admin'),
				'type'    => 'richtext',
				'label_block' => true,
				'default' => ''
			],

			'submit_text' => [
				'name'    => 'submit_text',
				'label'   => esc_html__('Button Text', 'bunyad-admin'),
				'type'    => 'text',
				'label_block' => true,
				'default' => 'Subscribe'
			],

			'submit_url' => [
				'name'    => 'submit_url',
				'label'   => esc_html__('MailChimp Submit URL', 'bunyad-admin'),
				'type'    => 'text',
				'condition' => ['service' => 'mailchimp']
			],

			'disclaimer' => [
				'name'    => 'disclaimer',
				'label'   => esc_html__('Disclaimer', 'bunyad-admin'),
				'type'    => 'textarea',
				'label_block' => true,
			],

			'checkbox' => [
				'name'    => 'checkbox',
				'label'   => esc_html__('Disclaimer Checkbox', 'bunyad-admin'),
				'type'    => 'switcher',
				'return_value' => '1',
				'default'      => '1',
			],

			'image_type' => [
				'name'    => 'image_type',
				'label'   => esc_html__('Image At Top', 'bunyad-admin'),
				'type'    => 'select',
				'options' => [
					'none'   => esc_html__('None', 'bunyad-admin'),
					'full'   => esc_html__('Full Width', 'bunyad-admin'),
					'normal' => esc_html__('Normal Image', 'bunyad-admin'),
				]
			],

			'image' => [
				'name'        => 'image',
				'label'       => esc_html__('Image', 'bunyad-admin'),
				'type'        => 'media',
				'condition'   => ['image_type!' => 'none'],
				'default'     => [],

				// String is expected by block. Elementor does array.
				'default_forced' => true,
			],

			'image_2x' => [
				'name'        => 'image_2x',
				'label'       => esc_html__('Image Retina/2x', 'bunyad-admin'),
				'type'        => 'media',
				'condition'   => ['image_type!' => 'none'],
				'default'     => [],
				'default_forced' => true,
			],
		];

		/**
		 * Style options.
		 */
		$options['sec-style'] = [

			'style' => [
				'name'    => 'style',
				'label'   => esc_html__('Base Style', 'bunyad-admin'),
				'type'    => 'select',
				'options' => [
					'a' => esc_html__('A: Minimal', 'bunyad-admin'),
					'b' => esc_html__('B: Modern', 'bunyad-admin'),
					'c' => esc_html__('C: Classic', 'bunyad-admin'),
				],
				// 'label_block' => true,
			],

			'scheme' => [
				'name'    => 'scheme',
				'label'   => esc_html__('Color Scheme', 'bunyad-admin'),
				'type'    => 'select',
				'options' => [
					'light' => esc_html__('Default / Light', 'bunyad-admin'),
					'dark'  => esc_html__('Dark / Contrast', 'bunyad-admin'),
				]
			],

			'fields_style' => [
				'name'    => 'fields_style',
				'label'   => esc_html__('Fields Style', 'bunyad-admin'),
				'description' => esc_html__('When using a Custom form provided by shortcode or HTML, No Style might be the preferred option.', 'bunyad-admin'),
				'type'    => 'select',
				'options' => [
					'full'   => esc_html__('One Per Row', 'bunyad-admin'),
					'inline' => esc_html__('Inline (Not for Custom)', 'bunyad-admin'),
					'none'   => esc_html__('No Extra Style', 'bunyad-admin')
				]
			],

			'container' => [
				'name'    => 'container',
				'label'   => esc_html__('Size / Spacing', 'bunyad-admin'),
				'description' => esc_html__('These presets will not apply if typography or spacing settings were modified via Customizer.', 'bunyad-admin'),
				'type'    => 'select',
				'options' => [
					'lg' => esc_html__('Large', 'bunyad-admin'),
					'sm' => esc_html__('Small', 'bunyad-admin'),
					'xs' => esc_html__('X-Small', 'bunyad-admin'),
				]
			],

			'align' => [
				'name'    => 'align',
				'label'   => esc_html__('Alignment', 'bunyad-admin'),
				'type'    => 'select',
				'options' => [
					'center' => esc_html__('Centered', 'bunyad-admin'),
					'left'   => esc_html__('Left', 'bunyad-admin'),
				]
			],

			'icon' => [
				'name'    => 'icon',
				'label'   => esc_html__('Icon Above', 'bunyad-admin'),
				'type'    => 'select',
				'options' => [
					''         => esc_html__('None', 'bunyad-admin'),
					'mail-bg'  => esc_html__('Faded Icon Background', 'bunyad-admin'),
					'mail-top' => esc_html__('Rounded Top Icon', 'bunyad-admin'),
					'mail'     => esc_html__('Simple Icon', 'bunyad-admin'),
				]
			],

			'css_max_width' => [
				'name'        => 'css_max_width',
				'label'       => esc_html__('Content Max Width', 'bunyad-admin'),
				'type'        => 'number',
				'input_attrs' => ['min' => 200, 'max' => 1500],
				'selectors'         => [
					'.spc-newsletter' => '--max-width: {{VALUE}}px',
				],
				'separator'   => 'before',
				'default'     => ''
			],

			'css_padding' => [
				'label'       => esc_html__('Padding', 'bunyad-admin'),
				'type'        => 'dimensions',
				'size_units'  => ['px'],
				'devices'     => true,
				'selectors'   => [
					'{{WRAPPER}} .spc-newsletter > .inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
				'default'     => [],
			],

			'css_bradius' => [
				'label'       => esc_html__('Box Roundness', 'bunyad-admin'),
				'type'        => 'number',
				'selectors'   => [
					'{{WRAPPER}} .spc-newsletter' => '--box-roundness: {{VALUE}}px;'
				],
				'default'     => [],
			],
		];

		$options['sec-style-color-typo'] = [
			'css_heading_typo' => [
				'name'       => 'css_heading_typo',
				'label'      => esc_html__('Heading Typography', 'bunyad-admin'),
				'type'       => 'group',
				'group_type' => 'typography',
				'devices'    => true,
				'selector'   => '{{WRAPPER}} .heading',
				'default'    => [],
			],

			'css_heading_color' => [
				'name'       => 'css_heading_color',
				'label'      => esc_html__('Heading Color', 'bunyad-admin'),
				'type'       => 'color',
				'selectors'  => [
					'{{WRAPPER}} .heading' => 'color: {{VALUE}}'
				],
				'default'    => ''
			],
			'css_heading_color_sd' => [
				'name'       => 'css_heading_color_sd',
				'label'      => esc_html__('Dark: Heading Color', 'bunyad-admin'),
				'type'       => 'color',
				'selectors'  => [
					'.s-dark {{WRAPPER}} .heading,
					{{WRAPPER}} .s-dark .heading' => 'color: {{VALUE}}'
				],
				'default'    => ''
			],

			'css_message_typo' => [
				'name'       => 'css_message_typo',
				'label'      => esc_html__('Message Typography', 'bunyad-admin'),
				'type'       => 'group',
				'group_type' => 'typography',
				'devices'    => true,
				'selector'   => '{{WRAPPER}} .message',
				'default'    => [],
			],

			'css_disclaimer_typo' => [
				'name'       => 'css_disclaimer_typo',
				'label'      => esc_html__('Disclaimer Typography', 'bunyad-admin'),
				'type'       => 'group',
				'group_type' => 'typography',
				'devices'    => true,
				'selector'   => '{{WRAPPER}} .disclaimer',
				'default'    => [],
			],

			'css_button_bg' => [
				'name'       => 'css_button_bg',
				'label'      => esc_html__('Button Background', 'bunyad-admin'),
				'type'       => 'color',
				'selectors'  => [
					'{{WRAPPER}} input[type=submit],
					{{WRAPPER}} input[type=submit]' => 'background: {{VALUE}}'
				],
				'default'    => ''
			],
		];

		$options['sec-style-bg'] = [

			'css_bg_color' => [
				'name'       => 'css_bg_color',
				'label'      => esc_html__('Background Color', 'bunyad-admin'),
				'type'       => 'color',
				'selectors'  => [
					'{{WRAPPER}} .spc-newsletter' => 'background-color: {{VALUE}}'
				],
				'default'    => ''
			],
			'css_bg_color_sd' => [
				'name'       => 'css_bg_color_sd',
				'label'      => esc_html__('Dark: Background Color', 'bunyad-admin'),
				'type'       => 'color',
				'selectors'  => [
					'{{WRAPPER}} .spc-newsletter' => 'background-color: {{VALUE}}'
				],
				'default'    => ''
			],

			'css_background_grad' => [
				'name'       => 'css_background',
				'label'      => esc_html__('Background', 'bunyad-admin'),
				'type'       => 'group',
				'group_type' => 'background',
				'selector'   => '{{WRAPPER}} .spc-newsletter',
				'types'      => ['gradient'],
				'default'    => [],
			],

			'css_bg_image' => [
				'name'       => 'css_bg_image',
				'label'      => esc_html__('Background Image', 'bunyad-admin'),
				'type'       => 'media',
				'selectors'  => [
					'{{WRAPPER}} .bg-wrap' => 'background-image: url("{{URL}}");'
				],
				'default'    => [],
			],

			'css_bg_opacity' => [
				'name'        => 'css_bg_opacity',
				'label'       => esc_html__('BG Opacity', 'bunyad-admin'),
				'type'        => 'number',
				'input_attrs' => ['min' => 0, 'max' => 1, 'step' => 0.1],
				'selectors'   => [
					'{{WRAPPER}} .bg-wrap' => 'opacity: {{VALUE}}',
				],
				'default'     => ''
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
			'sec-style-color-typo' => [
				'label' => esc_html__('Colors & Typography', 'bunyad-admin'),
				'tab'   => 'style'
			],
			'sec-style-bg'   => [
				'label' => esc_html__('Background', 'bunyad-admin'),
				'tab'   => 'style'
			],
		];
	}
}