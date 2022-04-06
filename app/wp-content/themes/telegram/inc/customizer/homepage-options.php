<?php
/**
 * carousel section
 *
 * @package Telegram
 */

$default = telegram_get_default_theme_options();
// Add Homepage Options Panel.
$wp_customize->add_panel( 'general_setting',
    array(
        'title'      => esc_html__( 'General Settings Options', 'telegram' ),
        'priority'   => 100,
        'capability' => 'edit_theme_options',
    )
);

// Top Header Add Section.
$wp_customize->add_section( 'site_header_add_section_settings',
	array(
		'title'      => __( 'Top Header Add Section', 'telegram' ),
		'priority'   => 90,
		'capability' => 'edit_theme_options',
		'panel'      => 'homepage_option_panel',
	)
);

// Setting - show_site_header_addvertisement_section.
$wp_customize->add_setting( 'show_site_header_addvertisement_section',
	array(
		'default'           => $default['show_site_header_addvertisement_section'],
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'telegram_sanitize_checkbox',
	)
);
$wp_customize->add_control( 'show_site_header_addvertisement_section',
	array(
		'label'    => __( 'Enable Top Header Advertisement', 'telegram' ),
		'section'  => 'site_header_add_section_settings',
		'type'     => 'checkbox',
		'priority' => 100,
	)
);

// Setting site_header_section_advertisement.
$wp_customize->add_setting('site_header_section_advertisement',
	array(
		'default'           => $default['site_header_section_advertisement'],
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'telegram_sanitize_image',
	)
);
$wp_customize->add_control(
	new WP_Customize_Image_Control($wp_customize, 'site_header_section_advertisement',
		array(
			'label'       => esc_html__('Site Header Advertisement', 'telegram'),
			'description' => sprintf(esc_html__('Recommended Size %1$s px X %2$s px', 'telegram'), 970, 90),
			'section'     => 'site_header_add_section_settings',
			'priority'    => 120,
		)
	)
);

/*site_header_advertisement_url*/
$wp_customize->add_setting('site_header_advertisement_url',
	array(
		'default'           => $default['site_header_advertisement_url'],
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'esc_url_raw',
	)
);
$wp_customize->add_control('site_header_advertisement_url',
	array(
		'label'    => esc_html__('Site Header Advertisement URL', 'telegram'),
		'section'  => 'site_header_add_section_settings',
		'type'     => 'text',
		'priority' => 130,
	)
);

// Top Header Section.
$wp_customize->add_section( 'top_header_section_settings',
	array(
		'title'      => __( 'Top Header Section', 'telegram' ),
		'priority'   => 90,
		'capability' => 'edit_theme_options',
		'panel'      => 'homepage_option_panel',
	)
);

// Setting - show_date_section.
$wp_customize->add_setting( 'show_date_section',
	array(
		'default'           => $default['show_date_section'],
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'telegram_sanitize_checkbox',
	)
);
$wp_customize->add_control( 'show_date_section',
	array(
		'label'    => __( 'Enable Header Date', 'telegram' ),
		'section'  => 'top_header_section_settings',
		'type'     => 'checkbox',
	)
);


