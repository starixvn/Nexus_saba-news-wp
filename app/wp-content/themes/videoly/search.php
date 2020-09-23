<?php
/**
 *
 * @package Search
*/
get_header();

?>

<div class="container">
  <div class="empty-space marg-lg-b60 marg-sm-b20 marg-xs-b15"></div>
  <?php get_template_part('templates/global/page-before-content'); ?>
    <?php if(have_posts()): while (have_posts()) : the_post(); ?>
    	<?php $post_thumbnail_class = (has_post_thumbnail()) ? 'has-thumbnail':'no-thumbnail'; ?>
	  <div <?php post_class('tt-post '.$post_thumbnail_class.' type-6 clearfix'); ?>>
	    <?php videoly_post_format('videoly-medium-ver', 'img-responsive'); ?>
	    <div class="tt-post-info">
	      <?php videoly_blog_category(); ?>
	      <?php videoly_blog_title('c-h5'); ?>
	      <?php videoly_blog_author_date(); ?>
	      <?php videoly_blog_excerpt(35); ?>
	      <?php videoly_blog_post_bottom(); ?>
	    </div>
	  </div>
	  <div class="empty-space marg-lg-b30"></div>
    <?php endwhile; 
    endif; ?>
  <?php get_template_part('templates/global/page-after-content'); ?> 
</div>
<div class="empty-space marg-lg-b55 marg-sm-b30 marg-xs-b20"></div>
<?php
get_footer();
