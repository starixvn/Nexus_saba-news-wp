<?php

namespace Sphere\Core\Elementor\Layouts\Documents;

use Sphere\Core\Elementor\Layouts\Module;

/**
 * Base document class.
 */
abstract class Base extends \Elementor\Core\Base\Document
{
	public static function get_properties()
	{
		$properties = parent::get_properties();

		$properties['admin_tab_group'] = '';
		$properties['support_kit']     = true;

		return $properties;
	}

	public function get_elements_raw_data($data = null, $with_html_content = false)
	{
		Module::instance()->preview->switch_to_preview_query();

		$editor_data = parent::get_elements_raw_data($data, $with_html_content);

		Module::instance()->preview->restore_current_query();

		return $editor_data;
	}

	public function render_element($data)
	{
		Module::instance()->preview->switch_to_preview_query();

		$render_html = parent::render_element( $data );

		Module::instance()->preview->restore_current_query();

		return $render_html;
	}

	/**
	 * Query args to use when overriding the global query for preview.
	 *
	 * @return array
	 */
	public abstract function get_preview_as_query_args();
}