<?php

namespace Sphere\Core\Elementor\Layouts;

/**
 * Preview handlers.
 */
class Preview
{
	public function __construct()
	{
		add_action('elementor/dynamic_tags/before_render', [$this, 'switch_to_preview_query']);
		add_action('elementor/dynamic_tags/after_render', [$this, 'restore_current_query']);		
	}
	
	public function get_document()
	{
		$current_post_id = get_the_ID();
		$document        = \Elementor\Plugin::instance()->documents->get_doc_or_auto_save($current_post_id);
		
		return $document;
	}

	public function switch_to_preview_query()
	{
		$document = $this->get_document();

		if (!is_object($document) || !method_exists($document, 'get_preview_as_query_args')) {
			return;
		}

		$new_query_vars = $document->get_preview_as_query_args();
		if (!$new_query_vars) {
			return;
		}

		\Elementor\Plugin::instance()->db->switch_to_query($new_query_vars, true);

		if (method_exists($document, 'after_preview_switch_to_query')) {
			$document->after_preview_switch_to_query();
		}
	}

	public function restore_current_query()
	{
		\Elementor\Plugin::instance()->db->restore_current_query();

		$document = $this->get_document();
		if (!is_object($document)) {
			return;
		}
		
		if (method_exists($document, 'after_restore_current_query')) {
			$document->after_restore_current_query();
		}
	}

}

