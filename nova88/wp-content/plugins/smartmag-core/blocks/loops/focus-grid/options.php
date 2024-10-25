<?php

namespace Bunyad\Blocks\Loops;

use Bunyad\Blocks\Base\LoopOptions;

/**
 * Grid Block Options
 */
class FocusGrid_Options extends LoopOptions
{
	public $block_id = 'FocusGrid';
	protected $supported_columns = [3, 4];

	/**
	 * @inheritDoc
	 */
	public function init()
	{
		parent::init();

		// Block name to be used by page builders
		$this->block_name = esc_html__('Focus Grid Block', 'bunyad-admin');

		$this->elementor_conf = [
			'title'      => $this->block_name,
			'icon'       => 'ts-ele-icon ts-ele-icon-focus-grid',
			'categories' => ['smart-mag-blocks'],
		];

		$this->options['sec-style-titles'] += [
			'css_title_typo_sm' => [
				'label'       => esc_html__('Small Titles Typography', 'bunyad-admin'),
				'type'        => 'group',
				'group_type'  => 'typography',
				'selector'    => '{{WRAPPER}} .loop-grid-sm .post-title',
				'default'     => [],
				'position'     => [
					'at' => 'after',
					'of' => 'css_title_typo'
				]
			],
		];

		// Remove devices.
		unset($this->options['sec-layout']['columns']['devices']);

		// Remove spacings.
		$this->remove_options([
			'separators',
			'separators_cols',
			'css_column_gap',
			'css_row_gap',
		]);
		
		$this->_add_defaults();
	}
}