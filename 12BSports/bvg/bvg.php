<?php
ini_set('error_reporting', E_ALL & ~E_DEPRECATED & ~E_STRICT & ~E_NOTICE);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1); 
header('Content-Type: application/json; charset=utf-8');
session_start();
require_once '../vendor/autoload.php';
include_once("../saba/Connections/smplPDO.php");

//include_once('../functions/mail.php'); 
//require __DIR__ . '/../vendor/autoload.php';
 
$info = array();

/*---------- 1. 查詢賽程表 -------------*/
if ((isset($_REQUEST["act"])) && ($_REQUEST["act"] == "query_schedule")) {
    $headers = [
        'Authorization: Bearer B32B475D9D3FFC19E4BC9485CA0002794639269DC8F0B47ECE1272F20FFD1572',
        'Content-Type: application/json'
    ];

    // 确定查询的日期
    $today = date('Y-m-d');
    $yesterday = date('Y-m-d', strtotime('-1 day'));
  
    //如果存在$_REQUEST["date"]，且有值，則使用$_REQUEST["date"]的日期
    if (isset($_REQUEST["date"]) && $_REQUEST["date"] != 0) {
        $today = $_REQUEST["date"];
        $yesterday = date('Y-m-d', strtotime($today . ' -1 day'));
    } 

    // 获取当天和前一天的比赛数据
    $todayGames = fetchGamesByDate($today, $headers, 'soccer');
    $yesterdayGames = fetchGamesByDate($yesterday, $headers , 'soccer');

    $todayGames['Result']['Match'] = adjustGameTimes_soccer($todayGames['Result']['Match']);
    $yesterdayGames['Result']['Match'] = adjustGameTimes_soccer($yesterdayGames['Result']['Match']);

 
     // 把$today格式從Y-m-d變成m-d
     $today = date('m-d', strtotime($today));
     $yesterday = date('m-d', strtotime($yesterday));
   
     $allGames = array_merge(
         array_filter($todayGames['Result']['Match'], function($game) use ($today) {
             return $game['MeiDonDate'] === $today && $game['DateShareZh'] === $today && $game['Status'] != 50 && $game['Status'] != 99 && $game['Status'] != 60 ;
         }),
         array_filter($yesterdayGames['Result']['Match'], function($game) use ($today) {
             // 过滤条件: 昨天的比赛，但时间调整后成为今天的
             return $game['MeiDonDate'] === $today && $game['Status'] != 50  && $game['Status'] != 99 && $game['Status'] != 60 ;
         })
     );
  
    // 根据比赛日期和时间排序
     usort($allGames, function($a, $b) {
         // 首先根据 'DateShareZh' 排序
         if ($a['MeiDonDate'] !== $b['MeiDonDate']) {
             return strcmp($a['MeiDonDate'], $b['MeiDonDate']);
         }
         // 如果 'DateShareZh' 相同，则根据 'Schedule' 排序
         return strcmp($a['Schedule'], $b['Schedule']);
     });

    usort($allGames, function($a, $b) {
        // 定义MatchStatus的排序顺序
        $order = [10 => 1, 20 => 2, 30 => 3, 40 => 4, 0 => 5, 50 => 6, 60 => 7];
        
        // 获取每个MatchStatus对应的排序权重，未定义的状态权重最大
        $rankA = isset($order[$a['Status']]) ? $order[$a['Status']] : max($order) + 1;
        $rankB = isset($order[$b['Status']]) ? $order[$b['Status']] : max($order) + 1;
        
        // 比较权重
        if ($rankA == $rankB) return 0;
        return ($rankA < $rankB) ? -1 : 1;
    });

    $topFourGames = array_slice($allGames, 0, 4);

    $info['code'] = 200;
    $info['game'] = $topFourGames;  
     
    // 筛选特定日期的比赛
    
}
 
 
/*---------- 2. 查詢日期表 -------------*/
if ((isset($_REQUEST["act"])) && ($_REQUEST["act"] == "query_dates")) {
    $dates = [];

    // 获取用户查询的日期或今天的时间戳
    if (!isset($_REQUEST["date"])) {
        $query_date = time();
    } else {
        $query_date = strtotime($_REQUEST["date"]);
    }
    
    // 获取今天的日期（确保与 $real_date 格式相同）
    $today = date('Y-m-d', time());
    
    // 将查询日期转换为 Y-m-d 格式，用于比较
    $query_date_formatted = date('Y-m-d', $query_date);
    
    // 循环获取查询日期前四天、查询日期、和查询日期后四天的日期和星期
    for ($i = -4; $i <= 4; $i++) {
        // 计算每天的时间戳
        $timestamp = strtotime($i . ' days', $query_date);
        
        // 获取月份和日期，格式为 "M-d"
        $date = date('M-d', $timestamp);
        $real_date = date('Y-m-d', $timestamp);
    
        // 获取星期几，格式为 "D"
        $dayOfWeek = date('D', $timestamp);
    
        // 判断当前循环的日期是否为真实的今天
        $isToday = ($real_date === $today);
    
        // 判断当前循环的日期是否为查询日期
        $isSelectedDate = ($real_date === $query_date_formatted);
    
        // 将日期、星期几、是否今天、是否选中日期加入到数组中
        $dates[] = [
            'day' => $date,
            'week' => $dayOfWeek,
            'isToday' => $isToday,
            'real_date' => $real_date,
            'sel_date' => $isSelectedDate // 新增的字段，标记是否为查询日期
        ];
    }


    $info['code'] = 200; 
    $info['dates'] = $dates; 
 
}
  

