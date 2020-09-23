<?php
/**
 * Part of footer file ( default style )
 *
 * @package videoly
 * @since 1.0
 */
?>
<?php if(videoly_get_opt('footer-enable-switch')): ?>
<div class="tt-footer">
  <div class="container">
    <div class="row">
      <div class="col-sm-6 col-md-3">
        <?php if (is_active_sidebar( videoly_get_custom_sidebar('footer-1', 'footer-sidebar-1') )): ?>
          <?php dynamic_sidebar( videoly_get_custom_sidebar('footer-1', 'footer-sidebar-1') ); ?>
        <?php endif; ?>
        <div class="empty-space marg-xs-b30"></div>
      </div>

      <div class="col-sm-6 col-md-3">
        <?php if (is_active_sidebar( videoly_get_custom_sidebar('footer-2', 'footer-sidebar-2') )): ?>
          <?php dynamic_sidebar( videoly_get_custom_sidebar('footer-2', 'footer-sidebar-2') ); ?>
        <?php endif; ?>
      </div>

      <div class="clearfix visible-sm-block"></div>

      <div class="col-sm-6 col-md-3">
        <?php if (is_active_sidebar( videoly_get_custom_sidebar('footer-3', 'footer-sidebar-3') )): ?>
          <?php dynamic_sidebar( videoly_get_custom_sidebar('footer-3', 'footer-sidebar-3') ); ?>
        <?php endif; ?>
      <div class="empty-space marg-xs-b30"></div>
      </div>

      <div class="col-sm-6 col-md-3">
        <?php if (is_active_sidebar( videoly_get_custom_sidebar('footer-4', 'footer-sidebar-4') )): ?>
          <?php dynamic_sidebar( videoly_get_custom_sidebar('footer-4', 'footer-sidebar-4') ); ?>
        <?php endif; ?>            
      </div>
    </div>
    <div class="empty-space marg-lg-b60 marg-sm-b50 marg-xs-b30"></div>
  </div>
  <div class="tt-footer-copy">
    <div class="container">
      <?php echo wp_kses_data(videoly_get_opt('footer-copyright-text')); ?>
    </div>
  </div>
</div> 
<?php endif; ?>