<?php
/**
 * Default theme options.
 *
 * @package Telegram
 */

if ( ! function_exists( 'telegram_get_default_theme_options' ) ) :

	/**
	 * Get default theme options
	 *
	 * @since 1.0.0
	 *
	 * @return array Default theme options.
	 */
	function telegram_get_default_theme_options() {

		$defaults = array();

		//home page section
		$defaults['show_selected_page_content_on_homepage']		= 1;
		$defaults['show_latest_post_content_on_homepage']		= 1;

		$defaults['show_site_header_addvertisement_section']				= 0;
		$defaults['site_header_section_advertisement']					= '';
		$defaults['site_header_advertisement_url']				= '#';
		// header top bar
		$defaults['show_date_section']		= 1;
		$defaults['top_header_tags']		= 1;
		$defaults['top_header_tags_title']				        = esc_html__( 'Trending Tags', 'telegram' );
		$defaults['top_header_tags_count']		= 8;


		
		$defaults['enable_mailchimp_subscription']  		 		= 1;
		$defaults['mailchimp_subscription_title']				= esc_html__( 'Subscribe US Now', 'telegram' );
		$defaults['mailchimp_subscription_image']					= '';
		$defaults['mailchimp_subscription_shortcode']   			= '';

		//header top bar
		$defaults['show_addvertisement_section']				= 0;
		$defaults['top_section_advertisement']					= '';
		$defaults['top_section_advertisement_url']				= '#';
		$defaults['show_top_social_section']				    = 1;
		$defaults['show_breaking_on_nav']						= 0;
		$defaults['breaking_section_title']				        = esc_html__( 'Breaking News', 'telegram' );
		$defaults['select_category_for_breaking_section']		= '';
		$defaults['sort_breaking_post_by']						= 'date';
		$defaults['show_search_icon_on_nav']					= 1;
		$defaults['show_off_canvss_section']					= 1;
		$defaults['show_theme_mode_switcher']					= 1;
		$defaults['enable_header_fix_nav']						= 1;
		$defaults['site_default_mode_option']					= 'day-mode';

		// Slider options.
		$defaults['show_main_banner_section']				    = 0;
		$defaults['select_category_for_slider_section']			= '';
		$defaults['number_of_home_slider']						= 4;
		$defaults['telegram_recent_post_section_title']			= esc_html__( 'Recent Stories', 'telegram' );
		$defaults['select_category_for_recent_post']			= '';
		$defaults['number_of_home_recent_post']					= 8;		
		$defaults['select_category_for_counter_list_section']	= '';

		// grid section options.
		$defaults['show_grid_section']						= 0;
		$defaults['grid_section_background_image']			= '';
		$defaults['number_of_home_grid']					= 8;
		$defaults['heading_text_on_grid']					= esc_html__( 'Breaking This Week', 'telegram' );
		$defaults['select_category_for_grid']				= 0;

		// Single post options.
		$defaults['enable_except_on_single_post'] 			= 1;
		$defaults['enable_authro_detail_single_page'] 		= 1;
		$defaults['enable_related_post_on_single_page'] 	= 1;
        $defaults['single_related_post_title']              = esc_html__('You May Like', 'telegram');
		$defaults['number_of_single_related_post'] 			= 6;


		// List Post With Add Section
		$defaults['show_list_section_with_add']				= 0;
        $defaults['heading_text_on_list_post_add']         = esc_html__('Editorial Choice', 'telegram');
		$defaults['select_category_for_list_post_section']	= '';
		$defaults['number_of_list_post']					= 7;
		$defaults['show_list_post_add_section']				= 1;
		$defaults['list_post_section_advertisement']		= '';
		$defaults['list_post_advertisement_url']			= '#';


		// Editorial Featured News section
		$defaults['show_carousel_slider_section']			= 0;
        $defaults['carousel_slider_section_title']         	= esc_html__('Carousel Slider', 'telegram');
		$defaults['number_of_post_on_carousle_slider']		= 10;
		$defaults['select_category_on_carousel']			= '';

		//Layout options.
		$defaults['site_date_layout_option']		= 'in-time-span';
		$defaults['global_layout']					= 'right-sidebar';
		$defaults['excerpt_length_global']			= 50;
		$defaults['select_global_archive_layout']	= 'twp-archive-layout-1';
		$defaults['pagination_type']				= 'numeric';
	
		$defaults['enable_copyright_credit']     	= 1;
		$defaults['copyright_text']					= esc_html__( 'Copyright All right reserved', 'telegram' );
		$defaults['footer_widget_layout']			= 'twp-footer-widget-style-1';
		$defaults['breadcrumb_type']				= 'simple';
		$defaults['enable_preloader']				= 1;

		

		// Pass through filter.
		$defaults = apply_filters( 'telegram_filter_default_theme_options', $defaults );

		return $defaults;

	}

endif;
