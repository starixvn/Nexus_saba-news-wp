<?php
ini_set('error_reporting', E_ALL & ~E_DEPRECATED & ~E_STRICT & ~E_NOTICE);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1); 
header('Content-Type: application/json; charset=utf-8');

 
$info = array();

if ((isset($_REQUEST["act"])) && ($_REQUEST["act"] == "get_home_video")) {
	$args = array( 'posts_per_page' => 5, 'offset'=> 1, 'category_name' => 'video' );

    $myposts = get_posts( $args );
	$info['code'] = 200;
    $info['game'] = $myposts;  
}


$serialized_info = json_encode($info);
	print_r($serialized_info);