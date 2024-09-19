<?php
/**
 * @var \Bunyad\Blocks\Header $block
 */

$props     = $block->get_props();
$rows_data = $block->get_rows_data();

/**
 * Classes and attributes for the header wrapper.
 */
$classes = [
	'smart-head',
	($props['style'] ? 'smart-head-' . $props['style'] : ''),

	// Add the type class. Can be a mobile header.
	($props['type'] ? 'smart-head-' . $props['type'] : 'smart-head-main'),
];

$attribs = [
	'class' => $classes,
	'id'    => ($props['type'] === 'mobile' ? 'smart-head-mobile' : 'smart-head'),
];

if ($props['sticky']) {
	$attribs += [
		'data-sticky'      => $props['sticky'],
		'data-sticky-type' => $props['sticky_type'],
		'data-sticky-full' => $props['sticky_full'],
	];
}

?>

<div <?php Bunyad::markup()->attribs('smart-head', $attribs); ?>>
	<?php foreach ($rows_data as $row => $items): ?>

	<div <?php Bunyad::markup()->attribs('smart-head-' . $row, [
		'class' => [
			'smart-head-row',
			'smart-head-' . $row,
			(!empty($items['center']) ? 'smart-head-row-3' : ''),
			($props['scheme_' . $row] == 'dark' ? 's-dark' : 'is-light'),

			// Case: Add class so equal center isn't forced by grid.
			(
				!empty($items['center']) && in_array('nav-menu', $items['center']) 
					? 'has-center-nav'
					: ''
			),

			// Can be: contain (container BG), full, or full-wrap (full BG)
			($items['width'] === 'contain' ? 'wrap' : 'smart-head-row-full')
		]
	]); ?>>

		<div <?php Bunyad::markup()->attribs('smart-head-' . $row . '-inner', [
			'class' => [
				'inner',
				(in_array($items['width'], ['full', 'contain']) ? 'full' : 'wrap')
			]
		]); ?>>

			<?php 
				foreach (['left', 'center', 'right'] as $position): 

					// NOTE: Can't skip due to grid-templates CSS.
					// if (empty($items[$position])) {
						// continue;
					// }

					$items_class = [
						'items',
						'items-' . $position,
						(empty($items[$position]) ? 'empty' : '')
					];

					$items_class = join(' ', $items_class);
			?>
				
				<div class="<?php echo esc_attr($items_class); ?>">
				<?php 
					foreach ($items[$position] as $item) {
						$block->render_item($item);
					}
				?>
				</div>

			<?php endforeach; ?>
			
		</div>
	</div>

	<?php endforeach; ?>
</div>