<?php
/*
 * Header Section
*/
$sections[] = array(
  'title' => esc_html__('General', 'videoly'),
  'desc' => esc_html__('Change the general section of theme configuration.', 'videoly'),
  'icon' => 'el-icon-cog',
  'fields' => array(
    array(
      'id'        => 'page-layout-local',
      'type'      => 'select',
      'compiler'  => true,
      'title'     => esc_html__('Page Layout', 'videoly'),
      'subtitle'  => esc_html__('Select page layout.', 'videoly'),
      'options'   => array(
        'full-page' => esc_html__('Full', 'videoly'),
        'boxed'     => esc_html__('Boxed', 'videoly'),
      ),
      'default'   => '',
    ),
    array(
      'id'        => 'theme-skin-accent-first-local',
      'type'      => 'color',
      'title'     => esc_html__('Accent Color', 'videoly'),
      'desc'     => esc_html__( 'This color is main color (optional).', 'videoly' ),
      'default'   => '',
    ),
  ), // #fields
);