/*---------- 3. 查詢Soccer LIVE Score -------------*/
if (isset($_REQUEST["act"]) && $_REQUEST["act"] == "query_live_score_soccer") {
    $headers = [
        'Authorization: Bearer B32B475D9D3FFC19E4BC9485CA0002794639269DC8F0B47ECE1272F20FFD1572',
        'Content-Type: application/json'
    ];

    // 确定查询的日期
    $today = date('Y-m-d');
    $yesterday = date('Y-m-d', strtotime('-1 day'));
  
    //如果存在$_REQUEST["date"]，且有值，則使用$_REQUEST["date"]的日期
    if (isset($_REQUEST["date"]) && $_REQUEST["date"] != 0) {
        $today = $_REQUEST["date"];
        $yesterday = date('Y-m-d', strtotime($today . ' -1 day'));
    } 

    // 获取当天和前一天的比赛数据
    $todayGames = fetchGamesByDate($today, $headers, $_REQUEST["type"]);
    $yesterdayGames = fetchGamesByDate($yesterday, $headers , $_REQUEST["type"]);

    $todayGames['Result']['Match'] = adjustGameTimes_soccer($todayGames['Result']['Match']);
    $yesterdayGames['Result']['Match'] = adjustGameTimes_soccer($yesterdayGames['Result']['Match']);

    $LeagueEn = '';
    if (isset($_REQUEST["game"]) && $_REQUEST["game"] !='all') {
        switch ($_REQUEST["game"]) {
		
            case "FRA": 
            $LeagueEn = "FRA Ligue 1";
            break;
            
            case "GER":
            $LeagueEn = "GER Bundesliga";
            break;
    
            case "ITA":
            $LeagueEn = "ITA Serie A";
            break;
    
            case "SPA":
            $LeagueEn = "SPA La Liga";
            break;
    
            case "IND":
            $LeagueEn = "Indonesia ISC";
            break;
    
            case "ENG":
            $LeagueEn = "ENG Premier League";
            break; 
        }
    }

    // 把$today格式從Y-m-d變成m-d
    $today = date('m-d', strtotime($today));
    $yesterday = date('m-d', strtotime($yesterday));
  
    $allGames = array_merge(
        array_filter($todayGames['Result']['Match'], function($game) use ($today,$LeagueEn) {
            $leagueMatch = empty($LeagueEn) || $game['LeagueEn'] === $LeagueEn;
            return $game['MeiDonDate'] === $today  && $game['DateShareZh'] === $today && $leagueMatch && $game['Status'] != 99;
        }),
        array_filter($yesterdayGames['Result']['Match'], function($game) use ($today,$LeagueEn) {
            // 过滤条件: 昨天的比赛，但时间调整后成为今天的
            $leagueMatch = empty($LeagueEn) || $game['LeagueEn'] === $LeagueEn;
            return $game['MeiDonDate'] === $today && $leagueMatch   && $game['Status'] != 99;
        })
    );
 
   // 根据比赛日期和时间排序
    usort($allGames, function($a, $b) {
        // 首先根据 'DateShareZh' 排序
        if ($a['MeiDonDate'] !== $b['MeiDonDate']) {
            return strcmp($a['MeiDonDate'], $b['MeiDonDate']);
        }
        // 如果 'DateShareZh' 相同，则根据 'Schedule' 排序
        return strcmp($a['Schedule'], $b['Schedule']);
    });

    usort($allGames, function($a, $b) {
        // 定义MatchStatus的排序顺序
        $order = [10 => 1, 20 => 2, 30 => 3, 40 => 4, 0 => 5, 50 => 6, 60 => 7];
        
        // 获取每个MatchStatus对应的排序权重，未定义的状态权重最大
        $rankA = isset($order[$a['Status']]) ? $order[$a['Status']] : max($order) + 1;
        $rankB = isset($order[$b['Status']]) ? $order[$b['Status']] : max($order) + 1;
        
        // 比较权重
        if ($rankA == $rankB) return 0;
        return ($rankA < $rankB) ? -1 : 1;
    });

    if (isset($_REQUEST["schedule"]) && $_REQUEST["schedule"] == 1) {
        $topFourGames = array_slice($allGames, 0, 4);

        $info['code'] = 200;
        $info['game'] = $topFourGames;  
    } else {
        $info['code'] = 200; 
        $info['games'] = $allGames; 
    }
     
    
 
}



