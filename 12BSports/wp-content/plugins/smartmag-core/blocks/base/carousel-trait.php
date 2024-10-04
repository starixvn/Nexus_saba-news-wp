<?php

namespace Bunyad\Blocks\Base;

/**
 * Share methods for implementing carousel support in a loop block.
 */
trait CarouselTrait 
{
	/**
	 * Gets the carousel related props.
	 *
	 * @return array
	 */
	public static function get_carousel_props()
	{
		return [
			'carousel'            => false,
			'carousel_slides'     => '',
			'carousel_slides_md'  => '',
			'carousel_slides_sm'  => '',
			'carousel_dots'       => true,
			'carousel_arrows'     => 'b',
			'carousel_autoplay'   => false,
			'carousel_play_speed' => '',
		];
	}

	/**
	 * Setup carousel for this block, if supported.
	 */
	protected function setup_carousel()
	{
		if (!$this->props['carousel']) {
			return;
		}

		// Enqueue slick slider.
		if (wp_script_is('smartmag-slick', 'registered')) {
			wp_enqueue_script('smartmag-slick');
		}

		// Pagination is not available for carousels.
		if ($this->props['pagination']) {
			$this->props['pagination'] = 0;
		}

		$this->props['slide_attrs'] = isset($this->props['slide_attrs']) ? $this->props['slide_attrs'] : [];

		$this->props['slide_attrs'] = array_replace($this->props['slide_attrs'], [
			'data-slider'     => 'carousel',
			'data-autoplay'   => $this->props['carousel_autoplay'],
			'data-speed'      => $this->props['carousel_play_speed'],
			'data-slides'     => (
				$this->props['carousel_slides'] 
					? $this->props['carousel_slides'] 
					: $this->props['columns']
			),
			'data-slides-md' => $this->props['carousel_slides_md'],
			'data-slides-sm' => $this->props['carousel_slides_sm'],
			'data-arrows'    => $this->props['carousel_arrows'],
			'data-dots'      => $this->props['carousel_dots'],
		]);

		// Add the classes.
		$this->props['class'] = array_merge(
			$this->props['class'],
			[
				'common-slider loop-carousel',
				$this->props['carousel_arrows'] ? 'arrow-hover' : '',
				$this->props['carousel_arrows'] ? 'slider-arrow-' . $this->props['carousel_arrows'] : ''
			]
		);

		// Add slide attrs to wrap attrs if it exists.
		$this->props['wrap_attrs'] = array_replace(
			$this->props['wrap_attrs'], 
			$this->props['slide_attrs']
		);
	}
}