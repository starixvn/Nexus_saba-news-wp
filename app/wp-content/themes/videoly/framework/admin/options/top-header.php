<?php
/*
 * Top Header Section
*/
$this->sections[] = array(
  'title' => esc_html__('Top Header', 'videoly'),
  'desc' => esc_html__('Change the header section configuration.', 'videoly'),
  'fields' => array(
    array(
      'id'    => 'top-header-enable-switch',
      'type'  => 'switch',
      'title' => esc_html__('Enable Top Header', 'videoly'),
      'options' => array(
        '1' => 'On',
        '0' => 'Off',
      ),
      'default'  => '1',
      'subtitle' => esc_html__('If on, this layout part will be displayed.', 'videoly'),
    ),
    array(
      'id'       => 'top-social-icons-category',
      'type'     => 'select',
      'title'    => esc_html__('Social Icons Category', 'videoly'),
      'subtitle' => esc_html__('Select desired category', 'videoly'),
      'options'  => videoly_get_terms_assoc('social-site-category'),
      'default'  => '',
    ),
  ), 
);
