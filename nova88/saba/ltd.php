<?php
require('/home/www/saba_th/wp-load.php'); // 載入 WordPress 核心文件

$qty = 10; // 一次取得的文章數量
if (isset($_GET['qty'])) {
    $qty = $_GET['qty'];
}

function uuid($prefix = '')
  {
    $chars = md5(uniqid(mt_rand(), true));
    $uuid  = substr($chars,0,8) . '-';
    $uuid .= substr($chars,8,4) . '-';
    $uuid .= substr($chars,12,4) . '-';
    $uuid .= substr($chars,16,4) . '-';
    $uuid .= substr($chars,20,12);
    return $prefix . $uuid;
  }

$args = array(
    'posts_per_page' => $qty, // 獲取最新的 N 篇文章
    'orderby' => 'date', // 根據日期排序
    'order' => 'DESC' // 最新的文章先顯示
);

$posts = get_posts($args); // 獲取文章

header('Content-Type: text/xml; charset=utf-8'); // 設置頭部為 XML 格式

echo '<?xml version="1.0" encoding="UTF-8"?>';
echo '<articles>';
    echo '<UUID>' .  uuid() . '</UUID>';
    echo '<time>' . time() . '000</time>'; 

foreach ($posts as $post) {
    setup_postdata($post);
    $post_id = $post->ID;
    $post_title = esc_html(get_the_title($post_id));
    $post_url = get_permalink($post_id);
    $post_url = $post_url . '?utm_source=line-th&amp;utm_medium=sharing-news&amp;utm_campaign=th-linetoday';
    $post_publish_time = get_the_date('U', $post_id); // Unix timestamp
    // 轉換成泰國時間
    $post_publish_time = $post_publish_time - 25200; // 7 小時
    $post_publish_time_end = get_the_date('U', $post_id) + 7776000; // Unix timestamp
    $post_categories = get_the_category($post_id);
    $category_name = $post_categories[0]->cat_name;
    // 取得文章封面圖片
    $post_thumbnail_id = get_post_thumbnail_id($post_id);
    $post_thumbnail_url = wp_get_attachment_image_src($post_thumbnail_id, 'full')[0];
    // 取得文章內容
    $post_content = apply_filters('the_content', $post->post_content);

    $post_content = preg_replace(
        '/<iframe\s+.*?src="\/\/www\.youtube\.com\/embed\/([a-zA-Z0-9_-]+)".*?<\/iframe>/',
        '<iframe width="560" height="315" src="https://www.youtube.com/embed/$1" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>',
        $post_content
    );
    
    $post_content = str_replace(']]>', ']]&gt;', $post_content);
    // 生成 XML

    

    echo '<article>';
    echo '<ID>' . $post_id . '</ID>';
    echo '<nativeCountry>TH</nativeCountry>'; // 原始國家代碼，根據實際情況修改
    echo '<language>th</language>'; // 語言，根據實際情況修改
    echo '<publishCountries><country>TH</country></publishCountries>';
    echo '<startYmdtUnix>' . $post_publish_time . '000</startYmdtUnix>';
    echo '<endYmdtUnix>' . $post_publish_time_end . '000</endYmdtUnix>'; // 假設文章有效期為30天
    echo '<title>' . $post_title . '</title>';
    echo '<category>' . $category_name . '</category>';
    echo '<publishTimeUnix>' . $post_publish_time . '000</publishTimeUnix>';
    echo '<updateTimeUnix>' . $post_publish_time . '000</updateTimeUnix>';
    echo '<contentType>0</contentType>';
    echo '<thumbnailUrl>'. $post_thumbnail_url . '</thumbnailUrl>';
    echo '<thumbnail>'. $post_thumbnail_url . '</thumbnail>';
    echo '<contents>';
        /*
        echo '<image>';
            echo '<url>' . $post_thumbnail_url . '</url>';
            echo '<thumbnailUrl>' . $post_thumbnail_url . '</thumbnailUrl>';
        echo '</image>';
        */
        echo '<text>';
            echo '<content>';
                echo '<![CDATA[' . $post_content . ']]>';
            echo '</content>';
        echo '</text>';
    echo '</contents>';

    echo '<author>Sabasports</author>';
    echo '<sourceUrl>' . $post_url . '</sourceUrl>';
    echo '</article>';
}

echo '</articles>';
//echo '</xml>';

wp_reset_postdata();
?>
