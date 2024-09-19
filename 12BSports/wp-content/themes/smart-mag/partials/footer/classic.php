<?php
/**
 * Classic Footer.
 */
?>
	<?php if (Bunyad::options()->footer_upper): ?>
		<div class="upper-footer classic-footer-upper">
			<div class="ts-contain wrap">
		
			<?php if (is_active_sidebar('main-footer')): ?>
				<div class="widgets row cf">
					<?php dynamic_sidebar('main-footer'); ?>
				</div>
			<?php endif; ?>
		
			</div>
		</div>
	<?php endif; ?>
	
	
	<?php if (Bunyad::options()->footer_lower): ?>
		<div class="lower-footer classic-footer-lower">
			<div class="ts-contain wrap">
				<div class="inner">

					<div class="copyright">
						<?php Bunyad::theme()->the_copyright(); ?>
					</div>
					
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
				</div>
			</div>
		</div>		
	<?php endif; ?>