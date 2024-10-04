<?php

namespace Sphere\Core\Elementor\DynamicTags\Tags;

use Elementor\Controls_Manager;
use Elementor\Core\DynamicTags\Tag;
use Elementor\Modules\DynamicTags\Module as TagsModule;

class ArchiveTitle extends Tag
{
	public function get_name() 
	{
		return 'archive-title';
	}

	public function get_title()
	{
		return esc_html__('Archive Title', 'sphere-core');
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
		$include_context = 'yes' === $this->get_settings('include_context');

		if ($include_context) {
			$title = \Bunyad::archives() ? \Bunyad::archives()->get_heading() : get_the_archive_title();
		}
		else {
			add_filter('get_the_archive_title_prefix', '__return_empty_string');
			$title = get_the_archive_title();
			remove_filter('get_the_archive_title_prefix', '__return_empty_string');
		}

		// Fix for archive preview sample titles.
		if ($title === 'Sample Title') {
			if ($include_context && strpos($title, ':') === false) {
				$title = 'Browsing: <span>' . $title . '</span>';
			}
		}

		echo wp_kses_post($title);
	}

	protected function register_controls() {
		$this->add_control(
			'include_context',
			[
				'label' => esc_html__('Include Context', 'sphere-core'),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);
	}
}