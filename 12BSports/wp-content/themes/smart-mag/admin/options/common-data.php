<?php
/**
 * Shared configs / common data.
 */
$_common['header_elements'] = [
	'logo'          => esc_html__('Site Logo', 'bunyad-admin'),
	'nav-menu'      => esc_html__('Main Nav', 'bunyad-admin'),
	'date'          => esc_html__('Date', 'bunyad-admin'),
	'nav-small'     => esc_html__('Secondary Nav', 'bunyad-admin'),
	'social-icons'  => esc_html__('Social Icons', 'bunyad-admin'),
	'search'        => esc_html__('Search', 'bunyad-admin'),
	'button'        => esc_html__('Button 1', 'bunyad-admin'),
	'button2'       => esc_html__('Button 2', 'bunyad-admin'),
	'button3'       => esc_html__('Button 3', 'bunyad-admin'),
	'scheme-switch' => esc_html__('Dark Switcher', 'bunyad-admin'),
	'hamburger'     => esc_html__('Hamburger Icon', 'bunyad-admin'),
	'cart'          => esc_html__('Cart Icon', 'bunyad-admin'),
	'ticker'        => esc_html__('News Ticker', 'bunyad-admin'),
	'text'          => esc_html__('Text/HTML 1', 'bunyad-admin'),
	'text2'         => esc_html__('Text/HTML 2', 'bunyad-admin'),
	'text3'         => esc_html__('Text/HTML 3', 'bunyad-admin'),
	'text4'         => esc_html__('Text/HTML 4', 'bunyad-admin'),
	'auth'          => esc_html__('Login/Auth', 'bunyad-admin'),
];

$_common['sidebar_options'] = [
	''      => esc_html__('Default', 'bunyad-admin'),
	'none'  => esc_html__('No Sidebar', 'bunyad-admin'),
	'right' => esc_html__('Right Sidebar', 'bunyad-admin')
];

// Only some valid for mobile.
$_common['header_mob_elements'] = array_intersect_key(
	$_common['header_elements'],
	array_flip([
		'logo',
		'social-icons',
		'hamburger',
		'search',
		'button3',
		'scheme-switch',
		'cart',
		'text'
	])
);

// Used for all customizer settings where social links exist, social profiles, and about widget.
// Note: Changing this also requires changes in Bunyad_Theme_Social::get_services().
$_common['social_services'] = [
	'facebook'   => esc_html__('Facebook', 'bunyad-admin'),
	'twitter'    => esc_html__('X (Twitter)', 'bunyad-admin'),
	'instagram'  => esc_html__('Instagram', 'bunyad-admin'),
	'pinterest'  => esc_html__('Pinterest', 'bunyad-admin'),
	'vimeo'      => esc_html__('Vimeo', 'bunyad-admin'),
	'youtube'    => esc_html__('Youtube', 'bunyad-admin'),
	'dribbble'   => esc_html__('Dribbble', 'bunyad-admin'),
	'tumblr'     => esc_html__('Tumblr', 'bunyad-admin'),
	'linkedin'   => esc_html__('LinkedIn', 'bunyad-admin'),
	'whatsapp'   => esc_html__('WhatsApp', 'bunyad-admin'),
	'reddit'     => esc_html__('Reddit', 'bunyad-admin'),
	'tiktok'     => esc_html__('TikTok', 'bunyad-admin'),
	'twitch'     => esc_html__('Twitch', 'bunyad-admin'),
	'discord'    => esc_html__('Discord', 'bunyad-admin'),
	'telegram'   => esc_html__('Telegram', 'bunyad-admin'),
	'flickr'     => esc_html__('Flickr', 'bunyad-admin'),
	'snapchat'   => esc_html__('Snapchat', 'bunyad-admin'),
	'threads'    => esc_html__('Threads', 'bunyad-admin'),
	'soundcloud' => esc_html__('SoundCloud', 'bunyad-admin'),
	'vk'         => esc_html__('VKontakte', 'bunyad-admin'),
	'steam'      => esc_html__('Steam', 'bunyad-admin'),		
	'lastfm'     => esc_html__('Last.fm', 'bunyad-admin'),
	'bloglovin'  => esc_html__('BlogLovin', 'bunyad-admin'),
	'rss'        => esc_html__('RSS', 'bunyad-admin'),
];

$_common['social_services_ext'] = [
	'google-news'  => esc_html__('Google News', 'bunyad-admin'),
	'flipboard'    => esc_html__('Flipboard', 'bunyad-admin'),
] + $_common['social_services'];

// $_common['social_follow_services'] = [
// 	'google-news'  => esc_html__('Google News', 'bunyad-admin'),
// 	'flipboard'    => esc_html__('Flipboard', 'bunyad-admin'),
// 	'facebook'   => esc_html__('Facebook', 'bunyad-admin'),
// 	'twitter'    => esc_html__('X (Twitter)', 'bunyad-admin'),
// 	'instagram'  => esc_html__('Instagram', 'bunyad-admin'),
// 	'pinterest'  => esc_html__('Pinterest', 'bunyad-admin'),
// 	'youtube'    => esc_html__('Youtube', 'bunyad-admin'),
// 	'linkedin'   => esc_html__('LinkedIn', 'bunyad-admin'),
// 	'soundcloud' => esc_html__('SoundCloud', 'bunyad-admin'),
// 	'tiktok'     => esc_html__('TikTok', 'bunyad-admin'),
// 	'twitch'     => esc_html__('Twitch', 'bunyad-admin'),
// 	'telegram'   => esc_html__('Telegram', 'bunyad-admin'),
// 	'whatsapp'   => esc_html__('WhatsApp', 'bunyad-admin'),
// ];

