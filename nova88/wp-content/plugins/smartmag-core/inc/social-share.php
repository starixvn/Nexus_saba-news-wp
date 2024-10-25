<?php
/**
 * Social sharing buttons
 */
class SmartMag_SocialShare
{
	/**
	 * Get an array of sharing services with links
	 */
	public function share_services($post_id = '') 
	{
		if (empty($post_id)) {
			$post_id = get_the_ID();
		}
		
		// Post and media URL
		$url   = rawurlencode(get_permalink($post_id));
		$media = rawurlencode(
			wp_get_attachment_url(get_post_thumbnail_id($post_id))
		);

		$the_title = get_post_field('post_title', $post_id, 'raw');
		$title     = rawurlencode($the_title);
		
		// Social Services
		$services = [
			'facebook' => [
				'label'      => esc_html__('Facebook', 'bunyad'),
				'label_full' => esc_html__('Share on Facebook', 'bunyad'), 
				'icon'       => 'tsi tsi-facebook',
				'url'        => 'https://www.facebook.com/sharer.php?u=' . $url,
			],
				
			'twitter' => [
				'label'      => esc_html__('Twitter', 'bunyad'), 
				'label_full' => esc_html__('Share on X (Twitter)', 'bunyad'), 
				'icon'       => 'tsi tsi-twitter',
				'url'        => 'https://twitter.com/intent/tweet?url=' . $url . '&text=' . $title,
			],

			'pinterest' => [
				'label'      => esc_html__('Pinterest', 'bunyad'), 
				'label_full' => esc_html__('Share on Pinterest', 'bunyad'), 
				'icon'       => 'tsi tsi-pinterest',
				'url'        => 'https://pinterest.com/pin/create/button/?url='. $url . '&media=' . $media . '&description=' . $title,
				'key'        => 'sf_instagram_id',
			],
			
			'linkedin' => [
				'label'      => esc_html__('LinkedIn', 'bunyad'), 
				'label_full' => esc_html__('Share on LinkedIn', 'bunyad'), 
				'icon'       => 'tsi tsi-linkedin',
				'url'        => 'https://www.linkedin.com/shareArticle?mini=true&url=' . $url,
			],
				
			'tumblr' => [
				'label'      => esc_html__('Tumblr', 'bunyad'), 
				'label_full' => esc_html__('Share on Tumblr', 'bunyad'), 
				'icon'       => 'tsi tsi-tumblr',
				'url'        => 'https://www.tumblr.com/share/link?url=' . $url . '&name=' . $title,
			],

			'vk'     => [
				'label'      => esc_html__('VKontakte', 'bunyad'),
				'label_full' => esc_html__('Share on VKontakte', 'bunyad'), 
				'icon'       => 'tsi tsi-vk',
				'url'        => 'https://vk.com/share.php?url='. $url .'&title=' . $title,
			],
				
			'email'  => [
				'label'      => esc_html__('Email', 'bunyad'),
				'label_full' => esc_html__('Share via Email', 'bunyad'), 
				'icon'       => 'tsi tsi-envelope-o',
				'url'        => 'mailto:?subject='. $title .'&body=' . $url,
			],

			'whatsapp' => [
				'label'      => esc_html__('WhatsApp', 'bunyad'),
				'label_full' => esc_html__('Share on WhatsApp', 'bunyad'), 
				'icon'       => 'tsi tsi-whatsapp',

				// rawurlencode to preserve space properly
				'url'   => 'https://wa.me/?text='. $title . rawurlencode(' ') . $url,
			],

			'reddit' => [
				'label'      => esc_html__('Reddit', 'bunyad'),
				'label_full' => esc_html__('Share on Reddit', 'bunyad'), 
				'icon'       => 'tsi tsi-reddit-alien',
				'url'        => 'https://www.reddit.com/submit?url=' . $url . '&title='. $title,
			],

			'telegram' => [
				'label'      => esc_html__('Telegram', 'bunyad'),
				'label_full' => esc_html__('Share on Telegram', 'bunyad'), 
				'icon'       => 'tsi tsi-telegram',
				'url'        => 'https://t.me/share/url?url=' . $url . '&title='. $title,
			],

			'link' => [
				'label'      => esc_html__('Copy Link', 'bunyad'),
				'label_full' => esc_html__('Copy Link', 'bunyad'), 
				'icon'       => 'tsi tsi-link',
				'url'        => '#',
			],
		];
		
		return apply_filters('bunyad_social_share_services', $services);
	}

