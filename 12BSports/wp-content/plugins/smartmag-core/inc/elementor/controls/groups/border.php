<?php
namespace Bunyad\Elementor\Controls\Groups;

/**
 * Extended border control with some extra options.
 */
class Border extends \Elementor\Group_Control_Border
{
	protected static $fields;

	public static function get_type()
	{
		return 'bunyad-border';
	}

	/**
	 * Modify the selector for dark dynamically since init is run once.
	 *
	 * @param array $fields
	 * @return array
	 */
	protected function prepare_fields($fields)
	{
		$args = $this->get_args();
		$fields = parent::prepare_fields($fields);

		if (isset($fields['color_dark'])) {
			$fields['color_dark']['selectors'] = [
				$args['selector_color_dark'] => 'border-color: {{VALUE}};',
			];
		}

		return $fields;
	}

	protected function init_fields()
	{
		$args = $this->get_args();
		$fields = parent::init_fields();

		if (!isset($args['selector_color_dark'])) {
			return $fields;
		}

		$fields['color_dark'] = [
			'label' => esc_html__('Dark: Color', 'bunyad-admin'),
			'type' => \Elementor\Controls_Manager::COLOR,
			'default' => '',
			// To be added later at prepare time since init isn't dynamic and only called once.
			'selectors' => [],
			'condition' => [
				'border!' => ['', 'none'],
			],
		];

		return $fields;
	}

	protected function get_default_options()
	{
		return [
			'popover' => [
				'starter_title' => esc_html__('Border Type', 'bunyad-admin'),
				'starter_name' => 'box_border_type',
				'starter_value' => 'yes',
				'settings' => [
					'render_type' => 'ui',
				],
			],
		];
	}
}