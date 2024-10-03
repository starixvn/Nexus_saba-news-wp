<?php

namespace Sphere\PostViews;

use Sphere\PostViews\Admin\Admin;
use Sphere\PostViews\Admin\OptionsData;

/**
 * Sphere Posts Views Counter plugin.
 * 
 * This object is also a facade with container methods.
 * 
 * @method static Options options()
 * @method static Admin admin()
 * @method static Front front()
 * @method static Endpoint endpoint()
 * @method static Api api()
 */
class Plugin
{
	/**
	 * Plugin version
	 */
	const VERSION = '1.0.1';
	
	public static $instance;

	/**
	 * @var string Path to plugin folder, trailing slashed.
	 */
	public $dir_path = '';

	/**
	 * @var string URL to plugin folder, trailing slashed.
	 */
	public $dir_url = '';

	/**
	 * @var string Plugin main file path.
	 */
	public $plugin_file;

	/**
	 * @var object Container DI container.
	 */
	public $container;

	/**
	 * Setup runs early to setup autoloader and basic functionality.
	 *
	 * @return void
	 */
	public function setup()
	{
		$this->dir_path = plugin_dir_path($this->plugin_file);
		$this->dir_url  = plugin_dir_url($this->plugin_file);

		$this->register_autoloader();

		// Init on plugins loaded
		add_action('plugins_loaded', [$this, 'init']);
	}

	/**
	 * Register our autoloader. Maybe called from outside.
	 */
	public function register_autoloader()
	{
		// Fallback if setup wasn't done.
		$this->dir_path = $this->dir_path ?: trailingslashit(dirname(__DIR__));

		// Fire up the main autoloader.
		require_once $this->dir_path . 'inc/autoloader.php';
		new Autoloader([
			'Sphere\PostViews\\'  => $this->dir_path . 'inc', 
		]);
	}

	public function init()
	{
		$this->container = new Container;
		$this->register_services();

		// Don't need to be referenced, hence outside container.
		new Query\Setup;
		new OptionsData;

		// Load the options.
		$this->container['options']->init();

		// Load front-end.
		$this->container['front']->init();

		if (!self::options()->spv_short_endpoint) {
			$this->container['endpoint']->init();
		}

		add_action('bunyad_core_post_init', function() {
			$this->container['theme_compat']->init();
		});
	}

	public function register_services()
	{
		// Lazy-loaded singletons.
		$this->container['options']      = new Options(OptionsData::OPTIONS_KEY);
		$this->container['translate']    = $this->container->shared(__NAMESPACE__ . '\Translate');
		$this->container['theme_compat'] = $this->container->shared(__NAMESPACE__ . '\ThemeCompat');

		// Construct right now.
		$this->container['front']    = new Front($this->container['options'], $this->container['translate']);
		$this->container['endpoint'] = new Endpoint($this->container['options']);
		$this->container['api']      = new Api($this->container['options'], $this->container['translate']);
		$this->container['admin']    = new Admin($this->container['options'], $this->container['api']);
	}

	/**
	 * @uses Container::get()
	 */
	public static function __callStatic($name, $args = [])
	{
		return self::get_instance()->container->get($name, $args);
	}

	/**
	 * @return $this
	 */
	public static function get_instance()
	{
		if (self::$instance == null) {
			self::$instance = new static();
		}

		return self::$instance;
	}
}