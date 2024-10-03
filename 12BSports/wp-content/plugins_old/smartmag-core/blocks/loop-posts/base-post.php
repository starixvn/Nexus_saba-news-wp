<?php

namespace Bunyad\Blocks\LoopPosts;
use Bunyad;

/**
 * Base Loop Post Class - should be extended for other posts
 */
class BasePost
{
	/**
	 * @var array Props/options for the post.
	 */
	public $props = [];

	/**
	 * @var string Identifier of the loop post.
	 */
	public $id  = '';

	/**
	 * Specific partial/view id for this post. If not specified, {$id} is used instead
	 * for the view file id.
	 * 
	 * @var string 
	 */
	public $view_id = '';

	/**
	 * @var string Used internally to keep track of caller block
	 */
	public $block;

	public function __construct($props = [])
	{
		$this->props = array_merge(
			$this->get_default_props(), 
			$this->map_props($props)
		);

		// Media ratio should go in image props.
		if (!empty($this->props['media_ratio'])) {
			$this->props['image_props'] += [
				'ratio' => $this->props['media_ratio']
			];
		}
	}

	/**
	 * Default props. No reason for it to be static here as, unlike bocks, 
	 * we won't need just the props alone.
	 * 
	 * Many of the props may be set by the block, but the blocks can call a 
	 * post directly. Hence, have to be set here too.
	 * 
	 * @return array
	 */
	public function get_default_props()
	{
		$props = [
			
			'show_content'       => true,
			'show_excerpt'       => true,
			'show_cat_label'     => true,
			'show_media'         => true,
			'show_title'         => true,

			// Post format icons overlays.
			'show_post_formats'  => true,
			'post_formats_pos'   => 'center',
			
			// Read more style expected.
			'read_more'          => 'none',
			'read_more_class'    => '',
			
			// Reviews style and location.
			'reviews'            => 'bars',
			
			'class_excerpt'      => '',
			'class_wrap'         => '',
			'class_wrap_add'     => [],
			
			'title_tag'          => 'h2',
			'title_class'        => 'post-title',
			'title_lines'        => '',
			
			'cat_labels_pos'     => '',
			'excerpt_length'     => 15,
			'excerpt_lines'      => '',
			'excerpt_class'      => 'excerpt',
			'meta_items_default' => true,
			'meta_id'            => 'loop',
			'meta_type'          => 'a',
			'meta_props'         => [],
			
			// Can be 'below' or empty for default.
			'media_location'     => '',
			'media_embed'        => false,
			'image'              => 'post-thumbnail',
			'image_props'        => [],
			
			// Alternate styles may be used.
			'style'              => '',
			
			// Center content.
			'content_center'     => false,
			
			// Add a wrapper to content.
			'content_wrap'       => false,

			// Internal counter when in a loop.
			'loop_number'        => 0,
			'skip_lazy_number'   => 0,
		];

		return $props;
	}

	/**
	 * Set a single property - sets on $this->props[] array
	 * 
	 * @return $this
	 */
	public function set($key, $value) 
	{
		$this->props[$key] = $value;
		return $this;
	}

	/**
	 * Get all props
	 * 
	 * @see self::get_default_props()
	 * @return array
	 */
	public function get_props()
	{
		return $this->props;
	}

	/**
	 * Convert block properties / aliases to recognized props.
	 */
	public function map_props($props)
	{
		$map = [
			'excerpts'     => 'show_excerpt',
			'excerpts_len' => 'excerpt_length', // Legacy
			'cat_labels'   => 'show_cat_label',
		];

		foreach ($props as $key => $value) {

			if (array_key_exists($key, $map)) {
				$props[ $map[$key] ] = $value;
				unset($props[$key]);
			}
		}

		// Don't override these if empty. Essentially falling back to default if empty.
		$non_empty_overrides = [
			'image', 
			'post_formats_pos'
		];

		foreach ($non_empty_overrides as $key) {
			if (isset($props[$key]) && !$props[$key]) {
				unset($props[$key]);
			}
		}
		
		return $props;
	}

