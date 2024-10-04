<?php

namespace Bunyad\Blocks\Loops;

use \Bunyad;
use Bunyad\Blocks\Base\LoopBlock;

/**
 * News Focus Block
 */
class FeatGrid extends LoopBlock
{
	public $id = 'feat-grid';

	// protected $item_configs;
	protected $max_slides = 25;

	/**
	 * @var array Base configuration for sm, md, lg items.
	 */
	protected $base_configs = [];

	/**
	 * Extend base props
	 */
	public static function get_default_props()
	{
		$props = parent::get_default_props();

		return array_replace($props, [
			'grid_width'       => 'container',
			'grid_type'        => 'grid-a',
			'has_ratio'        => true,
			'has_slider'       => false,
			'overlay_style'    => 'a',
			'title_style'      => '',
		
			// Show meta on hover.
			'meta_on_hover'      => false,
			'meta_items_default' => true,
			'content_position'   => 'bottom',
			// 'content_position' => 'center',

			// @todo Not yet implemented. Only supports 'labels' for now.
			'meta_cat_style'   => 'labels',
			'hover_effect'     => 'hover-zoom',
			'cat_labels'       => false,
			
			'slides'           => 1,
			'per_slide'        => 5,
			'slide_scroll'     => 1,
			'slide_scroll_md'  => 1,

			// Different position for post formats than default.
			'post_formats_pos'  => 'top-right',
		]);
	}

	public function map_global_props($props)
	{
		$global = [
			// Don't use these from default globals.
			'cat_labels'       => false,
			'cat_labels_pos'   => 'top-left',

			// @todo Not yet implemented. Only supports 'labels' for now.
			'meta_cat_style'   => 'labels',
		];

		// If we have global meta items enabled, add in global options.
		// Note: The global of featured grids is different from other globals.
		if (!isset($props['meta_items_default']) || $props['meta_items_default']) {
			$global += [
				'meta_above'     => Bunyad::options()->feat_grids_meta_above,
				'meta_below'     => Bunyad::options()->feat_grids_meta_below,
			];

			// Stops from getting overriden in map_global_props below.
			$props['meta_items_default'] = false;
		}

		$props = array_replace($global, $props);
		$props = parent::map_global_props($props);

		return $props;
	}

	/**
	 * @inheritDoc
	 */
	public function init()
	{
		parent::init();
		
		// Extra internal props.
		$this->props += [
			'equal_items'       => false,
			'item_configs'      => [],
			'grid_wrap_classes' => '',
		];

		// Defaults attribs for slides wrapper.
		$this->props['slide_attrs'] = [
			'class'         => ['slides'],
			'data-parallax' => 0,
		];

		// Set base configs for sm, lg, md etc.
		$this->set_base_configs();
	}

	public function _pre_render()
	{
		// Processing query before. None of the props below are query related.
		$this->process();

		// Setup configs for each item and the slider.
		$this->set_item_configs();
		$this->set_slider_configs();

		// Set wrapper and slider classes etc.
		$this->set_classes();

		// Enqueue slick slider.
		if ($this->props['has_slider'] && wp_script_is('smartmag-slick', 'registered')) {
			wp_enqueue_script('smartmag-slick');
		}
	}

