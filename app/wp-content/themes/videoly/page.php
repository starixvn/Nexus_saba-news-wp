<?php
/**
 * Page ( part of layout )
 *
 * @package videoly
 * @since 1.0
 */
get_header();
videoly_slider_template(videoly_get_opt('slider-template'));

?>

<div id="page-wrapper" class="content <?php echo videoly_get_post_opt('page-margin'); ?>">
  <div class="empty-space marg-lg-b55 marg-sm-b30"></div>
  <div class="container">
  	<div class="simple-tesxt">
    	<?php get_template_part('templates/content/content-page'); ?>
    </div>
  	<?php videoly_after_content_special_content(); ?>
  </div>
  <div class="empty-space marg-lg-b60 marg-sm-b50 marg-xs-b30"></div>
</div>

<?php
get_footer();
