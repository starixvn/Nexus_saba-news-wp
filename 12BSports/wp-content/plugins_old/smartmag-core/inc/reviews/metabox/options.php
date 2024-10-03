<?php
/**
 * Fields to show for page meta box
 */

$basic_options = [
	[
		'label' => esc_html__('Enable Review?', 'bunyad-admin'),
		'name'  => 'reviews', 
		'type'  => 'checkbox',
		'value' => 0,
	],

	[
		'label'   => esc_html__('Display Position', 'bunyad-admin'),
		'name'    => 'review_pos',
		'type'    => 'select',
		'options' => [
			'none'   => esc_html__('Do not display - Disabled', 'bunyad-admin'), 
			'top'    => esc_html__('Top', 'bunyad-admin'),
			'bottom' => esc_html__('Bottom', 'bunyad-admin')
		]
	],
	
	[
		'label'   => esc_html__('Show Rating As', 'bunyad-admin'),
		'name'    => 'review_type',
		'type'    => 'radio',
		'options' => [
			'percent' => esc_html__('Percentage', 'bunyad-admin'),
			'points'  => esc_html__('Points', 'bunyad-admin'),
			'stars'   => esc_html__('Stars', 'bunyad-admin'),
		], 
		'value' => 'points',
	],

	[
		'label' => esc_html__('Heading (Optional)', 'bunyad-admin'),
		'name'  => 'review_heading',
		'desc'  => 'A heading to display in the review box.',
		'type'  => 'text',
	],
	
	[
		'label' => esc_html__('Verdict', 'bunyad-admin'),
		'name'  => 'review_verdict',
		'desc'  => 'A one word verdit such as: Good or Awesome.',
		'type'  => 'text',
		'value' => '',
	],
	
	[
		'label'   => esc_html__('Verdict Summary', 'bunyad-admin'),
		'name'    => 'review_verdict_text',
		'type'    => 'textarea',
		'options' => ['rows' => 5, 'cols' => 90],
		'value'   => '',
	],
];

$schema_options = [
	[
		'label'   => esc_html__('Review Type/Schema', 'bunyad-admin'),
		'name'    => 'review_schema',
		'type'    => 'select',
		'options' => [
			''                   => 'Default (Product)',
			'none'               => 'Disabled',
			// 'Book'               => 'Book',
			'Course'             => 'Course',
			'CreativeWorkSeason' => 'CreativeWorkSeason',
			'CreativeWorkSeries' => 'CreativeWorkSeries',
			'Episode'            => 'Episode',
			// 'Event'              => 'Event',
			'Game'               => 'Game',
			// 'HowTo'              => 'HowTo',
			'LocalBusiness'       => 'LocalBusiness',
			'MediaObject'         => 'MediaObject',
			'Movie'               => 'Movie',
			'MusicPlaylist'       => 'MusicPlaylist',
			'MusicRecording'      => 'MusicRecording',
			'Organization'        => 'Organization',
			'Product'             => 'Product',
			'Recipe'              => 'Recipe',
			'SoftwareApplication' => 'SoftwareApplication',
		]
	],

	[
		'label' => esc_html__('Schema: Author / Brand / Org', 'bunyad-admin'),
		'group' => 'schema',
		'desc'  => 'Note: For schema "Product", this field should have brand/company of product. For CreativeWorks and Books it can be Author/Publisher.',
		'name'  => 'review_item_author',
		'type'  => 'text',
	],

	[
		'label'   => esc_html__('Schema: Author Type', 'bunyad-admin'),
		'group'   => 'schema',
		'name'    => 'review_item_author_type',
		'type'    => 'select',
		'options' => [
			'organization' => 'Organization',
			'person'       => 'Person',
		]
	],
	
	[
		'label' => esc_html__('Schema: Official Link', 'bunyad-admin'),
		'group' => 'schema',
		'name'  => 'review_item_link',
		'desc'  => 'Required for: Movie - Optional for other types. Link to the Wikipedia/official website/item site.',
		'type'  => 'text',
	],

	[
		'label' => esc_html__('Schema: Item Name (Optional)', 'bunyad-admin'),
		'group' => 'schema',
		'name'  => 'review_item_name',
		'desc'  => 'Will use post title when left empty.',
		'type'  => 'text',
	],

	[
		'label' => esc_html__('Schema: Operating System', 'bunyad-admin'),
		'group' => 'schema',
		'name'  => 'review_item_os',
		'desc'  => 'Optional: The operating system(s) required to use the app (for example, Windows 10, OSX 10.6, Android 1.6).',
		'type'  => 'text',
	],

	[
		'label'   => esc_html__('Schema: Application Category', 'bunyad-admin'),
		'group'   => 'schema',
		'name'    => 'review_item_app_cat',
		'desc'    => 'The type of app (for example, BusinessApplication or GameApplication). The value must be a supported app type.',
		'type'    => 'select',
		'options' => [
			'GameApplication'               => 'GameApplication',
			'SocialNetworkingApplication'   => 'SocialNetworkingApplication',
			'TravelApplication'             => 'TravelApplication',
			'ShoppingApplication'           => 'ShoppingApplication',
			'SportsApplication'             => 'SportsApplication',
			'LifestyleApplication'          => 'LifestyleApplication',
			'BusinessApplication'           => 'BusinessApplication',
			'DesignApplication'             => 'DesignApplication',
			'DeveloperApplication'          => 'DeveloperApplication',
			'DriverApplication'             => 'DriverApplication',
			'EducationalApplication'        => 'EducationalApplication',
			'HealthApplication'             => 'HealthApplication',
			'FinanceApplication'            => 'FinanceApplication',
			'SecurityApplication'           => 'SecurityApplication',
			'BrowserApplication'            => 'BrowserApplication',
			'CommunicationApplication'      => 'CommunicationApplication',
			'DesktopEnhancementApplication' => 'DesktopEnhancementApplication',
			'EntertainmentApplication'      => 'EntertainmentApplication',
			'MultimediaApplication'         => 'MultimediaApplication',
			'HomeApplication'               => 'HomeApplication',
			'UtilitiesApplication'          => 'UtilitiesApplication',
			'ReferenceApplication'          => 'ReferenceApplication',
		]
	],

	// Offer schema.
	[
		'label' => esc_html__('Schema: Price', 'bunyad-admin'),
		'group' => 'schema',
		'name'  => 'review_item_price',
		'desc'  => 'Required for: SoftwareApplication. Optional for other types.',
		'type'  => 'number',
	],
	[
		'label' => esc_html__('Schema: Currency', 'bunyad-admin'),
		'group' => 'schema',
		'name'  => 'review_item_currency',
		'value' => 'USD',
		'desc'  => 'The 3-letter currency code such as USD. Required for: SoftwareApplication. Optional for other types.',
		'type'  => 'text',
	],
];

$options = array_merge($basic_options, $schema_options);
