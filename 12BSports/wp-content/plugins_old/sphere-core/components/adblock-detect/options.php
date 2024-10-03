<?php
namespace Sphere\Core\AdblockDetect;

/**
 * Adblock Detection options.
 */
class Options
{
	public function register_hooks()
	{
		// Add relevant options
		add_filter('bunyad_theme_options', [$this, 'add_options'], 10, 2);
	}

	/**
	 * Callback: Add options to theme customizer.
	 *
	 * @param  array  $options
	 * @param  string $type
	 * @return array
	 */
	public function add_options($options, $type = 'short')
	{
		// Note: To be made translatable + localized by the theme.
		$strings = apply_filters('sphere/adblock/options_strings', [
			'title'   => 'Adblocker Detected',
			'message' => 'Please disable your Ad Blocker to continue browsing this site.',
		]);

		$fields = [
			[
				'name'    => 'adblock_enabled',
				'label'   => esc_html__('Enable Adblock Detection', 'sphere-core'),
				'desc'    => '',
				'value'   => 0,
				// 'style'   => 'inline-sm',
				'classes' => 'sep-bottom',
				'type'    => 'toggle',
			],
			[
				'name'  => 'adblock_title',
				'label' => esc_html__('Message Title', 'sphere-core'),
				'desc'  => '',
				'value' => $strings['title'],
				'type'  => 'text',
			],
			[
				'name'  => 'adblock_message',
				'label' => esc_html__('Message', 'sphere-core'),
				'desc'  => 'Basic html allowed.',
				'value' => $strings['message'],
				'type'  => 'textarea',
			],
			[
				'name'  => 'adblock_delay',
				'label' => esc_html__('Modal Delay', 'sphere-core'),
				'desc'  => '',
				'value' => 0,
				'style' => 'inline-sm',
				'type'  => 'number',
			],
			[
				'name'  => 'adblock_dismissable',
				'label' => esc_html__('Allow Dismissing Message', 'sphere-core'),
				'desc'  => '',
				'value' => 0,
				'type'  => 'toggle',
				'style' => 'inline-sm',
			],
			[
				'name'  => 'adblock_no_reshow',
				'label' => esc_html__('Dont Show After Dismissed', 'sphere-core'),
				'desc'  => '',
				'value' => 0,
				'type'  => 'toggle',
				'style' => 'inline-sm',
				'context' => [['key' => 'adblock_dismissable', 'value' => 1]]
			],
			[
				'name'  => 'adblock_reshow_timeout',
				'label' => esc_html__('Dont Show For Hours', 'sphere-core'),
				'desc'  => '',
				'value' => 24,
				'type'  => 'number',
				'style' => 'inline-sm',
				'context' => [['key' => 'adblock_no_reshow', 'value' => 1]]
			],

			[
				'name'  => 'adblock_button',
				'label' => esc_html__('Show Button', 'sphere-core'),
				'desc'  => '',
				'value' => 0,
				'type'  => 'toggle',
				'style' => 'inline-sm',
			],
			[
				'name'  => 'adblock_button_label',
				'label' => esc_html__('Button Label', 'sphere-core'),
				'desc'  => '',
				'value' => '',
				'type'  => 'text',
				'style' => 'inline-sm',
				'context' => [['key' => 'adblock_button', 'value' => 1]]
			],
			[
				'name'  => 'adblock_button_link',
				'label' => esc_html__('Button Link', 'sphere-core'),
				'desc'  => '',
				'value' => '',
				'type'  => 'text',
				'context' => [['key' => 'adblock_button', 'value' => 1]]
			],
		];

		if ($type !== 'short') {
			array_push($fields, ...[
				[
					'name'  => 'css_adblock_bg_color',
					'label' => esc_html__('Modal Background', 'sphere-core'),
					'desc'  => '',
					'value' => '',
					'type'  => 'color',
					'style' => 'inline-sm',
					'classes' => 'sep-top',
					'css'   => [
						'.detect-modal .ts-modal-container' => ['props' => ['background' => '%s']]
					]
				],
				[
					'name'  => 'css_adblock_bg_color_sd',
					'label' => esc_html__('Dark: Modal Background', 'sphere-core'),
					'desc'  => '',
					'value' => '',
					'type'  => 'color',
					'style' => 'inline-sm',
					'css'   => [
						'.s-dark .detect-modal .ts-modal-container' => ['props' => ['background' => '%s']]
					]
				],

				[
					'name'  => 'css_adblock_h_color',
					'label' => esc_html__('Heading Color', 'sphere-core'),
					'desc'  => '',
					'value' => '',
					'type'  => 'color',
					'style' => 'inline-sm',
					'css'   => [
						'.detect-modal .heading' => ['props' => ['color' => '%s']]
					]
				],
				[
					'name'  => 'css_adblock_h_color_sd',
					'label' => esc_html__('Dark: Heading Color', 'sphere-core'),
					'desc'  => '',
					'value' => '',
					'type'  => 'color',
					'style' => 'inline-sm',
					'css'   => [
						'.s-dark .detect-modal .heading' => ['props' => ['color' => '%s']]
					]
				],

				[
					'name'  => 'css_adblock_text_color',
					'label' => esc_html__('Text Color', 'sphere-core'),
					'desc'  => '',
					'value' => '',
					'type'  => 'color',
					'style' => 'inline-sm',
					'css'   => [
						'.detect-modal .message' => ['props' => ['color' => '%s']]
					]
				],
				[
					'name'  => 'css_adblock_text_color_sd',
					'label' => esc_html__('Dark: Text Color', 'sphere-core'),
					'desc'  => '',
					'value' => '',
					'type'  => 'color',
					'style' => 'inline-sm',
					'css'   => [
						'.detect-modal .message' => ['props' => ['color' => '%s']]
					]
				],

				[
					'name'  => 'css_adblock_hide_icon',
					'label' => esc_html__('Hide Icon', 'sphere-core'),
					'desc'  => '',
					'value' => 0,
					'type'  => 'toggle',
					'style' => 'inline-sm',
					'css'   => [
						'.detect-modal .stop-icon' => ['props' => ['display' => 'none']]
					]
				],

			]);
		}

		$add_options = [
			'priority' => 40,
			'sections' => [[
				'title'    => esc_html__('Ad Blocker Detection', 'sphere-core'),
				'id'       => 'sphere-adblock-detect',
				'fields'   => $fields
			]]
		];

		$options['sphere-adblock-detect'] = apply_filters('sphere/adblock/options', $add_options);
		return $options;
	}
}