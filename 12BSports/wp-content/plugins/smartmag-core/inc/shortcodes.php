<?php
/**
 * Setup shortcodes and blocks
 */
class SmartMag_Shortcodes
{
	public $blocks = array();
	
	/**
	 * Add a special kind of shortcode that's handled by an included php file
	 * 
	 * @param array|string $shortcodes
	 */
	public function add($shortcodes)
	{
		$shortcodes   = (array) $shortcodes;
		$this->blocks = array_merge($this->blocks, $shortcodes);
		
		foreach ($shortcodes as $tag => $shortcode) {
			add_shortcode($tag, array($this, '_render'));
		}
		
		return $this;
	}
	
	/**
	 * Callback: Render a shortcode.
	 */
	public function _render($atts, $content = '', $tag = '')
	{
		$block = $this->blocks[$tag];

		if (!$block) { 
			return;
		}

		$block_attribs = [];
		if (isset($block['attribs'])) {
			$block_attribs += (array) $block['attribs'];
		}

		// Alias for another block. Merge attribs.
		if (isset($block['alias'])) {
			if (isset($block['map_attribs'])) {
				foreach ($block['map_attribs'] as $from => $to)	{
					if (!isset($atts[$from])) {
						continue;
					}

					$atts[$to] = $atts[$from];
				}
			}

			$block = $this->blocks[ $block['alias'] ];
		}

		$atts = shortcode_atts($block_attribs, $atts);
		$atts['block_file'] = $block['render'];
		
		// save the current block in registry
		if (class_exists('Bunyad_Registry')) {
			Bunyad::registry()
				->set('block', $block)
				->set('block_atts', $atts);
		}
		
		// Block file
		$block_file = '';
		if (is_string($block['render'])) {
			$block_file = $block['render'];
		}

		ob_start();

		// File render or callback render.
		if ($block['render'] === 'block') {
			$this->render_block($atts, $block);
		}
		else if ($block_file && file_exists($block_file)) {
			include apply_filters('bunyad_shortcode_file', $block_file, $block);
		}
		else if (is_callable($block['render'])) {
			echo call_user_func_array($block['render'], [$atts, $block]);
		}

		return ob_get_clean();
	}

	/**
	 * Render a known block.
	 *
	 * @param array $atts
	 * @param array $data
	 * @return void
	 */
	public function render_block($atts, $data = [])
	{
		$atts['is_sc_call'] = true;

		if (!isset($atts['query_type'])) {
			$atts['query_type'] = 'custom';
		}

		$props = call_user_func(
			'Bunyad\Blocks\\' . $data['block_class'] . '::get_default_props'
		);
		$props = array_replace($props, $atts);

		/** @var \Bunyad\Blocks\Base\LoopBlock $block */
		$block = Bunyad::blocks()->load(
			$data['block_class'],
			$atts
		);

		$block->render();
	}
}

// init and make available in Bunyad::get('shortcodes')
Bunyad::register('shortcodes', array(
	'class' => 'SmartMag_Shortcodes',
	'init' => true
));