<?php
/*
 * Title Wrapper Section
*/

$this->sections[] = array(
  'title' => esc_html__('404 Page', 'videoly'),
  'desc' => esc_html__('Change the title wrapper section configuration.', 'videoly'),
  'icon' => 'el-icon-cog',
  'fields' => array(
    array(
      'id'=>'page404-content',
      'type' => 'textarea',
      'title' => esc_html__('Content', 'videoly'),
      'subtitle' => esc_html__('Content for 404 page.', 'videoly'),
    ),
  ), // #fields
);
