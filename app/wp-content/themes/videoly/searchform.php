<?php
/**
 *
 * Search form.
 * @since 1.0.0
 * @version 1.0.0
 *
 */
?>

<div class="tt-s-search">
  <form action="<?php echo esc_url( home_url( '/' ) ); ?>" method="get" class="search" id="searchform">
    <input type="text" class="field" name="s" id="s" required="" placeholder="<?php echo esc_attr('Search articles', 'videoly'); ?>">
    <div class="tt-s-search-submit">
      <i class="fa fa-search" aria-hidden="true"></i>
      <input type="submit" name="submit" id="searchsubmit" value="">
    </div>
  </form>
</div>
