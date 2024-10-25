<?php
/**
 * Auth Modal Partial: Social Login
 */

if (!Bunyad::authenticate()->has_social()) {
	return;
}

?>
	<div class="social-login">
		<div class="spc-social spc-social-colors spc-social-colored">
			<?php Bunyad::authenticate()->the_wsl_services(); ?>
		</div>

		<p class="social-label"><span><?php esc_html_e('or with email', 'bunyad'); ?></span></p>
	</div>