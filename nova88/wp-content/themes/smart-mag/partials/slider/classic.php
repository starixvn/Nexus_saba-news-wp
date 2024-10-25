<?php 
/**
 * Legacy: Classic default slider + 3 posts grid area of SmartMag
 * 
 * Not the best choice for featured area anymore.
 */

$props = array_replace([
	'posts' => 1,
	'query' => ''
], $props);

// Posts grid generated from a category or a tag? (right side of slider)
$limit_cat = Bunyad::options()->classic_slider_right_cat;
$limit_tag = Bunyad::options()->classic_slider_right_tag;

$main_limit = $props['posts'];

if (!empty($limit_cat)) {
	$args['posts_per_page'] = $main_limit;
	$grid_query = array('cat' => $limit_cat, 'posts_per_page' => 3);
}

if (!empty($limit_tag)) {
	$grid_query = array('tag' => $limit_tag, 'posts_per_page' => 3);
}

// Use rest of the 3 posts for grid if not post grid is not using 
// any category or tag. Create reference for to main query.
if (empty($grid_query) && $query->found_posts > $main_limit) {
	$grid_query = &$query;
}

$i = $z = 0; // loop counters

wp_print_styles(['smartmag-classic-slider']);
wp_enqueue_script('smartmag-flex-slider');

// setup configuration vars
$data_vars = array(
	'data-animation' => Bunyad::options()->classic_slider_animation,
	'data-animation-speed' => intval(Bunyad::options()->classic_slider_slide_delay),
	'data-slide-delay' => Bunyad::options()->classic_slider_animation_speed,
);

?>
	
	<div class="main-featured is-container has-classic-slider">
		<div class="wrap cf">
		
		<div class="classic-slider grid grid-8-4 md:grid-1">
			<div <?php Bunyad::markup()->attribs('classic-slider', $data_vars + [
					'class' => ''
				]); ?>>
				<div class="slider frame flexslider">
				<ul class="slides">
				
				<?php while ($query->have_posts()): $query->the_post(); ?>
					
					<li>
						<?php
							Bunyad::media()->the_image(
								'bunyad-classic-slider',
								[]								
							);
						?>
	
						<?php $cat = Bunyad::blocks()->get_primary_cat(); ?>
						
						<?php 
							echo Bunyad::blocks()->cat_label([
								'position' => 'top-left',
							]); 
						?>
						
						<div class="caption">

							<time class="the-date" datetime="<?php echo esc_attr(get_the_time(DATE_W3C)); ?>"><?php echo esc_html(get_the_date()); ?></time>
							
							<h3><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" class="post-title"><?php the_title(); ?></a></h3>
	
						</div>					
						
					</li>
					
				<?php 
						if ($i++ == ($main_limit - 1)) {
							
							// give a chance to loop_end hook to run because we are breaking - required for duplicates prevention
							$query->have_posts();
							break;
						}
					
					endwhile; //rewind_posts(); 
				?>
			
				</ul>
				
				<div class="pages" data-number="<?php echo esc_attr($main_limit); ?>">
				
				<?php foreach (range(1, $main_limit) as $page): ?>
					<a href="#"></a>
				<?php endforeach; ?>

				</div>
				
				</div>
				
				
			</div> <!-- .flexslider -->
		
			<div class="blocks">
			
			<?php
			 
			// init the grid query
			if (is_array($grid_query)) {
				$grid_query = new WP_Query
					(apply_filters('bunyad_block_query_args', $grid_query, 'slider_grid')
				);
			}
			
			if (!empty($grid_query) && $grid_query->have_posts()): 
			?>
			
				<?php 
				while ($grid_query->have_posts()): $grid_query->the_post(); $z++; 
				
						if (!has_post_thumbnail()) {
							continue;
						}
						
						// custom label selected?
						if (($cat_label = Bunyad::posts()->meta('cat_label'))) {
							$category = get_category($cat_label);
						}
						else {
							$category = current(get_the_category());						
						}
				?>
				
				<article class="<?php echo ($z == 1 ? 'large' : ($z == 2 ? 'small' : 'small last')); ?>">
					 
				<?php if ($z == 1): ?>
					<?php 
						echo Bunyad::blocks()->cat_label([
							'position' => 'top-left',
						]); 
					?>
				<?php endif; ?>
					 
					<?php
							Bunyad::media()->the_image(
								($z == 1 ? 'bunyad-classic-slider-md' : 'bunyad-classic-slider-sm'),
								[]								
							);
						?>

					 <h3><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" class="post-title"><?php the_title(); ?></a></h3>
					
				</article>
				
				
				<?php endwhile; ?>
				
		<?php endif; // end grid query check ?>				
		</div>
			
		</div> <!-- .row -->

		<?php 
			wp_reset_postdata();
		?>

		</div> <!--  .wrap  -->
	</div>
