<?php

namespace Sphere\Core\Elementor\DynamicTags\Tags;

use Elementor\Core\DynamicTags\Tag;
use Elementor\Modules\DynamicTags\Module as TagsModule;

class AuthorName extends Tag
{
	public function get_name() 
	{
		return 'author-name';
	}

	public function get_title()
	{
		return esc_html__('Author Name', 'sphere-core');
	}

	public function get_group()
	{
		return 'author';
	}

	public function get_categories()
	{
		return [TagsModule::TEXT_CATEGORY];
	}

	public function render()
	{
		echo wp_kses_post(get_the_author());
	}	
}