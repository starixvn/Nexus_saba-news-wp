<?php
/*
 * Advanced
*/
$this->sections[] = array(
  'title' => esc_html__('Blog Single Posts', 'videoly'),
  'desc' => esc_html__('Blog single posts confugration.', 'videoly'),
  'fields' => array(
    array(
      'id'=>'post-enable-post-share',
      'type' => 'switch',
      'title' => esc_html__('Enable Post Share', 'videoly'),
      'subtitle'=> esc_html__('If on, post share section will be displayed on a single post page.', 'videoly'),
      'options' => array(
        '1' => 'On',
        '0' => 'Off',
      ),
      'default' => '0',
    ),
    
    array(
      'id'=>'post-enable-author-description',
      'type' => 'switch',
      'title' => esc_html__('Enable Author Description', 'videoly'),
      'subtitle'=> esc_html__('If on, author description will be displayed on a single post page.', 'videoly'),
      'options' => array(
        '1' => 'On',
        '0' => 'Off',
      ),
      'default' => '1',
    ),
    array(
      'id'       => 'author-social-icons-category',
      'type'     => 'select',
      'title'    => esc_html__('Author Social Icons Category', 'videoly'),
      'subtitle' => esc_html__('Select desired category', 'videoly'),
      'options'  => videoly_get_terms_assoc('social-site-category'),
      'default'  => '',
      'required' => array('post-enable-author-description', 'equals', array('1')),
    ),
    array(
      'id'=>'post-enable-similar-posts',
      'type' => 'button_set',
      'title' => esc_html__('Enable Related Posts', 'videoly'),
      'subtitle'=> esc_html__('If on, similar posts will be displayed automatically on a single post page.', 'videoly'),
      'options' => array(
        '1' => 'On',
        '0' => 'Off',
      ),
      'default' => '1',
    ),
  ), // #fields
);
