<?php

namespace Bunyad\Blocks\Loops;

use Bunyad\Blocks\Base\LoopOptions;

/**
 * Featured Grid Options
 */
class FeatGrid_Options extends LoopOptions
{
	public $block_id = 'FeatGrid';

	/**
	 * @inheritDoc
	 */
	public function init()
	{
		parent::init();

		// Block name to be used by page builders
		$this->block_name = esc_html__('Featured Area Grids', 'bunyad-admin');

		$this->elementor_conf = [
			'title'      => $this->block_name,
			'icon'       => 'ts-ele-icon ts-ele-icon-feat-grid',
			'categories' => ['smart-mag-blocks'],
		];

		$this->options['sec-general'] += [
			'grid_type' => [
				'label'       => esc_html__('Style', 'bunyad-admin'),
				'description' => '',
				'type'        => 'select',
				'options'     => $this->common['featured_type_options'],
				'default'     => 'container',
			],

			'grid_width' => [
				'label'       => esc_html__('Grid Width', 'bunyad-admin'),
				'description' => esc_html__('Note: If setting to full width, make sure the container section is also set to full width.', 'bunyad-admin'),
				'type'        => 'select',
				'options'     => [
					'container' => esc_html__('Container / Boxed', 'bunyad-admin'),
					'viewport'  => esc_html__('Full Browser Width', 'bunyad-admin'),
				],
				'default'     => 'container',
			],

			'has_slider' => [
				'label'       => esc_html__('Enable Slider', 'bunyad-admin'),
				'description' => esc_html__('Uses a static grid by default. Enabling a slider changes mobile view as well. Note: Make sure to increase number of posts.', 'bunyad-admin'),
				'type'        => 'switcher',
				'default'     => '1',
			],
		];

		// Custom media ratio for featured grids.
		$this->options['sec-layout'] += [
			'title_style' => [
				'label'       => esc_html__('Titles Style', 'bunyad-admin'),
				'type'        => 'select',
				'default'     => '',
				'options'     =>[
					'' => esc_html__('Auto/Global', 'bunyad-admin')
				] + $this->common['post_title_styles'],
				'position'     => [
					'at' => 'before',
					'of' => 'cat_labels'
				]
			],

			'has_ratio' => [
				'label'        => esc_html__('Use Aspect Ratio', 'bunyad-admin'),
				'description' => esc_html__('Keep an aspect ratio for main item(s) over devices. Disable to use a fixed height instead.', 'bunyad-admin'),
				'type'         => 'switcher',
				'return_value' => '1',
				'default'      => '1',
				'position'     => [
					'at' => 'after',
					'of' => 'cat_labels'
				]
			],
			'css_media_ratio' => [
				'label'        => esc_html__('Image Ratio', 'bunyad-admin'),
				'type'         => 'number',
				'input_attrs'  => ['min' => 0.25, 'max' => 4.5, 'step' => .1],
				'default'      => '',
				'selectors'    => [
					'{{WRAPPER}} .feat-grid' => '--main-ratio: {{SIZE}};'
				],
				'position'     => [
					'at' => 'after',
					'of' => 'has_ratio'
				],
				'condition' => ['has_ratio' => '1'],
			],

			'css_media_height_eq' => [
				'label'        => esc_html__('Grid Height', 'bunyad-admin'),
				'type'         => 'slider',
				'range'        => [
					'px' => ['min' => 100, 'max' => 1500, 'step' => 1]
				],
				'devices'      => true,
				'size_units'   => ['px'],
				'selectors'    => [
					'{{WRAPPER}} .feat-grid' => '--main-height: {{SIZE}}px;',
					'{{WRAPPER}} .feat-grid-equals .item' => 'max-height: initial;'
				],
				'position'     => [
					'at' => 'after',
					'of' => 'has_ratio'
				],
				'condition' => [
					'has_ratio!' => '1',
					'grid_type!' => ['grid-a', 'grid-b', 'grid-c', 'grid-d']
				],
			],

			'css_media_height' => [
				'label'        => esc_html__('Grid Height', 'bunyad-admin'),
				'type'         => 'slider',
				'range'        => [
					'px' => ['min' => 100, 'max' => 1500, 'step' => 1]
				],
				'devices'      => false,
				'size_units'   => ['px'],
				'selectors'    => [
					'{{WRAPPER}} .feat-grid' => '--main-height: {{SIZE}}px;',
				],
				'position'     => [
					'at' => 'after',
					'of' => 'has_ratio'
				],
				'condition' => [
					'has_ratio!' => '1',
					'grid_type' => ['grid-a', 'grid-b', 'grid-c', 'grid-d']
				],
			],
		];

		$this->options['sec-style'] += [


			'overlay_style' => [
				'label'       => esc_html__('Overlay Style', 'bunyad-admin'),
				'description' => '',
				'type'        => 'select',
				'options'     => [
					'a' => esc_html__('Style A - Bottom Shade', 'bunyad-admin'),
					'b' => esc_html__('Style B - Full Overlay', 'bunyad-admin'),
				],
				'default'     => 'a',
			],

			'content_position' => [
				'label'       => esc_html__('Overlay Content Position', 'bunyad-admin'),
				'description' => '',
				'type'        => 'select',
				'options'     => [
					'bottom'     => esc_html__('Bottom Left', 'bunyad-admin'),
					'bot-center' => esc_html__('Bottom Centered', 'bunyad-admin'),
					'center'     => esc_html__('Centered', 'bunyad-admin'),
					'top-center' => esc_html__('Top Centered', 'bunyad-admin'),
					'top'        => esc_html__('Top Left', 'bunyad-admin'),
				],
				'default'     => 'bottom',
			],

			'meta_on_hover' => [
				'label'        => esc_html__('Meta On Hover', 'bunyad-admin'),
				'description' => esc_html__('Show bottom meta on hover only.', 'bunyad-admin'),
				'type'         => 'switcher',
				'return_value' => '1',
				'default'      => '1',
			],

			'hover_effect' => [
				'label'       => esc_html__('Hover Effect', 'bunyad-admin'),
				'description' => '',
				'type'        => 'select',
				'options' => [
					''           => esc_html__('None', 'bunyad-admin'),
					'hover-zoom' => esc_html__('Zoom Image', 'bunyad-admin'),
				],
				'default'     => '',
			],

			'css_grid_gap' => [
				'label'       => esc_html__('Gap / Spacing', 'bunyad-admin'),
				'description' => esc_html__('Spacing between posts.', 'bunyad-admin'),
				'type'        => 'slider',
				'devices'     => true,
				'size_units'  => ['px'],
				'selectors'    => [
					'{{WRAPPER}} .feat-grid' => '--grid-gap: {{SIZE}}{{UNIT}};'
				],
				'default'     => [],
			],

			'css_overlay_pad_lg' => [
				'label'       => esc_html__('Overlay Pad (Large Items)', 'bunyad-admin'),
				'type'        => 'dimensions',
				'devices'     => true,
				'size_units'  => ['%', 'px'],
				'selectors'   => [
					'{{WRAPPER}} .item-large .content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
				'default'     => [],
				'separator'   => 'before',
			],

			'css_overlay_pad_md' => [
				'label'       => esc_html__('Overlay Pad (Medium Items)', 'bunyad-admin'),
				'type'        => 'dimensions',
				'devices'     => true,
				'size_units'  => ['%', 'px'],
				'selectors'   => [
					'{{WRAPPER}} .item-medium .content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
				'default'     => [],
			],

			'css_overlay_pad_sm' => [
				'label'       => esc_html__('Overlay Pad (Small Items)', 'bunyad-admin'),
				'type'        => 'dimensions',
				'devices'     => true,
				'size_units'  => ['%', 'px'],
				'selectors'   => [
					'{{WRAPPER}} .item-small .content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
				'default'     => [],
			],
		];

		$this->options['sec-style-titles'] = [

			'css_title_size_sm' => [
				'label'       => esc_html__('Small Title Size', 'bunyad-admin'),
				'desc'        => esc_html__('Used for small slices of the grid.', 'bunyad-admin'),
				'type'        => 'number',
				'input_attrs' => ['max' => 100, 'min' => 9],
				'selectors'   => [
					'{{WRAPPER}} .feat-grid' => '--feat-grid-title-s: {{VALUE}}px;',
				],
				'default'     => '',
			],

			'css_title_size_md' => [
				'label'       => esc_html__('Medium Title Size', 'bunyad-admin'),
				'desc'        => esc_html__('Used for small slices of the grid.', 'bunyad-admin'),
				'type'        => 'number',
				'input_attrs' => ['max' => 100, 'min' => 9],
				'selectors'   => [
					'{{WRAPPER}} .feat-grid' => '--feat-grid-title-m: {{VALUE}}px;',
				],
				'default'     => '',
			],

			'css_title_size_lg' => [
				'label'       => esc_html__('Large Title Size', 'bunyad-admin'),
				'desc'        => esc_html__('Used for small slices of the grid.', 'bunyad-admin'),
				'type'        => 'number',
				'input_attrs' => ['max' => 100, 'min' => 9],
				'selectors'   => [
					'{{WRAPPER}} .feat-grid' => '--feat-grid-title-l: {{VALUE}}px;',
				],
				'default'     => '',
			],

			'h-style-titles-advanced' => [
				'label'       => esc_html__('Advanced', 'bunyad-admin'),
				'type'        => 'heading',
				'separator'   => 'before'
			],

			'css_title_typo_sm' => [
				'label'       => esc_html__('Titles Typpography: Small', 'bunyad-admin'),
				'type'        => 'group',
				'group_type'  => 'typography',
				'selector'    => '{{WRAPPER}} .feat-grid .item-small .post-title',
				'default'     => [],
			],

			'css_title_typo_md' => [
				'label'       => esc_html__('Titles Typpography: Medium', 'bunyad-admin'),
				'type'        => 'group',
				'group_type'  => 'typography',
				'selector'    => '{{WRAPPER}} .feat-grid .item-medium .post-title',
				'default'     => [],
			],

			'css_title_typo_lg' => [
				'label'       => esc_html__('Titles Typpography: Large', 'bunyad-admin'),
				'type'        => 'group',
				'group_type'  => 'typography',
				'selector'    => '{{WRAPPER}} .feat-grid .item-large .post-title',
				'default'     => [],
			],

			'css_title_max_width' => [
				'label'       => esc_html__('Max Title Width', 'bunyad-admin'),
				'desc'        => esc_html__('Used for small slices of the grid.', 'bunyad-admin'),
				'type'        => 'slider',
				'devices'     => true,
				'range'        => [
					'px' => ['min' => 100, 'max' => 1500, 'step' => 1],
					'%' =>  ['min' => 10, 'max' => 100, 'step' => 1]
				],
				'size_units'  => ['%', 'px'],
				'selectors'   => [
					'{{WRAPPER}} .feat-grid .post-title' => 'max-width: {{SIZE}}{{UNIT}};',
				],
				'default'     => [],
			],
		];

		$this->remove_options([
			'pagination',
			'scheme',
			'container_width', 
			'columns', 
			'media_ratio', 
			'media_ratio_custom',
			// 'cat_labels',
			// 'cat_labels_pos',
			'show_media',
			'separators',
			'separators_cols',
			'title_lines',
			'excerpts',
			'excerpt_lines',
			'excerpt_length',
			'read_more',
			'content_center',

			'column_gap',
			'css_title_typo',
			'css_excerpt_typo',
			'css_excerpt_lines',
		]);

		// Remove spacings. We have custom gaps for featured grids.
		$this->remove_options([
			'css_column_gap',
			'css_row_gap'
		]);

		// Only two options from content/box styling.
		if (isset($this->options['sec-style-content'])) {
			$this->options['sec-style-content'] = array_filter(
				$this->options['sec-style-content'],
				function($key) {
					return in_array($key, ['css_meta_above_margins', 'css_meta_below_margins']);
				},
				ARRAY_FILTER_USE_KEY
			);
		}
		
		$this->_add_defaults();

	}

	// public function get_sections()
	// {
	// 	$sections = parent::get_sections();
	// 	$sections += [
	// 		'sec-style-titles' => [
	// 			'label' => esc_html__('Post Titles', 'bunyad-admin'),
	// 			'tab'   => 'style'
	// 		]
	// 	];

	// 	return $sections;
	// }
}