<?php
/*
 * Header Section
*/
$sections[] = array(
  'title' => esc_html__('Header', 'videoly'),
  'desc' => esc_html__('Change the header section configuration.', 'videoly'),
  'icon' => 'el-icon-cog',
  'fields' => array(

    array(
      'id' => 'header-enable-switch-local',
      'type' => 'button_set',
      'title' => esc_html__('Enable Header', 'videoly'),
      'options' => array(
        '1' => 'On',
        '' => 'Default',
        '0' => 'Off',
      ),
      'default' => '',
      'subtitle' => esc_html__('If on, this layout part will be displayed.', 'videoly'),
    ),
    array(
      'id'       => 'header-template-local',
      'type'     => 'select',
      'title'    => esc_html__('Template', 'videoly'),
      'subtitle' => esc_html__('Choose template for navigation header.', 'videoly'),
      'options'  => array(
        'header-style1' => esc_html__('Header Style 1','videoly'),
        'header-style2' => esc_html__('Header Style 2','videoly'),
      ),
      'default' => '',
      'validate' => '',
    ),
    array(
      'id' => 'header-height-switch-local',
      'type' => 'button_set',
      'title' => esc_html__('Header Height', 'videoly'),
      'options' => array(
        '1' => 'On',
        '' => 'Default',
        '0' => 'Off',
      ),
      'default' => '',
      'subtitle' => esc_html__('If on, space will create below header.', 'videoly'),
    ),
    array(
      'id'       => 'header-social-icons-category-local',
      'type'     => 'select',
      'title'    => esc_html__('Social Icons Category', 'videoly'),
      'subtitle' => esc_html__('Select desired category', 'videoly'),
      'options'  => videoly_get_terms_assoc('social-site-category'),
      'default'  => '',
      'required'  => array('header-template-local', 'equals', array('menu-left-with-social-right', 'menu-right-with-social')),
    ),
    array(
      'id'=>'header-primary-menu-local',
      'type' => 'select',
      'title' => esc_html__('Primary Menu', 'videoly'),
      'subtitle' => esc_html__('Select a menu to overwrite the header menu location.', 'videoly'),
      'data' => 'menus',
      'default' => '',
    ),

    array(
      'id' => 'random-number',
      'type' => 'info',
      'desc' => esc_html__('Logo Module Configuration.', 'videoly')
    ),

    array(
      'id'=>'logo-local',
      'type' => 'media',
      'url' => true,
      'title' => esc_html__('Logo', 'videoly'),
      'subtitle' => esc_html__('Upload the logo that will be displayed in the header.', 'videoly'),
    ),
  ), // #fields
);
