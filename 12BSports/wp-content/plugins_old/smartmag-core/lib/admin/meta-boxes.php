<?php
/**
 * Metaboxes: Not to be used in themes
 */
class Bunyad_Admin_MetaBoxes extends Bunyad_Admin_MetaBase
{
	private $prefix;
	private $cache = [];
	
	public function __construct()
	{
		add_action('add_meta_boxes', array($this, 'init'));
		add_action('admin_enqueue_scripts', array($this, 'register_assets'));
		add_action('save_post', array($this, 'process_save'));
		
		// Add metabox id prefix 
		$this->prefix = Bunyad::options()->get_config('theme_prefix') . '_';

		// Set meta options prefix if exists
		if (Bunyad::options()->get_config('meta_prefix')) {
			$this->option_prefix = Bunyad::options()->get_config('meta_prefix') . '_';
		}
	}
	
	/**
	 * Setup metaboxes
	 */
	public function init()
	{
		// get theme meta configs
		$meta = apply_filters('bunyad_meta_boxes', Bunyad::options()->get_config('meta_boxes'));
		if (!is_array($meta)) {
			return;
		}
		
		$this->register_boxes($meta);
	}

	/**
	 * Register metaboxes with Wordpress API.
	 */
	public function register_boxes(array $meta)
	{
		// Set some nifty defaults
		$defaults = [
			'page'     => 'post',
			'priority' => 'high',
			'context'  => 'normal'
		];
		
		// Add metaboxes
		foreach ($meta as $box) {

			// Add defaults.
			$box = array_merge($defaults, $box);
			
			// Prefix it.
			$box['id']    = $this->prefix . $box['id'];			
			
			// Fix screen.
			$box['pages'] = is_array($box['page']) ? $box['page'] : ['post'];

			// Legacy used 'file' instead of 'form'.
			$form_file = !empty($box['form']) ? $box['form'] : '';
			if (isset($box['file'])) {
				$form_file = (string) $box['file'];
			}
			
			foreach ($box['pages'] as $screen) {
				add_meta_box(
					$box['id'], 
					$box['title'], 
					[$this, 'render'],
					$screen,
					$box['context'],
					$box['priority'],
					[
						'id'      => $box['id'],
						'form'    => $form_file,
						'options' => !empty($box['options_file']) ? $box['options_file'] : '',
					]
				);
			}
		}
	}

	/**
	 * Callback: Register assets for the right admin pages.
	 */
	public function register_assets($hook)
	{
		if (in_array($hook, ['post-new.php', 'post.php'])) {
			wp_enqueue_script('bunyad-lib-options');
		}
	}

	/**
	 * Get a theme metabox by id.
	 * 
	 * @return array
	 */
	public function get_box($box_id)
	{
		$meta = (array) Bunyad::options()->get_config('meta_boxes');
		foreach ($meta as $box) {
			if ($this->prefix . $box['id'] == $box_id) {
				return $box;
			}
		}
		
		return [];
	}
	
	/**
	 * Render the metabox - used via callback
	 * 
	 * @param object $post
	 * @param array $args
	 */
	public function render($post = null, $args = null)
	{
		if (!$args['id']) {
			return false;
		}
		
		// Add nonce for security
		if (!isset($this->cache['nonce'])) {
			wp_nonce_field('meta_save', '_nonce_' . $this->prefix . 'meta', false);
		}
		
		Bunyad::factory('admin/option-renderer');
		
		// Metabox file defined.
		$file = $args['args']['form'];

		// @deprecated Legacy way of meta file from theme.
		if (empty($file)) {
			$file = sanitize_file_name(str_replace($this->prefix, '', $args['id'])) . '.php';
			$file = locate_template('admin/meta/' . $file);
		}

		/** @var Bunyad_Admin_MetaRenderer $meta */
		$meta = Bunyad::factory('admin/meta-renderer', true); 

		// Render the template
		$meta->set_prefix($this->option_prefix)->template(
			[],
			$file,
			// We need meta with prefixes, hence remove_prefix=false.
			Bunyad::posts()->get_all_meta($post->ID, false),
			[
				'post'   => $post, 
				'box'    => $this->get_box($args['id']), 
				'box_id' => $args['id']
			]
		);
	}
	
	/**
	 * Save custom post meta.
	 * 
	 * @param integer $post_id
	 */
	public function process_save($post_id)
	{
		// Just an auto-save
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
			return;
		}
	
		// Security checks
		if (
			!current_user_can('edit_post', $post_id)
			|| (
				isset($_POST['_nonce_' . $this->prefix . 'meta']) 
				&& !wp_verify_nonce($_POST['_nonce_' . $this->prefix . 'meta'], 'meta_save')
			) 
		) {
			return false;
		}
		
		// Load meta-box fields and set in $this->options. This is later used for 
		// default value chekcs in save_meta() method.
		if (!empty($_POST['bunyad_meta_box'])) {

			$metaboxes = (array) $_POST['bunyad_meta_box'];

			foreach ($metaboxes as $box_id) {
				$box  = $this->get_box($box_id);

				// Use options file if specified.
				if ($box && isset($box['options'])) {
					$file = $box['options'];
				}
				// @deprecated - Legacy way of getting from theme.
				else if (is_string($_POST['bunyad_meta_box'])) {
					$path = apply_filters('bunyad_metabox_options_dir', get_theme_file_path('admin/meta/options'));
					$file = trailingslashit($path) . sanitize_file_name($_POST['bunyad_meta_box']) . '.php';
				}
			
				if (!empty($file)) {
					include $file; // phpcs:ignore WordPress.Security.EscapeOutput -- From safe internal config. 
					
					$this->options = array_replace(
						$this->options,
						$this->_build_meta_map($options)
					);
				}
			}
		}

		$this->save_meta($post_id);
	}

	/**
	 * @inheritDoc
	 */
	public function get_meta($object_id, $key, $single = false)
	{
		return get_post_meta($object_id, $key, $single);
	}

	/**
	 * @inheritDoc
	 */
	public function update_meta($object_id, $key, $value)
	{
		return update_post_meta($object_id, $key, $value);
	}

	/**
	 * @inheritDoc
	 */
	public function delete_meta($object_id, $key)
	{
		return delete_post_meta($object_id, $key);
	}

	/**
	 * Build meta options array using field name as key with the prefix
	 * 
	 * @param array $options
	 */
	public function _build_meta_map($options)
	{
		$map = array();
		
		foreach ($options as $option) {
			$map[ $this->option_prefix . $option['name'] ] = $option;
		}
		
		return $map;
	}
}