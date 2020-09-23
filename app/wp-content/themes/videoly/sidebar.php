<?php
/**
 * The sidebar containing the main widget area.
 *
 * @package videoly
 */
$layout    = (is_archive() || is_search()) ? 'right_sidebar':videoly_get_opt('main-layout');
switch ($layout):
  case 'left_sidebar': ?>
    <!-- Sidebar -->
    <div class="col-md-4">
      <div class="sidebar left-sidebar">
      <div class="empty-space marg-sm-b60"></div>
        <?php if (is_active_sidebar( videoly_get_custom_sidebar('main') )): ?>
          <?php dynamic_sidebar( videoly_get_custom_sidebar('main') ); ?>
        <?php endif; ?>
      </div>
    </div>
    <!-- End Sidebar -->
    <?php break;

  case 'right_sidebar': ?>
    <!-- Sidebar -->
    <div class="col-md-4">
      <div class="sidebar pleft75">
        <div class="empty-space marg-sm-b60"></div>
        <?php if (is_active_sidebar( videoly_get_custom_sidebar('main') )): ?>
          <?php dynamic_sidebar( videoly_get_custom_sidebar('main') ); ?>
        <?php endif; ?>
      </div>
    </div>
    <!-- End Sidebar -->
    <?php break;
endswitch;
?>
