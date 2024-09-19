<?php
/* 轉址到指定頁面 https://saba.im/th-line 並且觸發GA事件: click_goto */
$url = '';
if (isset($_GET['url'])) {
    $url = $_GET['url'];
} else {
    echo 'URL not found<br>';
    

    exit;
}

$event_name = 'click_goto';
if (isset($_GET['event'])) {
    $event_name = $_GET['event'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-BHQMHMXCB4"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-BHQMHMXCB4');
</script>

</head>
<body>
    <script>
        
        // 觸發GA事件
        gtag('event', '<?php echo $event_name;?>', {
            'event_category': 'click',
            'event_label': '<?php echo $event_name;?>',
            'value': '<?php echo $url; ?>'
        });

        window.location.href = '<?php echo $url; ?>';
    </script>
    
</body>
</html>