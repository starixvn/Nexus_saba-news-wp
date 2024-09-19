<?php 
/**
 * Common post footer components.
 */

// Add bottom social share
get_template_part('partials/single/social-share-bot');

// Add next/previous.
get_template_part('partials/single/post-navigation');

// Add author box.
do_action('bunyad_author_box_before');
get_template_part('partials/single/author-box');
do_action('bunyad_author_box_after');

// Add related posts.
get_template_part('partials/single/related-posts');