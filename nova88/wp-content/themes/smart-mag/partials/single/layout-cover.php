<?php 
/**
 * Partial Template for Single Post "Cover Layout" - called from single.php
 */

$props = array_replace([
	'post_classes' => [],
], $props);

?>
	
<div class="post-wrap">
	<section class="the-post-header post-cover">
	
			<div class="featured">
					
				<?php if (Bunyad::posts()->meta('featured_video')): // featured video available? ?>
				
					<div class="featured-vid">
						<?php echo apply_filters('bunyad_featured_video', Bunyad::posts()->meta('featured_video')); ?>
					</div>
					
				<?php else: ?>
				
					<?php if (get_post_format() == 'gallery' && !Bunyad::amp()->active()): // get gallery template ?>
						
						<?php
						/**
						 * Emulate disabled sidebar for the gallery to be rendered full-width
						 */
						$sidebar = Bunyad::core()->get_sidebar();
						Bunyad::core()->set_sidebar('none');
						
						get_template_part('partials/gallery-format');

						Bunyad::core()->set_sidebar($sidebar);

						?>
					
					<?php elseif (has_post_thumbnail()): ?>

						<?php 
						/**
						 * Normal featured image when no post format
						 */
						$thumb   = get_post(get_post_thumbnail_id());
						$caption = $thumb ? $thumb->post_excerpt : '';
						$url     = get_permalink();
						
						// On single page? Link to image
						if (is_single()):
							$url = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full'); 
							$url = $url[0];
						endif;

						Bunyad::media()->the_image(
							'bunyad-main-full',
							[
								'link'        => !Bunyad::options()->single_featured_link ? false : $url,
								'bg_image'    => false,
							]
						);
						?>

						<?php if (!empty($caption)): ?>
								
							<div class="caption">
								<?php echo $caption; // phpcs:ignore WordPress.Security.EscapeOutput -- Sanitized caption from WordPress post_excerpt ?>
							</div>
								
						<?php endif;?>
						
					<?php 
					endif; // end check for featured image/gallery
					?>
					
					<div class="overlay s-dark">	
					<?php

						$meta = Bunyad::blocks()->load('PostMeta', [
							'type'        => 'single',
							'is_single'   => true,
							'text_labels' => ['by', 'comments', 'views'],
							'cat_style'   => 'labels',
							
							// Items below should be from global settings.
							'show_title'  => true,
							'add_class'   => 'post-meta-single',
							'align'       => 'left',
							'author_img'  => Bunyad::options()->post_meta_single_author_img,
							'avatar_size' => 32,
						]);

						$meta->render();
					?>	
					</div>				
					
				<?php endif; // end normal featured image ?>
			</div>
	
	</section>
	
	
	<div class="ts-row">
		<div class="col-8 main-content">
			<div <?php Bunyad::markup()->attribs('the-post-wrap', ['class' => $post_classes]); ?>>

				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<?php get_template_part('partials/single/post-content'); ?>
				</article>

				<?php Bunyad::core()->partial('partials/single/post-footer'); ?>
					
				<div class="comments">
					<?php comments_template('', true); ?>
				</div>

			</div>
		</div>

		<?php Bunyad::core()->theme_sidebar(); ?>
	</div>
</div> <!-- .post-wrap -->