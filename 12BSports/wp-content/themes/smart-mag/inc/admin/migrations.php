<?php
/**
 * Handle migrations to newer version of theme.
 */
class Bunyad_Theme_Admin_Migrations
{
	public $from_version;
	public $to_version;
	protected $options;
	protected $prev_options;

	public function __construct()
	{
		add_action('bunyad_theme_version_change', [$this, 'begin']);

		// Debug: Enable to test without saving version number to db.
		// add_filter('bunyad_theme_version_update_done', '__return_false');
	}

	/**
	 * Begin migration
	 */
	public function begin($from_version)
	{
		$this->from_version = $from_version;
		$this->to_version = Bunyad::options()->get_config('theme_version');

		// If from_version is empty, it's likely fresh install.
		if (!$this->from_version) {
			return;
		}

		// Releases with an upgrader
		$releases = [
			'9.2.1',
			'9.0.0',
			'7.0.0',
			'5.3.0',
			'5.2.0',
			'5.0.0'
		];

		$releases = array_reverse($releases);
		$this->load_options();

		foreach ($releases as $index => $release) {

			// This shouldn't happen. Can't be a migrator method that's newer than 
			// installed version.
			if (version_compare($this->to_version, $release, '<')) {
				continue;
			}

			// Current version is newer or already at it, continue to next.
			if (version_compare($this->from_version, $release, '>=')) {
				continue;
			}
			
			$handler = [$this, 'migrate_' . str_replace('.', '', $release)];
			if (is_callable($handler)) {
				call_user_func($handler);
			}
		}

		$this->save_options();
	}

	/**
	 * Upgrade to 9.2.1
	 */
	public function migrate_921()
	{
		// Elementor 3.16 is wrongly setting containers active by default.
		update_option('elementor_experiment-container', 'inactive');
	}

	/**
	 * Upgrade to version 9.0.0
	 */
	public function migrate_900()
	{
		$migrate = new Bunyad_Theme_Admin_Migrations_900Update($this->options);
		$this->options = $migrate->options;

		// CSS has to be regenerated for social icons top.
		$this->flush_cache();
	}

	/**
	 * Upgrade to version 7.0.0
	 */
	public function migrate_700()
	{
		$migrate = new Bunyad_Theme_Admin_Migrations_700Update($this->options);
		$this->options = $migrate->options;

		// Options haven't changed, but a logo CSS change has to be done.
		$this->flush_cache();
	}

	/**
	 * Upgrade to version 5.3.0
	 */
	public function migrate_530()
	{
		$migrate = new Bunyad_Theme_Admin_Migrations_530Update($this->options);
		$this->options = $migrate->options;
	}

	/**
	 * Upgrade to version 5.2.0
	 */
	public function migrate_520()
	{
		$migrate = new Bunyad_Theme_Admin_Migrations_520Update($this->options);
		$this->options = $migrate->options;
	}

	/**
	 * Upgrade to version 5.0.0
	 */
	public function migrate_500()
	{
		$migrate = new Bunyad_Theme_Admin_Migrations_500Update($this->options);
		$this->options = $migrate->options;

		// Converter flag.
		update_option('smartmag_convert_from_v3', 1);
	}

	/**
	 * Load fresh options to the memory.
	 */
	public function load_options()
	{
		// Fresh init to discard any leaky overrides.
		Bunyad::options()->init();
		$this->options = get_option(Bunyad::options()->get_config('theme_prefix') .'_theme_options');

		// Save in previous options to detect changes.
		$this->prev_options = $this->options;
	}

	/**
	 * Save options and clear the caches.
	 */
	public function save_options()
	{
		// Nothing changed.
		if ($this->options === $this->prev_options) {
			return;
		}

		// Save the changes
		Bunyad::options()
			->set_all($this->options)
			->update();

		$this->flush_cache();
	}

	public function flush_cache()
	{
		// Flush CSS cache
		delete_transient('bunyad_custom_css_cache');
		delete_transient('bunyad_custom_css_state');
		wp_cache_flush();
	}
}

// init and make available in Bunyad::get('theme_migrations')
Bunyad::register('theme_migrations', [
	'class' => 'Bunyad_Theme_Admin_Migrations',
	'init'  => true
]);