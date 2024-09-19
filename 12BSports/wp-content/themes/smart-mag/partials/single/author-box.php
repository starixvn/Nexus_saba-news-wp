<?php
/**
 * Partial Template for Author Box on single pages
 */
?>

<?php if (is_single() && Bunyad::options()->author_box) : // author box? ?>

	<div class="author-box">
		<?php get_template_part('partials/author'); ?>
	</div>

<?php endif; ?>