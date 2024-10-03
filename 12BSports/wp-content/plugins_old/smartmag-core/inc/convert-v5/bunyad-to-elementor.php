<?php
namespace SmartMag\ConvertV5;
use \Bunyad;

/**
 * Convert Bunyad Page builder pages to Elementor.
 */
class BunyadToElementor
{
	protected $post;
	protected $elementor_data = [];
	protected $current_section;
	protected $parent_sections = [];
	protected $current_column;
	protected $parent_columns = [];

	/**
	 * @param WP_Post $post
	 */
	public function __construct($post)
	{
		$this->post = $post;
	}

	public function convert()
	{
		$data = get_post_meta($this->post->ID, 'panels_data', true);

		// Debug:
		// $data = 'a:3:{s:7:"widgets";a:13:{i:0;a:20:{s:12:"no_container";s:1:"1";s:5:"posts";s:0:"";s:7:"sort_by";s:0:"";s:10:"sort_order";s:4:"desc";s:7:"columns";s:1:"2";s:12:"heading_type";s:4:"auto";s:5:"cat_1";s:1:"0";s:5:"tag_1";s:0:"";s:9:"heading_1";s:0:"";s:8:"offset_1";s:1:"0";s:5:"cat_2";s:1:"0";s:5:"tag_2";s:0:"";s:9:"heading_2";s:0:"";s:8:"offset_2";s:1:"0";s:5:"cat_3";s:1:"0";s:5:"tag_3";s:0:"";s:9:"heading_3";s:0:"";s:8:"offset_3";s:1:"0";s:9:"post_type";s:0:"";s:4:"info";a:4:{s:5:"class";s:29:"Bunyad_PageBuilder_Highlights";s:2:"id";s:1:"1";s:4:"grid";s:1:"0";s:4:"cell";s:1:"0";}}i:1;a:13:{s:12:"no_container";s:1:"1";s:5:"posts";s:0:"";s:7:"sort_by";s:0:"";s:10:"sort_order";s:4:"desc";s:10:"highlights";s:1:"1";s:3:"cat";s:1:"0";s:7:"tax_tag";s:0:"";s:8:"sub_tags";s:0:"";s:7:"heading";s:0:"";s:12:"heading_type";s:5:"block";s:6:"offset";s:1:"0";s:9:"post_type";s:0:"";s:4:"info";a:4:{s:5:"class";s:28:"Bunyad_PageBuilder_NewsFocus";s:2:"id";s:1:"2";s:4:"grid";s:1:"1";s:4:"cell";s:1:"0";}}i:2;a:13:{s:12:"no_container";s:1:"1";s:5:"posts";s:0:"";s:7:"sort_by";s:0:"";s:10:"sort_order";s:4:"desc";s:10:"highlights";s:1:"1";s:3:"cat";s:1:"0";s:7:"tax_tag";s:0:"";s:8:"sub_tags";s:0:"";s:7:"heading";s:0:"";s:12:"heading_type";s:5:"block";s:6:"offset";s:1:"0";s:9:"post_type";s:0:"";s:4:"info";a:4:{s:5:"class";s:28:"Bunyad_PageBuilder_NewsFocus";s:2:"id";s:1:"3";s:4:"grid";s:1:"2";s:4:"cell";s:1:"0";}}i:3;a:3:{s:12:"no_container";s:1:"1";s:4:"type";s:4:"line";s:4:"info";a:4:{s:5:"class";s:24:"Bunyad_PbBasic_Separator";s:2:"id";s:1:"4";s:4:"grid";s:1:"3";s:4:"cell";s:1:"0";}}i:4;a:20:{s:12:"no_container";s:1:"1";s:5:"posts";s:0:"";s:7:"sort_by";s:0:"";s:10:"sort_order";s:4:"desc";s:7:"columns";s:1:"3";s:12:"heading_type";s:4:"auto";s:5:"cat_1";s:1:"0";s:5:"tag_1";s:0:"";s:9:"heading_1";s:0:"";s:8:"offset_1";s:1:"0";s:5:"cat_2";s:1:"0";s:5:"tag_2";s:0:"";s:9:"heading_2";s:0:"";s:8:"offset_2";s:1:"0";s:5:"cat_3";s:1:"0";s:5:"tag_3";s:0:"";s:9:"heading_3";s:0:"";s:8:"offset_3";s:1:"0";s:9:"post_type";s:0:"";s:4:"info";a:4:{s:5:"class";s:29:"Bunyad_PageBuilder_Highlights";s:2:"id";s:1:"5";s:4:"grid";s:1:"4";s:4:"cell";s:1:"0";}}i:5;a:8:{s:12:"no_container";s:1:"1";s:5:"title";s:13:"Recent Videos";s:6:"number";s:2:"10";s:6:"format";s:5:"video";s:3:"cat";s:1:"0";s:7:"tax_tag";s:0:"";s:9:"post_type";s:0:"";s:4:"info";a:4:{s:5:"class";s:32:"Bunyad_PageBuilder_LatestGallery";s:2:"id";s:1:"6";s:4:"grid";s:1:"5";s:4:"cell";s:1:"0";}}i:6;a:4:{s:5:"title";s:7:"Reviews";s:6:"number";i:5;s:5:"order";s:4:"date";s:4:"info";a:4:{s:5:"class";s:27:"Bunyad_LatestReviews_Widget";s:2:"id";s:1:"7";s:4:"grid";s:1:"6";s:4:"cell";s:1:"0";}}i:7;a:3:{s:5:"title";s:0:"";s:4:"code";s:105:"<img src="http://theme-sphere.com/smart-mag/wp-content/uploads/2014/01/ad-block.png" alt="Leaderboard" />";s:4:"info";a:4:{s:5:"class";s:17:"Bunyad_Ads_Widget";s:2:"id";s:1:"8";s:4:"grid";s:1:"6";s:4:"cell";s:1:"0";}}i:8;a:5:{s:5:"title";s:11:"Small Posts";s:8:"category";s:1:"0";s:9:"limit_tag";s:0:"";s:6:"number";s:1:"5";s:4:"info";a:4:{s:5:"class";s:25:"Bunyad_LatestPosts_Widget";s:2:"id";s:1:"9";s:4:"grid";s:1:"6";s:4:"cell";s:1:"0";}}i:9;a:5:{s:5:"title";s:13:"Flickr Photos";s:7:"user_id";s:12:"71865026@N00";s:8:"show_num";i:12;s:4:"tags";s:0:"";s:4:"info";a:4:{s:5:"class";s:20:"Bunyad_Flickr_Widget";s:2:"id";s:2:"10";s:4:"grid";s:1:"7";s:4:"cell";s:1:"0";}}i:10;a:4:{s:5:"title";s:7:"Testing";s:4:"text";s:12:"Random Text.";s:6:"filter";b:0;s:4:"info";a:4:{s:5:"class";s:14:"WP_Widget_Text";s:2:"id";s:2:"11";s:4:"grid";s:1:"7";s:4:"cell";s:1:"1";}}i:11;a:2:{s:4:"text";s:60:"<p><strong>Rich Text Stuff</strong></p><p>Something more</p>";s:4:"info";a:4:{s:5:"class";s:23:"Bunyad_PbBasic_RichText";s:2:"id";s:2:"12";s:4:"grid";s:1:"7";s:4:"cell";s:1:"2";}}i:12;a:17:{s:12:"no_container";s:1:"1";s:5:"posts";s:1:"4";s:7:"sort_by";s:0:"";s:10:"sort_order";s:4:"desc";s:4:"type";s:12:"tall-overlay";s:3:"cat";s:1:"0";s:7:"heading";s:0:"";s:12:"heading_type";s:4:"page";s:10:"cat_labels";s:1:"1";s:10:"pagination";s:1:"0";s:15:"pagination_type";s:0:"";s:4:"tags";s:0:"";s:7:"filters";s:0:"";s:13:"filters_terms";s:0:"";s:6:"offset";s:1:"0";s:9:"post_type";s:0:"";s:4:"info";a:4:{s:5:"class";s:23:"Bunyad_PageBuilder_Blog";s:2:"id";s:2:"13";s:4:"grid";s:1:"8";s:4:"cell";s:1:"0";}}}s:5:"grids";a:9:{i:0;a:2:{s:5:"cells";s:1:"1";s:5:"style";s:0:"";}i:1;a:2:{s:5:"cells";s:1:"1";s:5:"style";s:0:"";}i:2;a:2:{s:5:"cells";s:1:"1";s:5:"style";s:0:"";}i:3;a:2:{s:5:"cells";s:1:"1";s:5:"style";s:0:"";}i:4;a:2:{s:5:"cells";s:1:"1";s:5:"style";s:0:"";}i:5;a:2:{s:5:"cells";s:1:"1";s:5:"style";s:0:"";}i:6;a:2:{s:5:"cells";s:1:"1";s:5:"style";s:0:"";}i:7;a:2:{s:5:"cells";s:1:"3";s:5:"style";s:0:"";}i:8;a:2:{s:5:"cells";s:1:"1";s:5:"style";s:0:"";}}s:10:"grid_cells";a:11:{i:0;a:2:{s:6:"weight";s:1:"1";s:4:"grid";s:1:"0";}i:1;a:2:{s:6:"weight";s:1:"1";s:4:"grid";s:1:"1";}i:2;a:2:{s:6:"weight";s:1:"1";s:4:"grid";s:1:"2";}i:3;a:2:{s:6:"weight";s:1:"1";s:4:"grid";s:1:"3";}i:4;a:2:{s:6:"weight";s:1:"1";s:4:"grid";s:1:"4";}i:5;a:2:{s:6:"weight";s:1:"1";s:4:"grid";s:1:"5";}i:6;a:2:{s:6:"weight";s:1:"1";s:4:"grid";s:1:"6";}i:7;a:2:{s:6:"weight";s:18:"0.3333333333333333";s:4:"grid";s:1:"7";}i:8;a:2:{s:6:"weight";s:18:"0.3333333333333333";s:4:"grid";s:1:"7";}i:9;a:2:{s:6:"weight";s:18:"0.3333333333333333";s:4:"grid";s:1:"7";}i:10;a:2:{s:6:"weight";s:1:"1";s:4:"grid";s:1:"8";}}}';
		// $data = unserialize($data);
		
		if (!$data || empty($data['grids'])) {
			return;
		}

		$this->process_panels_data($data);

		// var_export($this->elementor_data);

		// Slashed as stripslashes happen on update post meta.
		$json = wp_slash(wp_json_encode($this->elementor_data));

		update_post_meta($this->post->ID, '_elementor_data', $json);
		update_post_meta($this->post->ID, '_elementor_version', '3.1.0');

		// To match \Elementor\DB::set_is_elementor_page().
		update_post_meta($this->post->ID, '_elementor_edit_mode', 'builder');

		// Update page template too or wp_update_post will reset it to default for missing templates.
		$page_template = get_page_template_slug($this->post->ID);
		if ($page_template === 'page-blocks.php') {
			$page_template = 'page-templates/blocks.php';
		}

		// To update last modified date/time and the template.
		wp_update_post([
			'ID' => $this->post->ID,
			'page_template' => $page_template
		]);

		// Delete elementor CSS that can sometimes become stuck on a version update.
		if (class_exists('\Elementor\Core\Files\CSS\Post')) {
			$css = new \Elementor\Core\Files\CSS\Post($this->post->ID);
			if (is_callable([$css, 'delete'])) {
				$css->delete();
			}
		}
	}

