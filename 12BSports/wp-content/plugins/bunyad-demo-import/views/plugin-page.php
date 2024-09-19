<?php
/**
 * Plugin page view.
 */
?>
<!-- The modal / dialog box, hidden somewhere near the footer -->
<div id="bunyad-missing-plugins" class="bunyad-depends-modal hidden" style="max-width:800px">

<p>The following required plugins should be installed and activated for this demo import.</p>
<ul class="plugin-names"></ul>

<form method="post" action="<?php echo esc_url(admin_url('admin.php?page=tgmpa-install-plugins')); ?>" target="_install_iframe">
	<input type="hidden" name="tgmpa-page" value="tgmpa-install-plugins" />
	<input type="hidden" name="plugin_status" value="all" />
	<!-- <input type="hidden" name="plugin[]" value="regenerate-thumbnails" /> -->
	<input type="hidden" name="action" value="tgmpa-bulk-install" />
	<?php wp_nonce_field('bulk-plugins'); ?>
	<div class="form-buttons ui-dialog-buttonpane">
		<input type="submit" class="button button-primary" value="<?php echo esc_attr__('Install/Activate Plugins'); ?>" />
		<!-- <a href="#" class="cancel button button-secondary">Cancel</a> -->
	</div>
</form>

<iframe name="_install_iframe" class="hidden"></iframe>
</div>


<div class="wrap bunyad-import">
<h1><?php echo esc_html_x('Import Theme Demos', 'Admin', 'pt-ocdi'); ?></h1>

<div class="notice notice-large intro-text">
	<h3>Using Importer:</h3>
	<p>We has several demos that can let you get quickly started with your setup. There are two type of imports available.</p>
	
	<ol>
		<li><p><strong>Settings Only</strong>: This will only import customizer settings but it will not import posts, menus, pages etc.</p></li>
		<li><p><strong>Full Content</strong>: Will import posts, menus, pages, images but it should only be used on fresh or test installs. It requires about 1-5 minutes to complete.</p></li>
	</ol>
	<p>
		<strong><?php echo esc_html_x('NOTE:', 'Admin', 'pt-ocdi'); ?></strong>
		DO NOT use "Full Content" option if your already have existing posts on your site. You cannot undo an import - create a backup if you really wish to use it on an existing site.
	</p> 

	<hr />
	<p>If it fails, you will have to request your webhost to increase your PHP <code>max_execution_time</code> (and other server timeouts) to at least 120 secs, and <code>memory_limit</code> to at least 196M, temporarily. If you tried it on a fresh WordPress install, you can go back to fresh install by using the "WordPress Reset" plugin. On localhost, a good internet connection is required.</p>

</div>

<div class="ajax-response"></div>

<div class="theme-browser">
<?php 

	// For is_plugin_active function.
	include_once  ABSPATH . 'wp-admin/includes/plugin.php';

	foreach ($this->import_files as $id => $demo): 
		$missing = [];

		// Required plugins check.
		if (!empty($demo['depends'])) {
			foreach ((array) $demo['depends'] as $slug => $name) {
				if (!is_plugin_active("{$slug}/{$slug}.php")) {
					$missing[$slug] = $name;
				}
			}
		}
		
		$missing = json_encode($missing);
?>

	<div class="theme">
		<a class="theme-screenshot" href="<?php echo esc_url($demo['demo_url']); ?>" target="_blank">
			<img src="<?php echo esc_url($demo['demo_image']); ?>" />
		</a>
		
		<div class="theme-id-container">
			<h3 class="theme-name"><?php echo esc_html($demo['demo_name']); ?></h3>
			<div class="theme-actions">
				<select name="import_type">
					<option value="settings"><?php echo esc_html_x('Settings Only', 'Admin', 'pt-ocdi'); ?></option>
					<option value="full"><?php echo esc_html_x('Full Content', 'Admin', 'pt-ocdi'); ?></option>
				</select>
				<a class="button import" data-id="<?php echo esc_attr($id); ?>" data-depends="<?php echo esc_attr($missing); ?>">Import</a>
			</div>
		</div>
	</div>

<?php endforeach; ?>
</div>
</div>