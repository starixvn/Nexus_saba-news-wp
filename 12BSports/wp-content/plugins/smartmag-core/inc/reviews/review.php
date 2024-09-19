<?php
/**
 * Block for review shortcode
 * 
 * Collect criteria and create an average rating to display.
 * 
 * Override by creating your-theme/partials/review.php file.
 */

$ratings = Bunyad::reviews()->get_criteria();
$review_scale = intval(Bunyad::options()->review_scale);

$overall = Bunyad::posts()->meta('review_overall');
$heading = Bunyad::posts()->meta('review_heading');
$type    = Bunyad::posts()->meta('review_type') ?: 'points';

if (!is_numeric($overall)) {
	$overall = 0;
}

// add verdict text 
$verdict = Bunyad::posts()->meta('review_verdict');
$verdict_text = Bunyad::posts()->meta('review_verdict_text');

// container classes
$classes[] = 'review-box';
if ($position == 'top-left') {
	array_push($classes, 'alignleft', 'column', 'half');
}

// add stars class
if ($type == 'stars') {
	array_push($classes, 'stars', 'review-box-stars');
}

// Allow themes to override.
if ($file = locate_template('partials/review.php')) {
	include $file; // phpcs:ignore WordPress.Security.EscapeOutput -- Safe from locate_template()
	return;
}

$pros_cons = Bunyad::reviews()->get_pros_cons();

?>

<section class="<?php echo esc_attr(implode(' ', $classes)); ?>">	
<div class="inner">
	<?php if ($heading): ?>
		<h3 class="heading"><?php echo esc_html($heading); ?></h3>
	<?php endif; ?>

	<div class="verdict-box">
		<div class="overall">
			<span class="number rating"><span class="value">
				<?php
					$overall_percent = round($overall / $review_scale * 100);
					echo (
						$type !== 'points' 
							? esc_html($overall_percent) . '<span class="percent">%</span>' 
							: esc_html($overall)
					); 

				?></span>
				
				<?php if ($type == 'stars'): ?>
				<div class="overall-stars">
					<div class="main-stars">
						<span style="width: <?php echo esc_attr($overall_percent); ?>%;"><strong class="number"><?php echo esc_html($overall_percent); ?>%</strong></span>
					</div>
				</div>
				
				<?php endif; ?>
				
				<span class="best"><span class="value-title" title="<?php echo esc_attr($type != 'points' ? 100 : Bunyad::reviews()->rating_max); ?>"></span></span>
			</span>
			<span class="verdict"><?php echo esc_html($verdict); ?></span>
		</div>
		
		<div class="text summary"><?php 
			echo do_shortcode(wpautop(
				wp_kses_post($verdict_text)
			));
		?></div>		
	</div>
	
	<?php if ($pros_cons): ?>

		<div class="review-pros-cons">
			<div class="the-pros">
				<h5 class="the-heading"><?php echo esc_html($pros_cons['pros_title']); ?></h5> 
				
				<ol class="the-pros-list">
				<?php foreach ($pros_cons['pros'] as $text): ?>
					<li>
					<svg viewBox="0 0 14 14"><path d="M12.376 2.056 4.788 9.644 1.623 6.479A.736.736 0 1 0 .581 7.521l3.686 3.686a.736.736 0 0 0 1.042 0l8.109-8.109a.736.736 0 1 0-1.042-1.042z"/></svg>
						<?php echo wp_kses_post($text); ?> 
					</li>
				<?php endforeach; ?>
				</ol>
			</div>

			<div class="the-cons">
				<h5 class="the-heading"><?php echo esc_html($pros_cons['cons_title']); ?></h5> 
				
				<ol class="the-cons-list">
				<?php foreach ($pros_cons['cons'] as $text): ?>
					<li>
					<svg viewBox="0 0 14 14"><path d="M2.209 3.219 5.99 7l-3.781 3.781a.714.714 0 1 0 1.01 1.01L7 8.01l3.781 3.781a.714.714 0 1 0 1.01-1.01L8.01 7l3.781-3.781a.714.714 0 1 0-1.01-1.01L7 5.99 3.219 2.209a.714.714 0 1 0-1.01 1.01z"/></svg>
						<?php echo wp_kses_post($text); ?> 
					</li>
				<?php endforeach; ?>
				</ol>
			</div>
		</div>

	<?php endif; ?>
	
	<ul class="criteria">
	<?php foreach ((array) $ratings as $rating): 
	
			$percent = round(($rating['rating'] / $review_scale) * 100);
	
			if ($type == 'percent') {
				$rating['rating'] = $percent . ' %';
			}
	?>
	
		<li>
		
		<?php if ($type == 'stars'): ?>
		
			<div class="criterion">
				<span class="label"><?php echo esc_html($rating['label']); ?></span>

				<div class="main-stars">
					<span style="width: <?php echo $percent; ?>%;"><strong class="rating"><?php echo esc_html($rating['rating']); ?></strong></span>
				</div>
			
			</div>
				
		<?php else: ?>
		
			<div class="criterion">
				<span class="label"><?php echo esc_html($rating['label']); ?></span>	
				<span class="rating"><?php echo esc_html($rating['rating']); ?></span>
			</div>
			
			<div class="rating-bar"><div class="bar" style="width: <?php echo $percent; ?>%;"></div></div>
				
		<?php endif; ?>

		</li>
	
	<?php endforeach; ?>
	
	<?php if (Bunyad::options()->user_rating): ?>
	
		<li class="review-box-user-ratings user-ratings<?php echo (!Bunyad::reviews()->can_rate() ? ' voted' : ''); ?>" data-post-id="<?php echo get_the_ID(); ?>">
			<span class="label"><?php esc_html_e('User Ratings', 'bunyad'); ?> <span class="votes">(<?php 
				printf(__('%s Votes', 'bunyad'), '<span class="number">' . Bunyad::reviews()->votes_count() . '</span>'); ?>)</span>
			</span>
			
			<?php if ($type == 'stars'): ?>
				
				<div class="main-stars">
					<span style="width: <?php echo Bunyad::reviews()->get_user_rating(null, 'percent'); ?>%;"><strong class="rating"><?php echo esc_html(Bunyad::reviews()->get_user_rating()); ?></strong></span>
				</div>
				
				<span class="hover-number"></span>
				
			<?php else: ?>
			
				<span class="rating"><?php echo esc_html(Bunyad::reviews()->get_user_rating(null, $type)) . ($type == 'percent' ? ' %' : ''); ?></span>
				
				<span class="hover-number"></span>
			
				<div class="rating-bar"><span class="bar" style="width: <?php echo Bunyad::reviews()->get_user_rating(null, 'percent'); ?>%;"></span></div>			
			
			<?php endif; ?>
		</li>
	
	<?php endif;?>
	
	</ul>
</div>
</section>