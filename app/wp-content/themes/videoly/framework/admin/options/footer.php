<?php
/*
 * Footer Section
*/
$this->sections[] = array(
  'title' => esc_html__('Footer', 'videoly'),
  'desc' => esc_html__('Change the footer section configuration.', 'videoly'),
  'icon' => 'el-icon-cog',
  'fields' => array(

    array(
      'id' => 'footer-enable-switch',
      'type' => 'switch',
      'title' => esc_html__('Enable Footer', 'videoly'),
      'options' => array(
        '1' => 'On',
        '0' => 'Off',
      ),
      'default' => '1',
      'subtitle' => esc_html__('If on, this layout part will be displayed.', 'videoly'),
    ),
    array(
      'id'        => 'footer-sidebar-1',
      'type'      => 'select',
      'title'     => esc_html__('Footer Sidebar 1', 'videoly'),
      'subtitle'  => esc_html__('Select custom sidebar', 'videoly'),
      'options'   => videoly_get_custom_sidebars_list(),
      'default'   => '',
    ),
    array(
      'id'        => 'footer-sidebar-2',
      'type'      => 'select',
      'title'     => esc_html__('Footer Sidebar 2', 'videoly'),
      'subtitle'  => esc_html__('Select custom sidebar', 'videoly'),
      'options'   => videoly_get_custom_sidebars_list(),
      'default'   => '',
    ),
    array(
      'id'        => 'footer-sidebar-3',
      'type'      => 'select',
      'title'     => esc_html__('Footer Sidebar 3', 'videoly'),
      'subtitle'  => esc_html__('Select custom sidebar', 'videoly'),
      'options'   => videoly_get_custom_sidebars_list(),
      'default'   => '',
    ),
    array(
      'id'        => 'footer-sidebar-4',
      'type'      => 'select',
      'title'     => esc_html__('Footer Sidebar 4', 'videoly'),
      'subtitle'  => esc_html__('Select custom sidebar', 'videoly'),
      'options'   => videoly_get_custom_sidebars_list(),
      'default'   => '',
    ),
    array(
      'id' => 'random-number',
      'type' => 'info',
      'desc' => '<h3 style="color:#0073aa;font-weight:700;">'.esc_html__('Copyright Configuration', 'videoly').'</h3>'
    ),
    array(
      'id'    =>'footer-copyright-text',
      'type'  => 'text',
      'title' => esc_html__('Copyright Text', 'videoly'),
    ),
  ), // #fields
);

