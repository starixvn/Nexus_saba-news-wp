<?php
/**
 *
 * @package Archive
*/
get_header();

if (get_query_var('paged')) {
  $paged = get_query_var('paged');
} elseif (get_query_var('page')) {
  $paged = get_query_var('page');
} else {
  $paged = 1;
}

$post_per_page = videoly_get_post_opt('blog-posts-per-page');
if (!$post_per_page) {
  $post_per_page = get_option('posts_per_page');
}

$post_args = array(
  'posts_per_page' => $post_per_page,
  'orderby'        => 'date',
  'paged'          => $paged,
  'order'          => 'DESC',
  'post_type'      => 'post',
  'post_status'    => 'publish'
);

$categories = videoly_get_post_opt('blog-category');
if (is_array($categories)) {
  $post_args['category__in'] =  $categories;
}

$query = new WP_Query( $post_args );
if(is_page()) {
  $max_num_pages = $query -> max_num_pages;
} else {
  global $wp_query;
  $query = $wp_query;
  $max_num_pages = false;
}

?>

<div class="container">
  <div class="empty-space marg-lg-b60 marg-sm-b20 marg-xs-b15"></div>
  <?php get_template_part('templates/global/page-before-content'); ?>
    <?php if($query -> have_posts()): while ($query -> have_posts()) : $query -> the_post(); ?>
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
	  <div class="empty-space marg-xs-b0 marg-lg-b30"></div>
    <?php endwhile; wp_reset_postdata(); else:
      get_template_part('templates/content', 'none');
    endif; ?>

    <?php videoly_paging_nav($max_num_pages, 'default'); ?>    
  <?php get_template_part('templates/global/page-after-content'); ?> 
</div>
<?php
get_footer();
