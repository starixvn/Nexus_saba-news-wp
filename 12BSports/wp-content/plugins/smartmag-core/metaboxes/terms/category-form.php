<?php
/**
 * @var array $options Provided by the Bunyad_Admin_Meta_Terms::edit_form() method.
 * @var string $context 'add' or 'edit' screen.
 */

// Don't pollute the add screen - these are mostly legacy options anyways.
if ($context !== 'edit') {
	return;
}

?>

<?php foreach ($options as $element): ?>

	<?php if ($context === 'edit'): ?>
		<tr class="form-field bunyad-meta bunyad-meta-term <?php echo esc_attr($element['name']); ?>">
			<th scope="row" valign="top">
				<label for="<?php echo esc_attr($element['name']); ?>">
					<?php echo esc_html($element['label']); ?>
				</label>
			</th>
			<td>
				<?php echo $this->render($element); // Bunyad_Admin_OptionRenderer::render(); ?>

				<?php if (!empty($element['desc'])): ?>
					<p class="description custom-meta">
						<?php echo wp_kses_post($element['desc']); ?>
					</p>
				<?php endif; ?>
			</td>
		</tr>
	<?php else: ?>
		<div class="form-field bunyad-meta bunyad-meta-term <?php echo esc_attr($element['name']); ?>">
			<label for="<?php echo esc_attr($element['name']); ?>">
				<?php echo esc_html($element['label']); ?>
			</label>

			<?php echo $this->render($element); // Bunyad_Admin_OptionRenderer::render(); ?>

			<?php if (!empty($element['desc'])): ?>
				<p class="description custom-meta">
					<?php echo esc_html($element['desc']); ?>
				</p>
			<?php endif; ?>
		</div>
	<?php endif; ?>

<?php endforeach; ?>

<script>
/**
 * Conditional show/hide 
 */
jQuery(function($) {
	$('._bunyad_slider select').on('change', function() {

		var depend_default = '._bunyad_slider_tags, ._bunyad_slider_type, ._bunyad_slider_posts, ._bunyad_slider_number';

		// hide all dependents
		$(depend_default).hide();
		
		if (!['none', ''].includes($(this).val())) {
			$(depend_default).show();
		}

		return;
	});

	const globalLayout = '<?php echo esc_attr(Bunyad::options()->category_loop); ?>';

	$('._bunyad_custom_template select').on('change', function() {
		const hide = '._bunyad_custom_template, ._bunyad_color, ._bunyad_main_color';
		const depends = '.bunyad-meta-term:not(' + hide + ')';

		let layout = $(this).val();

		if (!layout) {
			// If global layout is set to custom, we have a layout.
			layout = globalLayout === 'custom' ? globalLayout : 'none';
		}

		if (layout !== 'none') {
			$(depends).hide();
		}
		else {
			$(depends).show();
			$('._bunyad_slider select').trigger('change');
		}
	});

	// On load.
	$('._bunyad_slider select').trigger('change');
	$('._bunyad_custom_template select').trigger('change');
		
});
</script>