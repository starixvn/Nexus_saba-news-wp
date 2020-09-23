<?php
/**
 * Single Meida File
 *
 * @package videoly
 * @since 1.0
 */
?>
<?php
  global $post;
  $post_format = get_post_format();
  $image_src   = wp_get_attachment_image_src( get_post_thumbnail_id(), 'videoly-big');
  $video_url   = videoly_get_post_opt('post-video-url');
  switch ($post_format) {
    case 'audio':
    
      break;
    case 'video': 
    if(!empty($video_url)):
    ?>
      <div class="tt-fluid">
        <div class="tt-fluid-inner">
          <iframe class="tt-fluid-inner-iframe tt-iframe" width="960" height="720" src="<?php echo esc_url($video_url); ?>" frameborder="0" allowfullscreen></iframe>
        </div>
      </div>
      <?php
      endif;
      break;
    case 'gallery':

      break;
    default: ?>
      <?php if(is_array($image_src) && !empty($image_src)): ?>
      <a class="tt-thumb" href="<?php echo esc_url($image_src[0]); ?>">
        <img class="img-responsive" src="<?php echo esc_url($image_src[0]); ?>"  alt="">
        <span class="tt-thumb-icon">
          <i class="fa fa-arrows-alt" aria-hidden="true"></i>
        </span>
      </a>
      <?php endif; ?>
    <?php
      break;
  }

