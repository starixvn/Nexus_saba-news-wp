<?php 
/*
YARPP Template: Simple
Author: YARPP Team
Description: A simple example YARPP template.
*/
?>
<?php if (have_posts()):?>
<h3></h3>
<ul>
	<?php while (have_posts()) : the_post(); ?>
	<?php $cmt_number = get_comments_number(get_the_ID()); ?>
	<li>
		<a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?> 
			<?php if ($cmt_number != "0"): ?>
				<span class="<?php echo $cmt_number != "0" ?"relate_comment_number":"" ?>"><?php echo $cmt_number ?></span> 
			<?php endif; ?>
		</a>
	</li>
	<?php endwhile; ?>
</ul>
<?php else: ?>
<p></p>
<?php endif; ?>
