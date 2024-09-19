<?php
namespace Bunyad\Blocks;
use Bunyad;

/**
 * Helper methods for Blocks & Listing Archives
 */
class Ajax 
{
	public function init()
	{
		add_action('wp_ajax_nopriv_bunyad_block', [$this, 'render_block']);
		add_action('wp_ajax_bunyad_block', [$this, 'render_block']);
	}

	/**
	 * Callback: AJAX request, render block.
	 *
	 * @return void
	 */
	public function render_block()
	{
		if (!isset($_REQUEST['block'])) {
			wp_die('0');
		}

		$block = $_REQUEST['block'];

		if (!isset($block['props']) || !isset($block['id'])) {
			wp_die('0');
		}

		$block_class = str_replace(
			'Bunyad\Blocks\\', 
			'', 
			Bunyad::file_to_class_name($block['id'])
		);

		$block['props'] = (array) $block['props'];
		$query_type = $block['props']['query_type'] ?? '';

		// Can't support main query here, for obvious reasons.
		if (!in_array($query_type, ['section', 'custom'])) {
			$block['props']['query_type'] = 'custom';
		}

		if ($query_type === 'section' && empty($block['props']['section_query'])) {
			$block['props']['query_type'] = 'custom';
		}

		// Set a max posts per page limit to prevent DOS.
		if (isset($block['props']['posts'])) {
			$block['props']['posts'] = min(intval($block['props']['posts']), 100);
		}
		
		$block['props'] = $this->process_booleans($block['props']);

		/** @var \Bunyad\Blocks\Base\LoopBlock $block */
		$block = Bunyad::blocks()->load(
			'Loops\\' . $block_class,
			$block['props']
		);

		$block->render();
		wp_die();
	}

	/**
	 * Convert string true/false to boolean values.
	 *
	 * @param array $props
	 * @return array
	 */
	protected function process_booleans($props) 
	{
		foreach ($props as $prop => $value) {
			if ($value === "true" || $value === "false") {
				$props[$prop] = \filter_var($value, FILTER_VALIDATE_BOOLEAN);
			}
		}

		return $props;
	}
}
