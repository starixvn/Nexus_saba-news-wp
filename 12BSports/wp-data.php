<?php
require_once __DIR__ . '/wp-load.php';


$info = array();

if ((isset($_REQUEST["act"])) && ($_REQUEST["act"] == "test")) {
	$args = array( 'posts_per_page' => 5, 'offset'=> 0, 'category_name' => 'video' );

    $myposts = get_posts( $args );
	$info['code'] = 200;
    $info['data'] = $myposts;
}


$serialized_info = json_encode($info);
	print_r($serialized_info);