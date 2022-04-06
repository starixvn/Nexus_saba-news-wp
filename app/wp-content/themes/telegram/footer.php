<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Telegram
 */

?>
</div> <!--  skip-link-to-content-->
	<footer id="colophon" class="site-footer twp-site-footer">
		<div class="twp-container">
			<?php
			if( is_active_sidebar('footer-col-one') || is_active_sidebar('footer-col-two') || is_active_sidebar('footer-col-three') ): ?>
				<div class="twp-footer-widget">
					<div class="twp-row">
						<?php 
							$footer_widget_layout = telegram_get_option( 'footer_widget_layout');
							switch ( $footer_widget_layout ) {
								case 'twp-footer-widget-style-1': ?>
		
									<?php if (is_active_sidebar('footer-col-one')): ?>
										<div class="twp-col twp-col-lg-3">
											<?php dynamic_sidebar('footer-col-one'); ?>
										</div>
									<?php endif; ?>
									<?php if (is_active_sidebar('footer-col-two') ) : ?>
										<div class="twp-col twp-col-lg-6">
											<?php dynamic_sidebar('footer-col-two'); ?>
										</div>
									<?php endif; ?>
									<?php if (is_active_sidebar('footer-col-three')) : ?>
										<div class="twp-col twp-col-lg-3">
											<?php dynamic_sidebar('footer-col-three'); ?>
										</div>
									<?php endif; ?>
		
								<?php break;
								case 'twp-footer-widget-style-2': ?>
		
									<?php if (is_active_sidebar('footer-col-one')): ?>
										<div class="twp-col twp-col-lg-6">
											<?php dynamic_sidebar('footer-col-one'); ?>
										</div>
									<?php endif; ?>
									<?php if (is_active_sidebar('footer-col-two') ) : ?>
										<div class="twp-col twp-col-lg-3">
											<?php dynamic_sidebar('footer-col-two'); ?>
										</div>
									<?php endif; ?>
									<?php if (is_active_sidebar('footer-col-three')) : ?>
										<div class="twp-col twp-col-lg-3">
											<?php dynamic_sidebar('footer-col-three'); ?>
										</div>
									<?php endif; ?>
		
								<?php break;
								case 'twp-footer-widget-style-3': ?>
								
									<?php if (is_active_sidebar('footer-col-one')): ?>
										<div class="twp-col twp-col-lg-3">
											<?php dynamic_sidebar('footer-col-one'); ?>
										</div>
									<?php endif; ?>
									<?php if (is_active_sidebar('footer-col-two') ) : ?>
										<div class="twp-col twp-col-lg-3">
											<?php dynamic_sidebar('footer-col-two'); ?>
										</div>
									<?php endif; ?>
									<?php if (is_active_sidebar('footer-col-three')) : ?>
										<div class="twp-col twp-col-lg-6">
											<?php dynamic_sidebar('footer-col-three'); ?>
										</div>
									<?php endif; ?>
		
								<?php break;
								default:
									break;
							}
						?>
					</div>
				</div>
			<?php endif; ?>
			<?php
				if (has_nav_menu('footer-nav')){
					$colClass = "twp-col twp-col-lg-6";
					$alignment = "twp-right";
				}else{
					$colClass = "twp-col";
					$alignment = " ";
				}
			?>
			<div class="twp-copyright-section">
				<div class="twp-row">
					<?php if (has_nav_menu('footer-nav')) { ?>
						<div class="<?php echo esc_attr($colClass); ?>" >
							<div class="footer-menu-section">
								<?php
									wp_nav_menu(
										array('theme_location' => 'footer-nav',
											'link_before' => '<span>',
											'link_after' => '</span>',
											'menu_id' => 'footer-menu',
											'fallback_cb' => false,
											'depth'           => 1,
											'menu_class' => 'twp-footer-menu'
									));
								?>
							</div>
						</div>
					<?php } ?>	
					<div class="<?php echo esc_attr($colClass); ?>">
						<div class="site-info <?php echo esc_attr($alignment); ?>">
							<?php
								$telegram_copyright_text = telegram_get_option('copyright_text');
								if (!empty ($telegram_copyright_text)) {
									echo wp_kses_post($telegram_copyright_text);
								}
							?>
							<span class="sep"> | </span>
								<?php printf(esc_html__('Theme: %1$s by %2$s', 'telegram'), '<strong>Telegram</strong>', '<a href="http://themeinwp.com/" target = "_blank" rel="designer"><strong>Themeinwp</strong></a>'); ?>
			
						</div><!-- .site-info -->
					</div>
				</div>
			</div>
		</div>
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
