<?php
/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function telegram_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Off Canvas Section', 'telegram' ),
			'id'            => 'off-canvas-section',
			'description'   => esc_html__( 'Add widgets here.', 'telegram' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);

	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar Section', 'telegram' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'telegram' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);

    register_sidebar(
        array(
            'name'          => esc_html__( 'Home/Front Page Primary Widget Area', 'telegram' ),
            'id'            => 'home-front-page-primary-widget-area',
            'description'   => esc_html__( 'Add widgets here.', 'telegram' ),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h2 class="widget-title">',
            'after_title'   => '</h2>',
        )
    );

    register_sidebar(
        array(
            'name'          => esc_html__( 'Home/Front Page Secondary Widget Area', 'telegram' ),
            'id'            => 'home-front-page-secondary-widget-area',
            'description'   => esc_html__( 'Add widgets here.', 'telegram' ),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h2 class="widget-title">',
            'after_title'   => '</h2>',
        )
    );
    register_sidebar(
        array(
            'name' => __('Footer Column One', 'telegram'),
            'id' => 'footer-col-one',
            'description' => __('Displays items on footer section.','telegram'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget' => '</div>',
            'before_title'  => '<h3 class="widget-title">',
            'after_title'   => '</h3>',
        )
    );

    register_sidebar(
        array(
            'name' => __('Footer Column Two', 'telegram'),
            'id' => 'footer-col-two',
            'description' => __('Displays items on footer section.','telegram'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget' => '</div>',
            'before_title'  => '<h3 class="widget-title">',
            'after_title'   => '</h3>',
        )
    );

    register_sidebar(
        array(
            'name' => __('Footer Column Three', 'telegram'),
            'id' => 'footer-col-three',
            'description' => __('Displays items on footer section.','telegram'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget' => '</div>',
            'before_title'  => '<h3 class="widget-title">',
            'after_title'   => '</h3>',
        )
    );

}
add_action( 'widgets_init', 'telegram_widgets_init' );

/**
 * Widget Base Class.
 */
require get_template_directory() . '/inc/widgets/widget-base.php';
require get_template_directory() . '/inc/widgets/list-post-widget.php';
require get_template_directory() . '/inc/widgets/featured-post-with-list-widget.php';
require get_template_directory() . '/inc/widgets/double-category-post-widget.php';
require get_template_directory() . '/inc/widgets/social-menu-widget.php';