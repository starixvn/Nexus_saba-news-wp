<?php
/**
 * Telegram functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Telegram
 */

if ( ! defined( '_S_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( '_S_VERSION', '1.0.0' );
}

if ( ! function_exists( 'telegram_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function telegram_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on Telegram, use a find and replace
		 * to change 'telegram' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'telegram', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus( array(
			'primary-nav' => esc_html__( 'Primary Menu', 'telegram' ),
			'social-nav' => esc_html__( 'Social Menu', 'telegram' ),
			'footer-nav' => esc_html__( 'Footer Menu', 'telegram' ),
		) );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
				'style',
				'script',
			)
		);

		// Set up the WordPress core custom background feature.
		add_theme_support(
			'custom-background',
			apply_filters(
				'telegram_custom_background_args',
				array(
					'default-color' => 'ffffff',
					'default-image' => '',
				)
			)
		);
		
		/*
		 * Posts Format.
		 *
		 * https://wordpress.org/support/article/post-formats/
		 */
		add_theme_support( 'post-formats', array(
		    'video',
		    'audio',
		    'gallery',
		    'quote',
		    'image'
		) );

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support(
			'custom-logo',
			array(
				'height'      => 250,
				'width'       => 250,
				'flex-width'  => true,
				'flex-height' => true,
			)
		);
	}
endif;
add_action( 'after_setup_theme', 'telegram_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function telegram_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'telegram_content_width', 640 );
}
add_action( 'after_setup_theme', 'telegram_content_width', 0 );

/**
 * function for google fonts
 */
if (!function_exists('telegram_fonts_url')) :

    /**
     * Return fonts URL.
     *
     * @since 1.0.0
     * @return string Fonts URL.
     */
    function telegram_fonts_url()
    {
    	$fonts_url = '';
    	$fonts     = array();

    	$telegram_primary_font = 'Roboto:100,300,400,500,700';

    	$telegram_fonts = array();
    	$telegram_fonts[]=$telegram_primary_font;

    	  $telegram_fonts_stylesheet = '//fonts.googleapis.com/css?family=';

    	  $i  = 0;
    	  for ($i=0; $i < count( $telegram_fonts ); $i++) { 

    	    if ( 'off' !== sprintf( _x( 'on', '%s font: on or off', 'telegram' ), $telegram_fonts[$i] ) ) {
    	    $fonts[] = $telegram_fonts[$i];
    	  }

    	  }

    	if ( $fonts ) {
    	  $fonts_url = add_query_arg( array(
    	    'family' => urldecode( implode( '|', $fonts ) ),
    	  ), 'https://fonts.googleapis.com/css' );
    	}

    	return $fonts_url;
    }
endif;

/**
 * Enqueue scripts and styles.
 */
function telegram_scripts() {
	$fonts_url = telegram_fonts_url();
	if (!empty($fonts_url)) {
	    wp_enqueue_style('telegram-google-fonts', $fonts_url, array(), null);
	}
	wp_enqueue_style( 'telegram-style', get_stylesheet_uri(), array(), _S_VERSION );
	wp_enqueue_style( 'slick', get_template_directory_uri() . '/assets/resources/slick/css/slick.min.css');
    wp_enqueue_style( 'ionicons', get_template_directory_uri() . '/assets/resources/ionicons/css/ionicons.min.css');
    wp_enqueue_style( 'magnific', get_template_directory_uri() . '/assets/resources/magnific/css/magnific-popup.css');

	wp_enqueue_script( 'telegram-navigation', get_template_directory_uri() . '/js/navigation.js', array(), _S_VERSION, true );
	wp_enqueue_script( 'slick', get_template_directory_uri() . '/assets/resources/slick/js/slick.min.js', array('jquery'), '', 1);
	wp_enqueue_script( 'magnific', get_template_directory_uri() . '/assets/resources/magnific/js/jquery.magnific-popup.min.js', array('jquery'), '', 1);
	wp_enqueue_script('theiaStickySidebar', get_template_directory_uri() . '/assets/resources/theiaStickySidebar/theia-sticky-sidebar.min.js', array('jquery'), '', true);
	
	wp_enqueue_script( 'telegram-main', get_template_directory_uri() . '/assets/js/main.js', array('jquery'), '', 2);

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'telegram_scripts' );


/**
 * Enqueue admin scripts and styles.
 */
function telegram_admin_scripts( $hook ) {

	wp_enqueue_style( 'telegram-admin-style', get_template_directory_uri() . '/assets/css/twp-admin.css', array(), '1.0.0' );

}
add_action( 'admin_enqueue_scripts', 'telegram_admin_scripts' );


/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/widgets/widget-init.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';
require get_template_directory() . '/inc/hooks/booster-extention-component.php';
require get_template_directory() . '/inc/hooks/banner-with-slider.php';
require get_template_directory() . '/inc/hooks/news-blog-grid.php';
require get_template_directory() . '/inc/hooks/list-post-with-add.php';
require get_template_directory() . '/inc/hooks/carousel-slider.php';

// tgmpa plugin activation
require get_template_directory() . '/assets/resources/tgmpa/recommended-plugins.php';


/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}


add_filter( 'walker_nav_menu_start_el', 'telegram_add_description', 10, 2 );
function telegram_add_description( $item_output, $item ) {
    $description = $item->post_content;
    if (('' !== $description) && (' ' !== $description) ) {
        return preg_replace( '/(<a.*)</', '$1' . '<span class="menu-description">' . $description . '</span><', $item_output) ;
    }
    else {
        return $item_output;
    };
}

add_filter('wp_nav_menu_items', 'telegram_add_home_link', 1, 2);
function telegram_add_home_link($items, $args){
    if( $args->theme_location == 'primary-nav' ){
        $item = '<li class="brand-home"><a title="Home" href="'. esc_url( home_url() ) .'">' . "<span class='ion ion-ios-home'></span>" . '</a></li>';
        $items = $item . $items;
    }
    return $items;
}