	/**
	 * From SO builder.
	 *
	 * @param array $panels_data
	 * @return void
	 */
	public function process_panels_data($panels_data)
	{
		// Create the skeleton of the grids
		$grids = [];
		foreach ($panels_data['grids'] as $gi => $grid) {
			$gi = intval($gi);
			$grids[$gi] = [];
			for ($i = 0; $i < $grid['cells']; $i++) {
				$grids[$gi][$i] = [];
			}
		}

		if (!empty($panels_data['widgets'])) {
			foreach ($panels_data['widgets'] as $widget) {
				$grids[ intval($widget['info']['grid']) ][ intval($widget['info']['cell']) ][] = $widget;
			}
		}

		$weights = [];
		foreach ($panels_data['grid_cells'] as $cell) {
			$weights[$cell['grid']][] = $cell['weight']; 
		}

		$this->start_section();
		$this->start_column();
		foreach ($grids as $gi => $cells) {

			// Start inner section only if more than 1 cell.
			$is_inner = count($cells) > 1;
			$is_inner && $this->start_section();

			foreach ($cells as $ci => $widgets) {
				if (empty($widgets)) {
					continue;
				}

				$weight = (float) $weights[$gi][$ci];

				if ($weight <= 1) {
					$weight *= 100;
				}

				$is_inner && $this->start_column($weight);

				foreach ($widgets as $pi => $widget_info) {
					$this->process_widget($widget_info);
				}

				$is_inner && $this->end_column();
			}

			$is_inner && $this->end_section();
		}
		$this->end_column();
		$this->end_section();
	}

