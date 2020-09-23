<?php
/*
 * Title Wrapper Section
*/
$sections[] = array(
  'title' => esc_html__('Title Wrapper', 'videoly'),
  'desc' => esc_html__('Change the title wrapper section configuration.', 'videoly'),
  'icon' => 'el-icon-cog',
  'fields' => array(

    array(
      'id'       => 'title-wrapper-enable-local',
      'type'     => 'button_set',
      'title'    => esc_html__('Enable Title Wrapper', 'videoly'),
      'subtitle' => esc_html__('If on, this layout part will be displayed.', 'videoly'),
      'options' => array(
        '1' => 'On',
        ''  => 'Default',
        '0' => 'Off',
      ),
      'default' => '',
    ),
    array(
      'id'               =>'title-wrapper-background-local',
      'type'             => 'background',
      'background-color' => false,
      'title'            => esc_html__('Background', 'videoly'),
      'subtitle'         => esc_html__('Title wrapper background, color and other options.', 'videoly'),
      'output' => array(
        'background' => '.tt-heading.title-wrapper'
      )
    ),
    array(
      'id'        => 'title-wrapper-subheading-local',
      'type'      => 'text',
      'title'     => esc_html__('Sub Heading', 'videoly'),
      'default'   => '',
    ),
  ), // #fields
);
