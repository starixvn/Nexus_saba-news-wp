<?php

namespace Bunyad\Blocks\Base;
use \Bunyad;

/**
 * Block options for page builders, widgets and so on.
 */
class LoopOptions extends Options 
{
	/**
	 * @var integer|array Number of supported columns.
	 */
	protected $supported_columns = 6;

	/**
	 * @var array Internal terms cache.
	 */
	private $terms_cache;

	// Max number of categories to be shown.
	public $limit_cats = 500;

	/**
	 * Setup options
	 * 
	 * @return $this
	 */
	public function init()
	{
		$this->get_common_data();
		$this->options = $this->get_shared_options();
		return $this;
	}

	/**
	 * Most common options usually shared by loop blocks
	 */
	public function get_shared_options()
	{
		$options = [];

		/**
		 * General Options
		 */
		$options['sec-general'] = [
			'posts' => [
				'label'     => esc_html__('Number of Posts', 'bunyad-admin'),
				'type'      => 'number',
				'condition' => ['query_type!' => 'main'],
			],

			'pagination' => [
				'label'        => esc_html__('Pagination', 'bunyad-admin'),
				'type'         => 'switcher',
				'default'      => 0,
				'return_value' => '1',
			],

			'pagination_type' => [
				'label'       => esc_html__('Pagination Type', 'bunyad-admin'),
				'type'        => 'select',
				'options'     => [
					'numbers-ajax' => esc_html__('Page Numbers AJAX', 'bunyad-admin'),
					'load-more'    => esc_html__('Load More Button', 'bunyad-admin'),
					'numbers'      => esc_html__('Page Numbers', 'bunyad-admin'),
					'infinite'     => esc_html__('Infinite Scroll (For Last Block Only)', 'bunyad-admin'),
				],
				'default'     => 'numbers-ajax',
				'condition'   => ['pagination' => '1'],
			],

			'pagination_links' => [
				'label'        => esc_html__('SEO: Pagination Links', 'bunyad-admin'),
				'desc'         => 'Might be desirable to disable load more pagination links for homepage blocks to prevent indexing.',
				'type'         => 'switcher',
				'default'      => '1',
				'return_value' => '1',
				'condition'   => ['pagination_type' => ['load-more', 'infinite']],
			],

			'scheme' => [
				'label'       => esc_html__('Color Scheme', 'bunyad-admin'),
				'type'        => 'select',
				'options'     => [
					''      => esc_html__('Default', 'bunyad-admin'),
					'dark'  => esc_html__('Contrast (For Dark Background)', 'bunyad-admin'),
				],
				'default'     => '',
			],

			'title_tag' => [
				'label'       => esc_html__('SEO: Titles Tag', 'bunyad-admin'),
				'type'        => 'select',
				'options'     => $this->common['heading_tags'],
			],
			
		];

		/**
		 * Layout options.
		 */

		$options['sec-layout'] = [];

		if ($this->supported_columns) {
			$options['sec-layout'] = [
				'columns'     => [
					'label'       => esc_html__('Columns', 'bunyad-admin'),
					'type'        => 'select',
					'devices'     => true,
					'description' => esc_html__('Note: Make sure the container column/section is large enough for best look.', 'bunyad-admin'),
					'options'     => [$this, '_option_columns'],
				],
			];
		}

		$options['sec-layout'] += [

			'space_below' => [
				'label'       => esc_html__('Add Space Below', 'bunyad-admin'),
				'type'        => 'select',
				'description' => esc_html__('Space to add below the block. Note: Columns in Elementor also add additional space below via "Widgets Space" setting.', 'bunyad-admin'),
				'options'     => [
					''      => esc_html__('Default', 'bunyad-admin'),
					'none'  => esc_html__('None', 'bunyad-admin'),
					'sm'    => esc_html__('Small', 'bunyad-admin'),
					'md'    => esc_html__('Medium', 'bunyad-admin'),
					// 'lg'    => esc_html__('Large', 'bunyad-admin'),
				],
				'default'     => '',
				// 'label_block' => true
			],

			'cat_labels' => [
				'label'        => esc_html__('Category Label Overlay', 'bunyad-admin'),
				'type'         => 'select',
				'options'      => [
					''  => esc_html__('Auto/Global', 'bunyad-admin'),
					'1' => esc_html__('Enabled', 'bunyad-admin'),
					'0' => esc_html__('Disabled', 'bunyad-admin'),
				],
				'default'      => '1',
			],

			'cat_labels_pos' => [
				'label'        => esc_html__('Overlay Position', 'bunyad-admin'),
				'type'        => 'select',
				'options'     => [
					'' => esc_html__('Auto/Global', 'bunyad-admin')
				] + $this->common['cat_labels_pos_options'],
				'default'     => '',
				'separator'   => 'after',
				'condition'    => ['cat_labels' => '1'],
			],

			'title_lines' => [
				'label'       => esc_html__('Limit Title to Lines', 'bunyad-admin'),
				'type'        => 'select',
				'options'     => function() {
					return [
						''  => esc_html__('Default / Global', 'bunyad-admin'),
						'0' => 'No Limit',
					] + array_combine(range(1, 5), range(1, 5));
				}
			],

			'excerpts' => [
				'label'        => esc_html__('Show Excerpts', 'bunyad-admin'),
				'type'         => 'switcher',
				'return_value' => '1',
				'default'      => '1',
			],

			'excerpt_lines' => [
				'label'        => esc_html__('Excerpt Limit Lines', 'bunyad-admin'),
				'type'        => 'select',
				'options'     => function() {
					return [
						''  => esc_html__('Default / Global', 'bunyad-admin'),
						'0' => 'No Limit',
					] + array_combine(range(1, 5), range(1, 5));
				},
				'condition'    => ['excerpts' => '1'],
			],

			'excerpt_length' => [
				'label'          => esc_html__('Excerpt Words', 'bunyad-admin'),
				'type'           => 'number',
				'condition'      => ['excerpts' => '1'],

				// We have to force excerpt length here as blocks default to empty.
				// Empty default is needed in block config to allow global prefill.
				'default'        => 15,
				'default_forced' => true,
			],

			'content_center'  => [
				'label'        => esc_html__('Content Centered', 'bunyad-admin'),
				'type'         => 'switcher',
				'return_value' => '1',
				'default'      => '0',
			],
			
			'show_media' => [
				'label'        => esc_html__('Show Media/Image', 'bunyad-admin'),
				'type'         => 'switcher',
				'return_value' => '1',
				'default'      => '1',
				'separator'   => 'before'
			],

			'media_ratio' => [
				'label'        => esc_html__('Image Ratio', 'bunyad-admin'),
				'type'         => 'select',
				'options'      => $this->common['ratio_options'],
				'default'      => '',
				'condition'    => ['show_media' => '1'],
			],

			// media_ratio_custom will be the main value. 
			// media_ratio_custom_mobile, media_ratio_custom_tablet etc. will be for devices.
			'media_ratio_custom' => [
				'label'        => esc_html__('Image Ratio', 'bunyad-admin'),
				'type'         => 'number',
				'input_attrs'  => ['min' => 0.25, 'max' => 4.5, 'step' => .1],
				'devices'      => true,
				'default'      => '',
				'selectors'    => [
					'{{WRAPPER}} .media-ratio' => 'padding-bottom: calc(100% / {{SIZE}});'
				],
				'condition'    => ['show_media' => '1', 'media_ratio' => 'custom'],
			],

			'read_more' => [
				'label'        => esc_html__('Read More', 'bunyad-admin'),
				'type'         => 'select',
				'options'      => [
					'' => esc_html__('Auto/Global', 'bunyad-admin'),
				] + $this->common['read_more_options'],
				'default'      => '',
			],

			'reviews' => [
				'label'        => esc_html__('Reviews', 'bunyad-admin'),
				'type'         => 'select',
				'options'      => [
					'' => esc_html__('Auto/Global', 'bunyad-admin')
				] + $this->common['reviews_options'],
				'default'      => '',
			],

			'show_post_formats' => [
				'label'        => esc_html__('Post Formats Icons', 'bunyad-admin'),
				'type'         => 'switcher',
				'return_value' => '1',
				'default'      => '1',
			],

			'post_formats_pos' => [
				'label'     => esc_html__('Formats Default Position', 'bunyad-admin'),
				'type'      => 'select',
				'options'   => $this->common['post_format_pos_options'],
				'condition' => ['show_post_formats' => '1']
			],

			// -- Post Meta
			'h-post-meta' => [
				'label'       => esc_html__('Post Meta', 'bunyad-admin'),
				'type'        => 'heading',
				'separator'   => 'before'
			],

			'meta_items_default' => [
				'label'        => esc_html__('Default/Global Meta Items', 'bunyad-admin'),
				'type'         => 'switcher',
				'return_value' => '1',
				'default'      => '1',
				'separator'    => 'before'
			],

			'meta_above' => [
				'label'        => esc_html__('Items Above', 'bunyad-admin'),
				'type'         => 'bunyad-selectize',
				'options'      => $this->common['meta_options'],
				'multiple'     => true,
				'label_block'  => true,
				'condition'    => ['meta_items_default!' => '1'],
			],

			'meta_below' => [
				'label'        => esc_html__('Items Below', 'bunyad-admin'),
				'type'         => 'bunyad-selectize',
				'options'      => $this->common['meta_options'],
				'multiple'     => true,
				'label_block'  => true,
				'condition'    => ['meta_items_default!' => '1'],
			],

			'meta_cat_style' => [
				'label'        => esc_html__('Category Style', 'bunyad-admin'),
				'type'         => 'select',
				'options'      => [
					'text'  => esc_html__('Plain Text', 'bunyad-admin'),
					'labels' => esc_html__('Label', 'bunyad-admin'),
				],
				'default'      => 'text',
			],

			'meta_author_img' => [
				'label'        => esc_html__('Author Image', 'bunyad-admin'),
				'type'         => 'switcher',
				'return_value' => '1',
				'default'      => 0,
				// 'separator'    => 'after'
			],

			'show_title' => [
				'label'        => esc_html__('Show Post Title', 'bunyad-admin'),
				'type'         => 'switcher',
				'return_value' => '1',
				'default'      => '1',
			],

			// --

			'h-advanced-1' => [
				'label'       => esc_html__('Advanced', 'bunyad-admin'),
				'type'        => 'heading',
				'separator'   => 'before'
			],

			'container_width' => [
				'label'       => esc_html__('Container Width', 'bunyad-admin'),
				'type'        => 'select',
				'description' => esc_html__('The block will select image sizing based on specified container width.', 'bunyad-admin'),
				'options'     => [
					'33' => esc_html__('Small: 1 Column or In Sidebar', 'bunyad-admin'),
					'50' => esc_html__('Medium: 50% of Width', 'bunyad-admin'),
					'66' => esc_html__('Large: 66% of Width', 'bunyad-admin'),
					'100' => esc_html__('Full Width', 'bunyad-admin'),
				],
				'label_block' => true,
				'separator'   => 'before'
			],

			'skip_lazy_number' => [
				'label'        => esc_html__('Skip Lazyload on Images', 'bunyad-admin'),
				'type'         => 'number',
				'description'  => esc_html__('Number of posts to skip lazyload on for LCP performance.', 'bunyad-admin'),
				// 'separator'    => 'after',
				// 'condition'    => ['show_media' => '1'], // Not supported for feat-grid
			],

			// 'force_image' => [
			// 	'label'       => esc_html__('Forced Image Size', 'bunyad-admin'),
			// 	'type'        => 'select',
			// 	'description' => esc_html__('Best kept default! Forces a specific physical image to use. Does not effect width or height.', 'bunyad-admin'),
			// 	'options'     => [$this, '_option_images'],
			// 	'label_block' => true,
			// 	// 'separator'   => 'before'
			// ],
		];

		/**
		 * Posts Source / Filters
		 */
		$options['sec-filter'] = [

			'query_type' => [
				'label'       => esc_html__('Query Type', 'bunyad-admin'),
				'type'        => 'select',
				'options'     => [
					'custom'  => esc_html__('Custom', 'bunyad-admin'),
					'section' => esc_html__('Section Query (Parent Section)', 'bunyad-admin'),
					'main-custom' => esc_html__('Main Query / Archive', 'bunyad-admin'),
					'main'        => esc_html__('Global Query (for Devs)', 'bunyad-admin'),
				],
				'default'        => 'custom',
				'default_forced' => true,
			],

			'_n_section_query' => [
				'type'      => 'html',
				'raw'       => esc_html__('Edit the nearest/direct parent section and configure under the Section Query.', 'bunyad-admin'),
				'condition' => ['query_type' => 'section'],
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			],

			'sort_by'  => [
				'label'       => esc_html__('Sort By', 'bunyad-admin'),
				'type'        => 'select',
				'options'     => [
					''              => esc_html__('Published Date', 'bunyad-admin'),
					'modified'      => esc_html__('Modified Date', 'bunyad-admin'),
					'random'        => esc_html__('Random', 'bunyad-admin'),
					'comments'      => esc_html__('Comments Count', 'bunyad-admin'),
					'alphabetical'  => esc_html__('Alphabetical', 'bunyad-admin'),
					'rating'        => esc_html__('Rating', 'bunyad-admin'),
				] + (
					// Not using global setting for now.
					class_exists('\Jetpack') && \Jetpack::is_module_active('stats')
						? ['jetpack_views' => esc_html__('JetPack Plugin Views Count', 'bunyad-admin')]
						: []
				) + (
					class_exists('\Sphere\PostViews\Plugin')
						? [
							'views'       => esc_html__('Post Views: All Time', 'bunyad-admin'),
							'views-7days' => esc_html__('Post Views: 30 Days', 'bunyad-admin'),
							'views-days'  => esc_html__('Post Views: Custom Days', 'bunyad-admin')
						]
						: []
				),
				'condition' => ['query_type' => ['custom', 'main-custom']],
			],

			'sort_days'  => [
				'label'       => esc_html__('Sort Days', 'bunyad-admin'),
				'description' => esc_html__('Number of days to use for the views sort. Max limit is 90 days for Jetpack.', 'bunyad-admin'),
				'type'        => 'number',
				'default'     => 30,
				'condition'   => [
					'sort_by'    => ['jetpack_views', 'views-days'],
					'query_type' => 'custom'
				]
			],

			'sort_order'  => [
				'label'       => esc_html__('Sort Order', 'bunyad-admin'),
				'type'        => 'select',
				'options'     => [
					'desc' => esc_html__('Descending / Latest First', 'bunyad-admin'),
					'asc'  => esc_html__('Ascending / Oldest First', 'bunyad-admin'),
				],
				'default'     => 'desc',
				'condition' => ['query_type' => ['custom', 'main-custom']],
			],

			'terms' => [
				'label'       => esc_html__('From Categories', 'bunyad-admin'),
				'type'        => 'select2',
				'options'     => [$this, '_option_categories'],
				'description' => 'Limit posts to selected categories.',
				'multiple'    => true,
				'label_block' => true,
				'separator'   => 'before',
				'condition'   => ['query_type' => 'custom'],
				'editor_callback' => function($option) {
					return $this->maybe_ajax_for_terms($option, 'categories');
				}
			],

			'cat'  => [
				'label'       => esc_html__('Main Category', 'bunyad-admin'),
				'type'        => 'select2',
				'description' => esc_html__('OPTIONAL: Will also be used for heading link, text and color if set.', 'bunyad-admin'),
				'options'     => function() {
					return [
						'' => esc_html__('None', 'bunyad-admin'),
						'__current' => esc_html__('Archive: Current Category', 'bunyad-admin')
					] + $this->_option_categories();
				},
				'condition' => ['query_type' => 'custom'],
				'editor_callback' => function($option) {
					return $this->maybe_ajax_for_terms($option, 'categories');
				}
			],

			'exclude_terms' => [
				'label'       => esc_html__('Exclude Categories', 'bunyad-admin'),
				'type'        => 'select2',
				'options'     => [$this, '_option_categories'],
				'description' => 'Prevents posts from these categories.',
				'multiple'    => true,
				'label_block' => true,
				// 'separator'   => 'before',
				'condition'   => ['query_type' => 'custom'],
				'editor_callback' => function($option) {
					return $this->maybe_ajax_for_terms($option, 'categories');
				}
			],

			'offset' => [
				'label'       => esc_html__('Skip Posts / Offset', 'bunyad-admin'),
				'description' => esc_html__('Skip first X posts.', 'bunyad-admin'),
				'type'        => 'number',
				'condition' => ['query_type' => ['custom', 'main-custom']],
			],
			
			'reviews_only' => [
				'label'       => esc_html__('Review Posts Only', 'bunyad-admin'),
				'description' => '',
				'type'        => 'switcher',
				'return_value' => '1',
				'default'      => '1',
				'condition' => ['query_type' => ['custom', 'main-custom']],
			],

			'tags' => [
				'label'       => esc_html__('From Tags', 'bunyad-admin'),
				'type'        => 'select2',
				// 'options'     => [$this, '_option_tags'],
				'options'     => [],
				'description' => esc_html__('Will limit posts to selected tags.', 'bunyad-admin'),
				'multiple'    => true,
				'label_block' => true,
				'separator'   => 'before',
				'condition'   => ['query_type' => ['custom', 'main-custom']],
				'editor_callback' => function($option) {
					return $this->maybe_ajax_for_terms($option, 'tags');
				}
			],

			// 'author' => [

			// ]

			'post_ids' => [
				'label'       => esc_html__('Specific Post IDs', 'bunyad-admin'),
				'type'        => 'text',
				'description' => esc_html__('Post IDs separated by commas, ex: 25, 34', 'bunyad-admin'),
				'multiple'    => true,
				'label_block' => true,
				'condition' => ['query_type' => 'custom'],
			],

			'post_formats' => [
				'label'       => esc_html__('Specific Post Formats', 'bunyad-admin'),
				'type'        => 'select2',
				'description' => esc_html__('Show posts of specific post formats only. Defaults to standard posts.', 'bunyad-admin'),
				'options'     => [
					'video'   => esc_html__('Video', 'bunyad-admin'),
					'audio'   => esc_html__('Audio', 'bunyad-admin'),
					'gallery' => esc_html__('Gallery', 'bunyad-admin'),
				],
				'multiple'    => true,
				'label_block' => true,
				'condition' => ['query_type' => ['custom', 'main-custom']],
			],

			'h-advanced' => [
				'label'       => esc_html__('Advanced', 'bunyad-admin'),
				'type'        => 'heading',
				'separator'   => 'before',
				'condition' => ['query_type' => 'custom'],
			],

			'exclude_tags' => [
				'label'       => esc_html__('Exclude Tags', 'bunyad-admin'),
				'type'        => 'select2',
				'options'     => [],
				'description' => esc_html__('Skip posts using these tags.', 'bunyad-admin'),
				'multiple'    => true,
				'label_block' => true,
				'separator'   => 'before',
				'condition'   => ['query_type' => ['custom', 'main-custom']],
				'editor_callback' => function($option) {
					return $this->maybe_ajax_for_terms($option, 'tags');
				}
			],

			'exclude_ids' => [
				'label'       => esc_html__('Exclude Post IDs', 'bunyad-admin'),
				'type'        => 'text',
				'description' => esc_html__('Post IDs separated by commas, ex: 25, 34', 'bunyad-admin'),
				'multiple'    => true,
				'label_block' => true,
				'condition' => ['query_type' => 'custom'],
			],

			'taxonomy' => [
				'label'       => esc_html__('Taxonomy', 'bunyad-admin'),
				'type'        => 'text',
				'description' => esc_html__('A custom taxonomy limit instead of category or post_tag. This will disable category and tags limits from above.', 'bunyad-admin'),
				'label_block' => true,
				'condition' => ['query_type' => 'custom'],
			],

			'tax_ids' => [
				'label'       => esc_html__('Taxonomy Terms IDs', 'bunyad-admin'),
				'type'        => 'text',
				'description' => esc_html__('IDs of the taxonomy items to use separated by comma, for example ids of categories under product_cat.', 'bunyad-admin'),
				'label_block' => true,
				'condition'   => [
					'taxonomy!' => '', 
					'query_type' => 'custom'
				]
			],

			'post_type' => [
				'label'       => esc_html__('Post Type', 'bunyad-admin'),
				'type'        => 'select',
				'options'     => function() { 

					return wp_list_pluck(
						get_post_types([
							'public' => true,
						], 'objects'),
						'label'
					);
				},
				'condition' => ['query_type' => 'custom'],
			],

			'query_id' => [
				'label'       => esc_html__('Devs: Query ID', 'bunyad-admin'),
				'type'        => 'text',
				'description' => esc_html__('Optional custom query ID to modify query args using the bunyad_block_query_args filter.', 'bunyad-admin'),
				'separator'   => 'before',
				'label_block' => true,
				'condition'   => ['query_type' => ['custom', 'main-custom']],
			],

			'exclude_current' => [
				'label'       => esc_html__('Exclude Current Post', 'bunyad-admin'),
				'description' => esc_html__('If using on a single post sidebar, this will ignore the post being viewed.', 'bunyad-admin'),
				'return_value' => '1',
				'type'         => 'switcher',
				// 'label_block'  => true,
				'condition'    => ['query_type' => ['custom']],
			],

		];


		/**
		 * Heading Options
		 */
		$options['sec-heading'] = [

			'heading_type' => [
				'label'       => esc_html__('Heading Style', 'bunyad-admin'),
				'type'        => 'select',
				'options'     => [
					'none' => esc_html__('Disabled', 'bunyad-admin'),
					''     => esc_html__('Global / Default Style', 'bunyad-admin'),
				] + $this->common['block_headings'],
				'default'     => '',
				'label_block' => true
			],

			'heading_colors' => [
				'label'       => esc_html__('Base Colors', 'bunyad-admin'),
				'type'        => 'select',
				'options'     => [
					''      => esc_html__('Auto', 'bunyad-admin'),
					'force' => esc_html__('Accent / Category', 'bunyad-admin'),
					'none'  => esc_html__('Default', 'bunyad-admin'),
				],
				'condition' => [
					'heading_type!' => ['g', 'e2']
				]
				// 'label_block' => true,
			],

			'heading' => [
				'label'       => esc_html__('Heading Text', 'bunyad-admin'),
				'type'        => 'text',
				'description' => esc_html__('Optional. By default, main category name will be used. Note: Some heading styles can have multi-color headings when used with asterisks, example: World *News*', 'bunyad-admin'),
				'label_block' => true
			],

			'heading_link' => [
				'label'       => esc_html__('Heading Link', 'bunyad-admin'),
				'type'        => 'text',
				'description' => esc_html__('Optional. By default, main category link will be used.', 'bunyad-admin'),
				'label_block' => true
			],

			'heading_more_text' => [
				'label'       => esc_html__('View More', 'bunyad-admin'),
				'type'        => 'text',
				'description' => esc_html__('Add view more text with link.', 'bunyad-admin'),
				'label_block' => true
			],

			'heading_more' => [
				'label'       => esc_html__('View More Style', 'bunyad-admin'),
				'type'        => 'select',
				'description' => '',
				'options'     => [
					'a' => 'Simple Text',
					'b' => 'Round Pill Style',
				],
				'condition'   => ['heading_more_text!' => ''],
			],

			'heading_more_link' => [
				'label'       => esc_html__('View More Link', 'bunyad-admin'),
				'type'        => 'text',
				'description' => esc_html__('Optional. By default, main category link will be used.', 'bunyad-admin'),
				'label_block' => true,
				'condition'   => ['heading_more_text!' => ''],
			],

			'h-filters' => [
				'label'       => esc_html__('Filters', 'bunyad-admin'),
				'type'        => 'heading',
				'separator'   => 'before'
			],

			'filters' => [
				'label'       => esc_html__('Heading Filters', 'bunyad-admin'),
				'type'        => 'select',
				'description' => esc_html__('Filters when enabled, are displayed as links in the heading on right side.', 'bunyad-admin'),
				'options'     => [
					''         => esc_html__('None', 'bunyad-admin'),
					'category' => esc_html__('Categories', 'bunyad-admin'),
					'tag'      => esc_html__('Tags', 'bunyad-admin'),
				],
				'label_block' => true
			],

			'filters_terms' => [
				'label'       => esc_html__('Categories', 'bunyad-admin'),
				'type'        => 'select2',
				'description' => esc_html__('If no categories are selected, it will default to showing 3 sub-categories.', 'bunyad-admin'),
				'options'     => [$this, '_option_categories'],
				'label_block' => true,
				'multiple'    => true,
				'condition'   => ['filters' => 'category'],
				'editor_callback' => function($option) {
					return $this->maybe_ajax_for_terms($option, 'categories');
				}
			],

			'filters_tags' => [
				'label'       => esc_html__('Tags', 'bunyad-admin'),
				'type'        => 'select2',
				'description' => esc_html__('Filters when enabled, are displayed as links in the heading on right side.', 'bunyad-admin'),
				// 'options'     => [$this, '_option_tags'],
				'label_block' => true,
				'multiple'    => true,
				'condition'   => ['filters' => 'tag'],
				'editor_callback' => function($option) {
					return $this->maybe_ajax_for_terms($option, 'tags');
				}
			],

			'heading_tag' => [
				'label'       => esc_html__('SEO: Heading Tag', 'bunyad-admin'),
				'type'        => 'select',
				'options'     => $this->common['heading_tags'],
			],

			// 'h-bh-styles' => [
			// 	'label'       => esc_html__('Styling', 'bunyad-admin'),
			// 	'type'        => 'heading',
			// 	'separator'   => 'before'
			// ],

		];

		/**
		 * Style - General.
		 */
		$options['sec-style'] = [
			'css_accent_color' => [
				'label'       => esc_html__('Accent / Main Color', 'bunyad-admin'),
				'description' => esc_html__('Used for hovers, heading text (unless changed in Heading Settings) and accent, and so on.', 'bunyad-admin'),
				'type'        => 'color',
				'selectors'   => [
					'{{WRAPPER}},
					{{WRAPPER}} .cat-labels .category' => '--c-main: {{VALUE}};',
					'{{WRAPPER}} .block-head' => '--c-block: {{VALUE}};',

				],
				'default'      => '',
			],
			'css_accent_color_sd' => [
				'label'       => esc_html__('Dark: Accent / Main Color', 'bunyad-admin'),
				'description' => esc_html__('For dark mode.', 'bunyad-admin'),
				'type'        => 'color',
				'selectors'   => [
					'.s-dark {{WRAPPER}}, {{WRAPPER}} .s-dark' => '--c-main: {{VALUE}};',
					'.s-dark {{WRAPPER}} .block-head, {{WRAPPER}} .s-dark .block-head' => '--c-block: {{VALUE}};',
				],
				'default'      => '',
			],
			
			'column_gap' => [
				'label'        => esc_html__('Column Gap', 'bunyad-admin'),
				'type'         => 'select',
				'options'      => [
					''    => esc_html__('Default', 'bunyad-admin'),
					'sm'  => esc_html__('Small Gap', 'bunyad-admin'),
					'lg'  => esc_html__('Large Gap', 'bunyad-admin'),
					'xlg' => esc_html__('X-Large Gap', 'bunyad-admin'),
				],
				'default'      => '',
				'separator'   => 'before'
			],

			'css_column_gap' => [
				'label'       => esc_html__('Column Gap Custom', 'bunyad-admin'),
				'description' => esc_html__('Vertical spacing between posts.', 'bunyad-admin'),
				'type'        => 'slider',
				'devices'     => true,
				'size_units'  => ['%', 'px'],
				'selectors'    => [
					'{{WRAPPER}} .loop' => '--grid-gutter: {{SIZE}}{{UNIT}};'
				],
				'default'     => [],
			],
			'css_row_gap' => [
				'label'       => esc_html__('Row Gap', 'bunyad-admin'),
				'type'        => 'slider',
				'description' => esc_html__('Horizontal spacing between posts.', 'bunyad-admin'),
				'devices'     => true,
				'size_units'  => ['%', 'px'],
				'selectors'    => [
					'{{WRAPPER}} .loop' => '--grid-row-gap: {{SIZE}}{{UNIT}};'
				],
				'default'     => [],
			],
			
			'separators' => [
				'label'        => esc_html__('Add Separators', 'bunyad-admin'),
				'type'         => 'switcher',
				'return_value' => '1',
				'default'      => '',
				'separator'    => 'before'
			],

			'separators_cols' => [
				'label'        => esc_html__('Add Column Separators', 'bunyad-admin'),
				'type'         => 'switcher',
				'return_value' => '1',
				'default'      => '',
				'separator'    => 'after'
			],
			
			'css_format_icon_size' => [
				'label'        => esc_html__('Post Format Icon Scale', 'bunyad-admin'),
				'type'         => 'number',
				'input_attrs'  => ['min' => 0.4, 'max' => 3.5, 'step' => .1],
				'devices'      => true,
				'selectors'    => [
					'{{WRAPPER}} .l-post' => '--post-format-scale: {{SIZE}};'
				],
				'default'     => [],
			],

			'css_media_radius' => [
				'label'       => esc_html__('Media/Image Roundness', 'bunyad-admin'),
				'type'        => 'number',
				'selectors'   => [
					'{{WRAPPER}} .l-post' => '--media-radius: {{SIZE}}px;'
				],
				'default'     => '',
			],

			'media_style_shadow' => [
				'label'        => esc_html__('Add Media Shadow', 'bunyad-admin'),
				'type'         => 'select',
				'options'      => [
					''     => esc_html__('Default / Global', 'bunyad-admin'),
					'1'    => esc_html__('Enabled', 'bunyad-admin'),
					'none' => esc_html__('Disabled', 'bunyad-admin'),
				],
				'default'      => '',
			],
		];

		/**
		 * Style: Content
		 */
		$options['sec-style-content'] = [
			'css_l_post_shadow' => [
				'label'       => esc_html__('Post Box Shadow', 'bunyad-admin'),
				'type'        => 'group',
				'group_type'  => 'box-shadow',
				'selector'   => '{{WRAPPER}} .l-post',
				// 'default'     => [],
			],

			'css_l_post_border' => [
				'label'       => esc_html__('Post Box Border', 'bunyad-admin'),
				'type'        => 'group',
				'group_type'  => 'bunyad-border',
				'selector'    => '{{WRAPPER}} .l-post',
				'selector_color_dark' => '.s-dark {{WRAPPER}} .l-post, {{WRAPPER}} .s-dark .l-post',
				// 'default'     => [],
			],

			'css_l_post_radius' => [
				'label'       => esc_html__('Post Box Roundness', 'bunyad-admin'),
				'type'        => 'number',
				'selectors'   => [
					'{{WRAPPER}} .l-post' => 'border-radius: {{VALUE}}px; overflow: hidden;'
				],
				'default'     => '',
			],

			'css_l_post_bg' => [
				'label'       => esc_html__('Post Box Background', 'bunyad-admin'),
				'type'        => 'color',
				'selectors'   => [
					'{{WRAPPER}} .l-post' => 'background-color: {{VALUE}};'
				],
				'default'     => '',
			],

			'css_l_post_bg_sd' => [
				'label'       => esc_html__('Dark: Box Background', 'bunyad-admin'),
				'type'        => 'color',
				'selectors'   => [
					'.s-dark {{WRAPPER}} .l-post, {{WRAPPER}} .s-dark .l-post' => 'background-color: {{VALUE}};'
				],
				'default'     => '',
				'condition'   => ['css_l_post_bg!' => '']
			],

			'css_l_post_pad' => [
				'label'       => esc_html__('Post Box Paddings', 'bunyad-admin'),
				'type'        => 'dimensions',
				'devices'     => true,
				'size_units'  => ['%', 'px'],
				'selectors'   => [
					'{{WRAPPER}} .l-post' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
				'default'     => [],
			],

			'css_content_pad' => [
				'label'       => esc_html__('Content Paddings', 'bunyad-admin'),
				'type'        => 'dimensions',
				'devices'     => true,
				'separator'   => 'before',
				'size_units'  => ['%', 'px'],
				'selectors'   => [
					'{{WRAPPER}} .content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
				'default'     => [],
			],

			'css_content_margin' => [
				'label'       => esc_html__('Content Margins', 'bunyad-admin'),
				'type'        => 'dimensions',
				'devices'     => true,
				'size_units'  => ['%', 'px'],
				'selectors'   => [
					'{{WRAPPER}} .content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
				'default'     => [],
			],

			'css_content_border' => [
				'label'       => esc_html__('Content Border', 'bunyad-admin'),
				'type'        => 'group',
				'group_type'  => 'bunyad-border',
				'selector'    => '{{WRAPPER}} .content',
				'selector_color_dark' => '.s-dark {{WRAPPER}} .content, {{WRAPPER}} .s-dark .content',
				// 'default'     => [],
			],

			'css_content_shadow' => [
				'label'       => esc_html__('Content Shadow', 'bunyad-admin'),
				'type'        => 'group',
				'group_type'  => 'box-shadow',
				'selector'    => '{{WRAPPER}} .content',
				// 'default'     => [],
			],

			'css_content_bg' => [
				'label'       => esc_html__('Content Background', 'bunyad-admin'),
				'type'        => 'color',
				'selectors'   => [
					'{{WRAPPER}} .content' => 'background-color: {{VALUE}};'
				],
				'default'     => '',
			],

			'css_content_bg_sd' => [
				'label'       => esc_html__('Dark: Background', 'bunyad-admin'),
				'type'        => 'color',
				'selectors'   => [
					'.s-dark {{WRAPPER}} .content, {{WRAPPER}} .s-dark .content' => 'background-color: {{VALUE}};'
				],
				'default'     => '',
				'condition'   => ['css_content_bg!' => '']
			],

			'css_meta_above_margins' => [
				'label'       => esc_html__('Post Meta Above Margins', 'bunyad-admin'),
				'type'        => 'dimensions',
				'devices'     => true,
				'separator'   => 'before',
				'size_units'  => ['%', 'px'],
				'selectors'   => [
					'{{WRAPPER}} .meta-above' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
				'default'     => [],
			],

			'css_meta_below_margins' => [
				'label'       => esc_html__('Post Meta Below Margins', 'bunyad-admin'),
				'type'        => 'dimensions',
				'devices'     => true,
				'size_units'  => ['%', 'px'],
				'selectors'   => [
					'{{WRAPPER}} .meta-below' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
				'default'     => [],
			],
			'css_meta_typography' => [
				'label'       => esc_html__('Post Meta Typography', 'bunyad-admin'),
				'type'        => 'group',
				'group_type'  => 'typography',
				'selector'   => '{{WRAPPER}} .post-meta .meta-item, {{WRAPPER}} .post-meta .text-in',
				'default'     => [],
			],
			'css_meta_icons_size' => [
				'label'       => esc_html__('Post Meta Icons Size', 'bunyad-admin'),
				'type'        => 'number',
				'separator'   => 'after',
				'devices'     => true,
				'selectors'   => [
					'{{WRAPPER}} .post-meta' => '--p-meta-icons-s: {{SIZE}}px;'
				],
				'default'     => [],
			],

			'css_excerpt_typo' => [
				'label'       => esc_html__('Excerpts Typography', 'bunyad-admin'),
				'type'        => 'group',
				'group_type'  => 'typography',
				'selector'    => '{{WRAPPER}} .excerpt',
				'default'     => [],
			],

			'css_excerpt_margin' => [
				'label'       => esc_html__('Excerpt Margins', 'bunyad-admin'),
				'type'        => 'dimensions',
				'devices'     => true,
				// 'size_units'  => ['px'],
				'selectors'   => [
					'{{WRAPPER}} .excerpt' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
				'default'     => [],

			],

			'css_excerpt_visible' => [
				'label'      => esc_html__('Excerpt Visibility', 'bunyad-admin'),
				'type'       => 'select',
				'options'    => [
					''       => 'Default',
					'block'  => esc_html__('Show', 'bunyad-admin'),
					'none'   => esc_html__('Hide', 'bunyad-admin'),
				],
				'devices'  => true,
				'selectors'    => [
					'{{WRAPPER}} .excerpt' => 'display: {{VALUE}}',
				],
				'condition'    => ['excerpts' => '1'],
			],

			'css_excerpt_lines' => [
				'label'       => esc_html__('Excerpts Lines Per Device', 'bunyad-admin'),
				'type'        => 'number',
				'devices'     => true,
				'selectors'   => [
					'{{WRAPPER}} .excerpt' => '--limit-lines: {{VALUE}};'
				],
				'default'     => [],
				'condition'   => ['excerpt_lines!' => ['', '0']]
			],

		];

		/**
		 * Style: Post Titles.
		 */
		$options['sec-style-titles'] = [
			'css_title_typo' => [
				'label'       => esc_html__('Post Titles Typography', 'bunyad-admin'),
				'type'        => 'group',
				'group_type'  => 'typography',
				'selector'    => '{{WRAPPER}} .post-title',
				'default'     => [],
			],

			'css_title_margin' => [
				'label'       => esc_html__('Titles Margins', 'bunyad-admin'),
				'type'        => 'dimensions',
				'devices'     => true,
				'size_units'  => ['%', 'px'],
				'allowed_dimensions' => ['top', 'bottom'],
				'selectors'   => [
					'{{WRAPPER}} .post-title' => 'margin-top: {{TOP}}{{UNIT}}; margin-bottom: {{BOTTOM}}{{UNIT}};'
				],
				'default'     => [],
			],

			'css_title_padding' => [
				'label'       => esc_html__('Titles Paddings', 'bunyad-admin'),
				'type'        => 'dimensions',
				'devices'     => true,
				'size_units'  => ['%', 'px'],
				'selectors'   => [
					'{{WRAPPER}} .post-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
				'default'     => [],
			],
			
			'css_title_color' => [
				'label'       => esc_html__('Titles Color', 'bunyad-admin'),
				'type'        => 'color',
				'selectors'   => [
					'.elementor-element-{{ID}} .post-title' => '--c-headings: {{VALUE}};'
				],
				'default'     => '',
			],

			'css_title_color_sd' => [
				'label'       => esc_html__('Dark: Titles Color', 'bunyad-admin'),
				'type'        => 'color',
				'selectors'   => [
					'.s-dark .elementor-element-{{ID}} .post-title,
					.elementor-element-{{ID}} .s-dark .post-title' => '--c-headings: {{VALUE}};'
				],
				'default'     => '',
			],

			'css_title_hov_color' => [
				'label'       => esc_html__('Hover Color', 'bunyad-admin'),
				'type'        => 'color',
				'selectors'   => [
					'.elementor-element-{{ID}} .post-title a:hover' => '--c-a-hover: {{VALUE}};'
				],
				'default'     => '',
			],

			'css_title_hov_color_sd' => [
				'label'       => esc_html__('Dark: Hover Color', 'bunyad-admin'),
				'type'        => 'color',
				'selectors'   => [
					'.s-dark .elementor-element-{{ID}} .post-title a:hover,
					.elementor-element-{{ID}} .s-dark .post-title a:hover' => '--c-a-hover: {{VALUE}};'
				],
				'default'     => '',
			],
		];

		/**
		 * Style: Block Heading
		 */
		$options['sec-style-bhead'] = [
			'heading_align' => [
				'label'       => esc_html__('Heading Align', 'bunyad-admin'),
				'type'        => 'select',
				'options'     => [
					'left'   => esc_html__('Default / Left', 'bunyad-admin'),
					'center' => esc_html__('Centered (Filters Not Supported)', 'bunyad-admin'),
				],
				'label_block' => true,
			],

			'css_bhead_typo' => [
				'label'       => esc_html__('Heading Typography', 'bunyad-admin'),
				'type'        => 'group',
				'group_type'  => 'typography',
				'selector'    => '{{WRAPPER}} .block-head .heading',
				'default'     => [],
			],

			'css_bhead_space_below' => [
				'label'       => esc_html__('Space Below', 'bunyad-admin'),
				'type'        => 'slider',
				'devices'     => true,
				'size_units'  => ['%', 'px'],
				'selectors'   => [
					'{{WRAPPER}} .block-head' => '--space-below: {{SIZE}}{{UNIT}};'
				],
				'default'     => [],
			],

			// Note: Only makes sense for elementor, not for customizer.
			'css_bhead_accent' => [
				'label'       => esc_html__('Heading Accent Color', 'bunyad-admin'),
				'type'        => 'color',
				'selectors'   => [
					'{{WRAPPER}} .block-head' => '--c-main: {{VALUE}}; --c-block: {{VALUE}};'
				],
				'default'     => '',
				'condition'   => [
					'heading_type!' => ['g']
				]
			],
			'css_bhead_accent_sd' => [
				'label'       => esc_html__('Dark: Heading Accent', 'bunyad-admin'),
				'type'        => 'color',
				'selectors'   => [
					'{{WRAPPER}} .s-dark .block-head,
					.s-dark {{WRAPPER}} .block-head' => '--c-main: {{VALUE}}; --c-block: {{VALUE}};'
				],
				'default'     => '',
				'condition'   => [
					'heading_type!' => ['g']
				]
			],

			'css_bhead_color' => [
				'label'       => esc_html__('Heading Text Color', 'bunyad-admin'),
				'type'        => 'color',
				// 'description' => esc_html__('Category color or theme main color will be used by default.', 'bunyad-admin'),
				'selectors'   => [
					'{{WRAPPER}} .block-head .heading' => 'color: {{VALUE}};'
				],
				'default'     => '',
			],
			'css_bhead_color_sd' => [
				'label'       => esc_html__('Dark: Heading Color', 'bunyad-admin'),
				'type'        => 'color',
				'selectors'   => [
					'{{WRAPPER}} .s-dark .block-head .heading,
					.s-dark {{WRAPPER}} .block-head .heading' => 'color: {{VALUE}};'
				],
				'default'     => '',
			],

			'css_bhead_line_weight' => [
				'label'       => esc_html__('Accent Line Weight', 'bunyad-admin'),
				'type'        => 'number',
				'selectors'   => [
					'{{WRAPPER}} .block-head' => '--line-weight: {{VALUE}}px;'
				],
				'default'     => '',
				'condition'   => ['heading_type' => $this->common['supports_bhead_line_weight']],
			],

			'css_bhead_line_width' => [
				'label'       => esc_html__('Accent Line Width', 'bunyad-admin'),
				'type'        => 'number',
				'selectors'   => [
					'{{WRAPPER}} .block-head' => '--line-width: {{VALUE}}px;'
				],
				'default'     => '',
				'condition'   => ['heading_type' => $this->common['supports_bhead_line_width']],
			],

			'css_bhead_line_color' => [
				'label'       => esc_html__('Accent Line Color', 'bunyad-admin'),
				'type'        => 'color',
				'selectors'   => [
					'{{WRAPPER}} .block-head' => '--c-line: {{VALUE}};'
				],
				'default'     => '',
				'condition'   => ['heading_type' => $this->common['supports_bhead_line_color']],
			],
			'css_bhead_line_color_sd' => [
				'label'       => esc_html__('Dark: Accent Line Color', 'bunyad-admin'),
				'type'        => 'color',
				'selectors'   => [
					'{{WRAPPER}} .s-dark .block-head,
					.s-dark {{WRAPPER}} .block-head' => '--c-line: {{VALUE}};'
				],
				'default'     => '',
				'condition'   => ['heading_type' => $this->common['supports_bhead_line_color']],
			],

			// Only for style c and h.
			'css_bhead_border_weight' => [
				'label'       => esc_html__('Border Line Weight', 'bunyad-admin'),
				'type'        => 'number',
				'selectors'   => [
					'{{WRAPPER}} .block-head' => '--border-weight: {{VALUE}}px;'
				],
				'default'     => '',
				'condition'   => ['heading_type' => $this->common['supports_bhead_border_weight']],
			],

			'css_bhead_border_color' => [
				'label'       => esc_html__('Border Line Color', 'bunyad-admin'),
				'type'        => 'color',
				'selectors'   => [
					'{{WRAPPER}} .block-head' => '--c-border: {{VALUE}};'
				],
				'default'     => '',
				'condition'   => ['heading_type' => $this->common['supports_bhead_border_color']],
			],
			'css_bhead_border_color_sd' => [
				'label'       => esc_html__('Dark: Border Line Color', 'bunyad-admin'),
				'type'        => 'color',
				'selectors'   => [
					'{{WRAPPER}} .s-dark .block-head,
					.s-dark {{WRAPPER}} .block-head' => '--c-border: {{VALUE}};'
				],
				'default'     => '',
				'condition'   => ['heading_type' => $this->common['supports_bhead_border_color']],
			],

			'css_bhead_bg' => [
				'label'       => esc_html__('Background Color', 'bunyad-admin'),
				'type'        => 'color',
				'selectors'   => [
					'{{WRAPPER}} .block-head' => 'background-color: {{VALUE}};'
				],
				'default'     => '',
			],

			'css_bhead_pad' => [
				'label'       => esc_html__('Padding', 'bunyad-admin'),
				'type'        => 'dimensions',
				'size_units'  => ['%', 'px'],
				'devices'     => true,
				'selectors'   => [
					'{{WRAPPER}} .block-head' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
				'default'     => [],
				'condition'   => ['heading_type!' => ['e']],
			],

			'css_bhead_inner_pad' => [
				'label'       => esc_html__('Inner Padding', 'bunyad-admin'),
				'type'        => 'number',
				'devices'     => true,
				'selectors'   => [
					'{{WRAPPER}} .block-head' => '--inner-pad: {{VALUE}}px;'
				],
				'default'     => [],
				'condition'   => ['heading_type' => ['e']],
			],

			'css_bhead_roundness' => [
				'label'       => esc_html__('Roundness', 'bunyad-admin'),
				'type'        => 'number',
				'selectors'   => [
					'{{WRAPPER}} .block-head' => '--box-roundness: {{VALUE}}px;'
				],
				'default'     => [],
				'condition'   => ['heading_type' => $this->common['supports_bhead_roundness']],
			],
			
			'css_bhead_filters_typo' => [
				'label'       => esc_html__('Filters Typography', 'bunyad-admin'),
				'type'        => 'group',
				'group_type'  => 'typography',
				'selector'    => '{{WRAPPER}} .block-head .filters',
				'default'     => [],
			],

			'h-style-view-more' => [
				'label'       => esc_html__('View More Link', 'bunyad-admin'),
				'type'        => 'heading',
				'separator'   => 'before',
				'condition'   => ['heading_more_text!' => ''],
			],
			'css_bhead_more_typo' => [
				'label'       => esc_html__('More Link Typography', 'bunyad-admin'),
				'type'        => 'group',
				'group_type'  => 'typography',
				'selector'    => '{{WRAPPER}} .block-head .view-link',
				'default'     => [],
				'condition'   => ['heading_more_text!' => ''],
			],
			'css_bhead_more_pad' => [
				'label'       => esc_html__('More Link Padding', 'bunyad-admin'),
				'type'        => 'dimensions',
				'devices'     => true,
				'selector'    => '{{WRAPPER}} .block-head .view-link',
				'selectors'   => [
					'{{WRAPPER}} .block-head .view-link' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
				'condition'   => ['heading_more_text!' => ''],
			],
		];


		/**
		 * Style: Load More
		 */
		$options['sec-style-pagination'] = [

			'css_pagination_margin' => [
				'label'       => esc_html__('Pagination Margins', 'bunyad-admin'),
				'type'        => 'dimensions',
				'devices'     => true,
				'size_units'  => ['px'],
				'selectors'   => [
					'{{WRAPPER}} .loop + .main-pagination' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
				'default'     => [],
			],

			'load_more_style' => [
				'label'       => esc_html__('Load More Style', 'bunyad-admin'),
				'type'        => 'select',
				'options'     => [
					''  => esc_html__('Default / Global', 'bunyad-admin'),
				] + $this->common['load_more_options'],
				'label_block' => true,
				'condition'   => ['pagination_type' => 'load-more']
			],

			'css_load_more_typo' => [
				'label'       => esc_html__('Typography', 'bunyad-admin'),
				'type'        => 'group',
				'group_type'  => 'typography',
				'selector'    => '{{WRAPPER}} .load-button',
				'default'     => [],
				'condition'   => ['pagination_type' => 'load-more']
			],

			'css_load_more_color' => [
				'label'       => esc_html__('Text Color', 'bunyad-admin'),
				'type'        => 'color',
				'selectors'   => [
					'{{WRAPPER}} .load-button' => 'color: {{VALUE}};'
				],
				'default'     => '',
				'condition'   => ['pagination_type' => 'load-more']
			],
			'css_load_more_color_sd' => [
				'label'       => esc_html__('Dark: Text Color', 'bunyad-admin'),
				'type'        => 'color',
				'selectors'   => [
					'.s-dark {{WRAPPER}} .load-button,
					{{WRAPPER}} .s-dark .load-button' => 'color: {{VALUE}};'
				],
				'default'     => '',
				'condition'   => ['pagination_type' => 'load-more']
			],

			'css_load_more_bg' => [
				'label'       => esc_html__('Button Background', 'bunyad-admin'),
				'type'        => 'color',
				'selectors'   => [
					'{{WRAPPER}} .load-button' => 'background-color: {{VALUE}};'
				],
				'default'     => '',
				'condition'   => ['pagination_type' => 'load-more']
			],
			'css_load_more_bg_sd' => [
				'label'       => esc_html__('Dark: Button Background', 'bunyad-admin'),
				'type'        => 'color',
				'selectors'   => [
					'.s-dark {{WRAPPER}} .load-button,
					{{WRAPPER}} .s-dark .load-button' => 'background-color: {{VALUE}};'
				],
				'default'     => '',
				'condition'   => ['pagination_type' => 'load-more']
			],

			'css_load_more_bg_sd' => [
				'label'       => esc_html__('Border Color', 'bunyad-admin'),
				'type'        => 'color',
				'selectors'   => [
					'{{WRAPPER}} .load-button' => 'border-color: {{VALUE}};'
				],
				'default'     => '',
				'condition'   => ['pagination_type' => 'load-more']
			],

			'css_load_more_pad' => [
				'label'       => esc_html__('Button Padding', 'bunyad-admin'),
				'type'        => 'dimensions',
				'devices'     => true,
				'size_units'  => ['px'],
				'selectors'   => [
					'{{WRAPPER}} .load-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
				'default'     => [],
				'condition'   => ['pagination_type' => 'load-more']
			],

			'css_load_more_width' => [
				'label'       => esc_html__('Button Width', 'bunyad-admin'),
				'type'        => 'slider',
				'devices'     => false,
				'size_units'  => ['%', 'px'],
				'selectors'   => [
					'{{WRAPPER}} .load-button' => 'width: {{SIZE}}{{UNIT}}; min-width: 0;'
				],
				'default'     => [],
				'condition'   => ['pagination_type' => 'load-more']
			],

			'css_load_more_bradius' => [
				'label'        => esc_html__('Border Radius', 'bunyad-admin'),
				'type'         => 'number',
				'default'      => '',
				'selectors'   => [
					'{{WRAPPER}} .load-button' => 'border-radius: {{VALUE}}px'
				],
				'condition'   => ['pagination_type' => 'load-more']
			],
		];

		/**
		 * Style: Category labels
		 */
		$options['sec-style-cat-labels'] = [
			'_n_cat_labels' => [
				'type'      => 'html',
				'raw'       => esc_html__('These only apply to category overlay label style, when enabled for this block.', 'bunyad-admin'),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			],

			'css_cat_labels_typo' => [
				'label'       => esc_html__('Typography', 'bunyad-admin'),
				'type'        => 'group',
				'group_type'  => 'typography',
				'selector'    => '{{WRAPPER}} .cat-labels .category',
			],

			'css_cat_labels_bg' => [
				'label'       => esc_html__('Background Color', 'bunyad-admin'),
				'type'        => 'color',
				'selectors'   => [
					'{{WRAPPER}} .cat-labels .category' => 'background-color: {{VALUE}}'
				],
			],

			'css_cat_labels_color' => [
				'label'       => esc_html__('Text Color', 'bunyad-admin'),
				'type'        => 'color',
				'selectors'   => [
					'{{WRAPPER}} .cat-labels .category' => 'color: {{VALUE}}'
				],
			],

			'css_cat_labels_border_radius' => [
				'label'       => esc_html__('Border Radius', 'bunyad-admin'),
				'type'        => 'number',
				'selectors'   => [
					'{{WRAPPER}} .cat-labels .category' => 'border-radius: {{VALUE}}px'
				],
			],

			'css_cat_labels_pad' => [
				'label'       => esc_html__('Padding', 'bunyad-admin'),
				'type'        => 'dimensions',
				'devices'     => true,
				'size_units'  => ['%', 'px'],
				'selectors'   => [
					'{{WRAPPER}} .cat-labels .category' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
			],

			'css_cat_labels_margins' => [
				'label'       => esc_html__('Margins', 'bunyad-admin'),
				'type'        => 'dimensions',
				'devices'     => true,
				'size_units'  => ['%', 'px'],
				'selectors'   => [
					'{{WRAPPER}} .cat-labels .category' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
			],
		];

		return $options;
	}

	public function get_common_data(array $options = [])
	{
		$options['meta_options'] = [
			'cat'       => esc_html__('Category', 'bunyad-admin'),
			'author'    => esc_html__('Author', 'bunyad-admin'),
			'date'      => esc_html__('Date', 'bunyad-admin'),
			'comments'  => esc_html__('Comments', 'bunyad-admin'),
			'read_time' => esc_html__('Read Time', 'bunyad-admin'),
		];

		/**
		 * Carousel fields aren't to be used elsewhere, so defined here. 
		 */
		$options['carousel_fields'] = [
			'carousel' => [
				'label'        => esc_html__('Enable Carousel', 'bunyad-admin'),
				'type'         => 'switcher',
				'return_value' => '1',
				'default'      => '1',
			],

			'carousel_slides' => [
				'label'        => esc_html__('Custom Slides', 'bunyad-admin'),
				'description'  => esc_html__('Optional. Columns number by default.', 'bunyad-admin'),
				'type'         => 'number',
				'input_attrs'  => ['min' => 1, 'max' => 7],
				'default'      => '',
				'condition'    => ['carousel' => '1']
			],

			'carousel_slides_md' => [
				'label'        => esc_html__('Slides: Tablets', 'bunyad-admin'),
				'description'  => esc_html__('Optional. Auto by default.', 'bunyad-admin'),
				'type'         => 'number',
				'input_attrs'  => ['min' => 1, 'max' => 7],
				'default'      => '',
				'condition'    => ['carousel' => '1']
			],

			'carousel_slides_sm' => [
				'label'        => esc_html__('Slides: Phones', 'bunyad-admin'),
				'description'  => esc_html__('Optional. Auto by default.', 'bunyad-admin'),
				'type'         => 'number',
				'input_attrs'  => ['min' => 1, 'max' => 7],
				'default'      => '',
				'condition'    => ['carousel' => '1']
			],

			'carousel_arrows' => [
				'label'        => esc_html__('Arrows', 'bunyad-admin'),
				'type'         => 'select',
				'options'      => [
					''  => esc_html__('Disabled', 'bunyad-admin'),
					'a' => esc_html__('A: Transparent (Dark BG Only)', 'bunyad-admin'),
					'b' => esc_html__('B: Modern Round', 'bunyad-admin'),
				],
				'default'      => '',
			],

			'carousel_dots' => [
				'label'        => esc_html__('Navigation Dots', 'bunyad-admin'),
				'type'         => 'switcher',
				'return_value' => '1',
				'default'      => '1',
				'condition'    => ['carousel' => '1']
			],

			'carousel_autoplay' => [
				'label'        => esc_html__('Autoplay', 'bunyad-admin'),
				'type'         => 'switcher',
				'return_value' => '1',
				'default'      => '1',
				'condition'    => ['carousel' => '1']
			],

			'carousel_play_speed' => [
				'label'        => esc_html__('Autoplay Speed', 'bunyad-admin'),
				'type'         => 'number',
				'input_attrs'  => ['min' => 50],
				'default'      => '',
				'condition'    => ['carousel' => '1']
			],	

			'css_carousel_nav_spacing' => [
				'label'       => esc_html__('Navigation Dots Distance', 'bunyad-admin'),
				'description' => '',
				'type'        => 'number',
				'devices'     => true,
				'selectors'    => [
					'{{WRAPPER}} .common-slider .nav-dots' => 'margin-top: {{SIZE}}px;'
				],
				'default'     => [],
			],
		];

		return parent::get_common_data($options);
	}

	/**
	 * Get option sections
	 */
	public function get_sections()
	{
		return [
			'sec-general' => ['label' => esc_html__('General', 'bunyad-admin')],
			'sec-layout'  => ['label' => esc_html__('Layout', 'bunyad-admin')],
			'sec-filter'  => ['label' => esc_html__('Posts Source', 'bunyad-admin')],
			'sec-heading' => ['label' => esc_html__('Heading', 'bunyad-admin')],
			'sec-style'   => [
				'label' => esc_html__('Style', 'bunyad-admin'),
				'tab'   => 'style'
			],
			'sec-style-titles' => [
				'label' => esc_html__('Style: Post Titles', 'bunyad-admin'),
				'tab'   => 'style'
			],

			'sec-style-content' => [
				'label' => esc_html__('Style: Content & Box', 'bunyad-admin'),
				'tab'   => 'style'
			],

			'sec-style-bhead' => [
				'label' => esc_html__('Style: Block Heading', 'bunyad-admin'),
				'tab'  => 'style',
			],

			'sec-style-pagination' => [
				'label' => esc_html__('Style: Pagination', 'bunyad-admin'),
				'tab'  => 'style',
			],

			'sec-style-cat-labels' => [
				'label' => esc_html__('Style: Category Labels', 'bunyad-admin'),
				'tab'   => 'style'
			],
		];
		
	}

	/**
	 * Generate number of column options based on supported.
	 *
	 * @return array
	 */
	public function _option_columns()
	{
		$column_options = [
			'1'   => esc_html__('1 Column', 'bunyad-admin'),
			'2'   => esc_html__('2 Columns', 'bunyad-admin'),
			'3'   => esc_html__('3 Columns', 'bunyad-admin'),
			'4'   => esc_html__('4 Columns', 'bunyad-admin'),
			'5'   => esc_html__('5 Columns', 'bunyad-admin'),
			'6'   => esc_html__('6 Columns', 'bunyad-admin'),
		];

		if (!is_array($this->supported_columns)) {
			$this->supported_columns = range(1, intval($this->supported_columns));
		}
		
		// Remove unsupported.
		$column_options = array_filter(
			$column_options, 
			function($value) {
				return in_array($value, $this->supported_columns);
			},
			ARRAY_FILTER_USE_KEY
		);

		$options = [
			'' => esc_html__('Auto', 'bunyad-admin'),
		];

		$options += $column_options;

		return $options;
	}

	/**
	 * Get categories for control options
	 */
	public function _option_categories()
	{
		$categories = $this->get_limited_terms('category', $this->limit_cats);
		return $this->_recurse_terms_array(0, $categories);
	}

	/**
	 * Get tags for control options
	 * 
	 * @deprecated 5.1.0
	 */
	public function _option_tags()
	{
		$tags = $this->get_limited_terms('post_tag');
		return $this->_recurse_terms_array(0, $tags);
	}

	/**
	 * Image sizing options for blocks
	 * 
	 * @deprecated 5.1.0
	 */
	public function _option_images()
	{
		global $_wp_additional_image_sizes;

		foreach (get_intermediate_image_sizes() as $_size) {

			// Default sizes
			if (in_array($_size, array('thumbnail', 'medium', 'medium_large', 'large'))) {
				$size = [
					'width'  => get_option("{$_size}_size_w"),
					'height' => get_option("{$_size}_size_h"),
				];
			} 
			else if (isset($_wp_additional_image_sizes[ $_size ])) {
				$size = $_wp_additional_image_sizes[ $_size ];
			}

			$sizes[$_size] =  "{$size['width']}x{$size['height']} - {$_size}";
		}

		$sizes = array_merge(
			[
				'' => esc_html__('Default / Auto', 'bunyad-admin'),
			], 
			$sizes
		);

		return $sizes;
	}

	/**
	 * Get terms (from cache if already called), with a limit applied.
	 * 
	 * @param string $taxonomy
	 * @param integer $limit
	 * @return array
	 */
	protected function get_limited_terms($taxonomy, $limit = 200)
	{
		if (isset($this->terms_cache[$taxonomy])) {
			return $this->terms_cache[$taxonomy];
		}

		$terms = [];
		switch ($taxonomy) {
			case 'post_tag':
				$terms = get_terms('post_tag', [
					'hide_empty' => false,
					'order_by'   => 'count',
					'number'     => $limit
				]);
				break;

			default:
				$terms = get_terms($taxonomy, [
					'hide_empty'    => false,
					'hide_if_empty' => false,
					'hierarchical'  => 1, 
					'order_by'      => 'name',
					'number'        => $limit
				]);
				break;
		}

		$this->terms_cache[$taxonomy] = $terms;
		return $terms;
	}

	/**
	 * Add AJAX config for an option, if the terms exceed a certain number.
	 * 
	 * @param array $option
	 * @param string $type   categories or tags.
	 * @param integer $limit
	 * @return array
	 */
	protected function maybe_ajax_for_terms($option, $type, $limit = null)
	{
		switch ($type) {
			case 'tags':
				$taxonomy = 'post_tag';
				break;

			case 'categories':
				$taxonomy = 'category';

				// Keep same as _option_categories for cache.
				$limit = $limit ? $limit : $this->limit_cats;

				break;
		}

		// If no limit, assume always. Otherwise test if it's equal or exceeds.
		if (!$limit || count($this->get_limited_terms($taxonomy, $limit)) >= $limit) {
			$option = array_replace($option, [
				'type'              => 'bunyad-selectize',
				'options'           => [],
				'selectize_options' => [
					'multiple' => !empty($option['multiple']),
					'sortable' => false,
					'create'   => false,
					'ajax'     => true,
					'preload'  => true,

					// Will preload 50 too.
					'endpoint' => $type . '?per_page=50&orderby=count&order=desc&_fields=id,name&search={query}',

					// To fetch existing saved cats by id.
					'endpoint_saved' => $type . '?_fields=id,name&include={ids}',
				],
			]);
		}

		return $option;
	}

	/**
	 * Create hierarchical terms drop-down via recursion on parent-child relationship
	 * 
	 * @param integer  $parent
	 * @param object   $terms
	 * @param integer  $depth
	 */
	public function _recurse_terms_array($parent, $terms, $depth = 0)
	{	
		$the_terms = array();
			
		$output = array();
		foreach ($terms as $term) {
			
			// add tab to children
			if ($term->parent == $parent) {
				$output[ $term->term_id ] = str_repeat(" &middot; ", $depth) . $term->name;
				$output = array_replace(
					$output, 
					$this->_recurse_terms_array($term->term_id, $terms, $depth+1)
				);
			}
		}
		
		return $output;
	}
}