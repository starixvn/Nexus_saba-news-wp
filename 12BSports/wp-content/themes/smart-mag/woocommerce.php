<?php
/**
 * WooCommerce Main Template Catch-All 
 */

// Change sidebar for shop page
if (is_active_sidebar('smartmag-shop')) {
	Bunyad::registry()->sidebar = 'smartmag-shop';
}

get_header();
Bunyad::blocks()->load('Breadcrumbs')->render();

?>

<div <?php Bunyad::markup()->attribs('main'); ?>>

	<div class="ts-row">
		<div class="col-8 main-content">
			
			<?php woocommerce_content(); ?>
			
		</div>
		
		<?php Bunyad::core()->theme_sidebar(); ?>
		
	</div> <!-- .row -->
</div> <!-- .main -->

<?php get_footer(); ?>
