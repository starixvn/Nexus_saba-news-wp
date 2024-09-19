<?php

namespace Bunyad\Elementor;

/**
 * The base loop widget.
 */
class LoopWidget extends BaseWidget
{
	protected function _process_settings()
	{
		$settings = parent::_process_settings();

		/**
		 * Default query_type for archives is dynamically set as 'main-custom', but it would 
		 * revert to default of 'custom' when rendering.
		 * 
		 * Check raw settings to see if it's really 'custom', or use 'main-custom'.
		 */
		if (
			$settings['query_type'] === 'custom' 
			&& is_archive() 
			&& $this->get_doc_type() === 'ts-archive'
		) {
			$raw_settings = $this->get_data('settings');
			$raw_query    = $raw_settings['query_type'] ?? '';			
			
			if ($raw_query !== 'custom') {
				$settings['query_type'] = 'main-custom';
			}
		}

		// This shouldn't happen as elementor should have set it with a default value.
		// But if it does, default to custom query.
		if (!isset($settings['query_type'])) {
			$settings['query_type'] = 'custom';
		}

		// Section query if section_data available. Checking for query_type as section_query data
		// is only needed when the section query is used.
		if (isset($settings['section_data']) && $settings['query_type'] === 'section') {
			$settings['section_query'] = $settings['section_data'];
		}

		return $settings;
	}

	public function get_options($init_editor = false)
	{
		parent::get_options($init_editor);

		/**
		 * In editor, change the default query_type. 
		 * 
		 * NOTE: This cannot effect frontend consistently, DO NOT refactor it. 
		 * Pre-render checks should be added when processing the settings, as the option will 
		 * change per doc type with several doc types possible on a single page locations.
		 */
		if ($init_editor && $this->get_doc_type() === 'ts-archive') {
			$this->options->change_option('query_type', ['default' => 'main-custom']);
		}

		return $this->options;
	}

	/**
	 * Get current elementor document type.
	 * 
	 * Note: This isn't always available, such as when elementor CSS enqueue calls where
	 * the current document isn't set.
	 * 
	 * @return string Empty or the document type.
	 */
	protected function get_doc_type()
	{
		$documents = \Elementor\Plugin::instance()->documents;

		// For AJAX requests load method of Elementor editor.
		if (isset($_REQUEST['editor_post_id'])) {
			$document = $documents->get($_REQUEST['editor_post_id']);
		} else {
			$document = $documents->get_current();
		}

		if ($document && is_callable([$document, 'get_type'])) {
			return $document->get_type();
		}

		return '';
	}
}