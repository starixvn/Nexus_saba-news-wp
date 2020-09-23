<?php
/**
 * Slider Style 3
 *
 * @package videoly
 * @since 1.0
 */
?>

<?php
  wp_enqueue_script('swiper');
  wp_enqueue_style('swiper');

  $posts_per_page = videoly_get_opt('slider-posts-per-page');
  $posts_per_page = (empty($posts_per_page) || $posts_per_page < 4) ? 6:$posts_per_page;
  
  $args = array(
    'orderby'        => 'ID',
    'posts_per_page' => $posts_per_page,
    'meta_query'     => array(array('key' => '_thumbnail_id')),
  );

  $categories = videoly_get_opt('slider-category');
  if (is_array($categories)) {
    $args['category__in'] =  $categories;
  }

  $the_query = new WP_Query($args);

?>


<!-- TT-SLIDER-WIDE -->
<div class="tt-slider-wide slider-style1 tt-custom-arrows">
  <div class="container">
    <div class="tt-slider-entry">
      <div class="tt-swiper-margin-10">
        <div class="swiper-container" data-autoplay="0" data-loop="1" data-speed="500" data-center="1" data-slides-per-view="responsive" data-xs-slides="3" data-sm-slides="3" data-md-slides="3" data-lg-slides="3" data-add-slides="3">
          <div class="swiper-wrapper clearfix">
            <?php 
              $i = 0; 
              while ($the_query -> have_posts()) : $the_query -> the_post();
              $image_src = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full');
              $image_src = (!empty($image_src) && is_array($image_src)) ? $image_src[0]:''; 
            ?>

            <div class="swiper-slide <?php echo ($i === 0) ? 'active':''; ?>" data-val="<?php echo esc_attr($i); ?>">
              <div class="tt-swiper-margin-10-entry">
                <div class="tt-mslide type-2" style="background-image:url(<?php echo esc_url($image_src); ?>);">
                  <a class="tt-slide-anchor" href="<?php echo esc_url(get_the_permalink()); ?>"></a>
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
                        <h1 class="tt-mslide-title c-h1"><a href="<?php echo esc_url(get_the_permalink()); ?>"><?php the_title(); ?></a></h1>
                        <div class="tt-mslide-label">
                          <span><a href="#"><?php echo get_the_author(); ?></a></span>
                          <span><?php echo videoly_time_ago(); ?></span>
                        </div>

                      </div>
                    </div>          
                  </div>
                </div>
              </div>  
            </div> 

            <?php $i++; endwhile; wp_reset_postdata(); ?>

          </div>
          <div class="pagination c-pagination color-2 pos-3 visible-xs-block"></div>
        </div>
      </div>                
    </div>                
  </div>
  <div class="custom-arrow-left c-arrow left size-2 pos-1 hidden-xs hidden-sm">
      <i class="fa fa-chevron-left" aria-hidden="true"></i>
  </div>
  <div class="custom-arrow-right c-arrow right size-2 pos-1 hidden-xs hidden-sm">
      <i class="fa fa-chevron-right" aria-hidden="true"></i>
  </div>              
</div>