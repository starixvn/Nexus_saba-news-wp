<?php
/*
 * Content Section
*/

$sections[] = array(
  'title' => esc_html__('Content', 'videoly'),
  'desc' => esc_html__('Change the content section configuration.', 'videoly'),
  'icon' => 'el-icon-lines',
  'fields' => array(
    array(
      'id'        => 'page-margin',
      'type'      => 'select',
      'title'     => esc_html__('Content Margin', 'videoly'),
      'subtitle'  => esc_html__('Select desired margin for the content', 'videoly'),
      'options'   => array(
        'top-bottom'  => esc_html__('Top & bottom margin', 'videoly'),
        'only-top'    => esc_html__('Only top margin', 'videoly'),
        'only-bottom' => esc_html__('Only bottom margin', 'videoly'),
        'no-margin'   => esc_html__('No margin', 'videoly'),
      ),
      'default' => 'top-bottom',
    ),
    array(
      'id'       => 'page-show-special-content-after-content',
      'type'     => 'switch', 
      'title'    => esc_html__('Show Special Content After Page Content', 'videoly'),
      'subtitle' => esc_html__('If on, selected page content will be displayed after content.', 'videoly'),
      'default'  => 0,
    ),
    
    array(
      'id'       => 'page-after-special-content',
      'type'     => 'select',
      'title'    => esc_html__('Special Content Displayed After Content', 'videoly'),
      'subtitle' => esc_html__('Select special content item to be displayed after page content', 'videoly'),
      'options'  => videoly_get_special_content_array(),
      'default'  => '',
      'required' => array('page-show-special-content-after-content' ,'=', '1')
    ),
  ),
);
