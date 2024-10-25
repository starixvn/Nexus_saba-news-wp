<?php
namespace SmartMag\Integrations;

/**
 * Integration with debloat plugin.
 */
class Debloat
{
	public $allow_selectors = [];

	public function __construct()
	{
		add_action('wp', [$this, 'setup']);
	}

	public function setup()
	{
		$this->setup_css();
		$this->setup_delay_js();
	}

	public function setup_delay_js()
	{
		add_filter('debloat/delay_js_includes', function($includes) {
			$includes = array_merge($includes, [
				// Embeds need iframe message, can't be delayed.
				// 'id:wp-embed',
				'id:comment-reply',
				// Might cause slow connection delays. More testing needed.
				// 'id:micro-modal',
				// 'id:smartmag-float-share'
			]);

			return $includes;
		});

		add_filter('debloat/delay_js_excludes', function($excludes) {
			$excludes = array_merge($excludes, [
				// Always skip for switcher.
				'BunyadSchemeKey',
			]);

			return $excludes;
		});
	}

	public function setup_css()
	{
		$this->allow_selectors = [
			[
				'type'   => 'any',
				'sheet'  => 'id:smartmag-',
				'search' => [
					'.off-canvas-active', 
					'.lazyloaded',
					'.lazyload',
					'.lazyloading',
					'.no-display',
					'.appear',
					'.active',
					'.inactive',
					'.item-active',
					'.search-modal',
					'.is-open',
					// can be conditional
					'.post-share-b.all',
					'.mobile-menu *',
					'.theiaStickySidebar',
					'.tsi-spin',
					'.tsi-chevron-down',
					'.tsi-chevron-up',
					
					// Post formats for AJAX load
					'tsi-music',
					'tsi-play',
					'tsi-picture-o',
					
					// For pagination numbers.
					'.tsi-angle-right',
					'.tsi-angle-left',

					// Needed for multi-page slideshow AJAX.
					'.tsi-chevron-left',
					// '.tsi-chevron-right',

					// Block filters and other loading (load button)
					'.loading',

					// All animations including fade-in-lg etc.
					'.fade-in',
					'.fade-in-*',
					'.has-scrollbar',
					'.touch',

					// Relevant to snackbars.
					'.ts-snackbar*',
					'.tsi-close'
				],
			],
			[
				'type'  => 'class',
				'class' => 'has-lb',
				'search' => ['*.mfp-*'],
			],
			[
				'type'   => 'class',
				'class'  => 'live-search-query',
				'sheet'  => 'id:smartmag-',
				'search' => [
					'.live-search-results',
					'.has-live-results',
					'.loop-small *', 
					'.small-post *',
					'.loop-small-sep *',
				]
			],
			[
				'type'  => 'class',
				'class' => 'common-slider',
				// Other allows are applied to all sheets, to possibly allow child stylesheets.
				'sheet' => 'id:smartmag-',
				'search' => [
					'*.slick-*',
					'.common-slider',
					'.loaded',
					'.tsi-angle-left',
					'.tsi-angle-right',
					'.slider-arrow*',
					'.loop-carousel*',
				]
			],
			[
				'type'   => 'class',
				'class'  => 'smart-head',
				'sheet'  => 'id:smartmag-',
				// .off, .animate etc.
				'search' => [
					'.smart-head-sticky',
					'*.smart-head-sticky*'
				]
			],
			[
				'type'   => 'class',
				'class'  => 'load-button',
				'sheet'  => 'id:smartmag-',
				'search' => [
					'.format-overlay',
					'.c-overlay',
					'.review-number',
					'.review-radial'
				]
			],
			[
				'type'   => 'class',
				'class'  => 'filters',
				'sheet'  => 'id:smartmag-',
				'search' => [
					'.format-overlay',
					'.c-overlay',
					'.review-number',
					'.review-radial'
				]
			],
			[
				'type'  => 'prefix',
				'class' => 's-dark',
			],
			[
				'type'  => 'prefix',
				'class' => 'site-s-dark',
			],
			[
				'type'  => 'prefix',
				'class' => 's-light',
			],
			[
				'type'  => 'prefix',
				'class' => 'site-s-light',
			],
		];

		if (is_single()) {
			array_push(
				$this->allow_selectors,
				...[
					[
						'type'   => 'class',
						'class'  => 'post-slideshow',
						// Will allow classes not present in doc like loading, previous etc.
						'search' => ['.post-slideshow *', '.navigate-posts *']
					],
					[
						'type'   => 'class',
						'class'  => 'user-ratings',
						// Mainly for .voted
						'search' => ['.user-ratings *']
					],
					[
						'type'   => 'class',
						'class'  => 'post-share-float',
						'sheet' => 'id:smartmag-',
						'search' => [
							'.has-share-float-in',
							'.post-share-float-vp',
							'.has-share-float',
							'.is-hidden'
						]
					],
				]
			);
		}

		// Add to debloat allowed.
		add_filter('debloat/allow_css_selectors', function($allow) {
			return array_merge($allow, $this->allow_selectors);
		});

		// Skip WooCommerce removals.
		add_filter('debloat/remove_css_excludes', function($exclude) {
			array_push($exclude, ...[
				'id:smartmag-woocommerce',
				'id:smartmag-flex-slider',
				'id:smartmag-classic-slider'
			]);
			return $exclude;
		});

	}
}