<?php
/**
 * Default 404 Page
 */
get_header();

?>
<div <?php Bunyad::markup()->attribs('main'); ?>>

	<?php if (apply_filters('bunyad_do_partial_404', true)): ?>
		<div class="ts-row">
			<div class="col-12 cf">

				<div class="the-post the-page page-404 cf">
					<header>
						<h1 class="main-heading"><?php 
							echo esc_html(
								Bunyad::options()->get_or(
									'page_404_title', 
									esc_html__('Page Not Found!', 'bunyad')
								)
							); 
						?></h1>
					</header>
				
					<div class="post-content error-page row">
						<div class="col-3 text-404 main-color">
							<?php esc_html_e('404', 'bunyad'); ?>
						</div>
						
						<div class="col-8 post-content">
							<p>
							<?php 
								echo wp_kses_post(
									Bunyad::options()->get_or(
										'page_404_text',
										esc_html__("We're sorry, but we can't find the page you were looking for. It's probably some thing we've done wrong but now we know about it and we'll try to fix it. In the meantime, try one of these options:", 'bunyad')
									)	
								);
							?>
							</p>
							<ul class="links">
								<li> <a href="#" class="go-back"><?php esc_html_e('Go to Previous Page', 'bunyad'); ?></a></li>
								<li> <a href="<?php echo esc_url(home_url()); ?>"><?php esc_html_e('Go to Homepage', 'bunyad'); ?></a></li>
							</ul>
							
							<?php get_search_form(); ?>
						</div>
					
					</div>
				</div>

			</div>
		</div>
<?php endif; ?>

</div> <!-- .main -->

<?php get_footer(); ?>