<?php

class SmartMag_Widgets_TabbedRecent extends WP_Widget
{
	public function __construct()
	{
		parent::__construct(
			'bunyad-tabbed-recent-widget',
			'SmartMag - Recent Tabs',
			array('description' => esc_html__('Tabs: Recent, category1, category2...', 'bunyad-admin'), 'classname' => 'widget-tabbed')
		);
	
		// Enqueue assets.
		add_action('admin_enqueue_scripts', array($this, 'add_assets'));
		
	}
	
	public function add_assets($hook) 
	{
		// only in admin cp for form
		if ($hook == 'widgets.php') {
			wp_enqueue_script('smartmag-widget-tabs', SmartMag_Core::instance()->path_url . 'js/widget-tabs.js', ['jquery']);
		}
	}

	public function widget($args, $instance) 
	{
		global $post; // setup_postdata not enough
		
		// Set defaults
		$titles = $cats = $tax_tags = array();
		
		extract($args);
		extract($instance);
				
		// Missing data.
		if (!count($titles) || !count($cats)) {
			esc_html_e('Recent tabs widget still need to be configured! Add tabs, add a title, and select type for each tab in widgets area.', 'bunyad-widgets');
			return; 
		}
		
		$tabs = array();
		foreach ($titles as $key => $title) {
			
			// defaults missing?
			if (empty($tax_tags[$key])) {
				$tax_tags[$key] = '';
			}
			
			if (empty($cats[$key])) {
				$cats[$key] = '';
			}
			
			if (empty($posts[$key])) {
				$posts[$key] = 4;
			}
			
			$tabs[$title] = [
				'cat_type' => $cats[$key], 
				'tag'      => $tax_tags[$key], 
				'posts'    => $posts[$key]
			];
		}
				
		// Latest posts.
		$posts = $this->get_posts($tabs);
		
		// Do custom loop if available
		if (has_action('bunyad_widget_tabbed_recent_loop')):
		
			$args['tabs'] = $tabs;
			do_action('bunyad_widget_tabbed_recent_loop', $args, $posts);
			
		else:
		
		?>
	
			<?php echo $before_widget; ?>

			<div class="block-head block-head-g">	
				<ul class="tabs-list">
				
					<?php
					$count = 0; 
					foreach ($posts as $key => $val): $count++; $active = ($count == 1 ? 'active' : ''); 
					?>
					
					<li class="heading <?php echo $active;?>">
						<a href="#" data-tab="<?php echo esc_attr($count); ?>"><?php echo $key; ?></a>
					</li>
					
					<?php endforeach; ?>
				</ul>
			</div>
			
			<div class="tabs-data">
			<?php
				$i = 0; 
				foreach ($posts as $tab => $tab_posts): 
					$i++; 
					$active = ($i == 1 ? 'active' : ''); 
			?>
					
				<div class="tab-posts <?php echo $active; ?>" id="recent-tab-<?php echo esc_attr($i); ?>">

					<?php

					$new_args = [
						'query_type'     => 'custom',
						'query'          => $tab_posts,
						'posts'          => $tabs[$tab]['posts'],
						'pagination'     => false,
						'columns'        => 1,
						'heading_type'   => 'none',
						'space_below'    => 'none'
					];

					$block = new \Bunyad\Widgets\Loops\PostsSmall_Block;
					$block->widget($args, $new_args);

					?>
					
				</div>

			<?php endforeach; ?>
			</div>
			
			<?php echo $after_widget; ?>
		
		<?php
		
		endif;
		
		wp_reset_postdata();
		wp_reset_query();
	}
	
	public function get_posts($tabs)
	{
		// Get posts
		$args = ['ignore_sticky_posts' => 1];
		foreach ($tabs as $key => $val) {	
			
			$opts = array();
			$opts['posts_per_page'] = $val['posts'];
			
			switch ($val['cat_type']) {
				case 'popular':
					$opts['orderby'] = 'comment_count';
					break;
					
				case 'comments':
					$posts[$key] = get_comments([
						'number' => $val['posts'], 
						'status' => 'approve'
					]);
					continue 2; // jump switch and foreach loop
					
				case 'top-reviews':
					// Get top rated of all time.
					$opts = array_replace($opts, [
						'orderby'  => 'meta_value', 
						'meta_key' => '_bunyad_review_overall'
					]);

					break;
					
				case 'recent':
					break;
					
				case 'tag':
					$opts['tag'] = $val['tag'];
					break;
					
				default:
					$opts['cat'] = intval($val['cat_type']);
					break;
			}
						
			// Setup the query
			$posts[$key] = new WP_Query(
				apply_filters('bunyad_widget_tabbed_recent_query_args', array_replace($args, $opts))
			);
		}
		
		return $posts;
	}
	
