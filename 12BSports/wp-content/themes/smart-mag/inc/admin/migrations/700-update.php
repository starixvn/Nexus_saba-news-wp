<?php
use \Elementor\Plugin;

/**
 * Migrate to version 7.0.0
 * 
 * @var $this Bunyad_Theme_Admin_Migrations
 */
class Bunyad_Theme_Admin_Migrations_700Update extends Bunyad_Theme_Admin_Migrations_Base
{
	public function begin()
	{
		if (!class_exists('\Elementor\Plugin')) {
			return;
		}

		/**
		 * Rename old Elementor kit's main color smartmag-main to smartmag as 
		 * elementor doesn't seem to support the dash anymore. 
		 */
		$kits_manager = Plugin::$instance->kits_manager;
		if (!$kits_manager || !is_callable([$kits_manager, 'get_active_kit'])) {
			return;
		}

		$kit = $kits_manager->get_active_kit();
		if (!is_callable([$kit, 'get_post'])) {
			return;
		}
		
		$active_kit_post = $kit->get_post();
		if (strpos($active_kit_post->post_name, 'smartmag-kit') === false) {
			return;
		}

		$settings = $kit->get_settings();
		foreach ($settings['system_colors'] as $key => $setting) {
			if ($setting['_id'] === 'smartmag-main') {
				$settings['system_colors'][$key]['_id'] = 'smartmag';
			}
		}

		$kit->update_settings($settings);
	}
}