	/**
	 * Process an SO widget and convert to elementor.
	 */
	protected function process_widget($widget)
	{
		$type = $widget['info']['class'];
		$data = $widget;
		
		unset(
			$data['no_container'],
			$data['info']
		);

		// Convert common known settings.
		$data = $this->convert_settings($data);

		$data['heading_colors'] = 'force';
		if (isset($data['heading_type'])) {
			$data['heading_type'] = $this->map_heading($data['heading_type']);
		}

		switch ($type) {
			case 'Bunyad_PageBuilder_NewsFocus':
				$this->process_news_focus($data);
				break;

			case 'Bunyad_PageBuilder_FocusGrid':
				$this->process_focus_grid($data);
				break;
			
			case 'Bunyad_PageBuilder_Highlights':
				$this->process_highlights($data);
				break;

			case 'Bunyad_PageBuilder_Highlights_B':
				$this->process_highlights_b($data);
				break;
			
			case 'Bunyad_PageBuilder_Blog':
				$this->process_blog($data);
				break;

			case 'Bunyad_Ads_Widget':
				$this->process_ads_widget($data);
				break;

			case 'WP_Widget_Text':
				$this->process_text_widget($data);
				break;

			case 'Bunyad_PbBasic_RichText':
				$this->process_rich_text_widget($data);
				break;

			case 'Bunyad_Flickr_Widget':
				$this->process_flickr_widget($data);
				break;

			case 'Bunyad_PbBasic_Separator':
				$this->process_separator($data);
				break;

			case 'Bunyad_PageBuilder_LatestGallery':
				$this->process_latest_gallery($data);
				break;

			case 'Bunyad_LatestReviews_Widget':
				$this->process_reviews_widget($data);
				break;

			case 'Bunyad_LatestPosts_Widget':
				$this->process_latest_posts_widget($data);
				break;

			default:
				// echo "Unknown: {$type}\n";
				break;
		}
	}