	/**
	 * Get main configuration for the item types main, small, and medium.
	 *
	 * @return array
	 */
	protected function set_base_configs()
	{
		$configs = [
			'meta_props' => [
				// Should be set by BasePost based on meta_items_default.
				// 'items_above' => $this->props['meta_above'],
				// 'items_below' => $this->props['meta_below'],
				'title_class' => 'post-title',
				'title_tag'   => !empty($this->props['title_tag']) ? $this->props['title_tag'] : 'h3',
				'add_class'   => 'meta-contrast',
				'cat_style'   => isset($this->props['meta_cat_style']) ? $this->props['meta_cat_style'] : null,
				'text_labels' => [],
				
				// Auto-alignment - no post-meta alignment classes.
				'align'       => ''
			],
			'image_data' => [
				// Size for small and medium is half width of viewport in mobiles.
				'attr' => ['sizes' => '(max-width: 768px) 50vw, %1$dpx'],
				// 'bg_image'    => true,
				'ratio_class' => false,

				// To filter out images in responsive srcset that are too small of a height.
				// For small images, we never want the thumbnail size (150px or 300x2xx), generally.
				'srcset_filter' =>  [
					'min_height' => 220,
				],
			],
		
			// Default to small.
			'class' => 'item-small',
			'image' => 'bunyad-feat-grid-sm',

			// Can be null or ['full' => 100, 'wrap' => 200]
			'width' => null,
		];

		// Configuration for items based on chosen grid.
		$main = [
			'class' => 'item-large item-main',
			'image' => 'bunyad-feat-grid-lg',
			'image_data' => array_replace($configs['image_data'], [
				// To filter out images in responsive srcset that are too small of a height.
				// Most of the time, a minimum height of 500 will be desired.
				'srcset_filter' =>  [
					'min_height' => 500,
				],

				// Large / main is fine with default WP sizes.
				'attr' => []
			])
		];

		$medium = [
			'class' => 'item-medium',
			'image' => 'bunyad-feat-grid-lg',
			'image_data' => array_replace($configs['image_data'], [
				'srcset_filter' =>  [
					'min_height' => 500,
				]
			])
		];

		// Uses defaults.
		// $small = array_replace($configs, [
		// 	'image_data' => array_replace($configs['image_data'], [
		// 	])
		// ]);
		$small = $configs;

		$this->base_configs = compact('configs', 'main', 'small', 'medium');
	}