/*---------- 4. 查詢 Soccer 比賽細節 -------------*/
if ((isset($_REQUEST["act"])) && ($_REQUEST["act"] == "query_game_detail_soccer")) {
    $today = date('m-d');

    $url = 'https://api-dot-sabasport-com.df.r.appspot.com/api/1.0/Match/Soccer/Detail';

    $curl = curl_init($url);

    // 設定要發送的 HTTP 頭部
    $headers = [
        'Authorization: Bearer B32B475D9D3FFC19E4BC9485CA0002794639269DC8F0B47ECE1272F20FFD1572',
        'Content-Type: application/json'
    ];
    
    // 設定 cURL 選項
    $data = ['MatchId' => $_REQUEST["id"]];
    $jsonData = json_encode($data);

    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonData);
 
    
    // 執行 cURL 請求
    $response = curl_exec($curl);
    $err = curl_error($curl);
    
    // 關閉 cURL 資源，並釋放系統資源
    curl_close($curl);
    
    // 檢查是否有錯誤發生
    if ($err) {
       $info['code'] = 199;
       $info['msg'] = "cURL Error #:" . $err;
    } else {
        $response = json_decode($response, true);

        $game = $response['Result']['Match'] ;
        //$event = $response['Result']['Commentary'];

        if (isset($response['Result']['Commentary']) && !empty($response['Result']['Commentary'])) {
            $events = array_filter($response['Result']['Commentary'], function($game) use ($today) { // 注意这里使用 use 引入 $today
                // 比较日期是否为今天，同时满足其他条件
                return $game['Type'] === 1 || $game['Type'] === 3 || $game['Type'] === 4  ;
            });
        } else {
            $events = [];
        }

        $info['code'] = 200; 
        $info['game'] =  $game;
        $info['events'] =  $events;
    }
    // 筛选特定日期的比赛
    
}
 


