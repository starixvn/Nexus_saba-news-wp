<?php
/**
 * Page Template Blog
 */

$this->sections[] = array(
  'title' => esc_html__(' Slider', 'videoly'),
  'icon' => 'el-icon-screen',
  'fields' => array(
    array(
      'id' => 'slider-enable-switch',
      'type' => 'switch',
      'title' => esc_html__('Enable Slider', 'videoly'),
      'options' => array(
        '1' => 'On',
        '0' => 'Off',
      ),
      'default' => 1,
      'subtitle' => esc_html__('If on, slider will be displayed.', 'videoly'),
    ),
    array(
      'id'       => 'slider-template',
      'type'     => 'select',
      'title'    => esc_html__('Template', 'videoly'),
      'subtitle' => esc_html__('Choose layout for slider.', 'videoly'),
      'options'  => array(
        'slider-style1' => esc_html__('Slider Style 1', 'videoly'),
        'slider-style2' => esc_html__('Slider Style 2', 'videoly'),
        'slider-style3' => esc_html__('Slider Style 3', 'videoly'),
        'slider-style4' => esc_html__('Slider Style 4', 'videoly'),
        'slider-style5' => esc_html__('Slider Style 5', 'videoly'),
        'slider-style6' => esc_html__('Slider Style 6', 'videoly'),
      ),
      'default' => 'slider-style1',
      'required'  => array('slider-enable-switch', 'equals', array(1)),
    ),
    array(
      'id'        => 'slider-category',
      'type'      => 'select',
      'title'     => esc_html__('Categories', 'videoly'),
      'subtitle'  => esc_html__('Select desired category for slider', 'videoly'),
      'options'   => videoly_element_values_page( 'category', array(
        'sort_order'  => 'ASC',
        'hide_empty'  => false,
        'taxonomies'  => 'category',
        'args'        => '',
      ) ),
      'multi'     => true,
      'default' => '',
      'required'  => array('slider-enable-switch', 'equals', array(1)),
    ),
    array(
      'id'        => 'slider-posts-per-page',
      'type'      => 'text',
      'title'     => esc_html__('No of Slides', 'videoly'),
      'subtitle'  => esc_html__('The number of items to show on slider.', 'videoly'),
      'default'   => '',
      'required'  => array('slider-enable-switch', 'equals', array(1)),
    ),
    array(
      'id'        => 'slider-editor-pick-category',
      'type'      => 'select',
      'title'     => esc_html__('Editor Pick Category', 'videoly'),
      'subtitle'  => esc_html__('Select desired category', 'videoly'),
      'options'   => videoly_element_values_page( 'category', array(
        'sort_order'  => 'ASC',
        'hide_empty'  => false,
        'taxonomies'  => 'category',
        'args'        => '',
      ) ),
      'multi'     => true,
      'default' => '',
      'required'  => array('slider-template', 'equals', array('slider-style2')),
    ),
  )
);