	/**
	 * Process highlights block - the multi-col one.
	 */
	public function process_highlights($settings)
	{
		$columns = (int) $settings['columns'];
		if (!$columns) {
			$columns = 2;
		}

		$layout = Bunyad::posts()->meta('layout_style', $this->post->ID);

		// Fix heading
		if (empty($settings['heading_type']) || $settings['heading_type'] === 'auto') {
			$settings['heading_type'] = $this->map_heading(
				$columns === 3 && $layout !== 'full' ? 'block' : 'none'
			);
		}

		// 3 Column settings.
		if ($columns === 3 && $layout !== 'full') {
			$settings['small_style'] = 'b';
			$settings['excerpts']    = 0;
			$settings['cat_labels']  = '0';
		}
		else {
			$settings['cat_labels']  = '1';
		}

		$this->start_section($columns === 3 ? 30 : 20);

		foreach (range(1, $columns) as $column) {

			$element = array_replace($settings, [
				'cat'     => !empty($settings["cat_{$column}"]) ? $settings["cat_{$column}"] : '',
				'tags'    => !empty($settings["tag_{$column}"]) ? $settings["tag_{$column}"] : '',
				'heading' => !empty($settings["heading_{$column}"]) ? $settings["heading_{$column}"] : '',
				'offset'  => !empty($settings["offset_{$column}"]) ? $settings["offset_{$column}"] : '',
			]);

			unset(
				$element['columns'],
				$element['cat_1'],$element['cat_2'], $element['cat_3'], 
				$element['tag_1'], $element['tag_2'], $element['tag_3'],
				$element['heading_1'], $element['heading_2'], $element['heading_3'],
				$element['offset_1'], $element['offset_2'], $element['offset_3']
			);

			$this->start_column(100 / $columns);

			$element = $this->convert_settings($element);
			$this->add_element([
				'widgetType' => 'smartmag-highlights',
				'settings'   => array_replace($element, [
					'container_width' => 66,
					'columns'         => 1,
					'separators'      => 1,
					'excerpt_length'  => Bunyad::options()->get_or('excerpt_length_highlights', 20)
				])
			]);

			$this->end_column();
		}

		$this->end_section();
	}

