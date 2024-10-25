<?php
namespace Bunyad\Blocks\Loops;

/**
 * Grid Small Block Options
 */
class Overlay_Options extends Grid_Options
{
	public $block_id = 'Overlay';

	/**
	 * @inheritDoc
	 */
	public function init($type = '')
	{
		parent::init($type);

		// Block name to be used by page builders
		$this->block_name = esc_html__('Overlay Block', 'bunyad-admin');

		$this->elementor_conf = [
			'title'      => $this->block_name,
			'icon'       => 'ts-ele-icon ts-ele-icon-overlay',
			'categories' => ['smart-mag-blocks'],
		];

		$this->options['sec-layout'] += [
			'title_style' => [
				'label'       => esc_html__('Titles Style', 'bunyad-admin'),
				'type'        => 'select',
				'default'     => '',
				'options'     =>[
					'' => esc_html__('Auto/Global', 'bunyad-admin')
				] + $this->common['post_title_styles'],
				'position'     => [
					'at' => 'after',
					'of' => 'title_lines'
				]
			],
		];

		$this->options['sec-style'] += [
			'css_overlay_padding' => [
				'label'       => esc_html__('Overlay Paddings', 'bunyad-admin'),
				'type'        => 'dimensions',
				'devices'     => true,
				'size_units'  => ['%', 'px'],
				'selectors'   => [
					'{{WRAPPER}} .grid-overlay .content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
				'default'     => [],
			],

			'css_media_height' => [
				'label'        => esc_html__('Forced Media Height', 'bunyad-admin'),
				'description'  => 'Not Recommended: By default media will change its height based on chosen aspect ratio.',
				'type'         => 'slider',
				'range'        => [
					'px' => ['min' => 100, 'max' => 1500, 'step' => 1]
				],
				'devices'      => true,
				'size_units'   => ['px'],
				'selectors'    => [
					'{{WRAPPER}} .media' => 'height: {{SIZE}}px;',
				],
			],
		];

		$options = &$this->options['sec-layout']['cat_labels_pos']['options'];
		$options = array_intersect_key($options, array_flip(['', 'top-left', 'top-right']));

		$this->options['sec-layout']['show_media']['type'] = 'hidden';

		$this->remove_options([
			'scheme', 
			'read_more',
			'excerpts',
			'numbers',
			
			'content_center',
			// Inherited from Grid, but not applicable.
			'media_location',
			'style',
			'large_style',
			'media_embed',

			'css_l_post_bg',
			'css_l_post_bg_sd',
			'css_l_post_pad',
			'css_content_border',
			'css_content_shadow',
			'css_content_bg',
			'css_content_bg_sd',
		]);
		
		$this->_add_defaults();
	}
}