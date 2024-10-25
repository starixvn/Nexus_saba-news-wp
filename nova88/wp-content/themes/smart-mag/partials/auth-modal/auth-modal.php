<?php
/**
 * Authentication modal.
 */

// Whether registrations are open.
$registrations = get_option('users_can_register');

// Social services.
$services = Bunyad::get('social')->get_services();

?>
<div id="auth-modal" class="ts-modal auth-modal" aria-hidden="true">
	<div class="ts-modal-overlay" tabindex="-1" data-micromodal-close>
		<div class="ts-modal-container" role="dialog" aria-modal="true" aria-labelledby="auth-modal-title">
			<header class="ts-modal-header">
				<h3 id="auth-modal-title" class="visuallyhidden">
					<?php echo esc_html_x('Sign In or Register', 'aria', 'bunyad'); ?>
				</h3>

				<button class="close-btn" aria-label="<?php esc_attr_e('Close modal', 'bunyad'); ?>" data-micromodal-close></button>
			</header>

			<div class="auth-modal-content auth-widget">
				<div class="auth-modal-login">
					<?php get_template_part('partials/auth-modal/login'); ?>
				</div>

				<?php if ($registrations): ?>
					<div class="auth-modal-register">
						<?php get_template_part('partials/auth-modal/register'); ?>
					</div>
				<?php endif; ?>
			</div>

		</div>
	</div>
</div>