<?php
/**
 * Global Page Layout options
 */

$fields = [
	[
		'name'  => '_n_layout_pages',
		'type'  => 'message',
		'label' => '',
		'text'  => 'Design and some other settings of pages are shared with single posts. 
			Check <a href="#" class="focus-link is-with-nav" data-section="bunyad-posts-single-design">Single Post Design</a> section.',
		'style' => 'message-info',
	],
	[
		'name'    => 'page_sidebar',
		'label'   => esc_html__('Single Page Sidebar', 'bunyad-admin'),
		'desc'    => esc_html__('Default is from Main Layout settings. This setting can also be changed per post or page.', 'bunyad-admin'),
		'value'   => '',
		'type'    => 'select',
		'options' => [
			''      => esc_html__('Default / Global', 'bunyad-admin'),
			'none'  => esc_html__('No Sidebar', 'bunyad-admin'),
			'right' => esc_html__('Right Sidebar', 'bunyad-admin') 
		],
	],
	[
		'name'       => 'css_page_title_typo',
		'label'      => esc_html__('Page Titles Typography', 'bunyad-admin'),
		'desc'       => '',
		'value'      => '',
		'type'       => 'group',
		'group_type' => 'typography',
		'style'      => 'edit',
		'css'        => '.the-page-heading',
	],

	/**
	 * Homepage.
	 */
	[
		'name'   => 'no_home_duplicates',
		'label'  => esc_html__('Homepage: No Duplicate Posts', 'bunyad-admin'),
		'desc'   => esc_html__('Enable to display posts only once across the home blocks - same posts will not show again in another block. Note: Does not work with AJAX - use offset feature in blocks instead.', 'bunyad-admin'),
		'value'  => 0,
		'type'   => 'toggle',
		'style'  => 'inline-sm'
	],					

	/**
	 * Group: 404 Page
	 */
	[
		'name'  => '_g_page_404',
		'label' => esc_html__('404 Page', 'bunyad-admin'),
		'type'  => 'group',
		'style' => 'collapsible',
	],
		[
			'name'    => 'page_404_title',
			'label'   => esc_html__('404 Title', 'bunyad-admin'),
			'desc'    => '',
			'value'   => '',
			'placeholder' => esc_html__('Page Not Found!', 'bunyad'),
			'type'    => 'text',
			'group'   => '_g_page_404'
		],
		[
			'name'    => 'page_404_text',
			'label'   => esc_html__('404 Custom Text', 'bunyad-admin'),
			'desc'    => '',
			'value'   => '',
			'type'    => 'textarea',
			'group'   => '_g_page_404'
		],
	
];

$options['layout-pages'] = [
	'sections' => [[
		'id'     => 'layout-pages',
		'title'  => esc_html__('Pages Layouts', 'bunyad-admin'),
		'desc'   => '',
		'fields' => $fields
	]]
];

return $options;