<?php

namespace Bunyad\Blocks;

use Bunyad;
use Bunyad\Blocks\Base\Block;

/**
 * Site Header block.
 */
class Header extends Block
{
	public $id = 'header';

	/**
	 * @inheritdoc
	 */
	public static function get_default_props() 
	{
		$props = [
			// Can be 'mobile'.
			'type'       => '',

			'style'      => '',

			'width'      => 'full-wrap',
			'width_top'  => '',
			'width_mid'  => '',
			'width_bot'  => '',

			// Both sticky values accept a string.
			'sticky'       => '',
			'sticky_type'  => '',
			'sticky_full'  => '',

			'scheme_top'   => 'dark',
			'scheme_mid'   => 'dark',
			'scheme_bot'   => 'dark',

			'items_top_left'   => [],
			'items_top_center' => [],
			'items_top_right'  => [],

			'items_mid_left'   => [],
			'items_mid_center' => [],
			'items_mid_right'  => [],

			'items_bot_left'   => [],
			'items_bot_center' => [],
			'items_bot_right'  => [],

			// Off-canvas menu props.
			'offcanvas_scheme'         => '',
			'offcanvas_mobile_widgets' => '',
			'offcanvas_desktop_menu'   => '',
			'offcanvas_social'         => [],

			// Button Components.
			'button_link'    => '#',
			'button_style'   => 'alt',
			'button_text'    => 'Button',
			'button_target'  => '',

			'button2_link'   => '#',
			'button2_style'  => 'a',
			'button2_text'   => 'Button',
			'button2_target' => '',

			'button3_link'   => '#',
			'button3_style'  => 'alt',
			'button3_text'   => 'Button',
			'button3_target' => '',

			'date_format'   => '',
			'text'          => '',
			'text2'         => '',
			'text3'         => '',
			'text4'         => '',

			// Navigation options.
			'nav_hov_style'   => 'a',

			// Nav small / secodnary nav.
			'nav_small_menu'  => '',

			// Social options.
			'social_style'    => 'a',
			'social_services' => [],

			// Search
			'search_type'   => 'icon',

			// News Ticker
			'ticker_posts'   => 8,
			'ticker_heading' => '',
			'ticker_tags'    => '',
			'ticker_delay'   => 8,

			// Auth link.
			'auth_icon'      => true,
			'auth_text'      => '',
			'auth_logout'    => true,

			// Hamburger
			'hamburger_style' => 'a',

		];

		return $props;
	}

	public function init()
	{
        if (!Bunyad::options()) {
			return;
        }

		if ($this->props['type'] !== 'mobile') {

			// All except these for main header.
			$keys = array_diff(
				array_keys($this->props),
				[
					'style',
					'type',
					// 'text4',
					// 'date_format',
					// 'nav_hov_style',
				]
			);

			$prefix = 'header_';
		}
		else {

			// Only these keys, prefixed for mobile. 
			$keys = array_intersect(
				array_keys($this->props),
				[
					'width',
					'width_top',
					'width_mid',
					'scheme_top',
					'scheme_mid',
					'items_top_left',
					'items_top_center',
					'items_top_right',
					'items_mid_left',
					'items_mid_center',
					'items_mid_right',
					// 'date_format',
					'social_style',
					'social_services',
					'sticky',
					'sticky_type'
				]
			);

			$prefix = 'header_mob_';

			// Only search icon supported in mobile header.
			$this->props['search_type'] = 'icon';

			/**
			 * These elements don't have separate mobile settings.
			 */
			$main_keys = [
				'text',
				'button3_link',
				'button3_style',
				'button3_text',
				'button3_target',
			];

			foreach ($main_keys as $option) {
				$this->props[$option] = Bunyad::options()->get('header_' . $option);
			}
		}

		foreach ($keys as $option) {
			$value = Bunyad::options()->get($prefix . $option);

			if (is_array($this->props[$option])) {
				$value = (array) $value;
			}

			$this->props[$option] = $value;
		}
	}

	/**
	 * Render the block heading.
	 * 
	 * @return void
	 */
	public function render()
	{
		// Off-canvas menu should only be included once for main header.
		if ($this->props['type'] !== 'mobile') {
			Bunyad::core()->partial(
				'partials/header/off-canvas-menu',
				[
					'block'          => $this,
					'scheme'         => $this->props['offcanvas_scheme'],
					'mobile_widgets' => $this->props['offcanvas_mobile_widgets'],
					'desktop_menu'   => $this->props['offcanvas_desktop_menu'],
					'social'         => $this->props['offcanvas_social'],
				]
			);
		}
		
		$partial = 'partials/header/header';
		// if (strpos($this->props['style'], 'smart') !== false) {
		// 	$partial .= '-smart';
		// }

		$this->props['style'] = str_replace('smart-', '', $this->props['style']);

		Bunyad::core()->partial(
			$partial,
			[
				'block' => $this
			]
		);
	}

