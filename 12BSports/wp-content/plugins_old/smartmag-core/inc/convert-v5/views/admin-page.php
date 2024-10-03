<div class="ts-migrations wrap">
	<h1>Convert to SmartMag v5.0</h1>
	<p>SmartMag v5.0 is a major release and requires conversion of some old content to new format.</p>
	<hr />

	<?php if ($missing_plugins): ?>

		<div class="error">
			<h3>REQUIRED PLUGINS</strong></h3>
			<p>There are a few plugins required to be installed and updated before the conversion can begin. Please install/update the following plugins from SmartMag > Install Plugins:</p>
			<p><strong><?php echo esc_html(join(', ', $missing_plugins)); ?></strong>.</p>
		</div>

	<?php else: ?>
			
		<p><strong>The following have to be converted:</strong></p>

		<table class="ts-migration-table widefat striped" style="max-width: 600px">
			<tr class="item convert-builder">
				<td>Bunyad Page Builder Pages</td>
				<td class="progress pending">Pending...</td>
			</tr>	

			<tr class="item convert-profiles">
				<td>User Profile Fields</td>
				<td class="progress pending">Pending...</td>
			</tr>

			<tr class="item convert-terms-meta">
				<td>Categories Options</td>
				<td class="progress pending">Pending...</td>
			</tr>	

			<tr class="item convert-cleanup">
				<td>Cleanup</td>
				<td class="progress pending">Pending...</td>
			</tr>	
		</table>

		<p>
			<button class="smartmag-convert-begin button button-primary button-hero" 
				data-nonce="<?php echo wp_create_nonce('smartmag_convert_v5'); ?>">Begin conversion</button>
		</p>

		<div class="ts-migrations-done">
			<h2>&raquo; Just One More Step: Regenerate Thumbnails...</h2>
			<p>
				Image sizes have changed, so please go to <a href="<?php echo admin_url('tools.php?page=regenerate-thumbnails'); ?>">Tools > Regenerate Thumbnails</a> 
				and click Regenerate Thumbnails for all button.
			</p>
			<p>If you have thousands of images, you may instead click the button "Regenerate Thumbnails for the x Featured Images Only".</p>
		</div>

	<?php endif; ?>
	
</div>

<style>
	.ts-migrations .notice {
		display: none !important;
	}

	.ts-migration-table .progress {
		font-size: 12px;
		display: flex;
    	align-items: center;
    	justify-content: left;
	}

	.ts-migration-table .progress.pending {
		opacity: .7;
	}

	.ts-migration-table .error {
		color: red;
	}

	.ts-migration-table .success {
		color: green;
	}

	.ts-migrations-done {
		display: none;
		margin-top: 10px;
	}
</style>