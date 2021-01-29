<?php 
/**
 * Telegram Theme Customizer.
 *
 * @package Telegram
 */

//customizer core option
require get_template_directory() . '/inc/customizer/core/customizer-core.php';

//customizer 
require get_template_directory() . '/inc/customizer/core/default.php';
/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function telegram_customize_register( $wp_customize ) {

	// Load custom controls.
	require get_template_directory() . '/inc/customizer/core/control.php';

	// Load customize sanitize.
	require get_template_directory() . '/inc/customizer/core/sanitize.php';

	// Load customize callback.
	require get_template_directory() . '/inc/customizer/core/callback.php';

	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';

	$wp_customize->get_section( 'title_tagline' )->panel = 'general_setting';

	$wp_customize->get_section( 'header_image' )->panel = 'general_setting';
    $wp_customize->get_section( 'header_image' )->priority = '20';  

	$wp_customize->get_section( 'colors' )->panel = 'general_setting';
    $wp_customize->get_section( 'colors' )->priority = '20';  

	$wp_customize->get_section( 'background_image' )->panel = 'general_setting';
	$wp_customize->get_section( 'header_image' )->priority = '25';  

	/*theme option panel details*/
	require get_template_directory() . '/inc/customizer/theme-option.php';	


	// Register custom section types.
	$wp_customize->register_section_type( 'Telegram_Customize_Section_Upsell' );

	// Register sections.
	$wp_customize->add_section(
		new Telegram_Customize_Section_Upsell(
			$wp_customize,
			'theme_upsell',
			array(
				'title'    => esc_html__( 'Telegram Pro', 'telegram' ),
				'pro_text' => esc_html__( 'Upgrade To Pro', 'telegram' ),
				'pro_url'  => 'https://www.themeinwp.com/theme/telegram-pro/',
				'priority'  => 1,
			)
		)
	);
}
add_action( 'customize_register', 'telegram_customize_register' );


/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 *
 * @since 1.0.0
 */
function telegram_customize_preview_js() {

	wp_enqueue_script( 'telegram_customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), '20130508', true );

}
add_action( 'customize_preview_init', 'telegram_customize_preview_js' );


function telegram_customizer_css() {
	wp_enqueue_script('telegram_customize_admin_js', get_template_directory_uri().'/assets/js/customizer-admin.js', array('customize-controls'));

	wp_enqueue_style( 'telegram_customize_controls', get_template_directory_uri() . '/assets/css/customizer-controll.css' );
}
add_action( 'customize_controls_enqueue_scripts', 'telegram_customizer_css',0 );
