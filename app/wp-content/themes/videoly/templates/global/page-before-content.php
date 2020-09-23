<?php
/**
 * Before Loop ( page.php )
 *
 * @package videoly
 */
$layout    = (is_archive() || is_search()) ? 'right_sidebar':videoly_get_opt('main-layout');
$col_class = is_single() || is_home() ? 'col-md-8 col-md-offset-2':'col-md-12';
if ($layout == 'left_sidebar'): ?>
  <div class="row">
    <?php get_sidebar(); ?>
    <div class="col-md-8">

<?php elseif ($layout == 'right_sidebar'): ?>
  <div class="row">
    <div class="col-md-8">
<?php else: ?>
  <div class="row">
  	<div class="<?php echo videoly_sanitize_html_classes($col_class);?>">
<?php endif; ?>
