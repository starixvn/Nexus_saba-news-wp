<?php
/**
 * Newsletter form.
 * 
 * @var \Bunyad\Blocks\Newsletter $block
 */
$props = $block->get_props();

?>
<div class="block-newsletter <?php echo esc_attr($props['scheme'] === 'dark' ? 's-dark' : ''); ?>">
	<div class="<?php echo esc_attr($props['classes']); ?>">

		<div class="bg-wrap"></div>

		<?php if ($props['image_type'] === 'full'): ?>
			<?php $block->the_image(); ?>
		<?php endif; ?>

		<div class="inner">

			<?php if ($props['icon']): ?>
				<div class="<?php echo esc_attr($props['icon']); ?>-icon">
					<i class="tsi tsi-envelope-o"></i>
				</div>
			<?php endif; ?>

			<?php if (!in_array($props['image_type'], ['none', 'full'])): ?>
				<?php $block->the_image(); ?>
			<?php endif; ?>

			<h3 class="heading">
				<?php echo esc_html($props['headline']); ?>
			</h3>

			<?php if ($props['message']): ?>
				<div class="base-text message">
					<?php echo wpautop(wp_kses_post($props['message'])); ?></div>
			<?php endif; ?>

			<?php if ($props['service'] === 'mailchimp'): ?>
				<form method="post" action="<?php echo esc_url($props['submit_url']); ?>" class="form fields-style fields-<?php echo esc_attr($props['fields_style']); ?>" target="_blank">
					<div class="main-fields">
						<p class="field-email">
							<input type="email" name="EMAIL" placeholder="<?php esc_attr_e('Your email address..', 'bunyad'); ?>" required />
						</p>
						
						<p class="field-submit">
							<input type="submit" value="<?php echo esc_attr($props['submit_text']); ?>" />
						</p>
					</div>

					<?php if ($props['disclaimer']): ?>
						<p class="disclaimer">
							<label>
								<?php if ($props['checkbox']): ?>
									<input type="checkbox" name="privacy" required />
								<?php endif; ?>

								<?php echo wp_kses_post($props['disclaimer']); ?>
							</label>
						</p>
					<?php endif; ?>
				</form>
			<?php endif; ?>

			<?php $block->the_custom_form(); ?>

		</div>
	</div>
</div>