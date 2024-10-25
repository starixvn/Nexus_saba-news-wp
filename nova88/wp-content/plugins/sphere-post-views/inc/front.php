<?php

namespace Sphere\PostViews;

/**
 * The public-facing functionality of the plugin.
 */
class Front
{
	private $options;
	private $translate;

	public function __construct(Options $options, Translate $translate)
	{
		$this->options = $options;
		$this->translate = $translate;
	}

	public function init()
	{
		add_action('wp_enqueue_scripts', [$this, 'register_assets']);
	}

	/**
	 * Enqueues public facing assets.
	 */
	public function register_assets()
	{
		if ($this->options->spv_skip_loggedin && is_user_logged_in()) {
			return;
		}

		$post_id = Helper::is_single();

		wp_enqueue_script(
			'sphere-post-views',
			Plugin::get_instance()->dir_url . 'assets/js/post-views.js',
			[],
			Plugin::VERSION
		);

		// Get translated post id, if needed.
		$post_id = $this->translate->get_object_id(
			$post_id,
			get_post_type($post_id)
		);

		if ($this->options->spv_short_endpoint) {
			$endpoint_url = Plugin::get_instance()->dir_url . 'log-view.php';
		}
		else {
			$endpoint_url = admin_url('admin-ajax.php?sphere_post_views=1');
		}

		wp_add_inline_script(
			'sphere-post-views', 
			'var Sphere_PostViews = ' . json_encode([
					// With a unique identifier in GET.
					'ajaxUrl'          => $endpoint_url,
					'sampling'         => (int) $this->options->spv_sampling,
					'samplingRate'     => (int) $this->options->spv_sampling_rate,
					'repeatCountDelay' => (float) $this->options->spv_repeat_count_delay,
					'postID'           => $post_id,
					'token'            => Helper::create_token('spv-update-views'),
				]
			)
		);
	}
}
