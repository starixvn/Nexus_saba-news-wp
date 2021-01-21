<?php
/**
 * Template part for displaying results in search pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Telegram
 */

?>
<article id="post-<?php the_ID(); ?>" <?php post_class("twp-archive-post"); ?>>
	<div class="twp-image-section twp-image-with-content twp-image-hover-effect twp-image-zoom-in-effect">
	<a href="<?php the_permalink(); ?>"></a>
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
		<div class="twp-category twp-category-with-bg">
			<?php telegram_posted_category(); ?>
		</div>
	</div>
	<div class="twp-content-wrapper">
		<header class="entry-header">
			<div class="twp-meta-tag entry-meta twp-d-flex">
				<?php
					telegram_posted_on();
					telegram_posted_by();
				?>
			</div><!-- .entry-meta -->
			<?php
			if ( is_singular() ) :
				the_title( '<h1 class="entry-title">', '</h1>' );
			else :
				the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
			endif;
			?>
			
		</header><!-- .entry-header -->
		<div class="entry-content">
			<?php
				telegram_post_view_count();
			?>
			<?php the_excerpt(); ?>
			<div class="twp-btn-section">
				<div class="twp-read-more">
					<a href="<?php the_permalink(); ?>" class="twp-read-more-btn"><?php esc_html_e( 'Read More', 'telegram' ); ?></a>
				</div>
				<div class="twp-social-share-section twp-social-share-with-hover-effect twp-icon-left-align">
					<?php telegram_social_share_option(); ?>
				</div>
			</div>
		</div><!-- .entry-content -->
	</div>
</article><!-- #post-<?php the_ID(); ?> -->
