<?php
/**
 * Partial: Search used in header
 */

$props = array_replace([
	'type' => '',
], $props);


$input_classes = ['query'];

// live search enabled?
if (Bunyad::options()->header_search_live) {
	$input_classes[] = 'live-search-query';
}

?>

<?php if ($props['type'] === 'icon'): ?>

	<a href="#" class="search-icon has-icon-only is-icon" title="<?php esc_attr_e('Search', 'bunyad'); ?>">
		<i class="tsi tsi-search"></i>
	</a>

<?php else: ?>

	<div class="smart-head-search">
		<form role="search" class="search-form" action="<?php echo esc_url(home_url('/')); ?>" method="get">
			<input type="text" name="s" class="<?php echo esc_attr(join(' ', $input_classes)); ?>" value="<?php the_search_query(); ?>" placeholder="<?php esc_attr_e('Search...', 'bunyad'); ?>" autocomplete="off" />
			<button class="search-button" type="submit">
				<i class="tsi tsi-search"></i>
				<span class="visuallyhidden"><?php esc_html__('Search', 'bunyad'); ?></span>
			</button>
		</form>
	</div> <!-- .search -->

<?php endif; ?>