	/**
	 * Configure props for items at specific positions, based on featured grid type.
	 */
	public function set_item_configs()
	{

		// Configuration for items at specific positions.
		$item_configs = [
			1 => $this->base_configs['main'],
		];

		$equal_items = false;
		$props       = $this->props;

		switch ($props['grid_type']) {

			case 'grid-a':
				$this->props['per_slide']       = 5;
				$this->props['slide_scroll']    = 1;
				$this->props['slide_scroll_md'] = 1;
		
				// Without slide, 1 slide is max.
				$this->max_slides = !$props['has_slider'] ? 1 : $this->max_slides;
				break;
		
			case 'grid-b':
				$this->props['per_slide']       = 3;
				$this->props['slide_scroll']    = 1;
				$this->props['slide_scroll_md'] = 1;
		
				// Without slide, 1 slide is max.
				$this->max_slides = !$props['has_slider'] ? 1 : $this->max_slides;
		
				// Medium class (title etc.) but small image.
				$item_medium = array_replace(
					$this->base_configs['medium'],
					[
						'class' => 'item-medium',
						'width' => ['wrap' => 400, 'full' => 550]
					]
				);

				$item_configs = [
					1 => array_replace(
						$this->base_configs['main'], 
						[
							'image' => 'bunyad-feat-grid-lg-vw',
							'width' => ['wrap' => 810, 'full' => 1200]
						]
					),
					2 => $item_medium,
					3 => $item_medium,
				];
		
				// Default to no hover effects unless specified.
				if (!isset($this->props['hover_effect'])) {
					$props['hover_effect'] = null;
				}
		
				break;
		
			case 'grid-c':
				$this->props['per_slide']       = 3;
				$this->props['slide_scroll']    = 1;
				$this->props['slide_scroll_md'] = 1;
				
				// Without slide, 1 slide is max.
				$this->max_slides = !$props['has_slider'] ? 1 : $this->max_slides;
		
				// Medium class (title etc.) but small image.
				$item_medium = array_replace(
					$this->base_configs['medium'],
					[
						'class' => 'item-medium',
						// srcset_filter ensures 500px height too via medium's image_data.
						'width' => ['wrap' => 350, 'full' => 585]
					]
				);

				$item_configs = [
					1 => $this->base_configs['main'],
					2 => $item_medium,
					3 => $item_medium,
				];
		
				break;


			case 'grid-d':
				$this->props['per_slide']       = 4;
				$this->props['slide_scroll']    = 1;
				$this->props['slide_scroll_md'] = 1;
		
				// Without slide, 1 slide is max.
				$this->max_slides = !$props['has_slider'] ? 1 : $this->max_slides;

				// Remove responsive size attr as medium stays full-width in mobile for this.
				$medium = $this->base_configs['medium'];
				unset($medium['image_data']['attr']['sizes']);
				
				// Small is default so will auto-fill.
				$item_configs = [
					1 => $this->base_configs['main'],
					2 => $medium
				];

				break;
		
			case 'grid-eq1':
				$this->props['per_slide']       = 1;
				$this->props['slide_scroll']    = 1;
				$this->props['slide_scroll_md'] = 1;
				$equal_items = true;
		
				$image_data = $this->base_configs['main']['image_data'];
				if ($this->props['grid_width'] === 'viewport') {
					$image_data['attr'] = ['sizes' => '(max-width: 1920px) 100vw, 1920px'];
				}

				$item_configs = array_fill(1, $this->query->post_count, array_replace(
					$this->base_configs['main'], 
					[
						'class' => 'item-main item-large',
						'image_data' => $image_data
					]
				));
				
				break;
		
			case 'grid-eq2':
				$this->props['per_slide']       = 1;
				$this->props['slide_scroll']    = 2;
				$this->props['slide_scroll_md'] = 2;
				$equal_items = true;
		
				$item_configs = array_fill(1, $this->query->post_count, array_replace(
					$this->base_configs['main'], 
					['class' => 'item-main item-large']
				));
				
				break;
		
			case 'grid-eq3':
				$this->props['per_slide']       = 1;
				$this->props['slide_scroll']    = 3;
				$this->props['slide_scroll_md'] = 2;
				$equal_items = true;
		
				$_conf = array_replace(
					$this->base_configs['main'],
					['class' => 'item-main item-medium']
				);
		
				$_conf['width'] = ['wrap' => 400, 'full' => 700];
				$item_configs = array_fill(1, $this->query->post_count, $_conf);
				
				break;

			case 'grid-eq4':
				$this->props['per_slide']       = 1;
				$this->props['slide_scroll']    = 4;
				$this->props['slide_scroll_md'] = 2;
				$equal_items = true;
		
				$_conf = array_replace(
					$this->base_configs['main'],
					['class' => 'item-main item-small']
				);
		
				$_conf['width'] = ['wrap' => 400, 'full' => 550];
				$item_configs = array_fill(1, $this->query->post_count, $_conf);
				
				break;
		
			case 'grid-eq5':
				$this->props['per_slide']       = 1;
				$this->props['slide_scroll']    = 5;
				$this->props['slide_scroll_md'] = 2;
				$equal_items = true;
		
				$_conf = array_replace(
					$this->base_configs['main'],
					['class' => 'item-main item-small']
				);
		
				// 550 because 450 or less may get a height too less.
				$_conf['width'] = ['wrap' => 350, 'full' => 500];
				$item_configs = array_fill(1, $this->query->post_count, $_conf);
				
				break;
		
			default:
				break;

		}

		// Set the props.
		$this->props['equal_items']  = $equal_items;
		$this->props['item_configs'] = $item_configs;
	}

	/**
	 * Setup slider props.
	 */
	public function set_slider_configs()
	{
		$slides = 1;

		if ($this->query->post_count) {
			$slides = ceil($this->query->post_count / max(1, intval($this->props['per_slide'])));
			$slides = min($slides, $this->max_slides);
		}

		$this->props['slides'] = $slides;
		
		// When number of slides are greater than 1, activate the slider.
		if ($this->props['has_slider']) {
			$slide_attrs = array_replace($this->props['slide_attrs'], [
				'data-slider'    => 'feat-grid',
				// 'data-autoplay'  => Bunyad::options()->slider_autoplay,
				// 'data-speed'     => Bunyad::options()->slider_delay,
		
				// Can't have fade animation for carousel-esque sliders.
				// 'data-animation' => Bunyad::options()->slider_animation,
				'data-scroll-num' => $this->props['slide_scroll']
			]);
		
			if (isset($this->props['slide_scroll_md'])) {
				$slide_attrs['data-scroll-num-md'] = $this->props['slide_scroll_md'];
			}

			$this->props['slide_attrs'] = $slide_attrs;
		}
	}

