<?php

namespace Sphere\Core\Elementor\DynamicTags\Tags;

use Elementor\Core\DynamicTags\Tag;
use Elementor\Modules\DynamicTags\Module as TagsModule;

class Copyright extends Tag
{
	public function get_name() 
	{
		return 'ts-copyright';
	}

	public function get_title()
	{
		return esc_html__('Footer Copyright', 'sphere-core');
	}

	public function get_group()
	{
		return 'site';
	}

	public function get_categories()
	{
		return [TagsModule::TEXT_CATEGORY];
	}

	public function render()
	{
		\Bunyad::theme()->the_copyright();
	}	
}