	public function process_news_focus($settings)
	{
		$this->add_element([
			'widgetType' => 'smartmag-newsfocus',
			'settings'   => array_replace($settings, [
				'container_width' => 66,
				'separators'      => 1,
				'excerpt_length'  => Bunyad::options()->get_or('excerpt_length_news_focus', 20),
				'cat_labels'      => '0',
				'filters'         => empty($settings['filters']) ? 'category' : $settings['filters'],
			])
		]);
	}

	public function process_focus_grid($settings)
	{
		$this->add_element([
			'widgetType' => 'smartmag-focusgrid',
			'settings'   => array_replace($settings, [
				'container_width'    => 66,
				'excerpt_length'     => Bunyad::options()->get_or('excerpt_length_focus_grid', 30),
				'cat_labels'         => '0',
				'media_ratio'        => 'custom',
				'media_ratio_custom' => '1.59',
				'filters'            => empty($settings['filters']) ? 'category' : $settings['filters'],
			])
		]);
	}

	public function process_highlights_b($settings)
	{
		$this->add_element([
			'widgetType' => 'smartmag-highlights',
			'settings'   => array_replace($settings, [
				'container_width' => 66,
				'columns'         => 2,
				'excerpt_length'  => Bunyad::options()->get_or('excerpt_length_highlights', 20),
				'cat_labels'      => '0',
			])
		]);
	}

