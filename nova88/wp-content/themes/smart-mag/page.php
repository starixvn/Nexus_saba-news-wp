<?php
/**
 * Default Page Template
 */

$props = isset($props) ? $props : [];
$props = array_replace([
	'content_class' => 'post-content page-content entry-content',
	'breadcrumbs'   => !Bunyad::posts()->meta('hide_breadcrumbs'),
	'wrap_classes'  => [],
], $props);

get_header();

// Spacious style classes.
$spacious_style = Bunyad::posts()->meta('page_spacious');
if ($spacious_style) {	
	if (Bunyad::core()->get_sidebar() === 'none') {
		$props['wrap_classes'][] = 'the-post-modern';
	}

	$props['content_class'] .= Bunyad::core()->get_sidebar() === 'none' ? 'content-spacious-full' : 'content-spacious';
}

if ($props['breadcrumbs']) {
	Bunyad::blocks()->load('Breadcrumbs')->render();
}

if (Bunyad::posts()->meta('featured_slider')):
	get_template_part('partials/featured-area');
endif;

?>

<div <?php Bunyad::markup()->attribs('main'); ?>>
	<?php if (apply_filters('bunyad_do_partial_page', true)): ?>
		<div class="ts-row">
			<div class="col-8 main-content">
				
				<?php if (have_posts()): the_post(); endif; // load the page ?>

				<div id="post-<?php the_ID(); ?>" <?php post_class($props['wrap_classes']); ?>>

				<?php if (Bunyad::posts()->meta('page_title') !== 'no'): ?>
				
					<header class="post-header">				

					<?php if (has_post_thumbnail() && !Bunyad::posts()->meta('featured_disable')): ?>
						<div class="featured">
							<a href="<?php $url = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full'); echo $url[0]; ?>" title="<?php the_title_attribute(); ?>">
							
							<?php if ((!in_the_loop() && Bunyad::posts()->meta('layout_style') == 'full') OR Bunyad::core()->get_sidebar() == 'none'): // largest images - no sidebar? ?>
							
								<?php the_post_thumbnail('bunyad-main-full', array('title' => strip_tags(get_the_title()))); ?>
							
							<?php else: ?>
								
								<?php the_post_thumbnail('bunyad-main', array('title' => strip_tags(get_the_title()))); ?>
								
							<?php endif; ?>
							
							</a>
						</div>
					<?php endif; ?>
					
						<h1 class="main-heading the-page-heading entry-title">
							<?php the_title(); ?>
						</h1>
					</header><!-- .post-header -->
					
				<?php endif; ?>
			
					<div class="<?php echo esc_attr($props['content_class']); ?>">				
						<?php Bunyad::posts()->the_content(); ?>
					</div>

				</div>
				
			</div>
			
			<?php Bunyad::core()->theme_sidebar(); ?>
			
		</div> <!-- .row -->
	<?php endif; ?>
</div> <!-- .main -->

<?php get_footer(); ?>
