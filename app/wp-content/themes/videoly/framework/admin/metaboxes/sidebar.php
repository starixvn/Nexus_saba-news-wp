<?php
/*
 * Sidebar Section
*/

$sections[] = array(
  'title' => esc_html__('Sidebar', 'videoly'),
  'desc' => esc_html__('Change the sidebar and configure it.', 'videoly'),
  'icon' => 'el-icon-adjust-alt',
  'fields' => array(
    array(
      'id'        => 'main-layout-local',
      'type'      => 'select',
      'compiler'  => true,
      'title'     => esc_html__('Main Layout', 'videoly'),
      'subtitle'  => esc_html__('Select main content and sidebar alignment. Choose between 1 or 2 column layout.', 'videoly'),
      'options'   => array(
        'default'       => esc_html__('1 Column', 'videoly'),
        'left_sidebar'  => esc_html__('2 - Columns Left', 'videoly'),
        'right_sidebar' => esc_html__('2 - Columns Right', 'videoly'),
      ),
      'default'   => '',
    ),
    array(
      'id'        => 'sidebar-local',
      'type'      => 'select',
      'title'     => esc_html__('Sidebar', 'videoly'),
      'subtitle'  => esc_html__('Select custom sidebar', 'videoly'),
      'options'   => videoly_get_custom_sidebars_list(),
      'default'   => '',
      'required'  => array('main-layout-local', 'equals', array('left_sidebar', 'right_sidebar')),
    ),

  ),
);