	/**
	 * Convert old [blog] to relevant block.
	 */
	public function process_blog($settings)
	{

		$type = $settings['type'];
		unset($settings['type']);

		$settings = array_replace($settings, [
			'container_width' => 66,
			'columns'         => 2,
			'excerpt_length'  => Bunyad::options()->loop_grid_excerpt_length,

		]);

		$widget_type = '';
		if (!$type) {
			$type = Bunyad::options()->get('default_cat_template', 'category_loop');
		}

		switch ($type) {
			case 'modern':
			case 'modern-3':
				$widget_type = 'smartmag-grid';
				$columns     = abs((int) str_replace('modern', '', $type));
				$settings['columns'] = $columns ? $columns : 2;
				break;
			
			case 'grid-overlay':
			case 'grid-overlay-3':
				$widget_type = 'smartmag-overlay';
				$columns     = abs((int) str_replace('grid-overlay', '', $type));
				$settings['columns'] = $columns ? $columns : 2;
				break;

			case 'tall-overlay':

				$columns = 3;
				if (Bunyad::posts()->meta('layout_style', $this->post->ID) === 'full') {
					$columns = 4;
				}

				$widget_type = 'smartmag-overlay';
				$settings    = array_replace($settings, [
					'media_ratio' => '3-4',
					'columns'     => $columns,
					'meta_items_default' => false,
					'meta_above'  => [],
					'meta_below'  => ['date'],
					'css_column_gap' => [
						'unit' => 'px',
						'size' => 2,
					]
				]);
				break;

			case 'alt':
				$widget_type = 'smartmag-postslist';
				$settings    = array_replace($settings, [
					'excerpt_length'  => Bunyad::options()->loop_list_excerpt_length,
					'separators'      => 1,
					'columns'         => 1,
				]);
				break;

			case 'classic':
				$widget_type = 'smartmag-large';
				$settings    = array_replace($settings, [
					'excerpt_length'  => Bunyad::options()->loop_large_excerpt_length,
					'style'           => 'lg',
					'large_style'     => 'legacy',
					'columns'         => 1,
				]);
				break;

			case 'timeline':
				$widget_type = 'smartmag-postssmall';
				$settings    = array_replace($settings, [
					'columns' => 1,
					'style'   => 'b'
				]);
				break;
		}

		$this->add_element([
			'widgetType' => $widget_type,
			'settings'   => $settings
		]);
	}

	public function process_ads_widget($settings)
	{
		// Convert common known settings.
		$settings = $this->convert_settings($settings, [
			'title' => 'label'
		]);

		$this->add_element([
			'widgetType' => 'smartmag-codes',
			'settings'   => array_replace($settings, [
				'_margin' => [
					'unit' => 'px',
					'top'  => 0,
					'right' => 0,
					'bottom' => 42,
					'left'   => 0,
					'isLinked' => false
				]
			])
		]);
	}

	/**
	 * Convert old [latest_gallery] which had two version: 3 col carousel, or 1 col slider.
	 */
	public function process_latest_gallery($settings)
	{
		// 'title' has been mapped to 'heading' at this point.
		if (isset($settings['heading'])) {
			$settings['heading_type'] = $this->map_heading('block');
		}

		$type = empty($settings['type']) ? 'carousel' : 'slider';

		$this->add_element([
			'widgetType' => 'smartmag-grid',
			'settings'   => array_replace($settings, [
				'container_width'    => 66,
				'columns'            => $type === 'slider' ? 1 : 3,
				'cat_labels'         => '0',
				'style'              => 'sm',
				'show_content'       => $type === 'slider' ? false : true,
				'excerpts'           => false,
				'meta_items_default' => false,
				'carousel'           => true,
				'show_post_formats'  => false,
				'carousel_slides'    => $type === 'slider' ? 1 : 3,
				'carousel_dots'      => false,
				'meta_below'         => [],
				'meta_above'         => [],
				'media_ratio'        => $type === 'carousel' ? 'custom' : '',
				'media_ratio_custom' => $type === 'carousel' ? '1.52' : ''
			])
		]);
	}

	public function process_text_widget($settings)
	{
		$this->add_element([
			'widgetType' => 'wp-widget-text',
			'settings'   => [
				'wp' => [
					'title'  => !empty($settings['heading']) ? $settings['heading'] : '',
					'text'   => $settings['text'],
					'filter' => isset($settings['filter']) ? $settings['filter'] : '',
				],
				'_margin' => [
					'unit' => 'px',
					'top'  => 0,
					'right' => 0,
					'bottom' => 42,
					'left'   => 0,
					'isLinked' => false
				]
			]
		]);
	}

