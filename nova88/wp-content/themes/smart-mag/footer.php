<?php
/**
 * Footer Template.
 */

do_action('bunyad_footer_before');
$do_footer = apply_filters('bunyad_do_partial_footer', true);

if ($do_footer) {
	// Fallback to classic footer if we're here, implying custom is missing.
	$footer_layout = Bunyad::options()->footer_layout ?? 'classic';

	$classes = [
		'main-footer',
		'cols-gap-lg',
		'footer-' . $footer_layout,
		Bunyad::options()->footer_scheme === 'dark' ? 's-dark' : '',
	];
}

?>
	<?php if ($do_footer): ?>
		<footer <?php Bunyad::markup()->attribs('main-footer', [
			'class' => $classes
		]); ?>>

			<?php
				get_template_part('partials/footer/' . $footer_layout);
			?>
		</footer>
	<?php endif; ?>
	
	<?php do_action('bunyad_footer_after'); ?>

</div><!-- .main-wrap -->

<?php get_template_part('partials/search-modal'); ?>

<?php wp_footer(); ?>

</body>
</html>