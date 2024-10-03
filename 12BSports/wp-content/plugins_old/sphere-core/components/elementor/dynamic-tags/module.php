<?php

namespace Sphere\Core\Elementor\DynamicTags;

use Elementor\Plugin;

/**
 * Our custom dynamic tags for Elementor.
 */
class Module
{
	public $path;
	public $path_url;

	/**
	 * Types of custom layouts.
	 *
	 * @var array
	 */
	public $types = [];

	protected $groups_registered = false;
	
	protected static $instance;

	public function __construct()
	{
		// Register CPT whether elementor exists or not, mainly for import.
		add_action('elementor/dynamic_tags/register', [$this, 'register_tags']);

		// Elementor plugin missing.
		if (!did_action('elementor/loaded')) {
			return;
		}

		$this->path = \Sphere\Core\Plugin::instance()->path . 'components/elementor/layouts/';
		$this->path_url = \Sphere\Core\Plugin::instance()->path_url . 'components/elementor/layouts/';
	}

	public function register_tags($dynamic_tags)
	{
		$this->register_groups();
		
		$tags = [
			'ArchiveTitle',
			'Copyright',
		];

		// These are very similar to Elementor Pro, so skip in pro.
		if (!defined('ELEMENTOR_PRO_VERSION')) {
			array_push($tags, ...[
				'ArchiveDescription',
				'AuthorName',
				'SiteUrl',
			]);
		}

		foreach ($tags as $tag_class) {
			$class = __NAMESPACE__ . '\Tags\\' . $tag_class;
			$dynamic_tags->register(new $class);
		}
	}

	public function get_groups()
	{
		if (defined('ELEMENTOR_PRO_VERSION')) {
			return [];
		}

		return [
			'site' => [
				'title' => 'Site',
			],
			'archive' => [
				'title' => 'Archive',
			],
			'author' => [
				'title' => 'Author',
			]
		];
	}
	
	public function register_groups()
	{
		if ($this->groups_registered) {
			return;
		}

		foreach ($this->get_groups() as $group => $settings) {
			Plugin::instance()->dynamic_tags->register_group($group, $settings);
		}
	}
	/**
	 * Get singleton object
	 * 
	 * @return self
	 */
	public static function instance()
	{
		if (!isset(self::$instance)) {
			self::$instance = new self;
		}
		
		return self::$instance;
	}
}