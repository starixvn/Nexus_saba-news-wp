<?php

namespace Bunyad\Elementor;

use Bunyad\Blocks\Base\LoopBlock;
use Bunyad\Blocks\Base\LoopOptions;

/**
 * Extra section options.
 */
class SectionOptions extends LoopOptions
{
	public function init()
	{
		parent::init();
		if (LoopWidget::is_elementor()) {
			parent::init_editor();
		}

		$options = [
			'sec-query' => [
				// 'posts' => $this->options['sec-general']['posts']
			] + $this->options['sec-filter']
		];

		$this->options = $options;
		$this->_add_defaults(LoopBlock::get_query_props());

		/**
		 * IMPORTANT: Any changes will also have to reflect in blockQueryKeys in elementor-editor.js
		 */
		$this->options['sec-query']['query_type'] = array_replace(
			$this->options['sec-query']['query_type'],
			[
				'options' => [
					'none'        => esc_html__('None', 'bunyad-admin'),
					'custom'      => esc_html__('Custom', 'bunyad-admin'),

					// Main isn't supported yet.
					'main-custom' => esc_html__('Archive / Main Query', 'bunyad-admin'),

					// Using parent's query not supported yet.
					// 'section' => esc_html__('Section Query (Parent Section)', 'bunyad-admin'),
					
				],
				'default' => 'none'
			]
		);
	}
}