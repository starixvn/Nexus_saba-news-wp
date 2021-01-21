<?php 

/**
 * Theme Options Panel.
 *
 * @package Telegram
 */

$default = telegram_get_default_theme_options();

// Setting - show_selected_page_content_on_homepage.
$wp_customize->add_setting('show_selected_page_content_on_homepage',
    array(
        'default' => $default['show_selected_page_content_on_homepage'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'telegram_sanitize_checkbox',
    )
);
$wp_customize->add_control('show_selected_page_content_on_homepage',
    array(
        'label' => esc_html__('Enable Static Page Content on Home/Front Page', 'telegram'),
        'section' => 'static_front_page',
        'type' => 'checkbox',
        'priority' => 100,
		'active_callback' => 'telegram_blog_section_callback',
    )
);

// Setting - show_latest_post_content_on_homepage.
$wp_customize->add_setting('show_latest_post_content_on_homepage',
    array(
        'default' => $default['show_latest_post_content_on_homepage'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'telegram_sanitize_checkbox',
    )
);
$wp_customize->add_control('show_latest_post_content_on_homepage',
    array(
        'label' => esc_html__('Enable Latest Post on Home/Front Page', 'telegram'),
        'section' => 'static_front_page',
        'type' => 'checkbox',
        'priority' => 100,
		'active_callback' => 'telegram_homepage_section_callback',
    )
);


/*slider and its property section*/
require get_template_directory() . '/inc/customizer/slider.php';
require get_template_directory() . '/inc/customizer/homepage-options.php';

// Add Theme Options Panel.
$wp_customize->add_panel( 'homepage_option_panel',
	array(
		'title'      => esc_html__( 'HomePage Setting Options', 'telegram' ),
		'priority'   => 200,
		'capability' => 'edit_theme_options',
	)
);


// Add Theme Options Panel.
$wp_customize->add_panel( 'theme_option_panel',
	array(
		'title'      => esc_html__( 'Theme Options', 'telegram' ),
		'priority'   => 200,
		'capability' => 'edit_theme_options',
	)
);

/*layout management section start */
$wp_customize->add_section( 'theme_option_section_settings',
	array(
		'title'      => esc_html__( 'Layout Management', 'telegram' ),
		'priority'   => 100,
		'capability' => 'edit_theme_options',
		'panel'      => 'theme_option_panel',
	)
);

/*Date Layout*/
$wp_customize->add_setting( 'site_date_layout_option',
	array(
		'default'           => $default['site_date_layout_option'],
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'telegram_sanitize_select',
	)
);
$wp_customize->add_control( 'site_date_layout_option',
	array(
		'label'    => esc_html__( 'Select Date Format', 'telegram' ),
		'section'  => 'theme_option_section_settings',
		'choices'  => array(
                'in-time-span' => __( 'Time Span Format', 'telegram' ),
                'normal-format' => __( 'Regular Format', 'telegram' ),
		    ),
		'type'     => 'select',
		'priority' => 160,
	)
);

/*Global Layout*/
$wp_customize->add_setting( 'global_layout',
	array(
		'default'           => $default['global_layout'],
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'telegram_sanitize_select',
	)
);
$wp_customize->add_control( 'global_layout',
	array(
		'label'    => esc_html__( 'Global Sidebar Layout', 'telegram' ),
		'section'  => 'theme_option_section_settings',
		'choices'   => array(
			'left-sidebar'  => esc_html__( 'Primary Sidebar - Content', 'telegram' ),
			'right-sidebar' => esc_html__( 'Content - Primary Sidebar', 'telegram' ),
			'no-sidebar'    => esc_html__( 'No Sidebar', 'telegram' ),
			),
		'type'     => 'select',
		'priority' => 170,
	)
);


/*content excerpt in global*/
$wp_customize->add_setting( 'excerpt_length_global',
	array(
		'default'           => $default['excerpt_length_global'],
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'telegram_sanitize_positive_integer',
	)
);
$wp_customize->add_control( 'excerpt_length_global',
	array(
		'label'    => esc_html__( 'Set Global Archive Length', 'telegram' ),
		'section'  => 'theme_option_section_settings',
		'type'     => 'number',
		'priority' => 175,
		'input_attrs'     => array( 'min' => 1, 'max' => 200, 'style' => 'width: 150px;' ),

	)
);


// Add the layout setting.
$wp_customize->add_setting('select_global_archive_layout',
    array(
        'default' => $default['select_global_archive_layout'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'telegram_sanitize_select',
    )
);
$wp_customize->add_control(new Telegram_Radio_Image_Control($wp_customize, 'select_global_archive_layout',
        array(
            'label' => esc_html__('Select Global Archive Layout', 'telegram'),
            'section' => 'theme_option_section_settings',
            'priority' => 180,
            'choices' => array(
                'twp-archive-layout-1' => esc_url(get_template_directory_uri() . '/assets/images/archive1.png'),
                'twp-archive-layout-2' => esc_url(get_template_directory_uri() . '/assets/images/archive2.png'),
                'twp-archive-layout-3' => esc_url(get_template_directory_uri() . '/assets/images/archive3.png'),
                'twp-archive-layout-4' => esc_url(get_template_directory_uri() . '/assets/images/archive4.png'),
            )
        )
    )
);

// Footer Latest fix post Section.
$wp_customize->add_section('single_page_section_Settings',
    array(
        'title' => esc_html__('Single Post/page Section Options', 'telegram'),
        'priority' => 100,
        'capability' => 'edit_theme_options',
        'panel' => 'theme_option_panel',
    )
);
// Setting - enable_except_on_single_post.
$wp_customize->add_setting('enable_except_on_single_post',
    array(
        'default' => $default['enable_except_on_single_post'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'telegram_sanitize_checkbox',
    )
);
$wp_customize->add_control('enable_except_on_single_post',
    array(
        'label' => esc_html__('Enable Excerpt on Single Post/page', 'telegram'),
        'section' => 'single_page_section_Settings',
        'type' => 'checkbox',
        'priority' => 100,
    )
);

// Setting - enable_authro_detail_single_page.
$wp_customize->add_setting('enable_authro_detail_single_page',
    array(
        'default' => $default['enable_authro_detail_single_page'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'telegram_sanitize_checkbox',
    )
);
$wp_customize->add_control('enable_authro_detail_single_page',
    array(
        'label' => esc_html__('Enable Author Details on Single Post/page', 'telegram'),
        'section' => 'single_page_section_Settings',
        'type' => 'checkbox',
        'priority' => 100,
    )
);

// Setting - enable_related_post_on_single_page.
$wp_customize->add_setting('enable_related_post_on_single_page',
    array(
        'default' => $default['enable_related_post_on_single_page'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'telegram_sanitize_checkbox',
    )
);
$wp_customize->add_control('enable_related_post_on_single_page',
    array(
        'label' => esc_html__('Enable Related Post on Single Post/page', 'telegram'),
        'section' => 'single_page_section_Settings',
        'type' => 'checkbox',
        'priority' => 100,
    )
);
// Setting single_related_post_title.
$wp_customize->add_setting( 'single_related_post_title',
	array(
	'default'           => $default['single_related_post_title'],
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
	)
);
$wp_customize->add_control( 'single_related_post_title',
	array(
	'label'    => __( 'Title for Single Post/page Related Post', 'telegram' ),
	'section'  => 'single_page_section_Settings',
	'type'     => 'text',
	'priority' => 100,
	)
);
/*No of related post*/
$wp_customize->add_setting('number_of_single_related_post',
    array(
        'default'           => $default['number_of_single_related_post'],
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'telegram_sanitize_positive_integer',
    )
);
$wp_customize->add_control('number_of_single_related_post',
    array(
        'label'       => esc_html__('Select no of Related Post (max-8)', 'telegram'),
        'section'     => 'single_page_section_Settings',
        'type'        => 'number',
        'priority'    => 110,
        'input_attrs' => array('min' => 1, 'max' => 8, 'style' => 'width: 150px;'),

    )
);

// Pagination Section.
$wp_customize->add_section( 'pagination_section',
	array(
	'title'      => __( 'Pagination Options', 'telegram' ),
	'priority'   => 110,
	'capability' => 'edit_theme_options',
	'panel'      => 'theme_option_panel',
	)
);

// Setting pagination_type.
$wp_customize->add_setting( 'pagination_type',
	array(
	'default'           => $default['pagination_type'],
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'telegram_sanitize_select',
	)
);
$wp_customize->add_control( 'pagination_type',
	array(
	'label'       => __( 'Pagination Type', 'telegram' ),
	'section'     => 'pagination_section',
	'type'        => 'select',
	'choices'               => array(
		'default' => __( 'Default (Older / Newer Post)', 'telegram' ),
		'numeric' => __( 'Numeric', 'telegram' ),
	    ),
	'priority'    => 100,
	)
);

// Footer Section.
$wp_customize->add_section( 'footer_section',
	array(
	'title'      => __( 'Footer Options', 'telegram' ),
	'priority'   => 130,
	'capability' => 'edit_theme_options',
	'panel'      => 'theme_option_panel',
	)
);

// Add the layout setting.
$wp_customize->add_setting('footer_widget_layout',
    array(
        'default' => $default['footer_widget_layout'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'telegram_sanitize_select',
    )
);
$wp_customize->add_control(new Telegram_Radio_Image_Control($wp_customize, 'footer_widget_layout',
        array(
            'label' => esc_html__('Footer Widget Layout', 'telegram'),
            'section' => 'footer_section',
            'priority' => 100,
            'choices' => array(
                'twp-footer-widget-style-1' => esc_url(get_template_directory_uri() . '/assets/images/footer1.png'),
                'twp-footer-widget-style-2' => esc_url(get_template_directory_uri() . '/assets/images/footer2.png'),
                'twp-footer-widget-style-3' => esc_url(get_template_directory_uri() . '/assets/images/footer3.png'),
            )
        )
    )
);

// Setting copyright_text.
$wp_customize->add_setting( 'copyright_text',
	array(
	'default'           => $default['copyright_text'],
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
	)
);
$wp_customize->add_control( 'copyright_text',
	array(
	'label'    => __( 'Footer Copyright Text', 'telegram' ),
	'section'  => 'footer_section',
	'type'     => 'text',
	'priority' => 120,
	)
);


// Breadcrumb Section.
$wp_customize->add_section( 'breadcrumb_section',
	array(
	'title'      => __( 'Breadcrumb Options', 'telegram' ),
	'priority'   => 120,
	'capability' => 'edit_theme_options',
	'panel'      => 'theme_option_panel',
	)
);

// Setting breadcrumb_type.
$wp_customize->add_setting( 'breadcrumb_type',
	array(
	'default'           => $default['breadcrumb_type'],
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'telegram_sanitize_select',
	)
);
$wp_customize->add_control( 'breadcrumb_type',
	array(
	'label'       => __( 'Breadcrumb Type', 'telegram' ),
	'description' => sprintf( __( 'Advanced: Requires %1$sBreadcrumb NavXT%2$s plugin', 'telegram' ), '<a href="https://wordpress.org/plugins/breadcrumb-navxt/" target="_blank">','</a>' ),
	'section'     => 'breadcrumb_section',
	'type'        => 'select',
	'choices'               => array(
		'disabled' => __( 'Disabled', 'telegram' ),
		'simple' => __( 'Simple', 'telegram' ),
		'advanced' => __( 'Advanced', 'telegram' ),
	    ),
	'priority'    => 100,
	)
);


// Preloader Section.
$wp_customize->add_section('enable_preloader_option',
    array(
        'title' => __('Preloader Options', 'telegram'),
        'priority' => 120,
        'capability' => 'edit_theme_options',
        'panel' => 'theme_option_panel',
    )
);

// Setting enable_preloader.
$wp_customize->add_setting('enable_preloader',
    array(
        'default' => $default['enable_preloader'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'telegram_sanitize_checkbox',
    )
);
$wp_customize->add_control('enable_preloader',
    array(
        'label' => __('Enable Preloader', 'telegram'),
        'section' => 'enable_preloader_option',
        'type' => 'checkbox',
        'priority' => 150,
    )
);