	/**
	 * Render the partial view for this loop
	 * 
	 * @uses Bunyad_Core::partial()
	 */
	public function render()
	{
		$this->_pre_render();
		
		/**
		 * Run an action before rendering the post.
		 * 
		 * @param self $this
		 */
		do_action('bunyad_blocks_post_render', $this);

		// Set view if an ID is present.
		$view_id = ($this->view_id ? $this->view_id : $this->id);

		// Local variables to make available to the partial.
		extract([
			'post_obj' => $this
		]);

		/**
		 * Get our template partial.
		 * 
		 * Preferred file, usually: blocks/loop-posts/html/{$view_id}-post.php
		 * Default/Fallback: blocks/loop-posts/html/post.php
		 * 
		 * Note: Not using Bunyad::core()->partial() as that would result in post-grid.php
		 * for example, which is not consistent with rest of our naming.
		 */
		include locate_template([
			'blocks/loop-posts/html/' . $view_id . '-post.php',
			'blocks/loop-posts/html/post.php',
		]);
	}

	/**
	 * Checks and settings to do prior to render.
	 */
	public function _pre_render()
	{
		$this->props['class_wrap_add'] = (array) $this->props['class_wrap_add'];

		// Add style variation class to wrapper classes.
		if ($this->props['style']) {
			array_push(
				$this->props['class_wrap_add'],
				$this->id . '-' . $this->props['style'] . '-post'
			);
		}

		// Media position for some blocks.
		if (!empty($this->props['media_pos'])) {
			$this->props['class_wrap_add'][] = 'm-pos-' . $this->props['media_pos'];
		}

		// Vertically centered content.
		if ($this->props['content_center']) {
			$this->props['class_wrap_add'][] = 'l-post-center';
		}

		// Add block class to wrapper - don't add it class_wrap is manually defined.
		if (!$this->props['class_wrap'] && $this->id) {

			// Example: grid-post
			$this->props['class_wrap'] = [$this->id . '-post'];
		}

		// Add in the wrapper classes now.
		$this->props['class_wrap'] = array_merge((array) $this->props['class_wrap'], (array) $this->props['class_wrap_add']);

		/**
		 * Title classes.
		 */
		// Class title defaults to post-title.
		if (!$this->props['title_class']) {
			$this->props['title_class'] = join(' ', array('post-title', $this->props['class_title_add']));
		}

		// Add lines limit to post-title class.
		if ($this->props['title_lines']) {
			$this->props['title_class'] .= ' limit-lines l-lines-' . intval($this->props['title_lines']);
		}

		if (!$this->props['title_tag']) {
			$this->props['title_tag'] = 'h2';
		}

		// Excerpt
		// Add excerpt lines limit.
		if ($this->props['excerpt_lines']) {
			$this->props['excerpt_class'] .= ' limit-lines l-lines-' . intval($this->props['excerpt_lines']);
		}

		/**
		 * Read More classes.
		 */
		if ($this->props['read_more'] === 'none') {
			$this->props['read_more'] = '';
		}

		if ($this->props['read_more'] && !$this->props['read_more_class']) {
			$read_classes = [
				'read-more-link',

				// read-more-btn, read-more-basic etc.
				'read-more-' . $this->props['read_more'],
			];

			// Error? Legacy?
			if (!is_string($this->props['read_more'])) {
				$this->props['read_more'] = 'btn';
			}

			if ($this->props['read_more'] == 'btn') {
				$read_classes[] = 'ts-button ts-button-alt';
			}

			if ($this->props['read_more'] == 'btn-b') {
				$read_classes[] = 'ts-button read-more-btn';
			}

			$this->props['read_more_class'] = join(' ', $read_classes);
		}

		// Check if we have to skip lazyload on this post's media.
		if ($this->props['skip_lazy_number']) {
			$skip = intval($this->props['skip_lazy_number']);
			if ($this->props['loop_number'] <= $skip) {
				$this->props['image_props']['no_lazy'] = true; 
			}
		}

		// This comes after processing is done on title_class and so on.
		$this->add_meta_props();
	}

