<?php
/*
 * Post
*/
$sections[] = array(
  'icon' => 'el-icon-screen',
  'fields' => array(
    array(
      'id'       =>'post-video-lightbox',
      'type'     => 'switch',
      'title'    => esc_html__('Enable Lightbox', 'videoly'),
      'subtitle' => esc_html__('If on, lightbox will get enabled otherwise goes to single post.', 'videoly'),
      'options' => array(
        '1' => 'On',
        '0' => 'Off',
      ),
      'default' => '1',
    ),
    array(
      'id'        => 'post-video-url',
      'type'      => 'text',
      'title'     => esc_html__('Video URL', 'videoly'),
      'subtitle'  => esc_html__('Youtube, Vimeo or MP4 Video URL for e.g http://www.youtube.com/embed/wTcNtgA6gHs', 'videoly'),
      'default'   => '',
    ),
    array(
      'id'        => 'post-video-length',
      'type'      => 'text',
      'title'     => esc_html__('Video Length', 'videoly'),
      'subtitle'  => esc_html__('Youtube Video Length', 'videoly'),
      'default'   => '',
    ),    
    array(
      'id'        => 'post-video-quality',
      'type'      => 'text',
      'title'     => esc_html__('Video Quality', 'videoly'),
      'subtitle'  => esc_html__('Youtube Video Quality', 'videoly'),
      'default'   => '',
    ),
  )
);
