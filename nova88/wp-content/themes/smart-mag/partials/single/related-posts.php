<?php 
/**
 * Partial Template for related posts on single pages
 */

$props = !isset($props) ? [] : $props;
$props = array_replace([
	'heading'       => Bunyad::options()->get_or(
		'related_posts_heading', 
		esc_html__('Related *Posts*', 'bunyad')
	),
	'heading_style' => Bunyad::options()->single_section_head_style,
	'heading_tag'   => Bunyad::options()->single_section_head_tag,
	'meta_above'    => Bunyad::options()->related_posts_meta_above,
	'meta_below'    => Bunyad::options()->related_posts_meta_below,
	'number'        => (
		Bunyad::core()->get_sidebar() == 'none' 
			? Bunyad::options()->related_posts_number_full 
			: Bunyad::options()->related_posts_number
	),
	'per_row'      => (
		Bunyad::core()->get_sidebar() == 'none' 
			? Bunyad::options()->related_posts_grid_full
			: Bunyad::options()->related_posts_grid
	)
], $props);

if (!is_single() || !Bunyad::options()->related_posts) {
	return;
}

$related = Bunyad::posts()->get_related($props['number']);
if (!$related || !$related->have_posts()) {
	return;
}

?>

	<section class="related-posts">
		<?php

		// Heading Block.
		Bunyad::blocks()->load(
			'Heading',
			[
				'heading'   => $props['heading'],
				// 'align'     => $this->props['heading_align'],
				'type'      => $props['heading_style'],
				'html_tag'  => $props['heading_tag'],
			]
		)
		->render();
		
		// The Loop.
		Bunyad::blocks()
			->load_loop('grid', [
				'query'      => $related,
				'columns'    => $props['per_row'],
				'posts'      => $props['number'],
				'pagination' => false,
				'excerpts'   => false,
				'cat_labels' => false,
				'style'      => 'sm',
				'meta_items_default' => false,
				'meta_above'         => $props['meta_above'],
				'meta_below'         => $props['meta_below'],
			])
			->render();
		?>

	</section>