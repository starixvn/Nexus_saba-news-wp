<?php 
/**
 * Sidebar template
 */

// sidebar HTML attributes
$attribs = array('class' => 'col-4 main-sidebar has-sep');
$sticky_class = '';
if (Bunyad::options()->sidebar_sticky) {
	$sticky_class = 'ts-sticky-native';

	if (Bunyad::options()->sidebar_sticky_type === 'smart') {
		$attribs['data-sticky'] = 1;
		$sticky_class = 'theiaStickySidebar';
	}
}

$sidebar = 'smartmag-primary';
if (Bunyad::registry()->sidebar) {
	$sidebar = Bunyad::registry()->sidebar;
}

do_action('bunyad_sidebar_start');

?>
		
	
	<aside <?php Bunyad::markup()->attribs('sidebar', $attribs); ?>>
	
	<?php 
	// Add sticky sidebar wrappers to support ancient ad networks still relying on document.write() 
	?>
		<div <?php Bunyad::markup()->attribs('sidebar-inner', [
				'class' => ['inner', $sticky_class]
			]); ?>>
		
			<?php dynamic_sidebar($sidebar); ?>
		</div>
	
	</aside>
	
<?php do_action('bunyad_sidebar_end'); ?>