	protected function add_meta_props()
	{
		// Add defaults to meta_props.
		$this->props['meta_props'] += [
			'show_title'  => $this->props['show_title'],
			'type'        => $this->props['meta_id'],
			'title_tag'   => $this->props['title_tag'],
			'title_class' => $this->props['title_class'],
			'cat_style'   => $this->props['meta_cat_style'],
			'author_img'  => $this->props['meta_author_img'],
			'review'      => false,
		];

		// Set items if not default. Can set meta_props directly to bypass this.
		// if (!$this->props['meta_items_default']) {

		if (isset($this->props['meta_above']) && isset($this->props['meta_below'])) {
			$this->props['meta_props'] = array_replace($this->props['meta_props'], [
				'items_above' => $this->props['meta_above'],
				'items_below' => $this->props['meta_below'],
			]);
		}

		// Add stars handler if reviews enabled.
		if ($this->props['reviews'] === 'stars') {
			$this->props['meta_props']['review'] = true;

			if (!in_array('review_stars', $this->props['meta_props']['items_below'])) {
				array_unshift(
					$this->props['meta_props']['items_below'],
					'review_stars'
				);
			}
		}
	}

	/**
	 * Whether to embed media for this post.
	 *
	 * @return bool|string
	 */
	public function should_embed_media()
	{
		if (!$this->props['media_embed']) {
			return false;
		}

		if (get_post_format() === 'gallery' && !Bunyad::amp()->active()) {
			return 'gallery';
		}

		// Audio/Video code.
		if (Bunyad::posts()->meta('featured_video')) {
			return 'code';
		}
	}

	/**
	 * Output media embed.
	 *
	 * @return void
	 */
	public function embed_media()
	{
		$embed = $this->should_embed_media();
		if (!$embed) {
			return; 
		}
		
		if ($embed === 'gallery') {
			get_template_part('partials/gallery-format');
		}
		
		/**
		 * Audio / video code.
		 */
		if ($embed === 'code') {

			$allowed_tags = wp_kses_allowed_html('post');
			$allowed_tags['iframe'] = [
				'align'        => true,
				'width'        => true,
				'height'       => true,
				'frameborder'  => true,
				'name'         => true,
				'src'          => true,
				'id'           => true,
				'class'        => true,
				'style'        => true,
				'scrolling'    => true,
			];

			$embed_code = wp_kses(
				Bunyad::posts()->meta('featured_video'),
				$allowed_tags
			);

			// Will use Bunyad_Theme_SmartMag::featured_media_auto_embed() to auto-embed.
			printf(
				'<div class="featured-vid media-embed">%1$s</div>',
				apply_filters('bunyad_featured_video', $embed_code)
			);
		}
	}

	/**
	 * Output the post format icon.
	 */
	public function the_post_format_icon()
	{
		if (!$this->props['show_post_formats']) {
			return;
		}

		// Have to fallback to center if there's a review enabled at top right.
		if ($this->props['post_formats_pos'] === 'top-right' && $this->has_review_overlay()) {
			$this->props['post_formats_pos'] = 'center';
		}

		// If category overlay at same location, center it.
		if ($this->props['post_formats_pos'] === $this->props['cat_labels_pos']) {
			$this->props['post_formats_pos'] = 'center';
		}

		switch (get_post_format()) {
			
			case 'image':
			case 'gallery':
				$icon = 'tsi-picture-o';
				break;

			case 'video';
				$icon = 'tsi-play';
				break;
				
			case 'audio':
				$icon = 'tsi-music';
				break;
				
			default:
				return;
		}

		$classes = [
			'format-overlay c-overlay',
			'format-' . get_post_format(),
			'p-' . $this->props['post_formats_pos'],
		];

		printf(
			'<span class="%1$s"><i class="%2$s"></i></span>',
			join(' ', $classes),
			'tsi ' . esc_attr($icon)
		);
	}

