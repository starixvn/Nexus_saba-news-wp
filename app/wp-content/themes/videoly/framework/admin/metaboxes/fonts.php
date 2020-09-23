<?php



/*
 * Fonts Section
*/
$sections[] = array(
  'title'  => esc_html__('Fonts', 'videoly'),
  'desc'   => esc_html__('Change the font configuration.', 'videoly'),
  'icon'   => 'el-icon-cog',
  'fields' => array(

    array(
      'id'          => 'font-heading-local',
      'type'        => 'typography',
      'title'       => esc_html__('Heading', 'videoly'),
      'font-size'   => false,
      'line-height' => false,
      'text-align'  => false,
      'color'       => false,
      'output'      => array('#loading-text,
      .simple-text h1,
      .c-h1,.simple-text h2,
      .c-h2,.simple-text h3,.c-h3,.simple-text h4,.c-h4,.simple-text h5,.c-h5,.simple-text h6,
      .c-h6,.simple-text.font-poppins,.c-btn.type-1,.c-btn.type-2,.c-btn.type-3,.c-input,.tt-header .main-nav,
      .tt-mobile-nav > ul > li > a,.tt-mobile-nav > ul > li > ul > li > a,.tt-header .top-menu a,
      .tt-header .main-nav > ul > li:not(.mega) > ul > li > a,.tt-mega-list a,.tt-s-popup-title,.tt-mslide-label,
      .tt-title-text,.tt-title-block-2,
      .comment-reply-title,.tt-post-cat,.tt-post-label,.tt-post-bottom,.tt-tab-wrapper.type-1 .tt-nav-tab-item,
      .tt-f-list a,.tt-footer-copy,.tt-pagination a,.tt-blog-user-content,.tt-author-title,.tt-blog-nav-label,
      .tt-blog-nav-title,.tt-comment-label,.tt-search input[type="text"],.tt-share-title,.tt-heading-title,.tt-mblock-label, .page-numbers a,.page-numbers span, .footer_widget.widget_nav_menu li a'),
    ),
    array(
      'id'          => 'font-body-local',
      'type'        => 'typography',
      'title'       => esc_html__('Body', 'videoly'),
      'font-size'   => true,
      'line-height' => true,
      'text-align'  => false,
      'color'       => false,
      'output'      => array('body, .simple-text.title-droid h1,
      .simple-text.title-droid h2,
      .simple-text.title-droid h3,
      .simple-text.title-droid h4,
      .simple-text.title-droid h5,
      .simple-text.title-droid h6,
      .tt-s-popup-field input[type="text"],
      .tt-slide-2-title span,input,
      textarea,
      select'),
    ),
  ), // #fields
);



