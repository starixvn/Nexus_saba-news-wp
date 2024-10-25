<?php
/**
 * Advanced tabber widget.
 */
class SmartMag_Widgets_Tabber extends WP_Widget
{

	public $count = 1;
	
	public function __construct()
	{
		parent::__construct(
			'bunyad-tabber-widget',
			'SmartMag Legacy: Tabber Advanced',
			array('description' => esc_html__('Advanced tabber that supports all widgets.', 'bunyad-admin'), 'classname' => 'tabbed')
		);
	}

	public function widget($args, $instance) 
	{
		add_filter('dynamic_sidebar_params', array($this, 'widget_sidebar_params'));
		extract($args, EXTR_SKIP);

		echo $before_widget;
		
		if ($args['id'] != $instance['sidebar']) {
			ob_start();
			
			dynamic_sidebar($instance['sidebar']);
			$tabs = ob_get_clean();

			
			echo '<ul class="tabs-list">';

			// Get the titles.
			preg_match_all('#<div class="bunyad-tab-title">(.+?)</div>#', $tabs, $titles);
			
			$count = 1;
			foreach ((array) $titles[1] as $key => $title) {
				$tabs = str_replace($titles[0][$key], '', $tabs);
				
				echo '<li class="'. ($count == 1 ? 'active' : '') .'"><a href="#" data-tab="'. $count++ .'">'. esc_html($title) .'</a></li>';
			}
			
			echo '</ul>';
			echo '<div class="tabs-data">' . $tabs . '</div>';
			
		}
		
        echo $after_widget;
        
        // Remove temporary filter
        remove_filter('dynamic_sidebar_params', array($this, 'widget_sidebar_params'));
		
	}

	public function update($new, $old) 
	{
		$new['sidebar'] = strip_tags($new['sidebar']);		
		return $new;
	}

	public function form($instance) 
	{
		global $wp_registered_sidebars;
		
		$instance = wp_parse_args((array) $instance, array('sidebar' => ''));
?>

		<?php if (!is_plugin_active('custom-sidebars/customsidebars.php')): ?>
			<p>
				<strong>WARNING:</strong> Plugin "Custom Sidebars" is not installed. 
				The plugin is recommended to create a custom sidebar for this widget.
			</p>
		<?php endif; ?>
		
		<p>To use this widget, first <strong>create a new sidebar</strong>. Then add widgets to the new sidebar. Finally select the newly created sidebar below.</p>

		<p><label><?php esc_html_e('Select the sidebar:', 'bunyad-widgets'); ?></label>

			<select class="widefat" name="<?php echo $this->get_field_name('sidebar'); ?>">
			<?php
			foreach ($wp_registered_sidebars as $id => $sidebar) {
				if ($id !== 'wp_inactive_widgets' && !strstr($sidebar['class'], 'inactive')) {
					$selected = $instance['sidebar'] == $id ? ' selected="selected"' : '';
					echo sprintf('<option value="%s"%s>%s</option>', $id, $selected, $sidebar['name']);
				}
			}

			?>
			</select>
		</p>		
	<?php

	}
	
	public function widget_sidebar_params($params) 
	{
		
		// We're not using <ul as parent so li should be converted.
		$current_before = str_replace('<li', '<div', $params[0]['before_widget']);
		
		// Column classes should be removed.
		$current_before = preg_replace('/col-[0-9]+/', '', $current_before);

		// Close tags properly.
		$current_after  = str_replace('</li>', '</div>', $params[0]['after_widget']);

		$params[0]['before_widget'] = '<div class="tab-posts tab-widget-wrap" id="recent-tab-'. $this->count++ .'">' . $current_before;
		$params[0]['after_widget'] = $current_after . '</div>';
		$params[0]['before_title'] = '<div class="bunyad-tab-title">';
		$params[0]['after_title'] = '</div>';

		return $params;
	}
}

/*
add_action('init', 'bunyad_widget_tabber_init');

if (!function_exists('bunyad_widget_tabber_init')) {
	function bunyad_widget_tabber_init() {
		register_sidebar(array('name' => 'Bunyad Tabber Area 1', 'description' => esc_html__('Used for advanced tabber widget.', 'bunyad-admin')));
	} 
}*/