/*---------- 5. 查詢 Basketball LIVE Score -------------*/
if (isset($_REQUEST["act"]) && $_REQUEST["act"] == "query_live_score_basketball") {
    $headers = [
        'Authorization: Bearer B32B475D9D3FFC19E4BC9485CA0002794639269DC8F0B47ECE1272F20FFD1572',
        'Content-Type: application/json'
    ];

    // 确定查询的日期
    $today = date('Y-m-d');
    $yesterday = date('Y-m-d', strtotime('-1 day'));
  
    //如果存在$_REQUEST["date"]，且有值，則使用$_REQUEST["date"]的日期
    if (isset($_REQUEST["date"]) && $_REQUEST["date"] != 0) {
        $today = $_REQUEST["date"];
        $yesterday = date('Y-m-d', strtotime($today . ' -1 day'));
    } 

    // 获取当天和前一天的比赛数据
    $todayGames = fetchGamesByDate($today, $headers, $_REQUEST["type"]);
    $yesterdayGames = fetchGamesByDate($yesterday, $headers , $_REQUEST["type"]);

    $todayGames['Result']['Match'] = adjustGameTimes_basketball($todayGames['Result']['Match']);
    $yesterdayGames['Result']['Match'] = adjustGameTimes_basketball($yesterdayGames['Result']['Match']);

    // 把$today格式從Y-m-d變成m-d
    $today = date('m-d', strtotime($today));
    $yesterday = date('m-d', strtotime($yesterday));
  
    $allGames = array_merge(
        array_filter($todayGames['Result']['Match'], function($game) use ($today) {
            return $game['MeiDonDate'] === $today  && $game['MatchStatus'] != 99;
        }),
        array_filter($yesterdayGames['Result']['Match'], function($game) use ($today) {
            // 过滤条件: 昨天的比赛，但时间调整后成为今天的
            return $game['MeiDonDate'] === $today && $game['MatchStatus'] != 99;
        })
    );
 
   // 根据比赛日期和时间排序
    usort($allGames, function($a, $b) {
        // 首先根据 'DateShareZh' 排序
        if ($a['MeiDonDate'] !== $b['MeiDonDate']) {
            return strcmp($a['MeiDonDate'], $b['MeiDonDate']);
        }
        // 如果 'DateShareZh' 相同，则根据 'Schedule' 排序
        return strcmp($a['Schedule'], $b['Schedule']);
    });
    
    usort($allGames, function($a, $b) {
        // 定义MatchStatus的排序顺序
        $order = [11 => 1, 12 => 2, 13 => 3, 14 => 4, 31 => 5, 32 => 6, 33 => 7, 40 => 8, 0 => 9, 50 => 10, 63 => 11];
        
        // 获取每个MatchStatus对应的排序权重，未定义的状态权重最大
        $rankA = isset($order[$a['MatchStatus']]) ? $order[$a['MatchStatus']] : max($order) + 1;
        $rankB = isset($order[$b['MatchStatus']]) ? $order[$b['MatchStatus']] : max($order) + 1;
        
        // 比较权重
        if ($rankA == $rankB) return 0;
        return ($rankA < $rankB) ? -1 : 1;
    });
    
    if (isset($_REQUEST["schedule"]) && $_REQUEST["schedule"] == 1) {
        $topFourGames = array_slice($allGames, 0, 4);

        $info['code'] = 200;
        $info['game'] = $topFourGames;  
    } else {
        $info['code'] = 200; 
        $info['games'] = $allGames; 
    }
 
}



/*---------- 6. 查詢 Basketball 比賽細節 -------------*/
if ((isset($_REQUEST["act"])) && ($_REQUEST["act"] == "query_game_detail_basketball")) {
    $today = date('m-d');

    $url = 'https://api-dot-sabasport-com.df.r.appspot.com/api/1.0/Match/Basketball/Detail';

    $curl = curl_init($url);

    // 設定要發送的 HTTP 頭部
    $headers = [
        'Authorization: Bearer B32B475D9D3FFC19E4BC9485CA0002794639269DC8F0B47ECE1272F20FFD1572',
        'Content-Type: application/json'
    ];
    
    // 設定 cURL 選項
    $data = ['MatchId' => $_REQUEST["id"]];
    $jsonData = json_encode($data);

    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonData);
 
     
    $response = curl_exec($curl);
    $err = curl_error($curl);
    
    // 關閉 cURL 資源，並釋放系統資源
    curl_close($curl);
    
    // 檢查是否有錯誤發生
    if ($err) {
       $info['code'] = 199;
       $info['msg'] = "cURL Error #:" . $err;
    } else {
        $response = json_decode($response, true);

        $game = $response['Result']['Match'] ; 

        $info['code'] = 200; 
        $info['game'] =  $game; 
    } 
}
 