$_common['social_share_services'] = [
	'facebook'  => esc_html__('Facebook', 'bunyad-admin'),
	'twitter'   => esc_html__('X (Twitter)', 'bunyad-admin'),
	'pinterest' => esc_html__('Pinterest', 'bunyad-admin'),
	'linkedin'  => esc_html__('LinkedIn', 'bunyad-admin'),
	'tumblr'    => esc_html__('Tumblr', 'bunyad-admin'),
	'reddit'    => esc_html__('Reddit', 'bunyad-admin'),
	'vk'        => esc_html__('VKontakte', 'bunyad-admin'),
	'telegram'  => esc_html__('Telegram', 'bunyad-admin'),
	'whatsapp'  => esc_html__('WhatsApp', 'bunyad-admin'),
	'email'     => esc_html__('Email', 'bunyad-admin'),
	'link'      => esc_html__('Copy Link', 'bunyad-admin'),
];

$_common['header_widths'] = [
	'full-wrap' => esc_html__('Site Width + Full BG', 'bunyad-admin'),
	'full'      => esc_html__('Full Browser Width', 'bunyad-admin'),
	'contain'   => esc_html__('Site Width', 'bunyad-admin'),
];

$_common['meta_options'] = [
	'cat'       => esc_html__('Category', 'bunyad-admin'),
	'author'    => esc_html__('Author', 'bunyad-admin'),
	'date'      => esc_html__('Date', 'bunyad-admin'),
	'updated'   => esc_html__('Updated', 'bunyad-admin'),
	'comments'  => esc_html__('Comments', 'bunyad-admin'),
	'read_time' => esc_html__('Read Time', 'bunyad-admin'),
	'views'     => esc_html__('Views Count', 'bunyad-admin'),
];

$_common['read_more_options'] = [
	'none'   => esc_html__('Disabled', 'bunyad-admin'),
	'btn'    => esc_html__('Button', 'bunyad-admin'),
	'btn-b'  => esc_html__('Colored Button', 'bunyad-admin'),
	'basic'  => esc_html__('Simple Text', 'bunyad-admin'),
];

$_common['load_more_options'] = [
	'a'    => esc_html__('A: Minimal Button', 'bunyad-admin'),
	'b'  => esc_html__('B: Colored Border & Text', 'bunyad-admin'),
	'c'  => esc_html__('C: Solid Color Button', 'bunyad-admin'),
];

$_common['reviews_options'] = [
	'none'    => esc_html__('Disabled', 'bunyad-admin'),
	'bars'    => esc_html__('Small Bar Overlay', 'bunyad-admin'),
	'radial'  => esc_html__('Circle/Radial Overlay', 'bunyad-admin'),
	'stars'   => esc_html__('Stars (In Post Meta)', 'bunyad-admin'),
];

$_common['ratio_options'] = [
	''         => esc_html__('Default', 'bunyad-admin'),
	'16-9'     => esc_html__('16:9 Wide', 'bunyad-admin'),
	'4-3'      => esc_html__('4:3 Standard', 'bunyad-admin'),
	'21-9'     => esc_html__('21:9 Ultrawide', 'bunyad-admin'),
	'3-2'      => esc_html__('3:2 Rectangle', 'bunyad-admin'),
	'1-1'      => esc_html__('1:1 Square', 'bunyad-admin'),
	'3-4'      => esc_html__('3:4 Tall', 'bunyad-admin'),
	'2-3'      => esc_html__('2:3 Taller', 'bunyad-admin'),
	'custom'   => esc_html__('Custom', 'bunyad-admin'),
];

$positions = [
	'top-left'   => esc_html__('Top Left', 'bunyad-admin'),
	'top-center' => esc_html__('Top Center', 'bunyad-admin'),
	'top-right'  => esc_html__('Top Right', 'bunyad-admin'),
	'bot-left'   => esc_html__('Bottom Left', 'bunyad-admin'),
	'bot-center' => esc_html__('Bottom Center', 'bunyad-admin'),
	'bot-right'  => esc_html__('Bottom Right', 'bunyad-admin'),
];

$_common['cat_labels_pos_options'] = $positions;

$_common['post_format_pos_options'] = [
	''          => esc_html__('Auto', 'bunyad-admin'),
	'center'    => esc_html__('Centered', 'bunyad-admin'),
] + $positions;

$_common['pagination_options'] = [
	'numbers'   => esc_html__('Page Numbers', 'bunyad-admin'),
	'load-more' => esc_html__('Load More Button', 'bunyad-admin'),
	'infinite'  => esc_html__('Infinite Scroll', 'bunyad-admin'),
];

