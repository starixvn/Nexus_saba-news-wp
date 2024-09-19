<?php
/**
 * Meta box for post options.
 * 
 * @var Bunyad_Admin_MetaRenderer $this
 */

$dir = SmartMag_Core::instance()->path . 'metaboxes/options/';
include apply_filters('bunyad_metabox_options_dir', $dir) . 'post.php';

$options = $this->options(
	apply_filters('bunyad_metabox_post_options', $options)
);

// Legacy Fix: 'modern-b' no longer exists.
if (isset($this->default_values['_bunyad_layout_template'])
	&& $this->default_values['_bunyad_layout_template'] === 'modern-b'
) {
	$this->default_values['_bunyad_layout_template'] = 'modern';
	$this->default_values['_bunyad_layout_spacious'] = '0';
}

?>

<div class="bunyad-meta bunyad-meta-editor cf">
	<input type="hidden" name="bunyad_meta_box[]" value="<?php echo esc_attr($box_id); ?>">

<?php foreach ($options as $element): ?> 
	
	<div class="option <?php echo esc_attr($element['name']); ?>">
		<span class="label"><?php echo esc_html(isset($element['label_left']) ? $element['label_left'] : $element['label']); ?></span>
		<span class="field">

			<?php echo $this->render($element); // XSS ok. Bunyad_Admin_OptionRenderer::render() ?>
		
			<?php if (!empty($element['desc'])): ?>
			
			<p class="description"><?php echo esc_html($element['desc']); ?></p>
		
			<?php endif;?>
		
		</span>
	</div>
	
<?php endforeach; ?>

</div>

<script>
/**
 * Conditional show/hide 
 */

jQuery(function($) {

	/**
	 * Hide disable featured and featured video option on cover layout.
	 */
	var _global = '<?php echo esc_js(Bunyad::options()->post_layout_template); ?>';

	$('[name=_bunyad_layout_template]').on('change', function() {

		var current = $(this).val();
		if (!current) {
			current = _global;
		}

		// Subtitle isn't support for these.
		if (['cover', 'classic', 'classic-above'].indexOf(current) !== -1) {
			$('._bunyad_sub_title').hide();
		}
		else {
			$('._bunyad_sub_title').show();
		}

		// Cover doesn't support disabling featured area. And no video/audio supported.
		var coverUnsupported = '._bunyad_featured_disable, ._bunyad_featured_video';
		(current == 'cover' ? $(coverUnsupported).hide() : $(coverUnsupported).show());

		return;
	})
	.trigger('change');
		
});
</script>