	/**
	 * Check if the post has a review overlay.
	 */
	public function has_review_overlay()
	{
		// Enabled if review exists and prop isn't set to stars, none or empty.
		return (
			Bunyad::posts()->meta('reviews')
			&& !in_array($this->props['reviews'], ['none', 'stars', ''])
		);
	}

	/**
	 * Output the review overlay if reviews enabled.
	 * 
	 * @uses \Bunyad::posts()
	 * @uses \Bunyad::reviews()
	 */
	public function the_review_overlay()
	{
		if (!$this->has_review_overlay() || !Bunyad::reviews()) {
			return;
		}

		$output = '';

		switch ($this->props['reviews']) {
			case 'bars':
				$output = sprintf(
					'<div class="review review-number c-overlay">
						<span class="progress"></span><span>%1$s</span>
					</div>',
					esc_html(Bunyad::posts()->meta('review_overall'))
				);
				break;

			case 'radial':
				
				$type    = Bunyad::posts()->meta('review_type');
				$rating  = Bunyad::posts()->meta('review_overall');
				$percent = Bunyad::reviews()->decimal_to_percent($rating);

				// Star ratings should be shown in percent.
				if ($type === 'stars') {
					$type = 'percent';
				}	

				// Counter label for percent ratings.
				if ($type === 'percent') {
					$rating = $percent;
				}

				$counter = sprintf(
					'<span class="counter %1$s">%2$s</span>',
					esc_attr($type),
					$rating // Safe above
				);

				$output = sprintf(
					'<div class="review review-radial c-overlay">%1$s %2$s</div>',
					$this->review_progress_svg($percent),
					$counter
				);

				break;
		}

		echo $output; // phpcs:ignore WordPress.Security.EscapeOutput -- Safe output from above.

	}

	/**
	 * Generate an SVG for the radial progress bar for review.
	 * 
	 * Maths from: https://codepen.io/xgad/post/svg-radial-progress-meters
	 */
	public function review_progress_svg($percent, array $options = [])
	{
		// Radius is half of desired height/width.
		$options = array_replace([
			'radius' => 22,
			'stroke' => 3
		], $options);

		extract($options, EXTR_SKIP);

		// Calculations for radius and dash-offset.
		$norm_radius    = $radius - $stroke / 2;
		$circumference  = $norm_radius * 2 * pi();

		// Only for 2nd circle.
		$dash_offset   = (1 - $percent / 100) * $circumference;

		/**
		 * Create a circle.
		 */
		$create_circle = function($class, $offset = 0) use (
			$circumference, $stroke, $norm_radius, $radius
		) {
			
			$dash_offset = '';
			if ($offset) {
				$dash_offset = 'style="stroke-dashoffset:' . floatval($offset) . '"';
			}

			return sprintf(
				'<circle fill="transparent" stroke-dasharray="%1$s %1$s" %2$s stroke-width="%3$s"'
				. ' r="%4$s" cx="%5$s" cy="%5$s" class="%6$s" />',
				esc_attr($circumference),
				$dash_offset,
				esc_attr($stroke),
				esc_attr($norm_radius),
				esc_attr($radius),
				esc_attr($class)
			);
		};		

		$output = sprintf(
			'<svg class="progress-radial" height="%1$s" width="%1$s">%2$s %3$s</svg>',
			esc_attr($radius * 2),
			$create_circle('circle'),
			$create_circle('progress', $dash_offset)
		);

		return $output;
	}

	/**
	 * Magic method for print / echo
	 */
	public function __toString() 
	{
		ob_start();
		$this->render();
		
		return ob_get_clean();
	}
}