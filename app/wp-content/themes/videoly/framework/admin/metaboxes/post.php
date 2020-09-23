<?php
/*
 * Post
*/
$sections[] = array(
  'icon' => 'el-icon-screen',
  'fields' => array(
    
    array(
      'id'=>'post-style-local',
      'type' => 'select',
      'title' => esc_html__('Post Style', 'videoly'),
      'subtitle' => esc_html__('Select post style.', 'videoly'),
      'options' => array(
        'default'                    => esc_html__('Default','videoly'),
        'default-title-left-aligned' => esc_html__('Default ( Post Title Left Aligned )','videoly'),
        'default-alt'                => esc_html__('Default ( Post Title Below Featured )','videoly'),
        'alternative'                => esc_html__('Alternative','videoly'),
      ),
      'default' => '',
    ),
    
    array(
      'id'=>'post-enable-post-share-local',
      'type' => 'button_set',
      'title' => esc_html__('Enable Post Share', 'videoly'),
      'subtitle'=> esc_html__('If on, post share section will be displayed on a single post page.', 'videoly'),
      'options' => array(
        '1' => 'On',
        '' => 'Default',
        '0' => 'Off',
      ),
      'default' => '',
    ),
    
    array(
      'id'=>'post-enable-author-description-local',
      'type' => 'button_set',
      'title' => esc_html__('Enable Author Description', 'videoly'),
      'subtitle'=> esc_html__('If on, author description will be displayed on a single post page.', 'videoly'),
      'options' => array(
        '1' => 'On',
        '' => 'Default',
        '0' => 'Off',
      ),
      'default' => '',
    ),
    
    array(
      'id'=>'post-enable-similar-posts-local',
      'type' => 'button_set',
      'title' => esc_html__('Enable Related Posts', 'videoly'),
      'subtitle'=> esc_html__('If on, similar posts will be displayed automatically on a single post page.', 'videoly'),
      'options' => array(
        '1' => 'On',
        '' => 'Default',
        '0' => 'Off',
      ),
      'default' => '',
    ),

  )
);