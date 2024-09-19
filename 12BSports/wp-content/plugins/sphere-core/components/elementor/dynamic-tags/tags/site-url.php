<?php

namespace Sphere\Core\Elementor\DynamicTags\Tags;

use Elementor\Core\DynamicTags\Data_Tag;
use Elementor\Modules\DynamicTags\Module as TagsModule;

class SiteUrl extends Data_Tag
{
	public function get_name() 
	{
		return 'site-url';
	}

	public function get_title()
	{
		return esc_html__('Site URL', 'sphere-core');
	}

	public function get_group()
	{
		return 'site';
	}

	public function get_categories()
	{
		return [TagsModule::URL_CATEGORY];
	}

	public function get_value(array $options = [])
	{
		return home_url();
	}	
}