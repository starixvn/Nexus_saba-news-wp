<?php
/**
 * Title Wrapper
 *
 * @package videoly
 * @since 1.0
 */
?>
<?php
$sub_heading = videoly_get_opt('title-wrapper-subheading');
if(videoly_get_opt('title-wrapper-enable') && !is_single() || !class_exists('ReduxFramework') ): ?>
<div class="tt-heading title-wrapper">
  <div class="container">
    <h2 class="tt-heading-title"><?php echo videoly_get_the_title(); ?></h2>
    <?php if(!empty($sub_heading)): ?>
    <div class="simple-text size-6 color-4">
      <p><?php echo esc_html($sub_heading); ?></p>
    </div>
	<?php endif; ?>
  </div>
</div>
<?php endif; ?>