	public function process_rich_text_widget($settings)
	{
		// Never really supported headings.
		// if (!empty($settings['title'])) {
		// 	$this->add_element([
		// 		'widgetType' => 'smartmag-heading',
		// 		'settings' => [
		// 			'heading' => $settings['title'],
		// 			'type'    => 'b',
		// 		]
		// 	]);
		// }

		$this->add_element([
			'widgetType' => 'text-editor',
			'settings'   => [
				'editor' => $settings['text'],
				'_margin' => [
					'unit' => 'px',
					'top'  => 0,
					'right' => 0,
					'bottom' => 42,
					'left'   => 0,
					'isLinked' => false
				]
			]
		]);
	}

	public function process_flickr_widget($settings)
	{
		$this->add_element([
			'widgetType' => 'wp-widget-bunyad_flickr_widget',
			'settings'   => [
				// Add empty title if missing.
				'wp' => $settings + [
					'title' => ''
				],
				'_margin' => [
					'unit' => 'px',
					'top'  => 0,
					'right' => 0,
					'bottom' => 42,
					'left'   => 0,
					'isLinked' => false
				]
			]
		]);
	}

	public function process_reviews_widget($settings)
	{
		$this->add_element([
			'widgetType' => 'wp-widget-bunyad-latest-reviews-widget',
			'settings'   => [
				// Add defaults if missing.
				'wp' => $settings + [
					'title'  => '',
					'number' => 5,
					'order'  => ''
				],
				'_margin' => [
					'unit' => 'px',
					'top'  => 0,
					'right' => 0,
					'bottom' => 42,
					'left'   => 0,
					'isLinked' => false
				]
			]
		]);
	}

	public function process_latest_posts_widget($settings)
	{
		$this->add_element([
			'widgetType' => 'wp-widget-bunyad-latest-posts-widget',
			'settings'   => [
				// Add empty title if missing.
				'wp' => $settings + [
					'title' => ''
				],
				'_margin' => [
					'unit' => 'px',
					'top'  => 0,
					'right' => 0,
					'bottom' => 42,
					'left'   => 0,
					'isLinked' => false
				]
			]
		]);
	}

	public function process_separator($settings) 
	{
		$new_settings = [
			'color' => Bunyad::options()->predefined_style === 'classic' ? '#d9d9d9' : '#e8e8e8',
			'gap'   => ['unit' => 'px', 'size' => 0],
			'_padding' => [
				'unit'   => 'px',
				'top'    => 0,
				'right'  => 0,
				'bottom' => 42,
				'left'   => 0,
				'isLinked' => false,
			]
		];

		if ($settings['type'] === 'space') {
			// Elementor transparency.
			$new_settings['color'] = '#FFFFFF00';
		}

		$this->add_element([
			'widgetType' => 'divider',
			'settings'   => $new_settings
		]);
	}

	protected function convert_settings($settings, $map = [])
	{
		$map = array_replace([
			'tax_tag' => 'tags',
			'cats'    => 'terms',
			'number'  => 'posts',
			'format'  => 'post_formats',
			'title'   => 'heading'
		], $map);

		$new = [];
		foreach ($settings as $key => $value) {
			if (isset($map[$key])) {
				$new[ $map[$key] ] = $value;
				continue;
			}

			$new[$key] = $value;
		}

		// Empty pagination should be numbers ajax now.
		if (!empty($settings['pagination'])) {
			if (empty($settings['pagination_type'])) {
				$new['pagination_type'] = 'numbers-ajax';
			}
		}
		
		// Empty is the old default, but incompatible with new.
		if (empty($new['posts'])) {
			unset($new['posts']);
		}

		// Fix post_formats.
		if (isset($new['post_formats']) && $new['post_formats'] === 'all') {
			unset($new['post_formats']);
		}

		// Fix 'cat' field.
		if (isset($new['cat'])) {
			if (!$new['cat'] || $new['cat'] === 'all') {
				unset($new['cat']);
			}
		}

		/**
		 * Legacy filters conversion.
		 */
		if (!empty($settings['sub_cats'])) {
			$new += [
				'filters'       => 'category',
				'filters_terms' => $settings['sub_cats'],
			];

			unset($new['sub_cats']);
		}

		if (!empty($settings['sub_tags'])) {
			$new += [
				'filters'      => 'tag',
				'filters_tags' => $settings['sub_tags'],
			];

			unset($new['sub_tags']);
		}

		return $new;
	}

