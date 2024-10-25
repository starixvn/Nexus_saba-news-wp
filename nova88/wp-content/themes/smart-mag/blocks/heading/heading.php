<?php

namespace Bunyad\Blocks;

use Bunyad;
use Bunyad\Blocks\Base\Block;

/**
 * Heading block to use for block headings, widget headings, and so on.
 */
class Heading extends Block
{
	public $id = 'heading';

	/**
	 * @inheritdoc
	 */
	public static function get_default_props() 
	{
		$props = [
			'heading'   => '',
			'type'      => '',
			'link'      => '',
			'html_tag'  => 'h4',
			'align'     => 'left',
			'more'      => '',
			'more_text' => '',
			'more_link' => '',

			// Use accent colors.
			'accent_colors' => '',

			// Term object expected.
			'term'    => null,

			// Array of filter items with links.
			'filters' => [],
		];

		return $props;
	}

	/**
	 * Get classes for a particular style.
	 *
	 * @param string $style
	 * @param array $props
	 * @return array
	 */
	public static function get_classes($style, $props = [])
	{
		$map = [
			'a'   => 'block-head-a block-head-a1',
			'a2'  => 'block-head-a block-head-a2',
			'e'   => 'block-head-e block-head-e1',
			'e2'  => 'block-head-e block-head-e2',
			'e3'  => 'block-head-e block-head-e3',
			'c2'  => 'block-head-c block-head-c2',
		];

		$classes = ['block-head', 'block-head-ac'];
		if (isset($map[$style])) {
			$classes[] = $map[$style];
		}
		else {
			$classes[] = 'block-head-' . $style;
		}

		if (!empty($props['align'])) {
			$classes[] = 'is-' . $props['align'];
		}

		return $classes;
	}

	public function map_global_props($props)
	{
		// Add in heading type from global if not specified.
		if (empty($props['type'])) {
			$props['type'] = Bunyad::options()->block_head_style;
		}

		$option_key = $props['type'];
		// Special case: bhead_align_ and other options will be in a1 and e1.
		if (in_array($option_key, ['a', 'e'])) {
			$option_key .= '1';
		}

		$props = array_replace([
			'align' => Bunyad::options()->get('bhead_align_' . $option_key),
		], $props);

		return $props;
	}

	/**
	 * Render the block heading.
	 * 
	 * @return void
	 */
	public function render()
	{
		// No heading processed? It's important the process() run first
		if (empty($this->props['heading'])) {
			return;
		}
		
		// Heading disabled?
		if ($this->props['type'] == 'none') {
			return;
		}

		// Convert special asterisks to multi-color.
		$heading = $this->convert_text($this->props['heading']);
	
		// Set heading with link if possible
		if (!empty($this->props['link'])) {
			$heading = sprintf(
				'<a href="%1$s">%2$s</a>',
				esc_url($this->props['link']),
				$heading
			);
		}
		
		ob_start();
		?>
					
			<?php
			/**
			 * Heading types: empty/default, block-filter or block
			 */
			if (!$this->props['type'] || !in_array($this->props['type'], ['page'])):
					
					$classes   = $this->get_classes($this->props['type'], $this->props);
					$accent_colors = $this->props['accent_colors'] === 'force';
					
					// Unless disabled, add category id for accent colors.
					if ($this->props['accent_colors'] !== 'none' && is_object($this->props['term'])) { 
						$classes[]     = 'term-color-' . $this->props['term']->term_id;
						$accent_colors = true;
					}

					// Accent colors enabled: Remove the alt color class.
					if ($accent_colors) {
						$classes = array_diff($classes, ['block-head-ac']);
					}

					$view_more_classes = ['view-link'];
					if ($this->props['more']) {
						$view_more_classes[] = 'view-link-' . $this->props['more'];
					}

			?>
				
				<div <?php Bunyad::markup()->attribs('block-heading-wrap', ['class' => $classes]); ?>>

					<?php
						printf(
							'<%1$s class="heading">%2$s</%1$s>',
							esc_html($this->props['html_tag']),
							wp_kses_post($heading)
						);
					?>
					<?php echo $this->get_the_filters(); // phpcs:ignore WordPress.Security.EscapeOutput -- Safe output from method. ?>

					<?php if ($this->props['more_text']): ?>
						<a href="<?php echo esc_url($this->props['more_link']); ?>" class="<?php echo esc_attr(join(' ', $view_more_classes)); ?>">
							<?php 
							echo $this->convert_text(
								esc_html($this->props['more_text'])
							);
							?>
						</a>
					<?php endif; ?>
				</div>
				
			<?php 
			/**
			 * Legacy Heading type: Page
			 */
			else:
			?>

				<h2 class="block-head main-heading prominent"><?php echo wp_kses_post($heading); ?></h2>

			<?php endif; ?>

		<?php

		echo apply_filters('bunyad_blocks_heading', ob_get_clean());
	}

	/**
	 * Get the filters output
	 */
	public function get_the_filters()
	{
		$display_filters = $this->props['filters'];
		$filters = '';

		if ($display_filters) {

			ob_start();
			?>
			
			<ul class="subcats filters">
				<li><a href="#" data-id="0" class="active"><?php esc_html_e('All', 'bunyad'); ?></a></li>
				<?php echo join("\n", $display_filters); ?>
			</ul>
			
			<?php
			$filters = ob_get_clean();
		}

		return apply_filters('bunyad_blocks_heading_filters', $filters);
	}

	/**
	 * Auto-convert some text to icons and so on.
	 *
	 * @param string $text
	 * @return string
	 */
	public function convert_text($text)
	{
		// Convert > to an icon.
		$text = preg_replace('/(?<=\s)(>|&gt;)(?=\s|$)/', '<i class="arrow tsi tsi-angle-right"></i>', $text);

		// Add color for asterisks.
		$text = preg_replace('/(\*|_)(.*?)\1/', '<span class="color">\2</span>', $text);

		return $text;
	}
}