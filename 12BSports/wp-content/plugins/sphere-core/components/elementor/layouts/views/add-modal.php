<?php
/**
 * Partial: Popup modal for add new layout.
 */
?>
<div id="spc-el-add-layout-modal" class="spc-el-add-layout-content hidden" data-title="<?php esc_attr_e('Add Custom Layout', 'sphere-core'); ?>">

	<form method="post" action="<?php echo esc_url($submit_url); ?>">
		<?php wp_nonce_field('spc-el-layout-add'); ?>

		<div class="spc-modal-form-field">
			<label for="template_type">
				<?php esc_html_e('Select Type of Template', 'sphere-core'); ?>
			</label>
			
			<select name="template_type">
				<?php foreach ($types as $id => $type): ?>
					<option value="<?php echo esc_attr($id); ?>"><?php echo esc_attr($type['label']); ?></option>
				<?php endforeach; ?>
			</select>
		</div>

		<div class="spc-modal-form-field">
			<label for="template_name">
				<?php esc_html_e('Layout Template Name', 'sphere-core'); ?>
			</label>
			<input type="text" name="template_name" placeholder="<?php esc_attr_e('A descriptive name', 'sphere-core'); ?>" />
		</div>

		<div class="form-buttons">
			<input type="submit" class="button button-primary button-hero" value="<?php esc_attr_e('Create Template', 'sphere-core'); ?>" />
		</div>
	</form>

</div>