	/**
	 * Set relevant classes for wrappers/containers and posts.
	 */
	public function set_classes()
	{
		$feat_class  = 'feat-' . $this->props['grid_type'];
		$classes = [
			'feat-grid', 
			$feat_class,
		];
		
		// Static or slider.
		$slide_type = $this->props['has_slider'] ? 'slider' : 'static';
		$classes[]  = $slide_type;
		
		if ($slide_type === 'slider') {
			$classes = array_merge($classes, [
				'common-slider',
				'arrow-hover',
			]);
		}
		
		if ($this->props['grid_width'] === 'viewport') {
			$classes[] = 'feat-grid-full';
			$classes[] = $feat_class . '-full';
		}
		else {
			// Add wrapper to slides wrapper if not viewport width.
			// $this->props['slide_attrs']['class'][] = 'wrap';
		}
		
		// Ratio or height based.
		if ($this->props['has_ratio']) {
			$classes[] = 'feat-grid-ratio';
		}
		else {
			$classes[] = 'feat-grid-height';
		}
		
		// A grid with items of equal dimensions.
		if ($this->props['equal_items']) {
			$classes[] = 'feat-grid-equals';
		}

		$this->props['grid_wrap_classes'] = $classes;
	}

	/**
	 * Compute and get extra props for rendering the final post.
	 *
	 * @param integer $index  Position/number of the post.
	 * @return array
	 */
	public function get_post_props($index)
	{	
		/**
		 * Override default configs with item configs at this specific position.
		 */
		$config = $this->base_configs['configs'];
		if (isset($this->props['item_configs'][$index])) {

			$data = (array) $this->props['item_configs'][$index];

			// Don't override meta_props fully, but merge.
			if (isset($data['meta_props'])) {
				$data['meta_props'] = array_replace($config, $data['meta_props']);
			}

			$config = array_replace($config, $data);
		}

		// Viewport width gets different image name.
		if ($this->props['grid_width'] == 'viewport' && substr($config['image'], -3) !== '-vw') {
			$config['image'] .= '-vw';
		}

		// Width is manually specified to ensure right responsive width is set.
		if (!empty($config['width'])) {
			$config['image_data']['width'] = 
				(
					$this->props['grid_width'] !== 'viewport' 
						? $config['width']['wrap']
						: $config['width']['full']
				);
		}

		// Item classes.
		$item_class = [
			'item', 
			$config['class'], 
			'item-' . $index
		];

		$pos_map = [
			'top'        => 'pos-top',
			'top-center' => 'pos-center pos-top',
			'bot-center' => 'pos-center pos-bot',
			'center'     => 'pos-center pos-v-center',
			'bottom'     => 'pos-bot',
		];

		if (array_key_exists($this->props['content_position'], $pos_map)) {
			$item_class[] = $pos_map[ $this->props['content_position'] ];
		}

		// Classes for overlay wrapper.
		$overlay_class = [
			'grid-overlay', 
			'grid-overlay-' . $this->props['overlay_style']
		];

		if ($this->props['meta_below'] && $this->props['meta_on_hover']) {
			$overlay_class[] = 'meta-hide';
		}

		if ($this->props['hover_effect']) {
			$overlay_class[] = $this->props['hover_effect'];
		}

		return [
			'item_wrap_class' => $item_class,
			'class_wrap'      => $overlay_class,
			'meta_props'      => $config['meta_props'],
			'image'           => $config['image'],
			'image_props'     => $config['image_data'],
			'excerpts'        => false
		];
	}

}