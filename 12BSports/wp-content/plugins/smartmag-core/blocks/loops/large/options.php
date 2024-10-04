<?php

namespace Bunyad\Blocks\Loops;

use Bunyad\Blocks\Base\LoopOptions;

/**
 * Large Block Options
 */
class Large_Options extends Grid_Options
{
	public $block_id = 'Large';

	/**
	 * @inheritDoc
	 */
	public function init($type = '')
	{
		parent::init($type);

		// Block name to be used by page builders
		$this->block_name = esc_html__('Large Block', 'bunyad-admin');

		$this->elementor_conf = [
			'title'      => $this->block_name,
			'icon'       => 'ts-ele-icon ts-ele-icon-large',
			'categories' => ['smart-mag-blocks'],

		];

		// // Setup and extend options
		// $this->options = array_replace_recursive($this->options, [
			
		// ]);		
		$this->_add_defaults();
	}
}