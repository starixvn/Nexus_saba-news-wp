<?php

namespace Bunyad\Blocks;

use \Bunyad;
use Bunyad\Blocks\Base\Block;

/**
 * Newsletter / subscribe form block.
 */
class Newsletter extends Block
{
	public $id = 'newsletter';

	/**
	 * @inheritdoc
	 */
	public static function get_default_props() 
	{
		$props = [
			'headline'     => 'Subscribe to Updates',
			'style'        => 'b',

			// 'sm' or 'lg' container to adjust text sizes.
			'container'    => 'sm',
			'scheme'       => 'light',

			'image_type'   => 'none',
			'image'        => '',
			'image_2x'     => '',

			// 'inline' or 'full'
			'fields_style' => 'full',

			// Alignment.
			'align'        => 'center',

			// Service provider. Defaults to global.
			'service'     => '',
			'custom_form' => '',
			
			// Empty, 'mail-bg', 'mail-top', 'mail'
			'icon'        => '',
			'message'     => 'Get the latest creative news from FooBar about art, design and business.',
			'submit_text' => 'Subscribe',
			'submit_url'  => '',
			'disclaimer'  => 'By signing up, you agree to the our terms and our <a href="#">Privacy Policy</a> agreement.',
			'checkbox'    => 1,
		];

		return $props;
	}

	public function map_global_props($props)
	{
		// Add defaults from options.
		$props = array_replace([
			
		], $props);

		// Force globals for these if no service is selected.
		if (empty($props['service'])) {
			$props = array_replace($props, [
				'service'     => Bunyad::options()->newsletter_service,
				'submit_url'  => Bunyad::options()->newsletter_submit_url,
				'custom_form' => Bunyad::options()->newsletter_custom_form,
			]);
		}
		
		return $props;
	}

	public function init()
	{
		// Internal.
		$this->props += [
			'classes'  => '',
			'image_id' => '',
		];

		if (!$this->props['image_type']) {
			$this->props['image_type'] = 'none';
		}

		// Most likely from elementor if it's an array.
		if (is_array($this->props['image'])) {
			$this->props['image'] = $this->props['image']['url'];
		}

		if (is_array($this->props['image_2x'])) {
			$this->props['image_2x'] = $this->props['image_2x']['url'];
		}

		// Mailchimp URL fix.
		if ($this->props['service'] === 'mailchimp') {
			// Pasted whole form in submit URL? Capture the URL.
			if (preg_match('/action=\"([^\"]+)\"/', $this->props['submit_url'], $match)) {
				$this->props['submit_url'] = $match[1];
			}
		}
	}

	/**
	 * Print the image / media markup.
	 */
	public function the_image()
	{
		$image_url = $this->props['image'];

		if ($this->props['image_type'] === 'none' || !$image_url) {
			return;
		}

		$image_id  = attachment_url_to_postid($image_url);
		$srcset    = [$image_url => ''];
		if (!empty($this->props['image_2x'])) {
			$srcset[$this->props['image_2x']] = '2x';
		}

		$attrs = [
			'src'    => $image_url,
			'alt'    => $this->props['heading'],
			'srcset' => $srcset
		];

		$image_attrs = wp_get_attachment_image_src($image_id, 'full');
		if ($image_attrs) {
			$attrs += [
				'width'  => $image_attrs[1],
				'height' => $image_attrs[2],
			];
		}

		printf(
			'<div class="%1$s"><img %2$s /></div>',
			esc_attr('media media-' . $this->props['image_type']),
			Bunyad::markup()->attribs('block-newsletter', $attrs, ['echo' => false])
		);
	}

	/**
	 * Display custom form if not mailchimp.
	 */
	public function the_custom_form()
	{
		if ($this->props['service'] !== 'custom') {
			return;
		}

		printf(
			'<div class="fields %1$s">%2$s</div>', 
			$this->props['fields_style'] !== 'none' ? 'fields-style fields-full' : '',
			do_shortcode($this->props['custom_form'])
		);
	}

	/**
	 * Render all of the post meta HTML.
	 * 
	 * @return void
	 */
	public function render() 
	{
		if (class_exists('\SmartMag_Core') && empty(\SmartMag_Core::instance()->theme_supports['blocks'])) {
			return;
		}

		// Setup classes.
		$this->props['classes'] = [
			'spc-newsletter',
			'spc-newsletter-' . $this->props['style'],
			'spc-newsletter-' . $this->props['align'],
			'spc-newsletter-' . $this->props['container'],
			$this->props['icon'] === 'mail-top' ? 'has-top-icon' : ''
		];

		$this->props['classes'] = join(' ', array_filter($this->props['classes']));

		// Render view.
		Bunyad::core()->partial(
			'blocks/newsletter/html/newsletter',
			[
				'block' => $this,
			]
		);
	}
}