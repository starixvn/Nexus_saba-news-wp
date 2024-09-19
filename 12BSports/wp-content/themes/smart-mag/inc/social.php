<?php
/**
 * Functions relating to the social functionality.
 */
class Bunyad_Theme_Social
{
	public function __construct() 
	{
		add_filter('sphere/social-follow/options', [$this, 'follow_options']);
		add_filter('sphere_theme_docs_url', function() {
			return 'https://theme-sphere.com/docs/smartmag/';
		});
	}
	
	/**
	 * Filter: Modify default customizer options for social follow
	 * 
	 * @see \Sphere\Core\SocialFollow\Module::add_theme_options()
	 */
	public function follow_options($options)
	{
		$options['title'] = esc_html__('Social Follow & Count', 'bunyad-admin');
		$options['desc']  = sprintf(
			'Note: These settings are for Social Follow widget. For normal social settings, go to %sSocial Media Links%s',
			'<a href="#" class="focus-link" data-section="bunyad-misc-social">', '</a>'
		);
	
		$options['sections']['general']['fields']['sf_counters']['value'] = 0;
	
		return $options;
	}

	/**
	 * Get an array of services supported at different locations
	 * such as Top bar social icons.
	 */
	public function get_services()
	{
		$services = [
			'facebook' => [
				'icon'  => 'tsi tsi-facebook',
				'label' => esc_html__('Facebook', 'bunyad')
			],
			
			'twitter' => [
				'icon'  => 'tsi tsi-twitter',
				'label' => esc_html__('X (Twitter)', 'bunyad')
			],
			
			'instagram' => [
				'icon'  => 'tsi tsi-instagram',
				'label' => esc_html__('Instagram', 'bunyad')
			],
			
			'pinterest' => [
				'icon'  => 'tsi tsi-pinterest-p',
				'label' => esc_html__('Pinterest', 'bunyad')
			],
			
			'bloglovin' => [
				'icon'  => 'tsi tsi-heart',
				'label' => esc_html__('BlogLovin', 'bunyad')
			],
			
			'rss' => [
				'icon'  => 'tsi tsi-rss',
				'label' => esc_html__('RSS', 'bunyad')
			],
			
			// Only for authentications.
			'google' => [
				'icon'  => 'tsi tsi-google',
				'label' => esc_html__('Google', 'bunyad')
			],
			
			'youtube' => [
				'icon'  => 'tsi tsi-youtube-play',
				'label' => esc_html__('YouTube', 'bunyad')
			],
			
			'dribbble' => [
				'icon'  => 'tsi tsi-dribbble',
				'label' => esc_html__('Dribbble', 'bunyad')
			],
			
			'tumblr' => [
				'icon'  => 'tsi tsi-tumblr',
				'label' => esc_html__('Tumblr', 'bunyad')
			],
			
			'linkedin' => [
				'icon'  => 'tsi tsi-linkedin',
				'label' => esc_html__('LinkedIn', 'bunyad')
			],
			
			'flickr' => [
				'icon'  => 'tsi tsi-flickr',
				'label' => esc_html__('Flickr', 'bunyad')
			],
			
			'soundcloud' => [
				'icon'  => 'tsi tsi-soundcloud',
				'label' => esc_html__('SoundCloud', 'bunyad')
			],
			
			'vimeo' => [
				'icon'  => 'tsi tsi-vimeo',
				'label' => esc_html__('Vimeo', 'bunyad')
			],
				
			'lastfm' => [
				'icon'  => 'tsi tsi-lastfm',
				'label' => esc_html__('Last.fm', 'bunyad')
			],
				
			'steam' => [
				'icon'  => 'tsi tsi-steam',
				'label' => esc_html__('Steam', 'bunyad')
			],
				
			'vk' => [
				'icon'  => 'tsi tsi-vk',
				'label' => esc_html__('VKontakte', 'bunyad')
			],
			
			'reddit' => [
				'icon'  => 'tsi tsi-reddit-alien',
				'label' => esc_html__('Reddit', 'bunyad')
			],

			'tiktok' => [
				'icon'  => 'tsi tsi-tiktok',
				'label' => esc_html__('TikTok', 'bunyad')
			],

			'telegram' => [
				'icon'  => 'tsi tsi-telegram',
				'label' => esc_html__('Telegram', 'bunyad')
			],

			'twitch' => [
				'icon'  => 'tsi tsi-twitch',
				'label' => esc_html__('Twitch', 'bunyad')
			],

			'discord' => [
				'icon'  => 'tsi tsi-discord',
				'label' => esc_html__('Discord', 'bunyad')
			],
			
			'whatsapp' => [
				'icon'  => 'tsi tsi-whatsapp',
				'label' => esc_html__('WhatsApp', 'bunyad')
			],
			
			'snapchat' => [
				'icon'  => 'tsi tsi-snapchat',
				'label' => esc_html__('Snapchat', 'bunyad')
			],

			'threads' => [
				'icon'  => 'tsi tsi-threads',
				'label' => esc_html__('Threads', 'bunyad')
			],

			// Partially supported services for some areas.

			'flipboard' => [
				// 'icon'  => 'tsi tsi-flipboard',
				'icon_svg_og' => 'og-flipboard',
				'label' => esc_html__('Flipboard', 'bunyad')
			],

			'google-news' => [
				// 'icon'  => 'tsi tsi-google-news',
				'icon_svg_og' => 'og-google-news',
				'label' => esc_html__('Google News', 'bunyad')
			],
		];
		
		return apply_filters('bunyad_social_services', $services);
	}
}

// init and make available in Bunyad::get('social')
Bunyad::register('social', [
	'class' => 'Bunyad_Theme_Social',
	'init'  => true
]);