<?php
namespace Sphere\Core\Elementor\Layouts\Documents;

class Footer extends Base
{
	public static function get_type()
	{
		return 'ts-footer';
	}

	public static function get_title()
	{
		return esc_html__('Footer', 'sphere-core');
	}

	public static function get_plural_title()
	{
		return esc_html__('Footers', 'sphere-core');
	}

	protected static function get_site_editor_icon()
	{
		return 'eicon-footer';
	}

	public function get_preview_as_query_args()
	{
		return [
			'p' => get_the_ID()
		];
	}
}