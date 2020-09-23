<?php
/**
 * Blog Default
 *
 * @package videoly
 * @since 1.0
 */
?>

<div class="container">
  <div class="empty-space marg-lg-b60 marg-sm-b40 marg-xs-b30"></div>
  
  <?php get_template_part('templates/global/page-before-content'); ?>

    <?php while ( have_posts() ) : the_post(); ?>
      <?php videoly_setPostViews(get_the_ID()); ?>
      <article <?php post_class(); ?>>
        <!-- TT-BLOG-CATEGORY -->
        <div class="tt-blog-category post-single text-center">
          <?php 
            $category = get_the_category(); 
            if(is_array($category) && !empty($category)):
              foreach($category as $cat): ?>
                <a class="c-btn type-3 color-3" href="<?php echo esc_url(get_category_link($cat->term_id)); ?>"><?php echo esc_html($cat->cat_name); ?></a>
             <?php 
              endforeach;
            endif;
          ?>
        </div>


        <div class="empty-space marg-lg-b10"></div>
        <h1 class="c-h1 text-center"><?php the_title(); ?></h1>
        <div class="empty-space marg-lg-b5"></div>

        <!-- TT-BLOG-USER -->
        <div class="text-center">
          <div class="tt-blog-user clearfix">

            <a class="tt-blog-user-img" href="#">
              <?php echo get_avatar( get_the_author_meta('ID'), 40 ); ?>
            </a>
            <div class="tt-blog-user-content">
              <span><a href="#"><?php echo get_the_author(); ?></a></span>
              <span><?php echo get_the_date('M d' ); ?></span>
            </div>
          </div>                            
        </div>

        <div class="empty-space marg-lg-b10"></div>

        <?php videoly_social_share('style1'); ?>

        <!-- TT-DEVIDER -->
        <div class="tt-devider"></div>
        <div class="empty-space marg-lg-b20"></div>

        <?php get_template_part('templates/blog/blog-single/parts/single', 'media'); ?>

        
        <div class="empty-space marg-lg-b40 marg-sm-b30"></div>

        <div class="simple-text size-4 title-droid margin-big">
          <?php the_content(); ?>
        </div>
        <?php
          wp_link_pages( array(
            'before'      => '<div class="page-links">' . esc_html__( 'Pages:', 'videoly' ),
            'after'       => '</div>',
            'link_before' => '<span>',
            'link_after'  => '</span>',
          ) );
        ?>
        <div class="empty-space marg-lg-b40 marg-sm-b30"></div>
        <?php the_tags( '<span class="tt-tag-title">'.esc_html__('Tags:', 'videoly').'</span><ul class="tt-tags"><li>', '</li><li>', '</li></ul>' ); ?>
      </article>
      
    <?php endwhile; ?>

    <div class="empty-space marg-lg-b50 marg-sm-b30"></div>


    <?php videoly_post_author_details(); ?>


    <?php videoly_post_navigation(); ?>


    <?php videoly_related_post(); ?>


    <div class="tt-devider"></div>
    <div class="empty-space marg-lg-b55 marg-sm-b50 marg-xs-b30"></div>

    <?php
      if ( comments_open() || get_comments_number() ) :
        comments_template();
      endif;
    ?>

  <?php get_template_part('templates/global/page-after-content'); ?>
  
  <div class="empty-space marg-lg-b80 marg-sm-b50 marg-xs-b30"></div>              
</div>
