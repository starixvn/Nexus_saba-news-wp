<?php
/*
 * Footer Section
*/
$sections[] = array(
  'title' => esc_html__('Footer', 'videoly'),
  'desc' => esc_html__('Change the footer section configuration.', 'videoly'),
  'icon' => 'el-icon-cog',
  'fields' => array(

    array(
      'id' => 'footer-enable-switch-local',
      'type' => 'button_set',
      'title' => esc_html__('Enable Footer', 'videoly'),
      'options' => array(
        '1' => 'On',
        '' => 'Default',
        '0' => 'Off',
      ),
      'default' => '',
      'subtitle' => esc_html__('If on, this layout part will be displayed.', 'videoly'),
    ),
    array(
      'id'        => 'footer-sidebar-1-local',
      'type'      => 'select',
      'title'     => esc_html__('Footer Sidebar 1', 'videoly'),
      'subtitle'  => esc_html__('Select custom sidebar', 'videoly'),
      'options'   => videoly_get_custom_sidebars_list(),
      'default'   => '',
    ),
    array(
      'id'        => 'footer-sidebar-2-local',
      'type'      => 'select',
      'title'     => esc_html__('Footer Sidebar 2', 'videoly'),
      'subtitle'  => esc_html__('Select custom sidebar', 'videoly'),
      'options'   => videoly_get_custom_sidebars_list(),
      'default'   => '',
    ),
    array(
      'id'        => 'footer-sidebar-3-local',
      'type'      => 'select',
      'title'     => esc_html__('Footer Sidebar 3', 'videoly'),
      'subtitle'  => esc_html__('Select custom sidebar', 'videoly'),
      'options'   => videoly_get_custom_sidebars_list(),
      'default'   => '',
    ),
    array(
      'id'        => 'footer-sidebar-4-local',
      'type'      => 'select',
      'title'     => esc_html__('Footer Sidebar 4', 'videoly'),
      'subtitle'  => esc_html__('Select custom sidebar', 'videoly'),
      'options'   => videoly_get_custom_sidebars_list(),
      'default'   => '',
    ),
    array(
      'id' => 'random-number',
      'type' => 'info',
      'desc' => '<h2 style="color:#0073aa;font-weight:700;">'.esc_html__('Copyright Configuration', 'videoly').'</h2>'
    ),
    array(
      'id'    =>'footer-copyright-text-local',
      'type'  => 'text',
      'title' => esc_html__('Copyright Text', 'videoly'),
    ),
  ), // #fields
);