/*---------- 7. 查詢 Tennis LIVE Score -------------*/
if (isset($_REQUEST["act"]) && $_REQUEST["act"] == "query_live_score_tennis") {
    $headers = [
        'Authorization: Bearer B32B475D9D3FFC19E4BC9485CA0002794639269DC8F0B47ECE1272F20FFD1572',
        'Content-Type: application/json'
    ];

    // 确定查询的日期
    $today = date('Y-m-d');
    $yesterday = date('Y-m-d', strtotime('-1 day'));
  
    //如果存在$_REQUEST["date"]，且有值，則使用$_REQUEST["date"]的日期
    if (isset($_REQUEST["date"]) && $_REQUEST["date"] != 0) {
        $today = $_REQUEST["date"];
        $yesterday = date('Y-m-d', strtotime($today . ' -1 day'));
    } 

    // 获取当天和前一天的比赛数据
    $todayGames = fetchGamesByDate($today, $headers, $_REQUEST["type"]);
    $yesterdayGames = fetchGamesByDate($yesterday, $headers , $_REQUEST["type"]);

    $todayGames['Result']['Match'] = adjustGameTimes_basketball($todayGames['Result']['Match']);
    $yesterdayGames['Result']['Match'] = adjustGameTimes_basketball($yesterdayGames['Result']['Match']);

    // 把$today格式從Y-m-d變成m-d
    $today = date('m-d', strtotime($today));
    $yesterday = date('m-d', strtotime($yesterday));
  
    $allGames = array_merge(
        array_filter($todayGames['Result']['Match'], function($game) use ($today) {
            return $game['MeiDonDate'] === $today  && $game['MatchStatus'] != 4;
        }),
        array_filter($yesterdayGames['Result']['Match'], function($game) use ($today) {
            // 过滤条件: 昨天的比赛，但时间调整后成为今天的
            return $game['MeiDonDate'] === $today && $game['MatchStatus'] != 4;
        })
    );
 
   // 根据比赛日期和时间排序
    usort($allGames, function($a, $b) {
        // 首先根据 'DateShareZh' 排序
        if ($a['MeiDonDate'] !== $b['MeiDonDate']) {
            return strcmp($a['MeiDonDate'], $b['MeiDonDate']);
        }
        // 如果 'DateShareZh' 相同，则根据 'Schedule' 排序
        return strcmp($a['Schedule'], $b['Schedule']);
    });
    
    usort($allGames, function($a, $b) {
        // 定义MatchStatus的排序顺序
        $order = [2 => 1, 1 => 2, 3 => 3];
        
        // 获取每个MatchStatus对应的排序权重，未定义的状态权重最大
        $rankA = isset($order[$a['MatchStatus']]) ? $order[$a['MatchStatus']] : max($order) + 1;
        $rankB = isset($order[$b['MatchStatus']]) ? $order[$b['MatchStatus']] : max($order) + 1;
        
        // 比较权重
        if ($rankA == $rankB) return 0;
        return ($rankA < $rankB) ? -1 : 1;
    });
    
    if (isset($_REQUEST["schedule"]) && $_REQUEST["schedule"] == 1) {
        $topFourGames = array_slice($allGames, 0, 4);

        $info['code'] = 200;
        $info['game'] = $topFourGames;  
    } else {
        $info['code'] = 200; 
        $info['games'] = $allGames; 
    }
 
}



/*---------- 8. 查詢 Tennis 比賽細節 -------------*/
if ((isset($_REQUEST["act"])) && ($_REQUEST["act"] == "query_game_detail_tennis")) {
    $today = date('m-d');

    $url = 'https://api-dot-sabasport-com.df.r.appspot.com/api/1.0/Match/Tennis/Detail';

    $curl = curl_init($url);

    // 設定要發送的 HTTP 頭部
    $headers = [
        'Authorization: Bearer B32B475D9D3FFC19E4BC9485CA0002794639269DC8F0B47ECE1272F20FFD1572',
        'Content-Type: application/json'
    ];
    
    // 設定 cURL 選項
    $data = ['MatchId' => $_REQUEST["id"]];
    $jsonData = json_encode($data);

    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonData);
 
     
    $response = curl_exec($curl);
    $err = curl_error($curl);
    
    // 關閉 cURL 資源，並釋放系統資源
    curl_close($curl);
    
    // 檢查是否有錯誤發生
    if ($err) {
       $info['code'] = 199;
       $info['msg'] = "cURL Error #:" . $err;
    } else {
        $response = json_decode($response, true);

        $game = $response['Result']['Match'] ; 

        $info['code'] = 200; 
        $info['game'] =  $game; 
    } 
}
 

