<?php
/**
 * The template for displaying theme header
 * 
 * Parts: Top bar, Logo, Navigation
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="<?php 
	echo esc_attr(apply_filters('bunyad_html_root_class', '')); 
?>">

<head>
<?php if(WP_ENABLE_GA):?>
	<!-- Google Tag Manager -->
	<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
	new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
	j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
	'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
	})(window,document,'script','dataLayer','GTM-5GL33HG4');</script>
	<!-- End Google Tag Manager -->
	<!-- Google tag (gtag.js) -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=G-CVMCS03NGQ"></script>
	<script>
	window.dataLayer = window.dataLayer || [];
	function gtag(){dataLayer.push(arguments);}
	gtag('js', new Date());

	gtag('config', 'G-CVMCS03NGQ');
	</script>
<?php endif; ?>

	<meta charset="<?php bloginfo('charset'); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<?php wp_head(); ?>

<?php if (Bunyad::options()->image_effects): // Only add effects CSS if JS enabled, hence via script ?>
	<script>
	document.querySelector('head').innerHTML += '<style class="bunyad-img-effects-css">.main-wrap .wp-post-image, .post-content img { opacity: 0; }</style>';
	</script>
<?php endif; ?>
<script src="https://accounts.google.com/gsi/client" async defer></script>
</head>

<body <?php body_class(); ?>>

<?php if(WP_ENABLE_GA):?>
	<!-- Google Tag Manager (noscript) -->
	<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-5GL33HG4"
	height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
	<!-- End Google Tag Manager (noscript) -->
<?php endif; ?>

<?php do_action('bunyad_begin_body'); ?>
<?php function_exists('wp_body_open') ? wp_body_open() : do_action('wp_body_open'); ?>

<?php if (Bunyad::options()->layout_type === 'boxed'): ?>
	<div class="ts-bg-cover"></div>
<?php endif; ?>

<div class="main-wrap">

	<?php 
	do_action('bunyad_header_before');

	/**
	 * Get the selected header template, unless overridden by Elementor Pro.
	 * 
	 * Note: Default is partials/header/layout.php
	 */
	if (apply_filters('bunyad_do_partial_header', true)) {

		$header = Bunyad::options()->header_layout;
		Bunyad::blocks()->load(
			'Header',
			[
				'style' => $header,
			]
		)
		->render();

		// Mobile header.
		Bunyad::blocks()->load(
			'Header',
			[
				'style' => 'a',
				'type'  => 'mobile',
			]
		)
		->render();
	}

	do_action('bunyad_header_after');
	?>

<?php do_action('bunyad_pre_main_content'); ?>