	public function render_item($item)
	{
		$props = [
			'block' => $this
		];

		$partial = $item;

		/**
		 * Props for the relevant partial.
		 */
		switch ($item) {
			case 'button':
			case 'button2':
			case 'button3':
				$partial = 'button';
				$props += [
					'link'   => $this->props[$item . '_link'],
					'target' => $this->props[$item . '_target'],
					'style'  => $this->props[$item . '_style'],
					'text'   => $this->props[$item . '_text'],
					'class'  => $item === 'button' ? 'ts-button1' : 'ts-' . $item
				];
				break;

			case 'nav-small':
				$partial = 'nav-menu';
				$props += [
					'menu'     =>  $this->props['nav_small_menu'],
					'style'    => 'small',
					'location' => ''
				];
				break;

			case 'nav-menu':
				$props += [
					'hover_style' => $this->props['nav_hov_style'],
				];
				break;

			case 'social-icons':
				$props += [
					'style'    => $this->props['social_style'],
					'services' => $this->props['social_services'],
				];
				break;

			case 'text':
			case 'text2':
			case 'text3':
			case 'text4':
				$partial = 'text';
				$props += [
					'text'  => $this->props[$item],
					'class' => 'h-' . $item,
				];
				break;

			case 'ticker':
				$props += [
					'posts'   => $this->props['ticker_posts'],
					'heading' => $this->props['ticker_heading'],
					'tags'    => $this->props['ticker_tags'],
					'delay'   => $this->props['ticker_delay'],
				];
				break;

			case 'search':
				$props += [
					'type' => $this->props['search_type']
				];
				break;

			case 'logo':
				$props += [
					'type' => $this->props['type']
				];
				break;

			case 'auth':
				$props += [
					'icon'   => $this->props['auth_icon'],
					'text'   => $this->props['auth_text'],
					'logout' => $this->props['auth_logout'],
				];
				break;

			case 'hamburger':
				$props += [
					'style' => $this->props['hamburger_style']
				];
				break;

			case 'date':
				$props += [
					'date_format' => $this->props['date_format']
				];
				break;
		}

		$partial = 'partials/header/' . sanitize_file_name($partial);
		Bunyad::core()->partial($partial, $props);
	}

	/**
	 * Get data for the rows and their items to render.
	 * 
	 * @return array
	 */
	public function get_rows_data()
	{
		$base = [
			'width' => $this->props['width'],
		];

		$data = [];
		foreach (['top', 'mid', 'bot'] as $row) {

			// Skip if all columns empty for this row.
			if (!$this->props["items_{$row}_left"] 
				&& !$this->props["items_{$row}_center"] 
				&& !$this->props["items_{$row}_right"]
			) {
				continue;
			}

			$data[$row] = $base + [
				'left'   => $this->props["items_{$row}_left"],
				'center' => $this->props["items_{$row}_center"],
				'right'  => $this->props["items_{$row}_right"],
			];

			if (!empty($this->props['width_' . $row])) {
				$data[$row]['width'] = $this->props['width_' . $row];
			}
		}

		return $data;
	}

	/**
	 * View Helper: Output mobile logo.
	 */
	public function the_mobile_logo()
	{
		if (!Bunyad::options()->mobile_logo_2x) {
			return;
		}

		// Attachment id is saved in the option.
		$id   = Bunyad::options()->mobile_logo_2x;
		$logo = wp_get_attachment_image_src($id, 'full');
		
		if (!$logo) {
			return;
		}

		// Dark logo: Render first for CSS reasons.
		$dark_id = Bunyad::options()->mobile_logo_2x_sd;
		if ($dark_id) {
			$this->render_mobile_logo(
				wp_get_attachment_image_src($dark_id, 'full'),
				[
					'class' => 'logo-mobile logo-image logo-image-dark'
				]
			);
		}

		$this->render_mobile_logo($logo);
	}

	protected function render_mobile_logo($logo, $options = [])
	{
		$options = array_replace([
			'class' => 'logo-mobile logo-image'
		], $options);

		// Have the logo attachment - use half sizes for attributes since it's in 2x size
		if (is_array($logo)) {
			$url = $logo[0]; 
			$width  = round($logo[1] / 2);
			$height = round($logo[2] / 2);
		}
		else { 
			return;
		}

		$attribs = Bunyad::markup()->attribs('mobile-logo', [
			'class'  => $options['class'],
			'src'    => $url,
			'width'  => $width,
			'height' => $height,
			'alt'    => get_bloginfo('name', 'display')
		], ['echo' => false]);

		printf('<img %s/>', $attribs);
	}

	/**
	 * View Helper: Output main logo.
	 */
	public function the_logo($type = '')
	{
		$class = '';

		if ($type === 'dark') {
			$logo    = Bunyad::options()->image_logo_sd;
			$logo_2x = Bunyad::options()->image_logo_2x_sd;
			$class   = 'logo-image logo-image-dark';
		}
		else {
			$logo    = Bunyad::options()->image_logo;
			$logo_2x = Bunyad::options()->image_logo_2x;
			$class   = 'logo-image';
		}

		if (!$logo) {
			return;
		}

		$attrs = [
			'src'    => $logo,
			'class'  => $class,
			'alt'    => get_bloginfo('name', 'display'),
			'srcset' => [
				$logo     => '', 
				$logo_2x  => '2x'
			]
		];

		$logo_data = null;
		if (Bunyad::theme()) {
			$logo_data = Bunyad::theme()->get_logo_data($logo);
			$attrs += $logo_data;
		}

		$attribs = Bunyad::markup()->attribs(
			'image-logo' . ($type ? '-' . $type : ''),
			$attrs,
			['echo' => false]
		);

		printf('<img %s/>', $attribs);
	}
}