/*---------- 9. 查詢Banner -------------*/
if ((isset($_REQUEST["act"])) && ($_REQUEST["act"] == "query_banner")) {

    $sql = "SELECT t1.width,t1.height,t2.* FROM `saba_banner_positions` AS t1 
        LEFT JOIN `saba_banners` AS t2 ON t1.id = t2.position_id 
        WHERE t1.name = :name AND t1.deleted = '0' AND t2.deleted = '0' 
        AND t2.start_dateandtime <= NOW() AND t2.end_dateandtime >= NOW()
        AND t2.status = 'pass'";

	$banners = $website_db->run($sql, array(":name"=>$_REQUEST['id']))->fetchAll();
    if (!empty($banners)) {
        // 從結果中隨機選擇一個
        $randomKey = array_rand($banners);
        $randomBanner = $banners[$randomKey];
        
        $info['code'] = 200; 
        $info['data'] = $randomBanner;
    } else {
        // 沒有找到符合條件的條目
        $info['code'] = 199; // 或其他合適的錯誤代碼
        $info['data'] = "No banners found";
    }
 
}
  
/*---------- 10. 查詢 POP UP -------------*/
if ((isset($_REQUEST["act"])) && ($_REQUEST["act"] == "query_popup_ad")) {

    $sql = "SELECT t1.width,t1.height,t2.* FROM `saba_banner_positions` AS t1 
        LEFT JOIN `saba_banners` AS t2 ON t1.id = t2.position_id 
        WHERE t1.name = :name AND t1.deleted = '0' AND t2.deleted = '0' 
        AND t2.start_dateandtime <= NOW() AND t2.end_dateandtime >= NOW()
        AND t2.status = 'pass'";

	$banners = $website_db->run($sql, array(":name"=>$_REQUEST['id']))->fetchAll();
    if (!empty($banners)) {
        // 從結果中隨機選擇一個
        $randomKey = array_rand($banners);
        $randomBanner = $banners[$randomKey];
        
        $info['code'] = 200; 
        $info['data'] = $randomBanner;
    } else {
        // 沒有找到符合條件的條目
        $info['code'] = 199; // 或其他合適的錯誤代碼
        $info['data'] = "No banners found";
    }
 
}

/*---------- 11. 取得登入網址 -------------*/
if ((isset($_REQUEST["act"])) && ($_REQUEST["act"] == "get_login_url")) {
    $website_url = 'https://' . $_SERVER['HTTP_HOST'];
    $client = new Google_Client();
    $client->setClientId('926868985895-gv31tsp21j36k7stm3cp9t7pbc16pfbt.apps.googleusercontent.com');
    $client->setClientSecret('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');
    $client->setRedirectUri($website_url . '/bvg/auth_google.php');
    $client->addScope("email");
    $client->addScope("profile");
    

    $info['code'] = 200; 
    $info['data']['google'] = $client->createAuthUrl();
 
}
  


/* 拋查賽事資訊 API */
function fetchGamesByDate($date, $headers , $type) {
    $url = 'https://api-dot-sabasport-com.df.r.appspot.com/api/1.0/Match/'. $type .'/Date';
    $curl = curl_init($url);

    $data = ['Date' => $date];
    $jsonData = json_encode($data);

    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonData);

    $response = curl_exec($curl);
    curl_close($curl);

    return json_decode($response, true);
}

/* 變更時區 */
function adjustGameTimes_soccer($games) {
    foreach ($games as &$game) {
        $scheduleDateTime = DateTime::createFromFormat('Y-m-d H:i', $game['MeiDonDate'] . ' ' . $game['Schedule']);
        // 加上7小时
        $scheduleDateTime->modify('+7 hours');
        // 更新比赛时间和日期
        $game['Schedule'] = $scheduleDateTime->format('H:i');
        $game['MeiDonDate'] = $scheduleDateTime->format('m-d');
    }
    return $games;
}


function adjustGameTimes_basketball($games) {
    foreach ($games as &$game) {
        if (isset($game['Schedule'])) {
            // 创建DateTime对象
            $scheduleDateTime = new DateTime($game['Schedule']);
            // 加上7小时
            $scheduleDateTime->modify('+7 hours');
            // 更新比赛时间和日期，保持原始格式
            $game['Schedule'] = $scheduleDateTime->format('H:i');
            $game['MeiDonDate'] = $scheduleDateTime->format('m-d');
        }
    }
    return $games;
}


$serialized_info = json_encode($info);
	print_r($serialized_info);


