<?php
/**
 * Bold Footer.
 */
?>
	<?php if (Bunyad::options()->footer_upper && is_active_sidebar('main-footer')): ?>
		<div class="upper-footer bold-footer-upper">
			<div class="ts-contain wrap">
				<div class="widgets row cf">
					<?php dynamic_sidebar('main-footer'); ?>
				</div>
			</div>
		</div>
	<?php endif; ?>
	
	
	<?php if (Bunyad::options()->footer_lower): ?>
		<div class="lower-footer bold-footer-lower">
			<div class="ts-contain inner">

				<?php if (Bunyad::options()->footer_logo): ?>
					<div class="footer-logo">
						<img <?php
							/**
							 * Get escaped attributes and add optionally add srcset for retina.
							 */ 
							Bunyad::markup()->attribs('footer-logo', 
								Bunyad::theme()->get_logo_data(Bunyad::options()->footer_logo)
								+ [
									'class'  => 'logo',
									'alt'    => get_bloginfo('name', 'display'),
									'srcset' => [
										Bunyad::options()->footer_logo    => '', 
										Bunyad::options()->footer_logo_2x => '2x'
									]
								]
							); ?> />
					</div>
						
				<?php endif;?>


				<?php

				// Social Icons.
				if (Bunyad::options()->footer_social) {
					Bunyad::blocks()->load('SocialIcons', [
						'style'    => 'b',
						'services' => Bunyad::options()->footer_social,
					])->render();
				}

				?>

				<?php if (has_nav_menu('smartmag-footer-links')): ?>
							
						<div class="links">
							<?php 
								wp_nav_menu([
									'theme_location' => 'smartmag-footer-links', 
									'fallback_cb'    => '', 
									'walker'         => (class_exists('Bunyad_Menus') ? 'Bunyad_MenuWalker' : '')
								]); 
							?>
						</div>
						
				<?php endif; ?>

				<div class="copyright">
					<?php Bunyad::theme()->the_copyright(); ?>
				</div>
			</div>
		</div>		
	<?php endif; ?>