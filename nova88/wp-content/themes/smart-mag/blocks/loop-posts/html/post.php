<?php 
/**
 * Loop Post view called by loop post object
 * 
 * To Modify / Override this file:
 * 
 *  - You can copy this file to your child theme in the same path.
 *     Ex: child-theme/blocks/loop-posts/html/post.php
 * 
 *  - This is catch-all file. For a specific type of loop post, you can create 
 *    a file for that. 
 * 
 *    For example, for grid-post: child-theme/blocks/loop-posts/html/grid-post.php
 * 
 * CLASS: blocks/loop-posts/base.php
 * 
 * @see Bunyad\Blocks\LoopPosts\BasePost::get_default_props()
 * @see Bunyad\Blocks\LoopPosts\BasePost::render()
 * 
 * @var Bunyad\Blocks\LoopPosts\BasePost $post_obj
 * @var Bunyad\Blocks\Base\LoopBlock     $block
 */

$props = $post_obj->get_props();

// Article attributes
$wrap_atts = [
	'class' => array_merge(
		['l-post'],
		(array) $props['class_wrap']
	),
];

?>

<article <?php Bunyad::markup()->attribs('loop-' . $post_obj->id, $wrap_atts); ?>>

	<?php if ($props['media_location'] === 'below'): ?>
		<div class="content-above">
			<?php
				$meta = Bunyad::blocks()->load('PostMeta', $props['meta_props']);
				$meta->render();
			?>
		</div>
	<?php endif; ?>

	<?php if ($props['show_media']): ?>
		<div class="media">

		<?php if ($post_obj->should_embed_media()): ?>
		
			<?php $post_obj->embed_media(); ?>

		<?php elseif (has_post_thumbnail()): ?>

			<?php 
				Bunyad::media()->the_image(
					$props['image'],
					$props['image_props']
				);
			?>
			
			<?php $post_obj->the_post_format_icon(); ?>

			<?php $post_obj->the_review_overlay(); ?>

			<?php if ($props['show_cat_label']): ?>
				
				<?php 
					echo Bunyad::blocks()->cat_label([
						'position' => $props['cat_labels_pos'],
					]); 
				?>
			
			<?php endif; ?>

		<?php endif; ?>

		</div>
	<?php endif; ?>

<?php if ($props['show_content']) : ?>

	<?php if ($props['content_wrap']): ?>
		<div class="content-wrap">
	<?php endif; ?>

		<div class="content">

			<?php 
			if ($props['media_location'] !== 'below'): 
				$meta = Bunyad::blocks()->load('PostMeta', $props['meta_props']);
				$meta->render();
			endif; 
			?>
			
			<?php if ($props['show_excerpt']): ?>
			
				<div class="<?php echo esc_attr($props['excerpt_class']); ?>">
					<?php 
						echo Bunyad::posts()->excerpt(
							null, 
							$props['excerpt_length'], 
							['add_more'  => false]
						); 
					?>
				</div>
			
			<?php endif; ?>

			<?php if ($props['read_more']): ?>

				<a href="<?php the_permalink(); ?>" class="<?php echo esc_attr($props['read_more_class']); ?>">
					<?php echo esc_html(Bunyad::posts()->more_text); ?>
				</a>

			<?php endif; ?>

		</div>

	<?php if ($props['content_wrap']): ?>
		</div>
	<?php endif; ?>
<?php endif; // Show content check. ?>

</article>