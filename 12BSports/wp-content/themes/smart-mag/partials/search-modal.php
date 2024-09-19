<?php
/**
 * Search modal
 */

$input_classes = ['search-field'];

// live search enabled?
if (Bunyad::options()->header_search_live) {
	$input_classes[] = 'live-search-query';
}

?>

<?php if (Bunyad::amp()->active()): ?>
<amp-lightbox id="search-modal-lightbox" class="search-modal" layout="nodisplay">
	<button title="Close (Esc)" type="button" class="mfp-close" on="tap:search-modal-lightbox.close">&times;</button>
<?php endif; ?>

	<div class="search-modal-wrap" data-scheme="<?php echo esc_attr(Bunyad::options()->header_search_overlay_scheme); ?>">
		<div class="search-modal-box" role="dialog" aria-modal="true">

			<form method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
				<input type="search" class="<?php echo esc_attr(join(' ', $input_classes)); ?>" name="s" placeholder="<?php esc_attr_e('Search...', 'bunyad'); ?>" value="<?php 
						echo esc_attr(get_search_query()); ?>" required />

				<button type="submit" class="search-submit visuallyhidden"><?php esc_html_e('Submit', 'bunyad'); ?></button>

				<p class="message">
					<?php 
						printf(
							esc_html__('Type above and press %1$sEnter%2$s to search. Press %1$sEsc%2$s to cancel.', 'bunyad'),
							'<em>', 
							'</em>'
						);
					?>
				</p>
						
			</form>

		</div>
	</div>

<?php if (Bunyad::amp()->active()): ?>
</amp-lightbox>
<?php endif; ?>
