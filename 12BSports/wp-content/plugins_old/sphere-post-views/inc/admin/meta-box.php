<?php

namespace Sphere\PostViews\Admin;

use Sphere\PostViews\Api;
use Sphere\PostViews\Options;

class MetaBox
{
	protected $options;
	protected $api;

	public function __construct(Options $options, Api $api)
	{
		$this->options = $options;
		$this->api = $api;
	}

	public function init()
	{
		add_action('add_meta_boxes', [$this, 'register']);
		add_action('save_post', [$this, 'save']);
	}

	public function register()
	{
		$screens = apply_filters('sphere/post_views/post_types', ['post']);
		foreach($screens as $screen) {
			add_meta_box(
				'sphere_post_views',
				esc_html__('Post Views', 'sphere-post-views'),
				[$this, 'display'],
				$screen,
				'side'
			);
		}
	}

	public function display($post)
	{
		$views = $this->api->get_views($post->ID);

		?>
		<input type="hidden" name="spv_views_current" value="<?php echo esc_attr($views); ?>" />
		<input type="number" name="spv_views" value="<?php echo esc_attr($views); ?>" />
		<p class="small">Note: Changing views will not affect 7 days or custom days popular sort.</p>
		<?php
	}

	public function save($post_id)
	{
		if (!current_user_can('edit_post', $post_id)) {
			return;
		}

		if (!isset($_POST['spv_views']) || !isset($_POST['spv_views_current'])) {
			return;
		}

		// Unchanged. We check this as it might have already changed in DB due to visits occuring after
		// the post editing started.
		if ((int) $_POST['spv_views_current'] === (int) $_POST['spv_views']) {
			return;
		}

		$this->api->update_views($post_id, intval($_POST['spv_views']));
	}
}