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
<div class="nav-wrap">
		<nav class="navigation navigation-small nav-hov-a">
			<ul id="menu-top-links" class="menu"><li id="menu-item-7618" class="l-icon menu-item menu-item-type-custom menu-item-object-custom current-menu-ancestor current-menu-parent menu-item-has-children menu-item-7618"><a href="#">Language</a>
<ul class="sub-menu">
	<li id="menu-item-7589" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-7589"><a href="https://12bsportsvn.com/">Vietnam</a></li>
	<li id="menu-item-7590" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-7590"><a href="https://12bsportsin.com/">English</a></li>
	<li id="menu-item-7592" class="menu-item menu-item-type-custom menu-item-object-custom current-menu-item current_page_item menu-item-home menu-item-7592"><a href="https://12bsportsth.com" aria-current="page">Thailand</a></li>
	<li id="menu-item-7593" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-7593"><a href="https://12bsportsid.com/">Indonesia</a></li>
</ul>
</li>
</ul>		</nav>
	</div>
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