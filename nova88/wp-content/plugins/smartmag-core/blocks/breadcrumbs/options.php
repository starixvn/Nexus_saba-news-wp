<?php
namespace Bunyad\Blocks;

/**
 * Ads block options
 */
class Breadcrumbs_Options extends Base\Options
{
	public $block_id = 'Breadcrumbs';

	public function init($type = '') 
	{
		// Block name to be used by page builders
		$this->block_name = esc_html__('Breadcrumbs', 'bunyad-admin');

		$this->elementor_conf = [
			'title'      => $this->block_name,
			'icon'       => 'ts-ele-icon eicon-progress-tracker',
			'categories' => ['smart-mag-blocks'],
		];

		/**
		 * General Options.
		 */
		$options['sec-general'] = [			
			'style' => [
				'label'       => esc_html__('Breadcrumbs Style', 'bunyad-admin'),
				'type'        => 'select',
				'options' => [
					'a' => esc_html__('A: Simple', 'bunyad-admin'),
					'b' => esc_html__('B: With Background', 'bunyad-admin'),
				],
				'label_block' => true,
				'default'     => 'a',
			],

			'width' => [
				'label'       => esc_html__('Breadcrumbs Width', 'bunyad-admin'),
				'type'        => 'select',
				'options' => [
					'full' => esc_html__('Full Width', 'bunyad-admin'),
					'wrap' => esc_html__('Site Width', 'bunyad-admin'),
				],
				'label_block' => true,
				'condition'   => ['style' => 'b'],
				'default'     => 'full',
			],

			'add_label' => [
				'label'       => esc_html__('Add Label?', 'bunyad-admin'),
				'type'        => 'switcher',
				'return_value' => '1',
				'default'      => '1',
			],

			'renderer' => [
				'label'       => esc_html__('Breadcrumbs Renderer', 'bunyad-admin'),
				'type'        => 'select',
				'default'      => '',
				'options' => [
					''         => esc_html__('Auto', 'bunyad-admin'),
					'sphere'   => esc_html__('Theme Breadcrumbs', 'bunyad-admin'),
					'rankmath' => esc_html__('RankMath Plugin', 'bunyad-admin'),
					'yoast'    => esc_html__('Yoast', 'bunyad-admin'),
				],
			],

			'label_text' => [
				'label'       => esc_html__('Label Text', 'bunyad-admin'),
				'type'        => 'text',
				'condition'   => ['add_label' => '1'],
				'default'     => '',
			],

			'disable_at' => [
				'label' => '',
				'type'  => 'hidden',
				'default' => []	
			]
		];

		/**
		 * Style options.
		 */
		$options['sec-style'] = [
			'css_margins' => [
				'label'       => esc_html__('Margins', 'bunyad-admin'),
				'type'        => 'dimensions',
				'devices'     => true,
				'size_units'  => ['%', 'px'],
				'selectors'   => [
					'{{WRAPPER}} .breadcrumbs' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
				'default'     => [],
			],

			'css_paddings' => [
				'label'       => esc_html__('Padding', 'bunyad-admin'),
				'type'        => 'dimensions',
				'devices'     => true,
				'size_units'  => ['%', 'px'],
				'selectors'   => [
					'{{WRAPPER}} .breadcrumbs' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
				'default'     => [],
			],

			'css_typography' => [
				'label'       => esc_html__('Typography', 'bunyad-admin'),
				'type'        => 'group',
				'group_type'  => 'typography',
				'selector'    => '{{WRAPPER}} .breadcrumbs',
				'default'     => [],
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
}