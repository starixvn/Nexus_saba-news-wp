<?php
namespace Bunyad\Blocks;

/**
 * Ads block options
 */
class Codes_Options extends Base\Options
{
	public $block_id = 'Codes';

	public function init($type = '') 
	{
		// Block name to be used by page builders
		$this->block_name = esc_html__('Ads / Codes', 'bunyad-admin');

		$this->elementor_conf = [
			'title'      => $this->block_name,
			'icon'       => 'ts-ele-icon eicon-code',
			'categories' => ['smart-mag-blocks'],
		];

		/**
		 * General Options.
		 */
		$options['sec-general'] = [			
			'code' => [
				'name'    => 'code',
				'label'   => esc_html__('Ad / HTML Code', 'bunyad-admin'),
				'type'    => 'textarea',
				'label_block' => true,
				'sanitize_callback' => false,
			],

			'code_amp' => [
				'name'    => 'code_amp',
				'label'   => esc_html__('AMP Code', 'bunyad-admin'),
				'description' => 'Only used if its an AMP enabled page and its an AMP visitor.',
				'type'    => 'textarea',
				'label_block' => true,
				'sanitize_callback' => false,
			],

			'label' => [
				'name'    => 'label',
				'label'   => esc_html__('Label (Optional)', 'bunyad-admin'),
				'type'    => 'text',
				'default' => '',
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
					// 'description' => esc_html__('Not recommended except for minimal style.', 'bunyad-admin'),
					'type'     => 'text',
					'position' => [
						'of' => 'code',
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
		];
	}
}