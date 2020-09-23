<?php
/**
 * Slider Style 2
 *
 * @package videoly
 * @since 1.0
 */
?>

<?php
  wp_enqueue_script('swiper');
  wp_enqueue_style('swiper');

  $posts_per_page = videoly_get_opt('slider-posts-per-page');

  $args = array(
    'orderby'        => 'ID',
    'posts_per_page' => 4,
    'meta_query'     => array(array('key' => '_thumbnail_id')),
  );

  $categories = videoly_get_opt('slider-category');
  if (is_array($categories)) {
    $args['category__in'] =  $categories;
  }

  $the_query = new WP_Query($args);

?>


<div class="slider-style5">
  <!-- <div class="row row-14"> -->


    <!-- <div class="col-lg-8"> -->

      <?php 
        $i = 0; 
        while ($the_query -> have_posts()) : $the_query -> the_post();
        $image_src = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full');
        $image_src = (!empty($image_src) && is_array($image_src)) ? $image_src[0]:''; 
      ?>
      <div class="slider-style5-frame">
        <div class="tt-mslide type-2 long style-2" style="background-image:url(<?php echo esc_url($image_src); ?>);">
          <a class="tt-mslide-link" href="<?php echo esc_url(get_the_permalink()); ?>"></a>
          <div class="tt-mslide-table">
            <div class="tt-mslide-cell">
              <div class="tt-mslide-block">
                <div class="tt-mslide-cat">
                  <?php 
                    $category = get_the_category(); 
                    if(is_array($category) && !empty($category)):
                      foreach($category as $cat): ?>
                        <a class="c-btn type-3 color-2" href="<?php echo esc_url(get_category_link($cat->term_id)); ?>"><?php echo esc_html($cat->cat_name); ?></a>
                     <?php 
                      endforeach;
                    endif;
                  ?>
                </div>            
                <h2 class="tt-mslide-title c-h3"><a href="<?php echo esc_url(get_the_permalink()); ?>"><?php the_title(); ?></a></h2>
                <div class="tt-mslide-label">
                  <span><a href="#"><?php echo get_the_author(); ?></a></span>
                  <span><?php echo videoly_time_ago(); ?></span>
                </div>

              </div>
            </div>          
          </div>
        </div>
      </div>

      <?php $i++; endwhile; wp_reset_postdata(); ?>
      <div class="empty-space marg-md-b10"></div>                      
  <!-- </div> -->
</div>