	protected function map_heading($old)
	{
		$default = Bunyad::options()->predefined_style === 'classic' ? 'a2' : 'a';
		$map = [
			'block'        => $default,
			'block-filter' => $default,
			'default'      => '',
			'block-alt'    => 'd',
			'page'         => 'i',
			'none'         => 'none',
		];

		return isset($map[$old]) ? $map[$old] : '';
	}

	protected function start_section($structure = 10)
	{
		$section = [
			'id'       => $this->generate_random_string(),
			'elType'   => 'section',
			'settings' => [
				'gutter'    => 'default',
				'gap'       => 'no',
				'structure' => (string) $structure
			],
			'isInner'  => false,
			'elements' => []
		];

		// Add a sub-section.
		if ($this->current_section) {
			if (!$this->current_column) {
				$this->start_column();
			}

			$this->parent_sections[] = $this->current_section['id'];
			$section['isInner']    =  true;

			// Add to current column.
			$index = count($this->current_column['elements']) + 1;
			$this->current_column['elements'][$index] = $section;
			$this->current_section = &$this->current_column['elements'][$index];
		}
		else {
			// Add to main data.
			$index = count($this->elementor_data) + 1;
			$this->elementor_data[$index] = $section;
			$this->current_section = &$this->elementor_data[$index];
		}
	}

	protected function &find_by_id($id, &$_data = null, $depth = 1) {

		if (!$_data) {
			$_data = &$this->elementor_data;
		}

		$found = null;
		foreach ($_data as $key => $data) {
			if ($data['id'] === $id) {
				$found = &$_data[$key];
			}

			if (!$found && $data['elements']) {
				$found = &$this->find_by_id($id, $_data[$key]['elements'], $depth+1);
				if ($found) {
					break;
				}
			}
		}

		if ($found) {
			return $found;
		}

		return false;
	}

	protected function end_section()
	{
		// Get parent after closing an inner section.
		if ($this->current_section['isInner']) {

			$parent = array_pop($this->parent_sections);
			$this->current_section = &$this->find_by_id(
				$parent
			);

		}
		else {
			$this->parent_sections = [];
			$this->parent_columns  = [];
			unset($this->current_section);
		}
	}

	protected function start_column($size = 100)
	{
		$new_column = [
			'id'       => $this->generate_random_string(),
			'elType'   => 'column',
			'settings' => [
				'_column_size'          => round($size),
				'space_between_widgets' => 0
			],
			'isInner'  => false,
			'elements' => []
		];

		if ($this->current_column) {
			$this->parent_columns[] = $this->current_column['id'];
			$new_column['isInner'] = true;
		}

		$index = count($this->current_section['elements']) + 1;
		$this->current_section['elements'][$index] = $new_column;
		$this->current_column = &$this->current_section['elements'][$index];
	}

	protected function end_column()
	{
		// Nested end_column() has probably been called recently. Fallback to parent column.
		if ($this->parent_columns) {
			$this->current_column = &$this->find_by_id(
				array_pop($this->parent_columns)
			);
		}
		else {
			unset($this->current_column);
		}
	}

	protected function add_element($element)
	{
		$this->current_column['elements'][] = 
			 array_replace([
				'id'     => $this->generate_random_string(),
				'elType' => 'widget'
			], $element);
	}

	protected function generate_random_string()
	{
		return dechex(rand());
	}
}