$_common['heading_tags'] = [
	'h2'  => 'H2',
	'h3'  => 'H3',
	'h4'  => 'H4',
	'h5'  => 'H5',
	'h6'  => 'H6',
	'div' => 'div',
];

/**
 * Block Headings
 */
$_common['block_headings'] = [
	'a'    => esc_html__('A: Stylish Box', 'bunyad-admin'),
	'a2'   => esc_html__('A2: Legacy', 'bunyad-admin'),
	'b'    => esc_html__('B: Simple Text', 'bunyad-admin'),
	'c'    => esc_html__('C: Accent & Border Below', 'bunyad-admin'),
	'c2'   => esc_html__('C2: Small Accent Line', 'bunyad-admin'),
	'd'    => esc_html__('D: Trendy Box', 'bunyad-admin'),
	'e'    => esc_html__('E: Line on Right', 'bunyad-admin'),
	'e2'   => esc_html__('E2: Line on Right 2', 'bunyad-admin'),
	'e3'   => esc_html__('E3: Double Line on Right', 'bunyad-admin'),
	'f'    => esc_html__('F: Accent Above & Border Below', 'bunyad-admin'),
	'g'    => esc_html__('G: Dark BG Box', 'bunyad-admin'),
	'h'    => esc_html__('H: Simple Line Below', 'bunyad-admin'),
	'i'    => esc_html__('I: Bar Line Before', 'bunyad-admin'),
];

$_common['sidebar_headings'] = $_common['block_headings'];
$_common += [
	'supports_bhead_line_width'   => ['c', 'c2'],
	'supports_bhead_line_color'   => ['c', 'c2', 'e', 'e2', 'e3', 'f'],
	'supports_bhead_border_color' => ['a', 'a2', 'c', 'd', 'f', 'h'],
	'supports_bhead_line_weight'  => ['a', 'c', 'c2', 'd', 'e', 'e2', 'e3', 'f', 'i'],
	'supports_bhead_border_weight' => ['c', 'h'],
	'supports_bhead_roundness'      => ['d'],
];


/**
 * Archives and loops.
 */
$_common['archive_loop_options'] = [
	'large'      => esc_html__('Classic Large Posts', 'bunyad-admin'),
	'grid-2'     => esc_html__('Grid - 2 Columns', 'bunyad-admin'),
	'grid-3'     => esc_html__('Grid - 3 Columns', 'bunyad-admin'),
	'overlay-2'  => esc_html__('Overlay - 2 Columns', 'bunyad-admin'),
	'overlay-3'  => esc_html__('Overlay - 3 Columns', 'bunyad-admin'),
	'overlay-4'  => esc_html__('Overlay - 4 Columns', 'bunyad-admin'),
	'posts-list' => esc_html__('List Style', 'bunyad-admin'),
	'classic'    => esc_html__('Legacy Classic Large', 'bunyad-admin'),
	'timeline'   => esc_html__('Timeline (Deprecated)', 'bunyad-admin'),
];

/**
 * Sliders & Featured Grids.
 */
$_common['featured_type_options'] = [
	'grid-a'     => esc_html__('Grid 1: 1 Large + 4 small', 'bunyad-admin'),
	'grid-b'     => esc_html__('Grid 2: 1 Large + 2 small', 'bunyad-admin'),
	'grid-c'     => esc_html__('Grid 3: 1 Large + 2 medium', 'bunyad-admin'),
	'grid-d'     => esc_html__('Grid 4: 1 Large + 1 Med + 2 Small', 'bunyad-admin'),
	'grid-eq1'   => esc_html__('Equals: 1 Column', 'bunyad-admin'),
	'grid-eq2'   => esc_html__('Equals: 2 Column', 'bunyad-admin'),
	'grid-eq3'   => esc_html__('Equals: 3 Columns', 'bunyad-admin'),
	'grid-eq4'   => esc_html__('Equals: 4 Columns', 'bunyad-admin'),
	'grid-eq5'   => esc_html__('Equals: 5 Columns', 'bunyad-admin'),
];

/**
 * Single
 */
$_common['post_style_options'] = [
	'modern'        => esc_html__('Default / Modern', 'bunyad-admin'),
	'cover'         => esc_html__('Post Cover - Overlay', 'bunyad-admin'),
	'large'         => esc_html__('Modern Large', 'bunyad-admin'),
	'large-b'       => esc_html__('Modern Large Bold', 'bunyad-admin'),
	'large-image'   => esc_html__('Modern Large Image', 'bunyad-admin'),
	'modern-below'  => esc_html__('Modern Featured Top', 'bunyad-admin'),
	'large-center'  => esc_html__('Large Centered', 'bunyad-admin'),
	'classic'       => esc_html__('Legacy/Classic', 'bunyad-admin'),
	'classic-above' => esc_html__('Legacy/Classic: Title Above', 'bunyad-admin'),
];

// For a few blocks for now, such as overlay and featured grid.
$_common['post_title_styles'] = [
	'normal' => esc_html__('Normal', 'bunyad-admin'),
	'bg'     => esc_html__('Background Color', 'bunyad-admin'),
];

return $_common;