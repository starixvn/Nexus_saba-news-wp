<?php
/**
 * Slider Style 1
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
    'posts_per_page' => $posts_per_page,
    'meta_query'     => array(array('key' => '_thumbnail_id')),
  );

  $categories = videoly_get_opt('slider-category');
  if (is_array($categories)) {
    $args['category__in'] =  $categories;
  }

  $the_query = new WP_Query($args);

  // ----------------------- Editor Pick ----------------------- //
  $editor_args = array(
    'orderby'        => 'ID',
    'posts_per_page' => 4,
    'meta_query'     => array(array('key' => '_thumbnail_id')),
  );

  $editor_categories = videoly_get_opt('slider-editor-pick-category');
  if (is_array($editor_categories)) {
    $editor_args['category__in'] =  $editor_categories;
  }

  $editor_query = new WP_Query($editor_args);

?>

<div class="swiper-container" data-autoplay="0" data-loop="1" data-speed="500" data-center="0" data-slides-per-view="1" data-add-slides="1">
  <div class="swiper-wrapper clearfix">
    <?php 
      $i = 0; 
      while ($the_query -> have_posts()) : $the_query -> the_post();
      $image_src = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full');
      $image_src = (!empty($image_src) && is_array($image_src)) ? $image_src[0]:''; 
    ?>
    <div class="swiper-slide slider-style2 <?php echo ($i === 0) ? 'active':''; ?>" data-val="<?php echo esc_attr($i); ?>">
      <div class="tt-mslide" style="background-image:url(<?php echo esc_url($image_src); ?>);">
        <a class="tt-mslide-link" href="<?php echo esc_url(get_the_permalink()); ?>"></a>
        <div class="container">
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


              <div class="row hidden-xs">
                <div class="col-sm-4 col-sm-offset-8">
                  <div class="pleft30">
                    <div class="tt-title-block light">
                      <h3 class="tt-title-text"><?php esc_html_e('Editor’s Picks', 'videoly'); ?></h3>
                    </div>
                    <div class="empty-space marg-lg-b15"></div>     
                    <ul class="tt-post-list dark">
                      <?php while ($editor_query -> have_posts()) : $editor_query -> the_post(); ?>
                      <li>
                        <div <?php post_class('tt-post type-7 light clearfix'); ?>>
                        <?php videoly_post_format('videoly-small-alt', 'img-responsive'); ?>

                          <div class="tt-post-info">
                            <?php videoly_blog_title('c-h6'); ?>
                            <?php videoly_blog_category(); ?>
                          </div>
                        </div>               
                      </li>   
                      <?php endwhile; wp_reset_postdata(); ?>                                  
                    </ul>    
                  </div>                                           
                </div>
              </div>


            </div>          
          </div>
        </div>
      </div>
    </div>
    <?php $i++; endwhile; wp_reset_postdata(); ?>                                 
  </div>
  <div class="pagination c-pagination pos-2"></div>
  <div class="swiper-arrow-left c-arrow size-2 left hidden-xs hidden-sm"><i class="fa fa-chevron-left" aria-hidden="true"></i></div>
  <div class="swiper-arrow-right c-arrow size-2 right hidden-xs hidden-sm"><i class="fa fa-chevron-right" aria-hidden="true"></i></div>         
</div>