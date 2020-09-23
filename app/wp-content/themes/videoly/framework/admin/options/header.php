<?php
/*
 * Header Section
*/
$this->sections[] = array(
  'title' => esc_html__('Header', 'videoly'),
  'desc' => esc_html__('Change the header section configuration.', 'videoly'),
  'fields' => array(
    array(
      'id'    => 'header-enable-switch',
      'type'  => 'switch',
      'title' => esc_html__('Enable Header', 'videoly'),
      'options' => array(
        '1' => 'On',
        '0' => 'Off',
      ),
      'default'  => '1',
      'subtitle' => esc_html__('If on, this layout part will be displayed.', 'videoly'),
    ),
    array(
      'id'       => 'header-template',
      'type'     => 'select',
      'title'    => esc_html__('Template', 'videoly'),
      'subtitle' => esc_html__('Choose template for navigation header.', 'videoly'),
      'options'  => array(
        'header-style1' => esc_html__('Header Style 1','videoly'),
        'header-style2' => esc_html__('Header Style 2','videoly'),
      ),
      'default'  => 'header-style1',
      'validate' => 'not_empty',
    ),
    array(
      'id'    => 'header-height-switch',
      'type'  => 'switch',
      'title' => esc_html__('Header Height', 'videoly'),
      'options' => array(
        '1' => 'On',
        '0' => 'Off',
      ),
      'default'  => '1',
      'subtitle' => esc_html__('If on, space will create below header.', 'videoly'),
    ),
    array(
      'id'       =>'header-primary-menu',
      'type'     => 'select',
      'title'    => esc_html__('Primary Menu', 'videoly'),
      'subtitle' => esc_html__('Select a menu to overwrite the header menu location.', 'videoly'),
      'data'     => 'menus',
      'default'  => '',
    ),
    array(
      'id'       => 'header-social-icons-category',
      'type'     => 'select',
      'title'    => esc_html__('Social Icons Category', 'videoly'),
      'subtitle' => esc_html__('Select desired category', 'videoly'),
      'options'  => videoly_get_terms_assoc('social-site-category'),
      'default'  => '',
    ),
    array(
      'id'   => 'random-number',
      'type' => 'info',
      'desc' => '<h3 style="color:#0085ba;">Logo Module</h3>'
    ),
    array(
      'id'       =>'logo',
      'type'     => 'media',
      'url'      => true,
      'title'    => esc_html__('Logo', 'videoly'),
      'subtitle' => esc_html__('Upload the logo that will be displayed in the header.', 'videoly'),
    ),
    array(
      'id'       =>'side-header-logo',
      'type'     => 'media',
      'url'      => true,
      'title'    => esc_html__('Side Header Logo', 'videoly'),
      'subtitle' => esc_html__('Upload the logo that will be displayed in the sideheader header.', 'videoly'),
    ),
    array(
      'id'   => 'random-number',
      'type' => 'info',
      'desc' => '<h3 style="color:#0085ba;">Ads Module</h3>'
    ),
    array(
      'id'       =>'header-ads-img',
      'type'     => 'media',
      'url'      => true,
      'title'    => esc_html__('Image', 'videoly'),
    ),
    array(
      'id'       =>'header-ads-title',
      'type'     => 'text',
      'title'    => esc_html__('Title', 'videoly'),
      'default'  => esc_html__('Sponsored Banner', 'videoly'),
    ),
    array(
      'id'       =>'header-ads-size-text',
      'type'     => 'text',
      'title'    => esc_html__('Size Text', 'videoly'),
      'default'  => esc_html__('300 x 100', 'videoly'),
    ),    
    array(
      'id'       =>'header-ads-btn-text',
      'type'     => 'text',
      'title'    => esc_html__('Button Text', 'videoly'),
      'default'  => esc_html__('Subscribe', 'videoly'),
    ),
    array(
      'id'       =>'header-ads-btn-link',
      'type'     => 'text',
      'title'    => esc_html__('Button Link', 'videoly'),
      'default'  => esc_html__('#', 'videoly'),
    ),
  ), 
);
