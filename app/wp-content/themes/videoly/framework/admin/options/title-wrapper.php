<?php
/*
 * Title Wrapper Section
*/

$this->sections[] = array(
  'title' => esc_html__('Title Wrapper', 'videoly'),
  'desc' => esc_html__('Change the title wrapper section configuration.', 'videoly'),
  'icon' => 'el-icon-cog',
  'fields' => array(

    array(
      'id' => 'title-wrapper-enable',
      'type'   => 'switch',
      'title' => esc_html__('Enable Title Wrapper', 'videoly'),
      'subtitle'=> esc_html__('If on, this layout part will be displayed.', 'videoly'),
      'options' => array(
        '1' => 'On',
        '0' => 'Off',
      ),
      'default' => '1',
    ),
    array(
      'id'               =>'title-wrapper-background',
      'type'             => 'background',
      'background-color' => false,
      'title'            => esc_html__('Background', 'videoly'),
      'subtitle'         => esc_html__('Title wrapper background, color and other options.', 'videoly'),
      'output' => array(
        'background' => '.tt-heading.title-wrapper'
      )
    ),
  ), // #fields
);