$wp_customize->add_setting('top_header_tags',
    array(
        'default' => $default['top_header_tags'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'telegram_sanitize_checkbox',
    )
);
$wp_customize->add_control('top_header_tags',
    array(
        'label' => esc_html__('Enable Top Header Tags', 'telegram'),
        'section' => 'top_header_section_settings',
        'type' => 'checkbox',
    )
);

$wp_customize->add_setting('top_header_tags_title',
    array(
        'default' => $default['top_header_tags_title'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_text_field',
    )
);
$wp_customize->add_control('top_header_tags_title',
    array(
        'label' => esc_html__('Top Header Tags Title', 'telegram'),
        'section' => 'top_header_section_settings',
        'type' => 'text',
    )
);

$wp_customize->add_setting('top_header_tags_count',
    array(
        'default' => $default['top_header_tags_count'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'absint',
    )
);
$wp_customize->add_control('top_header_tags_count',
    array(
        'label' => esc_html__('Number Of Tags To Display', 'telegram'),
        'section' => 'top_header_section_settings',
        'type' => 'number',
    )
);

// Setting - show_top_social_section.
$wp_customize->add_setting( 'show_top_social_section',
	array(
		'default'           => $default['show_top_social_section'],
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'telegram_sanitize_checkbox',
	)
);
$wp_customize->add_control( 'show_top_social_section',
	array(
		'label'    => __( 'Enable Social Menu', 'telegram' ),
		'section'  => 'top_header_section_settings',
		'type'     => 'checkbox',
		'priority' => 100,
	)
);

// Top Header Add Section.
$wp_customize->add_section( 'top_header_add_section_settings',
	array(
		'title'      => __( 'Header Banner Add Section', 'telegram' ),
		'priority'   => 90,
		'capability' => 'edit_theme_options',
		'panel'      => 'homepage_option_panel',
	)
);

// Setting - show_addvertisement_section.
$wp_customize->add_setting( 'show_addvertisement_section',
	array(
		'default'           => $default['show_addvertisement_section'],
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'telegram_sanitize_checkbox',
	)
);
$wp_customize->add_control( 'show_addvertisement_section',
	array(
		'label'    => __( 'Enable Header Advertisement', 'telegram' ),
		'section'  => 'top_header_add_section_settings',
		'type'     => 'checkbox',
		'priority' => 100,
	)
);

// Setting top_section_advertisement.
$wp_customize->add_setting('top_section_advertisement',
	array(
		'default'           => $default['top_section_advertisement'],
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'telegram_sanitize_image',
	)
);
$wp_customize->add_control(
	new WP_Customize_Image_Control($wp_customize, 'top_section_advertisement',
		array(
			'label'       => esc_html__('Top Section Advertisement', 'telegram'),
			'description' => sprintf(esc_html__('Recommended Size %1$s px X %2$s px', 'telegram'), 728, 90),
			'section'     => 'top_header_add_section_settings',
			'priority'    => 120,
			'active_callback' => 'telegram_addvertisement_section_callback',
		)
	)
);

/*top_section_advertisement_url*/
$wp_customize->add_setting('top_section_advertisement_url',
	array(
		'default'           => $default['top_section_advertisement_url'],
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'esc_url_raw',
	)
);
$wp_customize->add_control('top_section_advertisement_url',
	array(
		'label'    => esc_html__('URL Link', 'telegram'),
		'section'  => 'top_header_add_section_settings',
		'type'     => 'text',
		'priority' => 130,
		'active_callback' => 'telegram_addvertisement_section_callback',
	)
);

// Main Header Section.
$wp_customize->add_section( 'main_header_section_settings',
	array(
		'title'      => __( 'Menu Bar setting', 'telegram' ),
		'priority'   => 100,
		'capability' => 'edit_theme_options',
		'panel'      => 'homepage_option_panel',
	)
);


// Setting - show_theme_mode_switcher.
$wp_customize->add_setting( 'show_theme_mode_switcher',
	array(
		'default'           => $default['show_theme_mode_switcher'],
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'telegram_sanitize_checkbox',
	)
);
$wp_customize->add_control( 'show_theme_mode_switcher',
	array(
		'label'    => __( 'Enable Theme (Dark/Light)Mode Switcher', 'telegram' ),
		'section'  => 'main_header_section_settings',
		'type'     => 'checkbox',
		'priority' => 100,
	)
);

/*site  Layout*/
$wp_customize->add_setting( 'site_default_mode_option',
	array(
		'default'           => $default['site_default_mode_option'],
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'telegram_sanitize_select',
	)
);
$wp_customize->add_control( 'site_default_mode_option',
	array(
		'label'    => esc_html__( 'Select Default Site Mode', 'telegram' ),
		'section'  => 'main_header_section_settings',
		'choices'  => array(
                'day-mode' => __( 'Light Mode', 'telegram' ),
                'night-mode' => __( 'Dark Mode', 'telegram' ),
		    ),
		'type'     => 'select',
		'priority' => 100,
	)
);

// Setting - show_search_icon_on_nav.
$wp_customize->add_setting('show_search_icon_on_nav',
	array(
		'default'           => $default['show_search_icon_on_nav'],
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'telegram_sanitize_checkbox',
	)
);
$wp_customize->add_control('show_search_icon_on_nav',
	array(
		'label'       => esc_html__('Enable Search Icon On Menu', 'telegram'),
		'section'     => 'main_header_section_settings',
		'type'        => 'checkbox',
		'priority'    => 150,
	)
);
// Setting - show_off_canvss_section.
$wp_customize->add_setting( 'show_off_canvss_section',
	array(
		'default'           => $default['show_off_canvss_section'],
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'telegram_sanitize_checkbox',
	)
);
$wp_customize->add_control( 'show_off_canvss_section',
	array(
		'label'    => __( 'Enable Off Canvass Section', 'telegram' ),
		'section'  => 'main_header_section_settings',
		'type'     => 'checkbox',
		'priority' => 150,
	)
);

// Setting enable_mailchimp_subscription.
$wp_customize->add_setting('enable_mailchimp_subscription',
    array(
        'default' => $default['enable_mailchimp_subscription'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'telegram_sanitize_checkbox',
    )
);
$wp_customize->add_control('enable_mailchimp_subscription',
    array(
        'label' => __('Enable Mailchimp Subscription', 'telegram'),
        'section' => 'main_header_section_settings',
        'type' => 'checkbox',
        'priority' => 160,
    )
);

// Setting mailchimp_subscription_image.
$wp_customize->add_setting('mailchimp_subscription_image',
	array(
		'default'           => $default['mailchimp_subscription_image'],
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'telegram_sanitize_image',
	)
);
$wp_customize->add_control(
	new WP_Customize_Image_Control($wp_customize, 'mailchimp_subscription_image',
		array(
			'label'       => esc_html__('Mailchimp Subscription Image', 'telegram'),
			'description' => sprintf(esc_html__('Recommended Size %1$s px X %2$s px', 'telegram'), 640, 360),
			'section'     => 'main_header_section_settings',
			'priority'    => 160,
		)
	)
);

// Setting mailchimp_subscription_title.
$wp_customize->add_setting( 'mailchimp_subscription_title',
	array(
	'default'           => $default['mailchimp_subscription_title'],
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
	)
);
$wp_customize->add_control( 'mailchimp_subscription_title',
	array(
	'label'    => __( 'Mailchimp Subscription Title', 'telegram' ),
	'section'  => 'main_header_section_settings',
	'type'     => 'text',
	'priority' => 160,
	)
);

// Setting mailchimp_subscription_shortcode.
$wp_customize->add_setting( 'mailchimp_subscription_shortcode',
	array(
	'default'           => $default['mailchimp_subscription_shortcode'],
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
	)
);
$wp_customize->add_control( 'mailchimp_subscription_shortcode',
	array(
	'label'    => __( 'Mailchimp Subscription Shortcode', 'telegram' ),
	'section'  => 'main_header_section_settings',
	'type'     => 'text',
	'priority' => 160,
	)
);



// Setting - enable_header_fix_nav.
$wp_customize->add_setting( 'enable_header_fix_nav',
	array(
		'default'           => $default['enable_header_fix_nav'],
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'telegram_sanitize_checkbox',
	)
);
$wp_customize->add_control( 'enable_header_fix_nav',
	array(
		'label'    => __( 'Enable Fix Navigation', 'telegram' ),
		'section'  => 'main_header_section_settings',
		'type'     => 'checkbox',
		'priority' => 150,
	)
);

// Breaking News Section.
$wp_customize->add_section( 'breaking_news_section_settings',
	array(
		'title'      => __( 'Breaking News Section', 'telegram' ),
		'priority'   => 100,
		'capability' => 'edit_theme_options',
		'panel'      => 'homepage_option_panel',
	)
);

// Setting - show_breaking_on_nav.
$wp_customize->add_setting('show_breaking_on_nav',
	array(
		'default'           => $default['show_breaking_on_nav'],
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'telegram_sanitize_checkbox',
	)
);
$wp_customize->add_control('show_breaking_on_nav',
	array(
		'label'       => esc_html__('Enable Scrolling News', 'telegram'),
		'section'     => 'breaking_news_section_settings',
		'type'        => 'checkbox',
		'priority'    => 140,
	)
);

$wp_customize->add_setting( 'breaking_section_title',
	array(
		'default'           => $default['breaking_section_title'],
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'sanitize_text_field',
	)
);
$wp_customize->add_control( 'breaking_section_title',
	array(
		'label'    => __( 'Breaking Section Title', 'telegram' ),
		'section'  => 'breaking_news_section_settings',
		'type'     => 'text',
		'priority' => 150,
	)
);


// Setting - drop down category for carousel.
$wp_customize->add_setting( 'select_category_for_breaking_section',
	array(
		'default'           => $default['select_category_for_breaking_section'],
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'absint',
	)
);
$wp_customize->add_control( new Telegram_Dropdown_Taxonomies_Control( $wp_customize, 'select_category_for_breaking_section',
	array(
        'label'           => __( 'Category for Breaking Section', 'telegram' ),
        'description'     => __( 'Select category to be shown on breaking section on click', 'telegram' ),
        'section'         => 'breaking_news_section_settings',
        'type'            => 'dropdown-taxonomies',
        'taxonomy'        => 'category',
		'priority'    	  => 150,

    ) ) );

/*shortby Layout*/
$wp_customize->add_setting( 'sort_breaking_post_by',
	array(
		'default'           => $default['sort_breaking_post_by'],
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'telegram_sanitize_select',
	)
);
$wp_customize->add_control( 'sort_breaking_post_by',
	array(
		'label'    => esc_html__( 'Short Breaking Post By', 'telegram' ),
		'section'  => 'breaking_news_section_settings',
		'choices'  => array(
                'date' => __( 'Latest Post', 'telegram' ),
                'rand' => __( 'Random Post', 'telegram' ),
                'comment_count' => __( 'Most Commented', 'telegram' ),
		    ),
		'type'     => 'select',
		'priority' => 150,
	)
);


// Grid Main Section.
$wp_customize->add_section( 'grid_section_settings',
	array(
		'title'      => __( 'Blog/News Grid Section', 'telegram' ),
		'priority'   => 110,
		'capability' => 'edit_theme_options',
		'panel'      => 'homepage_option_panel',
	)
);

// Setting - show_grid_section.
$wp_customize->add_setting( 'show_grid_section',
	array(
		'default'           => $default['show_grid_section'],
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'telegram_sanitize_checkbox',
	)
);
$wp_customize->add_control( 'show_grid_section',
	array(
		'label'    => __( 'Enable Grid', 'telegram' ),
		'section'  => 'grid_section_settings',
		'type'     => 'checkbox',
		'priority' => 100,
	)
);
// Setting grid_section_background_image.
$wp_customize->add_setting('grid_section_background_image',
	array(
		'default'           => $default['grid_section_background_image'],
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'telegram_sanitize_image',
	)
);
$wp_customize->add_control(
	new WP_Customize_Image_Control($wp_customize, 'grid_section_background_image',
		array(
			'label'       => esc_html__('Grid Section Background Image', 'telegram'),
			'description' => sprintf(esc_html__('Recommended Size %1$s px X %2$s px', 'telegram'), 1920, 1080),
			'section'     => 'grid_section_settings',
			'priority'    => 100,
		)
	)
);
$wp_customize->add_setting( 'heading_text_on_grid',
	array(
		'default'           => $default['heading_text_on_grid'],
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'sanitize_text_field',
	)
);
$wp_customize->add_control( 'heading_text_on_grid',
	array(
		'label'    => __( 'Section Title', 'telegram' ),
		'section'  => 'grid_section_settings',
		'type'     => 'text',
		'priority' => 100,
	)
);

/*No of Grid*/
$wp_customize->add_setting( 'number_of_home_grid',
	array(
		'default'           => $default['number_of_home_grid'],
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'telegram_sanitize_positive_integer',
	)
);
$wp_customize->add_control( 'number_of_home_grid',
	array(
		'label'    => __( 'Select no of grid', 'telegram' ),
		'section'  => 'grid_section_settings',
		'input_attrs'     => array( 'min' => 1, 'max' => 12, 'style' => 'width: 150px;' ),

		'type'     => 'number',
		'priority' => 105,
	)
);


// Setting - drop down category for grid.
$wp_customize->add_setting( 'select_category_for_grid',
	array(
		'default'           => $default['select_category_for_grid'],
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'absint',
	)
);
$wp_customize->add_control( new Telegram_Dropdown_Taxonomies_Control( $wp_customize, 'select_category_for_grid',
	array(
        'label'           => __( 'Category for Grid Section', 'telegram' ),
        'description'     => __( 'Select category to be shown on Grid bellow slider ', 'telegram' ),
        'section'         => 'grid_section_settings',
        'type'            => 'dropdown-taxonomies',
        'taxonomy'        => 'category',
		'priority'    	  => 130,

    ) ) );



// Featured Blog Main Section.
$wp_customize->add_section( 'list_post_with_add_section_settings',
	array(
		'title'      => __( 'Blog/News List With Fix Advertise', 'telegram' ),
		'priority'   => 120,
		'capability' => 'edit_theme_options',
		'panel'      => 'homepage_option_panel',
	)
);

// Setting - show_list_section_with_add.
$wp_customize->add_setting( 'show_list_section_with_add',
	array(
		'default'           => $default['show_list_section_with_add'],
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'telegram_sanitize_checkbox',
	)
);
$wp_customize->add_control( 'show_list_section_with_add',
	array(
		'label'    => __( 'Enable List Section with Advertise', 'telegram' ),
		'section'  => 'list_post_with_add_section_settings',
		'type'     => 'checkbox',
		'priority' => 110,
	)
);

$wp_customize->add_setting( 'heading_text_on_list_post_add',
	array(
		'default'           => $default['heading_text_on_list_post_add'],
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'sanitize_text_field',
	)
);
$wp_customize->add_control( 'heading_text_on_list_post_add',
	array(
		'label'    => __( 'Section Title', 'telegram' ),
		'section'  => 'list_post_with_add_section_settings',
		'type'     => 'text',
		'priority' => 100,
	)
);

// Setting - drop down category for featured_blog.
	$wp_customize->add_setting( 'select_category_for_list_post_section',
		array(
			'default'           => $default['select_category_for_list_post_section'],
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'absint',
		)
	);
	$wp_customize->add_control( new Telegram_Dropdown_Taxonomies_Control( $wp_customize, 'select_category_for_list_post_section',
		array(
	        'label'           => __( 'Category for List Post', 'telegram' ),
	        'section'         => 'list_post_with_add_section_settings',
	        'type'            => 'dropdown-taxonomies',
	        'taxonomy'        => 'category',
			'priority'    	  => 130,

	    ) ) );


$wp_customize->add_setting( 'number_of_list_post',
	array(
		'default'           => $default['number_of_list_post'],
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'telegram_sanitize_positive_integer',
	)
);
$wp_customize->add_control( 'number_of_list_post',
	array(
		'label'    => __( 'Number of Post to List', 'telegram' ),
		'section'  => 'list_post_with_add_section_settings',
		'input_attrs'     => array( 'min' => 1, 'max' => 12, 'style' => 'width: 150px;' ),

		'type'     => 'number',
		'priority' => 130,
	)
);


// Setting - show_list_post_add_section.
$wp_customize->add_setting( 'show_list_post_add_section',
	array(
		'default'           => $default['show_list_post_add_section'],
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'telegram_sanitize_checkbox',
	)
);
$wp_customize->add_control( 'show_list_post_add_section',
	array(
		'label'    => __( 'Enable List Post Advertisement', 'telegram' ),
		'section'  => 'list_post_with_add_section_settings',
		'type'     => 'checkbox',
		'priority' => 130,
	)
);

// Setting list_post_section_advertisement.
$wp_customize->add_setting('list_post_section_advertisement',
	array(
		'default'           => $default['list_post_section_advertisement'],
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'telegram_sanitize_image',
	)
);
$wp_customize->add_control(
	new WP_Customize_Image_Control($wp_customize, 'list_post_section_advertisement',
		array(
			'label'       => esc_html__('List Post Section Advertisement', 'telegram'),
			'description' => sprintf(esc_html__('Recommended Size %1$s px X %2$s px', 'telegram'), 300, 1050),
			'section'     => 'list_post_with_add_section_settings',
			'priority'    => 130,
		)
	)
);

/*list_post_advertisement_url*/
$wp_customize->add_setting('list_post_advertisement_url',
	array(
		'default'           => $default['list_post_advertisement_url'],
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'esc_url_raw',
	)
);
$wp_customize->add_control('list_post_advertisement_url',
	array(
		'label'    => esc_html__('List Post Advertisement URL', 'telegram'),
		'section'  => 'list_post_with_add_section_settings',
		'type'     => 'text',
		'priority' => 130,
	)
);



// Featured carousel  Section.
$wp_customize->add_section( 'carousel_slider_section_settings',
	array(
		'title'      => __( 'Carousel Slider Section', 'telegram' ),
		'priority'   => 120,
		'capability' => 'edit_theme_options',
		'panel'      => 'homepage_option_panel',
	)
);

// Setting - show_carousel_slider_section.
$wp_customize->add_setting( 'show_carousel_slider_section',
	array(
		'default'           => $default['show_carousel_slider_section'],
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'telegram_sanitize_checkbox',
	)
);
$wp_customize->add_control( 'show_carousel_slider_section',
	array(
		'label'    => __( 'Enable Carousel Slider Section', 'telegram' ),
		'section'  => 'carousel_slider_section_settings',
		'type'     => 'checkbox',
		'priority' => 110,
	)
);

$wp_customize->add_setting('carousel_slider_section_title',
	array(
		'default'           => $default['carousel_slider_section_title'],
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'sanitize_text_field',
	)
);
$wp_customize->add_control('carousel_slider_section_title',
	array(
		'label'       => esc_html__('Slider Section Title', 'telegram'),
		'section'     => 'carousel_slider_section_settings',
		'type'        => 'text',
		'priority'    => 110,
	)
);
$wp_customize->add_setting( 'number_of_post_on_carousle_slider',
	array(
		'default'           => $default['number_of_post_on_carousle_slider'],
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'telegram_sanitize_positive_integer',
	)
);
$wp_customize->add_control( 'number_of_post_on_carousle_slider',
	array(
		'label'    => __( 'Number of Post on Carousel', 'telegram' ),
		'section'  => 'carousel_slider_section_settings',
		'input_attrs'     => array( 'min' => 1, 'max' => 12, 'style' => 'width: 150px;' ),

		'type'     => 'number',
		'priority' => 110,
	)
);

$wp_customize->add_setting( 'select_category_on_carousel',
	array(
		'default'           => $default['select_category_on_carousel'],
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'absint',
	)
);
$wp_customize->add_control( new Telegram_Dropdown_Taxonomies_Control( $wp_customize, 'select_category_on_carousel',
	array(
        'label'           => __( 'Category for Carousel Slider', 'telegram' ),
        'section'         => 'carousel_slider_section_settings',
        'type'            => 'dropdown-taxonomies',
        'taxonomy'        => 'category',
		'priority'    	  => 120,

    ) ) );