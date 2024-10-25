<?php
ini_set('error_reporting', E_ALL & ~E_DEPRECATED & ~E_STRICT & ~E_NOTICE);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1); 
header('Content-Type: application/json; charset=utf-8');
 
include_once("../saba/Connections/db_pdo.php"); 


    $sql = "SELECT ID FROM wp_posts WHERE post_status = :post_status";

    $all_post = $website_db->run($sql, array(":post_status"=>'publish'))->fetchAll();

// 提取所有狀態為 'publish' 的文章ID 
    foreach ($all_post as $row){
        $post_id = $row['ID'];

        // 為每個文章ID新增記錄到 wp_term_relationships
        $post_data = array(
            'object_id' => $post_id,
            'term_taxonomy_id' => 2470
        );
         
        $website_db->insert('wp_term_relationships', $post_data);
 
       echo $post_id . " 完成\n";
    }
     
?>
