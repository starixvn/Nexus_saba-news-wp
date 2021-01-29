<?php
// Add skin-specific colors and fonts to the custom CSS
if ( ! function_exists( 'fcunited_skin_get_css' ) ) {
	add_filter( 'fcunited_filter_get_css', 'fcunited_skin_get_css', 10, 2 );
	function fcunited_skin_get_css( $css, $args ) {

		if ( isset( $css['fonts'] ) && isset( $args['fonts'] ) ) {
			$fonts         = $args['fonts'];
			$css['fonts'] .= <<<CSS

		.extra-countdown .sp-countdown-wrapper .countdown.sp-countdown,
		.sp-template-countdown .sp-event-name > a:not(.team-logo),
		.sp-template-countdown .sp-event-name > a:not(.team-logo):before,
		.extra-table .sc_table table tr h6 {
			{$fonts['h1_font-family']}
		}
		.sc_item_subtitle {
			{$fonts['p_font-family']}
		}

CSS;
		}

		if ( isset( $css['vars'] ) && isset( $args['vars'] ) ) {
			$vars         = $args['vars'];
			$css['vars'] .= <<<CSS


CSS;
		}

		if ( isset( $css['colors'] ) && isset( $args['colors'] ) ) {
			$colors         = $args['colors'];
			$css['colors'] .= <<<CSS

		.scheme_self {
			color: {$colors['alter_text']};
		}	

		button[disabled],
		input[type="submit"][disabled],
		input[type="button"][disabled],
		button[disabled]:hover,
		input[type="submit"][disabled]:hover,
		input[type="button"][disabled]:hover {
			background-color: {$colors['bd_color']} !important;
			color: {$colors['text']} !important;
		}
		

		.sp-view-all-link > a:hover,
		button:hover,
		button:focus,
		input[type="submit"]:hover,
		input[type="submit"]:focus,
		input[type="reset"]:hover,
		input[type="reset"]:focus,
		input[type="button"]:hover,
		input[type="button"]:focus,
		.post_item .more-link:hover,
		.comments_wrap .form-submit input[type="submit"]:hover,
		.comments_wrap .form-submit input[type="submit"]:focus,
		.wp-block-button:not(.is-style-outline) > .wp-block-button__link:hover,
		.wp-block-button:not(.is-style-outline) > .wp-block-button__link:focus,
		/* BB & Buddy Press */
		#buddypress .comment-reply-link:hover,
		#buddypress .generic-button a:hover,
		#buddypress a.button:hover,
		#buddypress button:hover,
		#buddypress input[type="button"]:hover,
		#buddypress input[type="reset"]:hover,
		#buddypress input[type="submit"]:hover,
		#buddypress ul.button-nav li a:hover,
		a.bp-title-button:hover,
		/* Booked */
		.booked-calendar-wrap .booked-appt-list .timeslot .timeslot-people button:hover,
		body #booked-profile-page .booked-profile-appt-list .appt-block .booked-cal-buttons .google-cal-button > a:hover,
		body #booked-profile-page input[type="submit"]:hover,
		body #booked-profile-page button:hover,
		body .booked-list-view input[type="submit"]:hover,
		body .booked-list-view button:hover,
		body table.booked-calendar input[type="submit"]:hover,
		body table.booked-calendar button:hover,
		body .booked-modal input[type="submit"]:hover,
		body .booked-modal button:hover,
		/* ThemeREX Addons */
		.sc_button_default:hover,
		.sc_button:not(.sc_button_simple):not(.sc_button_bordered):not(.sc_button_bg_image):hover,
		.socials_share:not(.socials_type_drop) .social_icon:hover,
		/* Tour Master */
		.tourmaster-tour-search-wrap input.tourmaster-tour-search-submit[type="submit"]:hover,
		/* Tribe Events */
		#tribe-bar-form .tribe-bar-submit input[type="submit"]:hover,
		#tribe-bar-form .tribe-bar-submit input[type="submit"]:focus,
		#tribe-bar-form.tribe-bar-mini .tribe-bar-submit input[type="submit"]:hover,
		#tribe-bar-form.tribe-bar-mini .tribe-bar-submit input[type="submit"]:focus,
		#tribe-bar-form .tribe-bar-views-toggle:hover,
		#tribe-bar-views li.tribe-bar-views-option:hover,
		#tribe-bar-views .tribe-bar-views-list .tribe-bar-views-option.tribe-bar-active,
		#tribe-bar-views .tribe-bar-views-list .tribe-bar-views-option.tribe-bar-active:hover,
		#tribe-events .tribe-events-button:hover,
		.tribe-events-button:hover,
		.tribe-events-cal-links a:hover,
		/* EDD buttons */
		.edd_download_purchase_form .button:hover, .edd_download_purchase_form .button:active, .edd_download_purchase_form .button:focus,
		#edd-purchase-button:hover, #edd-purchase-button:active, #edd-purchase-button:focus,
		.edd-submit.button:hover, .edd-submit.button:active, .edd-submit.button:focus,
		.widget_edd_cart_widget .edd_checkout a:hover,
		.sc_edd_details .downloads_page_tags .downloads_page_data > a:hover,
		/* MailChimp */
		.mc4wp-form input[type="submit"]:hover,
		.mc4wp-form input[type="submit"]:focus,
		/* WooCommerce */
		.woocommerce #respond input#submit:hover,
		.woocommerce .button:hover, .woocommerce-page .button:hover,
		.woocommerce a.button:hover, .woocommerce-page a.button:hover,
		.woocommerce button.button:hover, .woocommerce-page button.button:hover,
		.woocommerce input.button:hover, .woocommerce-page input.button:hover,
		.woocommerce input[type="button"]:hover, .woocommerce-page input[type="button"]:hover,
		.woocommerce input[type="submit"]:hover, .woocommerce-page input[type="submit"]:hover {
			color: {$colors['inverse_link']};
			background-color: {$colors['text']};
		}
		.widget_shopping_cart .buttons a:not(.checkout ) {
		    color: {$colors['inverse_link']};
			background-color: {$colors['inverse_dark']};
		}
		.wp-block-button.is-style-outline > .wp-block-button__link:hover,
		.wp-block-button.is-style-outline > .wp-block-button__link:focus {
			color:{$colors['text']};
			border-color:{$colors['text']};
		}

		#tribe-bar-views-toggle:hover,
		#tribe-bar-views .tribe-bar-views-option:hover,
		#tribe-bar-form input[type="text"]:focus {
			color: {$colors['text']}!important;
		}

		.select_container:after {
			color: {$colors['inverse_dark']};
		}
		.select_container:hover:after {
			color: {$colors['text_link']};
		}
		
		.select_container:hover:before {
			color: {$colors['input_dark']};
			background-color: {$colors['input_bg_color']};
		}
		.select_container:focus:before {
			background-color: {$colors['input_bg_hover']};
		}
		.select_container:focus:after {
			color: {$colors['text_link']};
		}

		/* Sportpress */
		aside.widget_sp_countdown .sp-template a,
		aside.widget_sp_countdown .sp-data-table a,
		aside.widget_sp_countdown .sp-template-countdown .sp-event-date,
		aside.widget_sp_countdown .sp-countdown-wrapper .countdown.sp-countdown time span {
			color: {$colors['inverse_dark']}!important;
		}
		aside.widget_sp_countdown .sp-template a:hover, aside.widget_sp_countdown .sp-data-table a:hover {
			color: {$colors['text_link']}!important;
		}
		.extra-countdown.top-style {
			background: {$colors['input_dark']}!important;
		}
		.extra-countdown.top-style .sp-table-caption,
		.extra-countdown.top-style .sp-countdown-wrapper .countdown.sp-countdown time span {
			color: {$colors['inverse_hover']}!important;
		}
		aside .sp-league-table.sp-data-table th,
		.sp-league-table.sp-data-table td,
		.sp-league-table.sp-data-table td a {
			color: {$colors['inverse_dark']}!important;
		}
		.sp-league-table.sp-data-table td a:hover {
			color: {$colors['text_link']}!important;
		}
		/* White Title, Subtitle & Description */
		.white_title .sc_item_subtitle,
		.white_title .sc_item_title,
		.white_title .sc_item_descr {
			color: {$colors['inverse_link']}!important;
		}
		.white_title .sc_button {
			background-color: {$colors['extra_bg_color']}!important;
			color: {$colors['bg_color']}!important;
		}
		.white_title .sc_button:hover {
			background-color: {$colors['bg_color']}!important;
			color: {$colors['extra_bg_color']}!important;
		}

		/* Header */
		.sc_layouts_menu_nav > li a {
			color: {$colors['text_dark']}!important;
		}
		.sc_layouts_row_fixed_on .sc_layouts_menu_nav > li > a {
			color: {$colors['inverse_link']}!important;
		}
		.sc_layouts_row_fixed_on .sc_layouts_menu_nav > li.current-menu-item > a, 
		.sc_layouts_row_fixed_on .sc_layouts_menu_nav > li.current-menu-parent > a, 
		.sc_layouts_row_fixed_on .sc_layouts_menu_nav > li.current-menu-ancestor > a {
			color: {$colors['inverse_dark']}!important;
		}
		.sc_layouts_menu_nav > li > a:hover,
		.sc_layouts_menu_nav > li.sfHover > a {
			color: {$colors['extra_hover3']}!important;
		}
		.sc_layouts_row_fixed_on .sc_layouts_menu_nav > li > a:hover,
		.sc_layouts_row_fixed_on .sc_layouts_menu_nav > li.sfHover > a {
			color: {$colors['inverse_dark']}!important;
		}
		.sc_layouts_menu_nav > li.current-menu-item > a, 
		.sc_layouts_menu_nav > li.current-menu-parent > a, 
		.sc_layouts_menu_nav > li.current-menu-ancestor > a {
			color: {$colors['extra_hover3']}!important;
		}

		header.scheme_self .search_wrap .search_field {
			background-color: {$colors['extra_link']};
		}
		header.scheme_self input[placeholder]::-webkit-input-placeholder { color: {$colors['inverse_link']}; opacity: 1; }
		header.scheme_self input[placeholder]::-moz-placeholder { color: {$colors['inverse_link']}; opacity: 1; }
		header.scheme_self input[placeholder]:-ms-input-placeholder { color: {$colors['inverse_link']}; opacity: 1; }
		header.scheme_self input[placeholder]::placeholder { color: {$colors['inverse_link']}; opacity: 1; }


		header.scheme_self .sc_layouts_row_type_normal .sc_layouts_item_icon, 
		header.scheme_self .sc_layouts_row_type_normal .sc_layouts_item .sc_layouts_item_icon {
			background-color: {$colors['extra_link']};
			color: {$colors['text_link']};
		}
		header.scheme_self .sc_layouts_row_type_normal .sc_layouts_item_icon:hover, 
		header.scheme_self .sc_layouts_row_type_normal .sc_layouts_item .sc_layouts_item_icon:hover {
			color: {$colors['inverse_link']};
		}
		header .sc_layouts_cart_items_short, 
		header .sc_layouts_item .sc_layouts_cart_items_short {
			background-color:{$colors['alter_link3']};
		}
		.sc_layouts_row_fixed_on {
			background-color:{$colors['text_link']};
		}
		.top_panel_default .top_panel_navi.sc_layouts_row_fixed_on  {
			background-color:{$colors['extra_link2']};
		}
		.sc_layouts_menu_popup .sc_layouts_menu_nav, 
		.sc_layouts_menu_nav > li ul,
		.sc_layouts_menu_nav > li > ul:after {
			background-color: {$colors['inverse_link']};
		}
		.sc_layouts_menu_popup .sc_layouts_menu_nav > li > a,
		.sc_layouts_menu_nav > li li > a {
			color: {$colors['inverse_dark']}!important;
		}
		.sc_layouts_title .sc_layouts_title_breadcrumbs a:hover {
			color: {$colors['text_link']}!important;
		}
		/* Footer */
		.scheme_self.footer_wrap, .footer_wrap .scheme_self.vc_row {
			background-color:{$colors['extra_link3']};
		}
		.scheme_self.elementor-section h2 {
			color:{$colors['text_dark']};
		}

		/* Footer Menu */
		footer .sc_layouts_menu_nav > li > a:hover,
		footer .sc_layouts_menu_nav > li.sfHover > a {
			color: {$colors['text_light']}!important;
		}
		footer .sc_layouts_menu_nav > li.current-menu-item > a, 
		footer .sc_layouts_menu_nav > li.current-menu-parent > a, 
		footer .sc_layouts_menu_nav > li.current-menu-ancestor > a {
			color: {$colors['text_light']}!important;
		}

		.footer_wrap a:hover {
			color: {$colors['text_link']}!important;
		}
		


		/* SportPress */
		.wp-widget-sportspress-countdown .sp-template-countdown .sp-event-date {
			color: {$colors['text']}!important;
		}

		/* Socials */
		.sc_layouts_row_type_normal .socials_wrap .social_item .social_icon {
			background-color:{$colors['text']};
		}


		/* Promo */
		.scheme_self.sc_promo {
			background-color: {$colors['bg_color_0']};
		}




		/* Table */
		table th,
		/* Event Calendar */
		.tribe-events-calendar thead th {
			color: {$colors['extra_link']};
		} 


		/* Blog */

		.sticky,
		.author_info {
			background-color: {$colors['input_dark']}!important;
		}


		/* Sidebar */
		.sc_title.sc_title_accent, 
		.widget .widget_title, 
		.widget .widgettitle {
			color: {$colors['inverse_link']};
			background-color: {$colors['extra_link2']};
		}
		.sc_layouts_widgets .widget, 
		.sidebar .widget, 
		.sidebar .widget {
			background-color: {$colors['inverse_link']};
		}
		.widget_nav_menu li,
		.widget_recent_entries li, 
		.widget_meta li, 
		.widget_pages li, 
		.widget_archive li, 
		.widget_categories li,
		.widget a {
			color: {$colors['inverse_dark']};
		}
		.widget a:hover {
			color: {$colors['text_link']};
		}
		.sidebar .widget.widget_search {
			background-color: {$colors['inverse_hover']};
		}
		.sc_edd_details .downloads_page_tags .downloads_page_data > a, 
		.widget_product_tag_cloud a, 
		.widget_tag_cloud a {
			color: {$colors['text_dark']};
		}

		.widget_calendar #prev a:hover, 
		.widget_calendar #next a:hover {
			color: {$colors['inverse_dark']};
		}
		.recentcomments .comment-author-link a, 
		.recentcomments .comment-author-link {
			color: {$colors['inverse_dark']};
		}

		.widget_price_filter .price_label span {
			color: {$colors['inverse_dark']};
		}
		.woocommerce .widget_price_filter .ui-slider .ui-slider-range, 
		.woocommerce .widget_price_filter .ui-slider .ui-slider-handle {
			background-color: {$colors['inverse_dark']};
		}
		.shop_mode_thumbs .fcunited_shop_mode_buttons a.woocommerce_thumbs, 
		.shop_mode_list .fcunited_shop_mode_buttons a.woocommerce_list,
		.fcunited_shop_mode_buttons a:hover,
		.woocommerce ul.products li.product .post_header a {
			color: {$colors['inverse_dark']};
		}

		.woocommerce div.product form.cart div.quantity span, 
		.woocommerce-page div.product form.cart div.quantity span, 
		.woocommerce .shop_table.cart div.quantity span, 
		.woocommerce-page .shop_table.cart div.quantity span {
			background-color: {$colors['input_bg_hover']};
			color: {$colors['text_dark']};
		}
		.woocommerce div.product form.cart div.quantity span:hover, 
		.woocommerce-page div.product form.cart div.quantity span:hover, 
		.woocommerce .shop_table.cart div.quantity span:hover, 
		.woocommerce-page .shop_table.cart div.quantity span:hover {
			background-color: {$colors['input_bg_hover']};
			color: {$colors['text_link']};
		}
		.woocommerce ul.products li.product .post_featured:hover {
			border-color: {$colors['bg_color_0']};
		}

		.woocommerce .select_container:after {
			color: {$colors['inverse_dark']};
		}

		.select_container:after,
		.select_container:hover:after,
		.select_container:before,
		.select_container:hover:before {
			background-color: {$colors['bg_color_0']} !important;
		}

		/* Recent news */
		.sc_recent_news_style_news-line .post_item .post_title a {
			color: {$colors['inverse_link']};
		}
		.sc_recent_news_style_news-line .post_item .post_title a:hover {
			color: {$colors['inverse_dark']};
		}
		.sc_recent_news_style_news-plain .post_item:not(.post_size_small) .post_featured .post_info, 
		.sc_recent_news_style_news-plain .post_size_small {
			background-color: {$colors['inverse_link']};
		}
		.sc_recent_news_style_news-plain .post_item .post_title a {
			color: {$colors['inverse_dark']}!important;
		}
		.sc_recent_news_style_news-plain .post_item .post_title a:hover {
			color: {$colors['text_link']}!important;
		}

		/* Price */
		.sc_price_item {
			background-color: {$colors['bg_color_0']};
		}
		.sc_price_item_info {
			background-color: {$colors['bg_color']};
		}


		/* Pagination	*/
		.esg-filters div.esg-navigationbutton,
		.woocommerce nav.woocommerce-pagination ul li a,
		.page_links > a,
		.comments_pagination .page-numbers, 
		.nav-links .page-numbers {
			color: {$colors['inverse_dark']};
		}	

		/* Tribe events */
		.tribe-events-event-meta a:hover {
			color: {$colors['inverse_dark']};
		}


		.post_item {
			color: {$colors['alter_text']};
		}

		.sc_slider_controls .slider_prev, 
		.sc_slider_controls .slider_next, 
		.slider_container .slider_prev, 
		.slider_container .slider_next, 
		.slider_outer .slider_prev, 
		.slider_outer_controls .slider_next {
			background-color: {$colors['text_link_02']};
		}

		.screen-reader-text:hover,
		.screen-reader-text:active,
		.screen-reader-text:focus { 
			background-color: {$colors['bg_color_0']};
		}


		.post_featured.hover_dots .post_info {
			color: {$colors['inverse_link']}!important;
		}
		.nav-links-more a:hover {
			color: {$colors['inverse_dark']};
		}
CSS;
		}

		return $css;
	}
}

