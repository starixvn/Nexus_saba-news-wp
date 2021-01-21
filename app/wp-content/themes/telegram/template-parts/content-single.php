<?php
/**
 * Template part for displaying single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Telegram
 */
telegram_disable_post_views();
telegram_disable_post_read_time();
telegram_disable_post_like_dislike();
?>
	<div class="twp-single-banner">
		<div class="twp-post twp-post-style-5 twp-d-flex">
			<div class="twp-image-section twp-image-with-content twp-image-hover-effect twp-image-zoom-in-effect twp-image-420">
				<?php 
				if (has_post_thumbnail()) {
					$thumb = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'full');
					$url = $thumb['0'];
				}else {
					$url = '';
				}
				?>
				<div class="twp-image bg-image" style="background-image:url('<?php echo esc_url($url); ?>')">
				</div>
				<div class="twp-post-format-wrapper twp-d-flex">
					<div class="twp-post-format twp-post-format-with-bg">
						<?php echo esc_attr(telegram_post_format(get_the_ID())); ?>
					</div>
					<?php telegram_post_read_time(); ?>
				</div>
			</div>
			<div class="twp-desc">
				<div class="twp-wrapper">
					<div class="twp-category twp-category-with-bg">
						<?php telegram_posted_category(); ?>
					</div>
	
					<header class="entry-header">
						<?php
						if ( is_singular() ) :
							the_title( '<h1 class="entry-title">', '</h1>' );
						else :
							the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
						endif;?>
					</header><!-- .entry-header -->
					
					<!-- Meta Tag -->
					<?php if ( 'post' === get_post_type() ) : ?>
						<div class="twp-meta-tag entry-meta twp-d-flex twp-meta-tag-lg">
							<?php
							telegram_posted_on();
							telegram_posted_by();
							?>
						</div><!-- .entry-meta -->
					<?php endif; ?>
	
					<div class="twp-social-share-section twp-social-share-lg twp-social-share-rounded">
						<?php telegram_social_share_option(); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="twp-site-content-wrapper">
		<main id="primary" class="site-main">
			<article id="post-<?php the_ID(); ?>" <?php post_class("twp-single-post"); ?>>
			
				<div class="entry-content">
					<?php
					the_content(
						sprintf(
							wp_kses(
								/* translators: %s: Name of current post. Only visible to screen readers */
								__( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'telegram' ),
								array(
									'span' => array(
										'class' => array(),
									),
								)
							),
							wp_kses_post( get_the_title() )
						)
					);
		
					wp_link_pages(
						array(
							'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'telegram' ),
							'after'  => '</div>',
						)
					);
					?>
				</div><!-- .entry-content -->
		
				<footer class="entry-footer">
					<?php telegram_entry_footer(); ?>
				</footer><!-- .entry-footer -->
			</article><!-- #post-<?php the_ID(); ?> -->
			<?php
			 	echo '<div class="twp-pagination">';
					the_post_navigation(
						array(
							'prev_text' => '<span class="twp-icon"><i class="ion ion-md-arrow-back"></i></span> <span class="nav-title">%title</span>',
							'next_text' => '<span class="twp-icon"><i class="ion ion-md-arrow-forward"></i></span></span> <span class="nav-title">%title</span>',
						)
					);
				echo '</div>';
		
				// If comments are open or we have at least one comment, load up the comment template.
				if ( comments_open() || get_comments_number() ) :
					comments_template();
				endif;
		
			?>

			<?php 
			/**
			 * Hook telegram_related_posts
			 *
			 * @hooked telegram_get_related_posts
			 */
			// do_action('telegram_related_posts');
			 ?>
			
		</main><!-- #main -->


		<?php get_sidebar(); ?>
	</div>
