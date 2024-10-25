<?php

namespace Sphere\Core\Elementor\DynamicTags\Tags;

use Elementor\Core\DynamicTags\Tag;
use Elementor\Modules\DynamicTags\Module as TagsModule;

class ArchiveDescription extends Tag
{
	public function get_name() 
	{
		return 'archive-description';
	}

	public function get_title()
	{
		return esc_html__('Archive Description', 'sphere-core');
	}

	public function get_group()
	{
		return 'archive';
	}

	public function get_categories()
	{
		return [TagsModule::TEXT_CATEGORY];
	}

	public function render()
	{
		echo wp_kses_post(get_the_archive_description());
	}	
}