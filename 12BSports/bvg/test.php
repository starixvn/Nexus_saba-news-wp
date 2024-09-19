<?php
if (!function_exists('d')){
    function d($d){
            echo '<pre>';
            print_r($d);
            echo '</pre>';
    }
    }


function api_get($url, $data = null) {
// 目標 URL
//$url = 'https://api-dot-sabasport-com.df.r.appspot.com/api/1.0/Match/Soccer/Today';

// 初始化 cURL 會話
$curl = curl_init($url);

// 設定要發送的 HTTP 頭部
$headers = [
    'Authorization: Bearer B32B475D9D3FFC19E4BC9485CA0002794639269DC8F0B47ECE1272F20FFD1572',
    'Content-Type: application/json'
];

// 設定 cURL 選項
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    // 如果有要发送的数据
    if ($data !== null) {
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
    }

// 執行 cURL 請求
$response = curl_exec($curl);
$err = curl_error($curl);

// 關閉 cURL 資源，並釋放系統資源
curl_close($curl);

// 檢查是否有錯誤發生
if ($err) {
	return "cURL Error #:" . $err;
} else {
    $response = json_decode($response, true);
	return $response;
}

}


$url = 'https://api-dot-sabasport-com.df.r.appspot.com/api/1.0/Match/Soccer/Today';
//$url = 'https://api-dot-sabasport-com.df.r.appspot.com/api/1.0/Match/Soccer/Date';
//$url = 'https://api-dot-sabasport-com.df.r.appspot.com/api/1.0/Match/Live';

//$data = ['Date' => '2024-02-14'];


//$games = api_get($url);
$games = api_get($url, $data);
/*

d($games);

foreach ($games['Result']['Match'] as $game) {
    d($game);
}
*/

// 筛选特定日期的比赛
$filteredGames = array_filter($games['Result']['Match'], function($game) {
    return $game['DateShareZh'] === '02-16' && $game['Status'] != 50 && $game['Status'] != 99 && $game['IsMain'] != 1;// 根据实际字段调整条件
});

// 根据比赛时间排序
usort($filteredGames, function($a, $b) {
    return strcmp($a['ScheduleTicks'], $b['ScheduleTicks']); // 比较字符串来排序
});

// 输出结果
foreach ($filteredGames as $game) {
    d($game);
}

?>
<!--
<div id="Football1" >
    <div class="row">
        <div class="col-2" style="font-size: xx-small;place-self: center;">Chung Yee Village Football Team</div>
        <div class="col-3" style=""><img style="height: 30px;" src="https://sports-cdn.abjing.com/uploads/teamIcon/1854/c1eizwH52kO.png" alt="" /></div>
        <div class="col-2" style="text-align: center;">1-0</div>
        <div class="col-3" style=""><img style="height: 30px;" src="https://sports-cdn.abjing.com/uploads/teamIcon/1507/c3wrSoDPjan.png" alt="" /></div>
        <div class="col-2" style="font-size: xx-small;place-self: center;">Bombay Ymca</div>
    </div>
  
</div>
  
<div class="saba-score-card" id="football_card_1">
  <div class="saba-header">
    <span class="saba-league-name">Libertadores</span>
    <span class="saba-saba-region">South America</span>
  </div>
  <div class="saba-match-status">比賽結束</div>
  <div class="saba-teams-score">
    <div class="saba-team">
      <div class="saba-team-logo"><img style="height: 30px;" src="https://sports-cdn.abjing.com/uploads/teamIcon/1854/c1eizwH52kO.png"></div>
      <div class="saba-team-name">Club Nacional Club Nacional Club Nacional Club Nacional</div>
    </div>
    <div class="saba-score">3 - 0</div>
    <div class="saba-team">
      <div class="saba-team-logo"><img style="height: 30px;" src="https://sports-cdn.abjing.com/uploads/teamIcon/1507/c3wrSoDPjan.png"></div>
      <div class="saba-team-name">Aucas</div>
    </div>
  </div>
  <div class="saba-aggregate-score">Aggregate 3 - 1</div>
</div>

-->