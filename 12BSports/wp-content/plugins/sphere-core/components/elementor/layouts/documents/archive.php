<?php
namespace Sphere\Core\Elementor\Layouts\Documents;

class Archive extends Base
{
	public static function get_type()
	{
		return 'ts-archive';
	}

	public static function get_title()
	{
		return esc_html__('Archive', 'sphere-core');
	}

	public static function get_plural_title()
	{
		return esc_html__('Archives', 'sphere-core');
	}

	protected static function get_site_editor_icon()
	{
		return 'eicon-archive';
	}

	public function get_preview_as_query_args()
	{
		return [
			'post_type'   => 'post',
			'post_status' => 'publish',
			'numberposts' => get_option('posts_per_page', 10),
		];
	}

	public function after_preview_switch_to_query()
	{
		global $wp_query;
		$wp_query->is_archive = true;

		add_filter('get_the_archive_title', [$this, 'dummy_archive_title']);
		add_filter('get_the_archive_description', [$this, 'dummy_archive_desc']);
	}

	public function dummy_archive_title() 
	{
		return 'Sample Title';
	}

	public function dummy_archive_desc()
	{
		return 'Sample description text here for the archive.';
	}

	public function after_restore_current_query()
	{
		remove_filter('get_the_archive_title', [$this, 'dummy_archive_title']);
		remove_filter('get_the_archive_description', [$this, 'dummy_archive_desc']);
	}

}