<?php
/*
 * General Section
*/
$this->sections[] = array(
  'title' => esc_html__('General', 'videoly'),
  'desc' => esc_html__('Configure general styles.', 'videoly'),
  'subsection' => true,
  'fields'  => array(
    array(
      'id' => 'general-loader-enable-switch',
      'type' => 'switch',
      'title' => esc_html__('Enable Loader', 'videoly'),
      'options' => array(
        '1' => 'On',
        '0' => 'Off',
      ),
      'default' => '1',
      'subtitle' => esc_html__('If on, this layout part will be displayed.', 'videoly'),
    ),
    array(
      'id'        => 'page-layout',
      'type'      => 'select',
      'compiler'  => true,
      'title'     => esc_html__('Page Layout', 'videoly'),
      'subtitle'  => esc_html__('Select page layout.', 'videoly'),
      'options'   => array(
        'full-page' => esc_html__('Full', 'videoly'),
        'boxed'     => esc_html__('Boxed', 'videoly'),
      ),
      'default'   => 'full-page',
    ),
    array(
      'id'        => 'theme-skin-accent-first',
      'type'      => 'color',
      'title'     => esc_html__('Accent Color', 'videoly'),
      'desc'     => esc_html__( 'This color is main color.', 'videoly' ),
      'default'   => '',
    ),
    array(
      'id'        => 'main-layout',
      'type'      => 'select',
      'compiler'  => true,
      'title'     => esc_html__('Main Layout', 'videoly'),
      'subtitle'  => esc_html__('Select main content and sidebar alignment. Choose between 1 or 2 column layout.', 'videoly'),
      'options'   => array(
        'default'       => esc_html__('1 Column', 'videoly'),
        'left_sidebar'  => esc_html__('2 - Columns Left', 'videoly'),
        'right_sidebar' => esc_html__('2 - Columns Right', 'videoly'),
      ),
      'default'   => 'default',
    ),
    array(
      'id'        => 'sidebar',
      'type'      => 'select',
      'title'     => esc_html__('Sidebar', 'videoly'),
      'subtitle'  => esc_html__('Select custom sidebar', 'videoly'),
      'options'   => videoly_get_custom_sidebars_list(),
      'default'   => '',
      'required'  => array('main-layout', 'equals', array('left_sidebar', 'right_sidebar')),
    ),
    array(
      'id'       => 'custom-sidebars',
      'type'     => 'multi_text',
      'title'    => esc_html__( 'Custom Sidebars', 'videoly' ),
      'subtitle' => esc_html__( 'Custom sidebars can be assigned to any page or post.', 'videoly' ),
      'desc'     => esc_html__( 'You can add as many custom sidebars as you need.', 'videoly' )
    ),
  ),
);



