<?php
/**
 * Custom term meta for taxonomies.
 */
class Bunyad_Admin_MetaTerms extends Bunyad_Admin_MetaBase
{
	/**
	 * @var Bunyad_Admin_MetaRenderer
	 */
	public $renderer;

	public $taxonomy;
	public $options_file;
	public $form_file;
	
	/**
	 * Setup the hooks for this particular taxonomy.
	 */
	public function init()
	{
		if (!$this->options_file || !$this->form_file) {
			return;
		}

		/**
		 * Setup handlers for each taxonomy spe
		 */
		add_action($this->taxonomy . '_edit_form_fields', [$this, 'edit_form'], 10, 2);
		add_action($this->taxonomy . '_add_form_fields', [$this, 'edit_form'], 10, 2);
		
		add_action('edited_' . $this->taxonomy, [$this, 'process_save'], 10, 2);
		add_action('create_' . $this->taxonomy, array($this, 'process_save'), 10, 2);
	}

	public function load_options()
	{
		$this->options = include_once $this->options_file;

		// No auto-loader, so manual load.
		Bunyad::factory('admin/option-renderer');

		$this->renderer = Bunyad::factory('admin/meta-renderer');
		$this->renderer->set_prefix($this->option_prefix);

		$this->options = $this->renderer->options(
			apply_filters('bunyad_meta_terms_options', $this->options, $this->taxonomy)
		);
	}
	
	/**
	 * Action callback: Add form fields to term editing / adding form.
	 */
	public function edit_form($term = null)
	{
		// Add required assets.
		wp_enqueue_style('wp-color-picker');
		wp_enqueue_script('wp-color-picker');
		
		// Add media scripts.
		wp_enqueue_media(); 
		wp_enqueue_script('bunyad-lib-options');
		
		// Load the options and pass them to the renderer to add prefixes etc.
		$this->load_options();
	
		// Note: Don't add prefix at start or it will be saved in meta.
		wp_nonce_field('term_save', '_nonce_' . $this->option_prefix . 'terms_meta');
		
		$existing_options = [];
		$context = 'add';

		if (is_object($term)) {
			$context = 'edit';
			$existing_options = (array) get_term_meta($term->term_id);
		}

		$this->renderer->template(
			$this->options,
			$this->form_file,
			$existing_options,
			[
				'context' => $context
			]
		);
	}
	
	/**
	 * Action callback: Save custom meta for the term.
	 */
	public function process_save($term_id)
	{
		// Just an auto-save.
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
			return;
		}

		// Security verification.
		$nonce = '_nonce_' . $this->option_prefix . 'terms_meta';
		if (!isset($_POST[$nonce]) || !wp_verify_nonce($_POST[$nonce], 'term_save')) {
			return false;
		}

		$this->load_options();
		$this->save_meta($term_id);

		// Clear custom css cache.
		delete_transient('bunyad_custom_css_cache');
	}

	/**
	 * @inheritDoc
	 */
	public function get_meta($object_id, $key, $single = false)
	{
		return get_term_meta($object_id, $key, $single);
	}

	/**
	 * @inheritDoc
	 */
	public function update_meta($object_id, $key, $value)
	{
		return update_term_meta($object_id, $key, $value);
	}

	/**
	 * @inheritDoc
	 */
	public function delete_meta($object_id, $key)
	{
		return delete_term_meta($object_id, $key);
	}
}
