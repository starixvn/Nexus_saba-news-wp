<?php

namespace Bunyad\Blocks\Loops;

use Bunyad\Blocks\Base\LoopOptions;

/**
 * Grid Block Options
 */
class Grid_Options extends LoopOptions
{
	public $block_id = 'Grid';

	/**
	 * @inheritDoc
	 */
	public function init($type = '')
	{
		parent::init();

		// Block name to be used by page builders
		$this->block_name = esc_html__('Grid Block', 'bunyad-admin');

		$this->elementor_conf = [
			'title'      => $this->block_name,
			'icon'       => 'ts-ele-icon ts-ele-icon-grid',
			'categories' => ['smart-mag-blocks'],
		];

		// // Setup and extend options
		// $this->options = array_replace_recursive($this->options, [
			
		// ]);

		$this->options['sec-general']['style'] = [
			'label'       => esc_html__('Style', 'bunyad-admin'),
			'type'        => 'select',
			'options'     => [
				''        => esc_html__('Normal', 'bunyad-admin'),
				'sm'      => esc_html__('Small', 'bunyad-admin'),
				'stylish' => esc_html__('Stylish', 'bunyad-admin'),
				'card'    => esc_html__('Cards', 'bunyad-admin'),
				'lg'      => esc_html__('Large Post', 'bunyad-admin'),
			],
			'default'     => '',
		];

		// Large style, if style is specified.
		$this->options['sec-general']['large_style'] = [
			'label'       => esc_html__('Large Style', 'bunyad-admin'),
			'type'        => 'select',
			'options'     => [
				''    => esc_html__('Normal', 'bunyad-admin'),
				'legacy'  => esc_html__('Legacy (Not Recommended)', 'bunyad-admin'),
			],
			'default'     => '',
			'condition'   => ['style' => 'lg']
		];

		$this->options['sec-layout']['media_location'] = [
			'label'       => esc_html__('Media Location', 'bunyad-admin'),
			'type'        => 'select',
			'options'     => [
				''       => esc_html__('Default (Above)', 'bunyad-admin'),
				'below'  => esc_html__('Below Title', 'bunyad-admin'),
			],
			'position'    => [
				'at' => 'after',
				'of' => 'show_media'
			],
			'default'     => '',
			'condition'   => ['show_media' => '1'],
		];

		$this->options['sec-layout']['media_embed'] = [
			'label'       => esc_html__('Media Embeds', 'bunyad-admin'),
			'description' => 'Embed the video, gallery etc. (by post format) - instead of image. Not recommended as it can affect speed.',
			'type'         => 'switcher',
			'return_value' => '1',
			'default'      => '1',
			'position'    => [
				'at' => 'after',
				'of' => 'media_ratio_custom'
			],
			'condition'   => ['show_media' => '1'],
		];

		$this->options['sec-layout']['show_content'] = [
			'label'        => esc_html__('Show Content', 'bunyad-admin'),
			'description'  => 'Disabling this will hide all content except the media image.',
			'type'         => 'switcher',
			'return_value' => '1',
			'default'      => '1',
			'separator'    => 'before',
			'position'     => [
				'at' => 'after',
				'of' => 'post_formats_pos',
			]
		];

		// Numbers
		$this->options['sec-layout']['numbers'] = [
			'label'       => esc_html__('Add Numbers', 'bunyad-admin'),
			'description' => 'Add 1, 2, 3. etc. numbers for posts.',
			'type'         => 'select',
			'options'      => [
				''   => esc_html__('Disabled', 'bunyad-admin'),
				'a'  => esc_html__('Simple A: Before Title (Centered)', 'bunyad-admin'),
				'b'  => esc_html__('Simple B: Before Content', 'bunyad-admin'),
				'c'  => esc_html__('Circled', 'bunyad-admin'),
			],
			'default'     => '',
			'position'    => [
				'at' => 'after',
				'of' => 'media_embed'
			],
		];

		// Carousel options.
		$this->options['sec-carousel'] = $this->common['carousel_fields'];

		$this->remove_options([
			'separators'
		]);

		/**
		 * Widget only options.
		 */
		if ($type === 'widget') {

			// Force default to 1 column instead of 2.
			$col_option = &$this->options['sec-layout']['columns'];
			$col_option = array_replace(
				$col_option,
				[
					'default' => 1,
					'default_forced' => true
				]
			);
		}

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