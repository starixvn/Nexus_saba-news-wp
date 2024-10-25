<?php 
/**
 * Post bottom inline social follow.
 */
if (is_single() && Bunyad::options()->single_follow_bot) {

	Bunyad::blocks()->load('SocialIcons', [
		'style'         => 'custom',
		'class'         => 'spc-social-follow-inline',
		'services'      => Bunyad::options()->single_follow_bot_services,
		'icons_type'    => 'svg_og',
		'show_labels'   => true,
		'brand_colors'  => 'color',
		'labels_format' => Bunyad::options()->get_or(
			'single_follow_bot_format',
			esc_html__('Follow on %s', 'bunyad')
		)
	])->render();
}

/**
 * Post bottom social sharing buttons.
 */
if (
	(is_single() || Bunyad::options()->social_icons_classic) 
	&& Bunyad::options()->single_share_bot
	&& class_exists('SmartMag_Core')
) {
	// See plugins/smartmag-core/social-share/views/social-share.php
	Bunyad::get('smartmag_social')->render(
		'social-share',
		[
			'active' => Bunyad::options()->single_share_bot_services
		]
	);
}