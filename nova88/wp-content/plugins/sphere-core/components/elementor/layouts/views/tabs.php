<?php
/**
 * Partial: Tabs for Custom Layouts.
 */
?>
<div class="nav-tab-wrapper spc-el-tabs">
	<?php 
		foreach ($tabs as $tab => $label):

			$the_link = $tab_link;

			if ($tab !== 'all') {
				$the_link = add_query_arg([$query_arg => $tab], $tab_link);
			}
	?>

		<a href="<?php echo esc_url($the_link); ?>" class="nav-tab<?php echo ($tab === $active_tab ? ' nav-tab-active' : ''); ?>">
			<?php echo esc_html($label); ?>
		</a>
	<?php endforeach; ?>
</div>