	public function update($new, $old)
	{
		foreach (['cats', 'titles', 'tax_tags', 'posts'] as $var) {	
			foreach ($new[$var] as $key => $val) {
				$new[$var][$key] = trim(strip_tags($val));				
			}			
		}

		return $new;
	}
	
	public function form($instance)
	{
		$instance = array_merge(
			array(
				'titles' => [], 
				'cats'   => [0], 
				'posts'  => [],
				'cat'    => 0, 
				'tax_tags' => []
			), 
			$instance
		);
		
		extract($instance);
		
	?>
		
		<style>
			.widget-content p.separator { padding-top: 10px; border-top: 1px solid #d8d8d8; }
			.widget-content .tax_tag { display: none; }
		</style>
		
		
		<div id="tab-options">
			

		<script type="text/html" class="template-tab-options">
		<p class="title separator">
			<label><?php printf(esc_html__('Tab #%s Title:', 'bunyad-widgets'), '<span>%n%</span>'); ?></label>
			<input class="widefat" name="<?php 
				echo esc_attr($this->get_field_name('titles')); ?>[%n%]" type="text" value="%title%" />
		</p>
		
		
		<div class="cat">
			<label><?php printf(esc_html__('Tab #%s Category:', 'bunyad-widgets'), '<span>%n%</span>'); ?></label>
			<?php
			
			$r = array('orderby' => 'name', 'hierarchical' => 1, 'selected' => $cat, 'show_count' => 0);
			
			// categories list
			$cats_list = walk_category_dropdown_tree(get_terms('category', $r), 0, $r);
			
			// custom options
			$options = apply_filters('bunyad_widget_tabbed_recent_options', array(
				'recent' => esc_html__('Recent Posts', 'bunyad-widgets'), 
				'popular' => esc_html__('Popular Posts', 'bunyad-widgets'), 
				'top-reviews' => esc_html__('Top Reviews', 'bunyad-widgets'),
				// 'comments' => esc_html__('Recent Comments', 'bunyad-widgets'),
				'tag' => esc_html__('Use a Tag', 'bunyad-widgets'),
			));
			
			?>

			<select name="<?php echo $this->get_field_name('cats') .'[%n%]'; ?>">

			<?php foreach ($options as $key => $val): ?>
	
				<option value="<?php echo esc_attr($key); ?>"<?php echo ($cat == $key ? ' selected' : ''); ?>><?php echo esc_html($val); ?></option>			
	
			<?php endforeach; ?>

				<optgroup label="<?php esc_html_e('Category', 'bunyad-admin'); ?>">
					<?php echo $cats_list; ?>
				</optgroup>

			</select>

			<div class="tax_tag">
				<p><label><?php printf(esc_html__('Tab #%s Tag:', 'bunyad-widgets'), '<span>%n%</span>'); ?></label> <input type="text" name="<?php 
					echo esc_attr($this->get_field_name('tax_tags')); ?>[%n%]" value="%tax_tag%" /></p>
			</div>

			<p><?php esc_html_e('Posts:', 'bunyad'); ?> <input name="<?php echo $this->get_field_name('posts'); ?>[%n%]" type="text" value="%posts%" size="3" /></p>

			<p><a href="#" class="remove-recent-tab">[x] <?php esc_html_e('remove', 'bunyad-admin'); ?></a></p>
		</div>
		</script>
				
			
			<p class="separator"><a href="#" id="add-more-tabs"><?php esc_html_e('Add More Tabs', 'bunyad-admin'); ?></a></p>
			
			<?php

			if (is_integer($this->number)): // create for valid instances only 
			
				foreach ($cats as $n => $cat):
				
					if (!isset($tax_tags[$n])) {
						$tax_tags[$n] = '';
					}
					
					// set posts to default number
					if (!isset($posts[$n])) {
						$posts[$n] = 4;
					}
			?>
			
				<script>
					jQuery(function($) {
	
						$('.widget-liquid-right [id$="bunyad-tabbed-recent-widget-'+ <?php echo $this->number; ?> +'"] #add-more-tabs').trigger(
								'click', 
								[{
									'n': <?php echo ($n+1); ?>, 
									'title': '<?php echo esc_attr($titles[$n]); ?>', 
									'selected': '<?php echo esc_attr($cat); ?>',
									'tax_tag': '<?php echo esc_attr($tax_tags[$n]); ?>',
									'posts' : '<?php echo esc_attr($posts[$n]); ?>',
								}]);
					});
				</script>
			
			<?php
				endforeach; 
			endif; 
			?>
			
		</div>	
		
	<?php
	}
	
}