	/**
	 * Services for social follow widget.
	 */
	public function follow_services($process = true)
	{
		/**
		 * Setup an array of services and their associate URL, label and icon
		 */
		$services = [
			'facebook' => [
				'label' => esc_html__('Facebook', 'bunyad'),
				'text'  => Bunyad::options()->sf_facebook_label,
				'icon'  => 'facebook',
				'url'   => 'https://facebook.com/%',
				'key'   => 'sf_facebook_id',
			],
				
			'twitter' => [
				'label' => esc_html__('X (Twitter)', 'bunyad'), 
				'text'  => Bunyad::options()->sf_twitter_label,
				'icon'  => 'twitter',
				'url'   => 'https://twitter.com/%',
				'key'   => 'sf_twitter_id',
			],

			'pinterest' => [
				'label' => esc_html__('Pinterest', 'bunyad'), 
				'text'  => Bunyad::options()->sf_pinterest_label,
				'icon'  => 'pinterest-p',
				'url'   => 'https://pinterest.com/%',
				'key'   => 'sf_pinterest_id',
			],
				
			'instagram' => [
				'label' => esc_html__('Instagram', 'bunyad'), 
				'text'  => Bunyad::options()->sf_instagram_label,
				'icon'  => 'instagram',
				'url'   => 'https://instagram.com/%',
				'key'   => 'sf_instagram_id',
			],
			
			'youtube' => [
				'label' => esc_html__('YouTube', 'bunyad'), 
				'text'  => Bunyad::options()->sf_youtube_label,
				'icon'  => 'youtube-play',
				'url'   => '%',
				'key'   => 'sf_youtube_url',
			],
				
			'vimeo' => [
				'label' => esc_html__('Vimeo', 'bunyad'), 
				'text'  => Bunyad::options()->sf_vimeo_label,
				'icon'  => 'vimeo',
				'url'   => '%',
				'key'   => 'sf_vimeo_url',
			],

			'linkedin' => [
				'label' => esc_html__('LinkedIn', 'bunyad'), 
				'text'  => Bunyad::options()->sf_linkedin_label,
				'icon'  => 'linkedin',
				'url'   => '%',
				'key'   => 'sf_linkedin_url',
			],

			'soundcloud' => [
				'label' => esc_html__('Soundcloud', 'bunyad'), 
				'text'  => Bunyad::options()->sf_soundcloud_label,
				'icon'  => 'soundcloud',
				'url'   => 'https://soundcloud.com/%',
				'key'   => 'sf_soundcloud_id',
			],

			'twitch' => [
				'label' => esc_html__('Twitch', 'bunyad'), 
				'text'  => Bunyad::options()->sf_twitch_label,
				'icon'  => 'twitch',
				'url'   => 'https://twitch.tv/%',
				'key'   => 'sf_twitch_id',
			],

			'reddit' => [
				'label' => esc_html__('Reddit', 'bunyad'), 
				'text'  => Bunyad::options()->sf_reddit_label,
				'icon'  => 'reddit-alien',
				'url'   => '%',
				'key'   => 'sf_reddit_url',
			],

			'tiktok' => [
				'label' => esc_html__('TikTok', 'bunyad'), 
				'text'  => Bunyad::options()->sf_tiktok_label,
				'icon'  => 'tiktok',
				'url'   => 'https://www.tiktok.com/@%',
				'key'   => 'sf_tiktok_id',
			],

			'telegram' => [
				'label' => esc_html__('Telegram', 'bunyad'), 
				'text'  => Bunyad::options()->sf_telegram_label,
				'icon'  => 'telegram',
				'url'   => 'https://t.me/%',
				'key'   => 'sf_telegram_id',
			],

			'whatsapp' => [
				'label' => esc_html__('WhatsApp', 'bunyad'), 
				'text'  => Bunyad::options()->sf_whatsapp_label,
				'icon'  => 'whatsapp',
				'url'   => 'https://wa.me/%',
				'key'   => 'sf_whatsapp_id',
			],

			// 'google-news' => [
			// 	'label' => esc_html__('Google News', 'bunyad'),
			// 	'text'  => Bunyad::options()->sf_google_news_label,
			// 	'icon_svg' => 'google-news',
			// 	'url'   => '%',
			// 	'key'   => 'sf_google_news_url',
			// ],

			// 'flipboard' => [
			// 	'label' => esc_html__('Flipboard', 'bunyad'),
			// 	'text'  => Bunyad::options()->sf_flipboard_label,
			// 	'icon_svg' => 'flipboard',
			// 	'url'   => '%',
			// 	'key'   => 'sf_flipboard_url',
			// ],	
		];
		
		$services = apply_filters('bunyad_social_follow_services', $services);
		
		if ($process) {
			$services = $this->_replace_urls($services);
		}
		
		return $services;
	}
	
	/**
	 * Perform URL replacements for social follow.
	 * Currently only used by the widget.
	 * 
	 * @param  array  $services
	 * @return array
	 */
	public function _replace_urls($services) 
	{
		foreach ($services as $id => $service) {
		
			if (!isset($service['key'])) {
				continue;
			}
			
			// Get the URL or username from settings.
			if ($the_url = Bunyad::options()->get($service['key'])) {
				$services[$id]['url'] = str_replace('%', $the_url, $service['url']);
			}
			else {
				// Try to fallback to social profile URLs.
				$profiles = Bunyad::options()->get('social_profiles');
				$services[$id]['url'] = !empty($profiles[$id]) ? $profiles[$id] : '';
			}
		}
			
		return $services;
	}

	/**
	 * Render social sharing.
	 */
	public function render($type = '', $props = [])
	{
		$file = sanitize_file_name($type) . '.php';
		$template_path = locate_template('partials/social-share/' . $file) ?: SmartMag_Core::instance()->path . 'social-share/views/' . $file;

		// @since 1.3.8 with theme v9.0.1
		include apply_filters(
			'bunyad_social_share_template',
			$template_path,
			$type
		);
	}
}

// init and make available in Bunyad::get('smartmag_social')
Bunyad::register('smartmag_social', array(
	'class' => 'SmartMag_SocialShare',
	'init' => true
));