<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Telegram
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
	<?php if ( function_exists( 'wp_body_open' ) ) {
	    wp_body_open();
	}
	?>
	<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#skip-link-to-content"><?php esc_html_e( 'Skip to content', 'telegram' ); ?></a>
	
	<?php if (telegram_get_option('show_site_header_addvertisement_section') == 1) { ?>
		<div class="twp-header-ad-section">
			<div class="twp-container">
				<div class="twp-advertisement-section twp-center">
					<a  href="<?php echo esc_url(telegram_get_option('site_header_advertisement_url')); ?>">
						<img src="<?php echo esc_url(telegram_get_option('site_header_section_advertisement')); ?>">
					</a>
				</div><!--/twp-advertisement-section-->
			</div>
		</div>
	<?php } ?>

	<div class="twp-topbar-section">
		<div class="twp-container-fluid twp-no-gap">
			<div class="twp-d-flex">
				<?php if (telegram_get_option('show_date_section') == 1) { ?>
				<div class="twp-date-section">
					<span><i class="ion ion-md-calendar"></i></span>
					<?php 
						echo date_i18n(get_option( 'date_format' )); 
					?>
				</div>
				<?php } ?>
				<div class="twp-tag-section">
					<?php telegram_header_tages()?>
				</div>
				<div class="twp-social-menu-section">
					<?php if (telegram_get_option('show_top_social_section') == 1) { ?>
						<?php if (has_nav_menu('social-nav')) { ?>
							<div class="twp-social-section">
								<?php
									wp_nav_menu(
										array('theme_location' => 'social-nav',
											'link_before' => '<span>',
											'link_after' => '</span>',
											'menu_id' => 'social-menu',
											'fallback_cb' => false,
											'menu_class' => 'twp-social twp-social-with-bg'
										));
									?>
							</div><!--/twp-topbar-content-right-->
						<?php } ?>		
					<?php } ?>
				</div>								
			</div>
		</div>
	</div>
	<?php 
		if (has_header_image()) {
			$header_class = "twp-header-with-image";
			$twp_header_image_url = get_header_image();
		}else{
			$header_class ="";
			$twp_header_image_url = '';
		}

	?>
	<header id="masthead" class="site-header">
		<div class="site-branding  <?php echo $header_class; ?>">
			<div class="twp-header-image bg-image" style="background-image:url(<?php echo esc_url($twp_header_image_url); ?>)"></div>
			<div class="twp-container">
				<?php 
					if (telegram_get_option('show_addvertisement_section') == 1){ 
						$header_wrapper_class = "twp-wrapper";
					}else{
						$header_wrapper_class = "twp-center";
					}
				?>
				<div class="<?php echo $header_wrapper_class; ?>">
					<div class="twp-logo-section">
						<?php
							the_custom_logo();
							if ( is_front_page() && is_home() ) :
								?>
								<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
								<?php
							else :
								?>
								<p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
								<?php
							endif;
							$telegram_description = get_bloginfo( 'description', 'display' );
							if ( $telegram_description || is_customize_preview() ) :
								?>
								<p class="site-description"><?php echo esc_html($telegram_description); ?></p>
						<?php endif; ?>
					</div>
					<?php if (telegram_get_option('show_addvertisement_section') == 1) { ?>
						<div class="twp-advertisement-section">
							<a  href="<?php echo esc_url(telegram_get_option('top_section_advertisement_url')); ?>">
								<img src="<?php echo esc_url(telegram_get_option('top_section_advertisement')); ?>">
							</a>
						</div><!--/twp-advertisement-section-->
					<?php } ?>
				</div>
			</div>
		</div><!-- .site-branding -->

		<?php if ((telegram_get_option('enable_header_fix_nav')) == 1) { ?>
			<div class="sticky-nav-menu" id="sticky-nav-menu" style="height:1px;"></div>
		<?php } ?>
		<div class="twp-navbar-section" id="twp-navigation-bar">
			<div class="twp-container twp-no-gap">
				<div class="twp-d-flex twp-wrapper">
					<nav id="site-navigation" class="main-navigation twp-site-navigation desktop">
						<?php
						wp_nav_menu(
							array(
								'theme_location' => 'primary-nav',
								'menu_id'        => 'primary-menu',
								'menu_class'     => 'twp-nav-menu'
							)
						);
						?>
						<div class="twp-menu-icon-section">
							<button id="nav-menu-icon">
								<span class="twp-menu-icon-with-box">
									<span class="twp-line twp-line-1"></span>
									<span class="twp-line twp-line-2"></span>
									<span class="twp-line twp-line-3"></span>
								</span>
							</button>
						</div>
						
					</nav><!-- #site-navigation -->
					<div class="twp-site-feature">
						<div class="twp-dark-mode">
							<button id="color-mode">
								<i class="ion ion-md-moon"></i>
								<span class="twp-tooltip dark-mode-caption"><?php esc_html_e( 'Dark Mode', 'telegram' ); ?> </span>
								<span class="twp-tooltip light-mode-caption"><?php esc_html_e( 'Light Mode', 'telegram' ); ?> </span>
							</button>
						</div>
						<div class="twp-search-section">
							<a href="javascript:void(0)" class="skip-link-search-start"></a>
							<button id="search">
								<i class="ion ion-md-search"></i>
								<span class="twp-tooltip"> <?php esc_html_e( 'Search', 'telegram' ); ?> </span>
							</button>
						</div>
						<?php if( is_active_sidebar('off-canvas-section') ) {  ?>
							<div class="twp-offcanvas-section">
								<button id="offcanvas">
									<span class="twp-menu-icon">
										<span class="twp-line twp-line-1"></span>
										<span class="twp-line twp-line-2"></span>
										<span class="twp-line twp-line-3"></span>
									</span>
								</button>
							</div>
						<?php } ?>
						
						<?php if ((telegram_get_option('enable_mailchimp_subscription')) == 1) { ?>
							<div class="twp-nav-newsletter">
								<button id="newsletter">
									<span class="twp-icon"><i class="ion ion-md-mail"></i></span>
									<span class="twp-caption"><?php esc_html_e( 'Subscribe', 'telegram' ); ?></span>
								</button>
							</div>
						<?php } ?>
					</div>
				</div>
			</div>

			<div class="twp-nav-search-content" id="search-content">
				<div class="twp-wrapper twp-d-flex">
					<a href="javascript:void(0)" class="skip-link-search-start-1"></a>
					<a href="javascript:void(0)" class="skip-link-search-start-2"></a>
					<?php get_search_form(); ?>
					<div class="twp-close">
						<button id="search-close"><i class="ion ion-md-close"></i></button>
					</div>
				</div>
			</div>
			<a href="javascript:void(0)" class="skip-link-search-end"></a>

		</div>
	</header><!-- #masthead -->

	<!-- breaking news section -->
	<?php if (telegram_get_option('show_breaking_on_nav') == 1) { ?>
		<div class="twp-breaking-news-section"> 
			<div class="twp-container">
				<div class="twp-wrapper clearfix">
					<div class="twp-title-section">
						<h2 class="twp-section-title"><?php echo esc_html(telegram_get_option('breaking_section_title'));?></h2>
					</div>
					<?php
					  if( is_rtl() ) {
						$rtl = 'true';
						}else{
							$rtl = 'false';
						}
					?>
					<div class="twp-breaking-news-slider" data-slick='{"rtl": <?php echo esc_attr( $rtl ); ?>}'>
						<?php
							$telegram_breaking_category = esc_attr(telegram_get_option('select_category_for_breaking_section'));
							$telegram_breaking_sort = esc_attr(telegram_get_option('sort_breaking_post_by'));
							
							$args = array(
								'post_type' => 'post',
								'posts_per_page' =>  10,
								'ignore_sticky_posts' => 1,
								'orderby' => esc_attr($telegram_breaking_sort),
								'order' => 'DESC',
								'cat' => absint($telegram_breaking_category),
							);
							$twp_recent_nav_posts = new WP_Query($args);
							if($twp_recent_nav_posts->have_posts()):?>
								<?php while ($twp_recent_nav_posts->have_posts()):$twp_recent_nav_posts->the_post();?>
									<div class="twp-breaking-post">
										<?php if(has_post_thumbnail()){
                                                $thumb = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'thumbnail' );
                                                $url = $thumb['0'];
                                            }
                                            else{
                                                $url = '';
                                            }?> 
										<div class="twp-image-section bg-image" style="background-image:url('<?php echo esc_url($url);?>')">
											<a href="<?php the_permalink(); ?>"></a>
										</div>  
										<div class="twp-desc">
											<h3 class="twp-post-title twp-post-title-xs"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
										</div>
									</div>
								<?php endwhile;wp_reset_postdata();?>
							<?php endif; ?>
					</div>
				</div>
			</div>
		</div><!--  twp-breaking-news-section-->
	<?php } ?>

	<?php if ((telegram_get_option('enable_mailchimp_subscription')) == 1) { ?>
		<div class="twp-newsletter-section twp-popup-section" id="newsletter-content" >
			<div class="twp-wrapper">
				<div class="twp-newsletter-post twp-d-flex">
					<div class="twp-image-section bg-image" style="background-image:url('<?php echo esc_url(telegram_get_option('mailchimp_subscription_image')); ?>')">
					</div>
					<div class="twp-desc">

						<a href="javascript:void(0)" class="skip-link-newsletter-start"></a>

						<div class="twp-close">
							<button id="newsletter-close"><i class="ion ion-md-close"></i></button>
						</div>
						<h2> <?php echo esc_html(telegram_get_option('mailchimp_subscription_title')); ?></h2>
						<?php $telegram_mailchimp_code = wp_kses_post(telegram_get_option('mailchimp_subscription_shortcode'));
						echo do_shortcode($telegram_mailchimp_code); ?>

						<a href="javascript:void(0)" class="skip-link-newsletter-end-1"></a>
						<a href="javascript:void(0)" class="skip-link-newsletter-end-2"></a>

					</div>
				</div>
			</div>
		</div>
	<?php } ?>
	
	<div class="twp-body-overlay" id="body-overlay"></div>
	<div class="twp-scroll-top" id="scroll-top"><i class="ion ion-ios-arrow-round-up"></i></div>


	<?php if( is_active_sidebar('off-canvas-section') ) {  ?>

		<div class="twp-offcanvas-content-section" id="offcanvas-content">

			<a href="javascript:void(0)" class="skip-link-offcanvas-start"></a>

			<div class="twp-close" id="offcanvas-close"><button><i class="ion ion-md-close"></i></button></div>
			
			<?php dynamic_sidebar('off-canvas-section'); ?>
			
			<a href="javascript:void(0)" class="skip-link-offcanvas-end-1"></a>
			<a href="javascript:void(0)" class="skip-link-offcanvas-end-2"></a>

		</div>

	<?php } ?>

	<div class="twp-mobile-menu-section" id="mobile-menu">

		<a href="javascript:void(0)" class="skip-link-menu-start"></a>
		<div class="twp-close"><button  id="mobile-menu-close"><i class="ion ion-md-close"></i></button></div>
		<div class="menu-caontainer-mobile"></div>
		<a href="javascript:void(0)" class="skip-link-menu-end"></a>
	</div>
	
	<a href="javascript:void(0)" class="skip-link-menu-end"></a>

	<?php if( ( telegram_get_option('enable_preloader') ) == 1 ){ ?>
		<div id="preloader">
			<div id="status">&nbsp;</div>
		</div>
	<?php } ?>

	<!-- breadcrums -->
	<?php do_action( 'telegram_action_get_breadcrumb' ); ?>	
	<div id="skip-link-to-content">