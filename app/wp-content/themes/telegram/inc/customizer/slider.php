<?php
/**
 * slider section
 *
 * @package Telegram
 */

$default = telegram_get_default_theme_options();
// Slider Main Section.
$wp_customize->add_section( 'main_banner_section_settings',
	array(
		'title'      => __( 'Main Banner Section', 'telegram' ),
		'priority'   => 110,
		'capability' => 'edit_theme_options',
		'panel'      => 'homepage_option_panel',
	)
);

// Setting - show_main_banner_section.
$wp_customize->add_setting( 'show_main_banner_section',
	array(
		'default'           => $default['show_main_banner_section'],
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'telegram_sanitize_checkbox',
	)
);
$wp_customize->add_control( 'show_main_banner_section',
	array(
		'label'    => __( 'Enable Main Banner', 'telegram' ),
		'section'  => 'main_banner_section_settings',
		'type'     => 'checkbox',
		'priority' => 100,
	)
);

$wp_customize->add_setting( 'telegram_recent_post_section_title',
	array(
		'default'           => $default['telegram_recent_post_section_title'],
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'sanitize_text_field',
	)
);
$wp_customize->add_control( 'telegram_recent_post_section_title',
	array(
		'label'    => __( 'Recent Post Section Title Text', 'telegram' ),
		'section'  => 'main_banner_section_settings',
		'type'     => 'text',
		'priority' => 100,
	)
);

// Setting - drop down category for slider.
$wp_customize->add_setting( 'select_category_for_recent_post',
	array(
		'default'           => $default['select_category_for_recent_post'],
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'absint',
	)
);
$wp_customize->add_control( new Telegram_Dropdown_Taxonomies_Control( $wp_customize, 'select_category_for_recent_post',
	array(
        'label'           => __( 'Category for Recent Post', 'telegram' ),
        'description'     => __( 'Select category to be shown on recent post left of main slider block', 'telegram' ),
        'section'         => 'main_banner_section_settings',
        'type'            => 'dropdown-taxonomies',
        'taxonomy'        => 'category',
		'priority'    	  => 100,

    ) ) );


/*No of Slider*/
$wp_customize->add_setting( 'number_of_home_recent_post',
	array(
		'default'           => $default['number_of_home_recent_post'],
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'telegram_sanitize_positive_integer',
	)
);
$wp_customize->add_control( 'number_of_home_recent_post',
	array(
		'label'    => __( 'Select Number of Post on Recent Post', 'telegram' ),
        'description'     => __( 'Post will be shown on left of slider along with one featured post (Max 10)', 'telegram' ),
		'section'  => 'main_banner_section_settings',
		'type'     => 'number',
		'input_attrs'     => array( 'min' => 1, 'max' => 10, 'style' => 'width: 150px;' ),
		'priority' => 100,
	)
);

// Setting - drop down category for exclusive section.
$wp_customize->add_setting( 'select_category_for_slider_section',
	array(
		'default'           => $default['select_category_for_slider_section'],
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'absint',
	)
);
$wp_customize->add_control( new Telegram_Dropdown_Taxonomies_Control( $wp_customize, 'select_category_for_slider_section',
	array(
        'label'           => __( 'Category for Slider Section', 'telegram' ),
        'description'     => __( 'Select category to be shown on slider section on your banner as well as the recent two featured post ', 'telegram' ),
        'section'         => 'main_banner_section_settings',
        'type'            => 'dropdown-taxonomies',
        'taxonomy'        => 'category',
		'priority'    	  => 100,
    ) ) );


/*No of Slider*/
$wp_customize->add_setting( 'number_of_home_slider',
	array(
		'default'           => $default['number_of_home_slider'],
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'telegram_sanitize_positive_integer',
	)
);
$wp_customize->add_control( 'number_of_home_slider',
	array(
		'label'    => __( 'Select Number of Post on Slider', 'telegram' ),
		'section'  => 'main_banner_section_settings',
		'type'     => 'number',
		'input_attrs'     => array( 'min' => 1, 'max' => 5, 'style' => 'width: 150px;' ),
		'priority' => 100,
	)
);


// Setting - drop down category for exclusive section.
$wp_customize->add_setting( 'select_category_for_counter_list_section',
	array(
		'default'           => $default['select_category_for_counter_list_section'],
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'absint',
	)
);
$wp_customize->add_control( new Telegram_Dropdown_Taxonomies_Control( $wp_customize, 'select_category_for_counter_list_section',
	array(
        'label'           => __( 'Category for Counter List Section', 'telegram' ),
        'description'     => __( 'Select category to be shown on counter list only 5 recent post will be shown', 'telegram' ),
        'section'         => 'main_banner_section_settings',
        'type'            => 'dropdown-taxonomies',
        'taxonomy'        => 'category',
		'priority'    	  => 100,
    ) ) );

