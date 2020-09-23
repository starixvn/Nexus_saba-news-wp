<?php
/*
 * Custom Code
*/

$this->sections[] = array(
  'title' => esc_html__('Custom CSS', 'videoly'),
  'desc' => esc_html__('Easily add custom CSS to your website.', 'videoly'),
  'icon' => 'el-icon-wrench',
  'fields' => array(

    array(
        'id'       => 'css_editor',
        'type'     => 'ace_editor',
        'title'    => esc_html__('CSS Code', 'videoly'),
        'subtitle' => esc_html__('Insert your custom CSS code right here. It will be displayed globally in the website.', 'videoly'),
        'mode'     => 'css',
        'theme'    => 'monokai',
        'desc'     => '',
        'default'  => ""
    )
  ),
);
