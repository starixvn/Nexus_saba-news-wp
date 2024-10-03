<?php

namespace Bunyad\Blocks\Loops;

use Bunyad\Blocks\Base\LoopOptions;

/**
 * ListSmall Block Options
 */
class PostsSmall_Options extends LoopOptions
{
	public $block_id = 'PostsSmall';

	/**
	 * @inheritDoc
	 */
	public function init()
	{
		parent::init();

		// Block name to be used by page builders
		$this->block_name = esc_html__('Small List Block', 'bunyad-admin');

		$this->elementor_conf = [
			'title'      => $this->block_name,
			'icon'       => 'ts-ele-icon ts-ele-icon-small',
			'categories' => ['smart-mag-blocks'],
		];

		$this->options['sec-general']['style'] = [
			'label'       => esc_html__('Style', 'bunyad-admin'),
			'type'        => 'select',
			'options'     => [
				'a'  => esc_html__('Style A: With Thumbs', 'bunyad-admin'),
				'b'  => esc_html__('Style B: Arrows / No Thumbs', 'bunyad-admin'),
			],
			'default'     => 'a',
			'position'    => ['at' => 'before', 'of' => 'h-advanced-1']
		];

		// Add to beginning of sec-layout.
		$this->options['sec-layout'] = [

			'media_pos'  => [
				'label'       => esc_html__('Image Position', 'bunyad-admin'),
				'type'        => 'select',
				'options'     => [
					'left'    => esc_html__('Left', 'bunyad-admin'),
					'right'   => esc_html__('Right', 'bunyad-admin'),
				],
				'default'     => '',
				'position'    => ['at' => 'after', 'of' => 'show_media'],
				'condition'   => ['show_media' => '1']
			],

			'media_width' => [
				'label'       => esc_html__('Image Width %', 'bunyad-admin'),
				'type'        => 'number',
				// 'devices'     => true,
				'input_attrs' => ['min' => 1, 'max' => 85],
				'default'     => '',
				'position'    => ['at' => 'after', 'of' => 'show_media'],
				'condition'   => ['show_media' => '1'],
				'selectors'   => [
					'{{WRAPPER}} .media' => 'width: {{SIZE}}%; max-width: 85%;'
				],
			],

			'css_media_width' => [
				'label'       => esc_html__('Max Width', 'bunyad-admin'),
				'type'        => 'slider',
				'devices'     => true,
				'default'     => [],
				'position'    => ['at' => 'after', 'of' => 'media_width'],
				'condition'   => ['media_width!' => ''],
				'size_units'  => ['%', 'px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 300,
					],
				],
				'selectors'   => [
					'{{WRAPPER}} .media' => 'max-width: {{SIZE}}{{UNIT}};'
				],
			],

		] + $this->options['sec-layout'];

		// Carousel options.
		$this->options['sec-carousel'] = $this->common['carousel_fields'];
		
		$this->remove_options([
			'read_more',
			'excerpts',
			'content_center',

			// Only center supported.
			'post_formats_pos',
		]);
		
		$this->_add_defaults();
	}

	public function get_sections()
	{
		$sections = parent::get_sections();
		\Bunyad\Util\array_insert($sections, 'sec-layout', [
			'sec-carousel' => ['label' => esc_html__('Carousel', 'bunyad-admin')],
		], 'after');

		return $sections;
	}
}