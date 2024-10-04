<?php
namespace Bunyad\Blocks;

/**
 * Heading block options.
 */
class Heading_Options extends Base\Options
{
	public $block_id = 'Heading';
	protected $common = [];

	public function init() 
	{
		$this->get_common_data();

		// Block name to be used by page builders
		$this->block_name = esc_html__('Block Heading', 'bunyad-admin');

		$this->elementor_conf = [
			'title'      => $this->block_name,
			'icon'       => 'ts-ele-icon ts-ele-icon-heading',
			'categories' => ['smart-mag-blocks'],
		];

		/**
		 * General Options.
		 */
		$options['sec-general'] = [
			'type' => [
				'label'       => esc_html__('Heading Style', 'bunyad-admin'),
				'type'        => 'select',
				'options'     => [
					''  => esc_html__('Global / Default Style', 'bunyad-admin'),
				] + $this->common['block_headings'],
				'default'     => '',
				'label_block' => true
			],

			'accent_colors' => [
				'label'       => esc_html__('Base Colors', 'bunyad-admin'),
				'type'        => 'select',
				'options'     => [
					''      => esc_html__('Auto', 'bunyad-admin'),
					'force' => esc_html__('Accent / Category', 'bunyad-admin'),
					'none'  => esc_html__('Default', 'bunyad-admin'),
				],
				'condition' => [
					'type!' => ['g', 'e2']
				]
				// 'label_block' => true,
			],

			'heading' => [
				'label'       => esc_html__('Heading Text', 'bunyad-admin'),
				'type'        => 'text',
				// 'description' => esc_html__('Optional. By default, main category name will be used. Note: Some heading styles can have multi-color headings when used with asterisks, example: World *News*', 'bunyad-admin'),
				'label_block' => true,
				'dynamic' => [
					'active' => true,
				],
			],

			'link' => [
				'label'       => esc_html__('Heading Link', 'bunyad-admin'),
				'type'        => 'text',
				'label_block' => true
			],

			'more_text' => [
				'label'       => esc_html__('View More', 'bunyad-admin'),
				'type'        => 'text',
				'description' => esc_html__('Add view more text with link.', 'bunyad-admin'),
				'label_block' => true
			],

			'more' => [
				'label'       => esc_html__('View More Style', 'bunyad-admin'),
				'type'        => 'select',
				'description' => '',
				'options'     => [
					'a' => 'Simple Text',
					'b' => 'Round Pill Style',
				],
				'condition'   => ['more_text!' => ''],
			],

			'more_link' => [
				'label'       => esc_html__('View More Link', 'bunyad-admin'),
				'type'        => 'text',
				'description' => esc_html__('Optional. By default, main category link will be used.', 'bunyad-admin'),
				'label_block' => true
			],

			'html_tag' => [
				'label'       => esc_html__('SEO: Heading Tag', 'bunyad-admin'),
				'type'        => 'select',
				'options'     => ['h1' => 'H1'] + $this->common['heading_tags'],
			],
		];

		/**
		 * Style options.
		 */
		$options['sec-style'] = [
			'align' => [
				'label'       => esc_html__('Heading Align', 'bunyad-admin'),
				'type'        => 'select',
				'options'     => [
					'left'   => esc_html__('Default / Left', 'bunyad-admin'),
					'center' => esc_html__('Centered', 'bunyad-admin'),
				],
				'label_block' => true,
			],

			'css_bhead_accent' => [
				'label'       => esc_html__('Heading Accent Color', 'bunyad-admin'),
				'type'        => 'color',
				'selectors'   => [
					'{{WRAPPER}} .block-head' => '--c-main: {{VALUE}}; --c-block: {{VALUE}};'
				],
				'default'     => '',
				'condition'   => [
					'heading_type!' => ['g']
				]
			],
			'css_bhead_accent_sd' => [
				'label'       => esc_html__('Dark: Heading Accent', 'bunyad-admin'),
				'type'        => 'color',
				'selectors'   => [
					'{{WRAPPER}} .s-dark .block-head,
					.s-dark {{WRAPPER}} .block-head' => '--c-main: {{VALUE}}; --c-block: {{VALUE}};'
				],
				'default'     => '',
				'condition'   => [
					'heading_type!' => ['g']
				]
			],

			'css_bhead_typo' => [
				'label'       => esc_html__('Heading Typography', 'bunyad-admin'),
				'type'        => 'group',
				'group_type'  => 'typography',
				'selector'    => '{{WRAPPER}} .block-head .heading',
				'default'     => [],
			],

			'css_bhead_space_below' => [
				'label'       => esc_html__('Space Below', 'bunyad-admin'),
				'type'        => 'slider',
				'devices'     => true,
				'size_units'  => ['%', 'px'],
				'selectors'   => [
					'{{WRAPPER}} .block-head' => '--space-below: {{SIZE}}{{UNIT}};'
				],
				'default'     => [],
			],

			'css_bhead_color' => [
				'label'       => esc_html__('Heading Text Color', 'bunyad-admin'),
				'type'        => 'color',
				// 'description' => esc_html__('Category color or theme main color will be used by default.', 'bunyad-admin'),
				'selectors'   => [
					'{{WRAPPER}} .block-head .heading' => 'color: {{VALUE}};'
				],
				'default'     => '',
			],
			'css_bhead_color_sd' => [
				'label'       => esc_html__('Dark: Heading Color', 'bunyad-admin'),
				'type'        => 'color',
				'selectors'   => [
					'{{WRAPPER}} .s-dark .block-head .heading,
					.s-dark {{WRAPPER}} .block-head .heading' => 'color: {{VALUE}};'
				],
				'default'     => '',
			],

			'css_bhead_line_weight' => [
				'label'       => esc_html__('Accent Line Weight', 'bunyad-admin'),
				'type'        => 'number',
				'selectors'   => [
					'{{WRAPPER}} .block-head' => '--line-weight: {{VALUE}}px;'
				],
				'default'     => '',
				'condition'   => ['type' => $this->common['supports_bhead_line_weight']],
			],

			'css_bhead_line_width' => [
				'label'       => esc_html__('Accent Line Width', 'bunyad-admin'),
				'type'        => 'number',
				'selectors'   => [
					'{{WRAPPER}} .block-head' => '--line-width: {{VALUE}}px;'
				],
				'default'     => '',
				'condition'   => ['type' => $this->common['supports_bhead_line_width']],
			],

			'css_bhead_line_color' => [
				'label'       => esc_html__('Accent Line Color', 'bunyad-admin'),
				'type'        => 'color',
				'selectors'   => [
					'{{WRAPPER}} .block-head' => '--c-line: {{VALUE}};'
				],
				'default'     => '',
				'condition'   => ['type' => $this->common['supports_bhead_line_color']],
			],
			'css_bhead_line_color_sd' => [
				'label'       => esc_html__('Dark: Accent Line Color', 'bunyad-admin'),
				'type'        => 'color',
				'selectors'   => [
					'{{WRAPPER}} .s-dark .block-head,
					.s-dark {{WRAPPER}} .block-head' => '--c-line: {{VALUE}};'
				],
				'default'     => '',
				'condition'   => ['type' => $this->common['supports_bhead_line_color']],
			],

			// Only for style c.
			'css_bhead_border_weight' => [
				'label'       => esc_html__('Border Weight', 'bunyad-admin'),
				'type'        => 'number',
				'selectors'   => [
					'{{WRAPPER}} .block-head' => '--border-weight: {{VALUE}}px;'
				],
				'default'     => '',
				'condition'   => ['heading_type' => 'c'],
			],

			'css_bhead_border_color' => [
				'label'       => esc_html__('Border Line Color', 'bunyad-admin'),
				'type'        => 'color',
				'selectors'   => [
					'{{WRAPPER}} .block-head' => '--c-border: {{VALUE}};'
				],
				'default'     => '',
				'condition'   => ['type' => $this->common['supports_bhead_border_color']],
			],
			'css_bhead_border_color_sd' => [
				'label'       => esc_html__('Dark: Border Line Color', 'bunyad-admin'),
				'type'        => 'color',
				'selectors'   => [
					'{{WRAPPER}} .s-dark .block-head,
					.s-dark {{WRAPPER}} .block-head' => '--c-border: {{VALUE}};'
				],
				'default'     => '',
				'condition'   => ['type' => $this->common['supports_bhead_border_color']],
			],

			'css_bhead_bg' => [
				'label'       => esc_html__('Background Color', 'bunyad-admin'),
				'type'        => 'color',
				'selectors'   => [
					'{{WRAPPER}} .block-head' => 'background-color: {{VALUE}};'
				],
				'default'     => '',
			],

			'css_bhead_pad' => [
				'label'       => esc_html__('Padding', 'bunyad-admin'),
				'type'        => 'dimensions',
				'size_units'  => ['%', 'px'],
				'devices'     => true,
				'selectors'   => [
					'{{WRAPPER}} .block-head' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
				'default'     => [],
				'condition'   => ['type!' => ['e']],
			],

			'css_bhead_inner_pad' => [
				'label'       => esc_html__('Inner Padding', 'bunyad-admin'),
				'type'        => 'number',
				'devices'     => true,
				'selectors'   => [
					'{{WRAPPER}} .block-head' => '--inner-pad: {{VALUE}}px;'
				],
				'default'     => [],
				'condition'   => ['type' => ['e']],
			],

			'h-style-view-more' => [
				'label'       => esc_html__('View More Link', 'bunyad-admin'),
				'type'        => 'heading',
				'separator'   => 'before',
				'condition'   => ['more_text!' => ''],
			],
			'css_view_more_typo' => [
				'label'       => esc_html__('More Link Typography', 'bunyad-admin'),
				'type'        => 'group',
				'group_type'  => 'typography',
				'selector'    => '{{WRAPPER}} .block-head .view-link',
				'default'     => [],
				'condition'   => ['more_text!' => ''],
			],
			'css_view_more_pad' => [
				'label'       => esc_html__('More Link Padding', 'bunyad-admin'),
				'type'        => 'dimensions',
				'devices'     => true,
				'selector'    => '{{WRAPPER}} .block-head .view-link',
				'selectors'   => [
					'{{WRAPPER}} .block-head .view-link' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
				'condition'   => ['more_text!' => ''],
			],

		];

		$this->options = $options;
		$this->_add_defaults();
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

	public function get_common_data(array $options = [])
	{
		$options = array_replace([
			'block_headings' => [],
			'heading_tags'   => [],
		], $options);


		return parent::get_common_data($options);
	}
}