<?php
require_once __DIR__ . '/wp-load.php';


$info = array();

if ((isset($_REQUEST["act"])) && ($_REQUEST["act"] == "home_video")) {
	$args = array( 'posts_per_page' => 5, 'offset'=> 0, 'category_name' => 'video' );

    $myposts = get_posts( $args );

	$html = '
		<div class="block block-video">
			<h2 class="block__title">
				<a title="@ViewResources.Video" href="/video" class="none-bg">Video</a>
			</h2>
			<div class="group-video">
				<div class="box-vertical">
	';
	$info['code'] = 200;
    $info['data'] = $myposts;
}


$serialized_info = json_encode($info);
	print_r($serialized_info);