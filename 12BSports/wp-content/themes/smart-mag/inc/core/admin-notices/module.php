<?php

namespace Bunyad\Core\AdminNotices;

/**
 * Handle admin notices in WordPress.
 */
class Module
{
	protected $id;

	// Just for cache.
	protected $nonce; 

	/**
	 * @var Notice[]
	 */
	protected $notices = [];
	
	public function __construct($id = '')
	{
		$this->id = $id;

		if (!$this->id && is_callable([\Bunyad::options(), 'get_config'])) {
			$this->id = \Bunyad::options()->get_config('theme_name');
		}
		
		add_action('admin_notices', [$this, 'display'], 9);
		
		// AJAX dismiss messages.
		add_action('wp_ajax_bunyad_admin_notice_dismiss', [$this, 'ajax_dismiss']);
		
		add_action('admin_enqueue_scripts', [$this, 'register_assets']);
	}

	/**
	 * Add a notice to display.
	 *
	 * @return Notice
	 */
	public function add($id, $message, array $options = [])
	{
		$this->notices[$id] = new Notice($id, $this, $message, $options);
		return $this->notices[$id];
	}

	public function register_assets()
	{
		wp_register_script(
			'bunyad-admin-notices', 
			get_template_directory_uri() . '/inc/core/admin-notices/js/notices.js',
			['jquery']
		);

		wp_localize_script('bunyad-admin-notices', 'Bunyad_Admin_Notices', [
			'confirm_update' => esc_html('Do you wish to update the theme? IMPORTANT: Create a backup to be safe.', 'bunyad-admin')
		]);
	}

	/**
	 * Display the notices.
	 *
	 * @return void
	 */
	public function display()
	{
		$notice_displayed = false;

		foreach ($this->notices as $notice) {
			if ($notice->render()) {
				$notice_displayed = true;
			}
		}

		if ($notice_displayed) {
			wp_enqueue_script('bunyad-admin-notices');
		}
	}

	public function get_nonce()
	{
		return $this->nonce ? $this->nonce : wp_create_nonce('bunyad_admin_notice_' . $this->id);
	}

	public function ajax_dismiss()
	{
		// Security check.
		$notice_id = $_POST['notice_id'] ?? 'activation';
		$notice    = $this->notices[$notice_id] ?? false;

		if ($notice && check_admin_referer('bunyad_admin_notice_' . $this->id)) {
			$notice->dismiss($_POST['remind'] ?? false);
		}

		wp_die();
	}

	public function set_dismissed($value, $scope)
	{
		$storage_key = $this->id . '_dismissed_notices';
		if ($scope === 'user') {
			update_user_meta(
				get_current_user_id(),
				$storage_key,
				$value
			);
		}
		else {
			set_transient($storage_key, $value);
		}
	}

	/**
	 * Get dismissed message, provided the scope (user or global).
	 *
	 * @param string $scope
	 * @return array
	 */
	public function get_dismissed($scope)
	{
		$storage_key = $this->id . '_dismissed_notices';

		if ($scope === 'user') {
			return (array) get_user_meta(
				get_current_user_id(), 
				$storage_key,
				true
			);
		}

		return (array) get_transient($storage_key);
	}
}