<?php
ini_set('error_reporting', E_ALL & ~E_DEPRECATED & ~E_STRICT & ~E_NOTICE);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
date_default_timezone_set('Asia/Jakarta');


include_once('../saba/Connections/db_pdo.php');
include_once('inc/inc_tools.php');

header('Content-Type: application/json; charset=utf-8');

$cache_dir = "/home/www/saba_id/games2/cache/";

/*
1. 寫入到 Google sheets
2. 查詢近期賽事
3. 單場比賽結果
4. 排名
5. 最佳射手

單場比賽結果
https://api-football-v1.p.rapidapi.com/v3/fixtures?id=868007

單場比賽兩隊數據
https://api-football-v1.p.rapidapi.com/v3/fixtures/statistics?fixture=868007


*/


/*---------- 1. 寫入到 Google sheets -------------*/
if ((isset($_REQUEST["act"])) && ($_REQUEST["act"] == "add_to_sheets")) {


require '../vendor/autoload.php';

// 載入服務帳戶金鑰
$credentials_file = 'syt-th-e551db5fdf78.json';
$credentials = json_decode(file_get_contents($credentials_file), true);

// 設置作用域
$scope = ['https://www.googleapis.com/auth/spreadsheets'];

// 創建Google API客戶端
$client = new Google_Client();
$client->setAuthConfig($credentials);
$client->addScope($scope);

// 創建Google Sheets服務
$service = new Google_Service_Sheets($client);

// 設置表單數據
$name 	= $_REQUEST['name'];
$tel 	= $_REQUEST['tel'];
$email 	= $_REQUEST['email'];
$player = $_REQUEST['player'];
$score = $_REQUEST['score'];
$wishlist = $_REQUEST['wishlist'];
$message = $_REQUEST['message'];
$dateandtime = date("Y-m-d H:i:s");

// 設置要提交的數據
$data = array(
    array($name, $tel,$email, $player, $score, $wishlist, $message, $dateandtime)
);

// 設置請求選項
$options = array(
    'valueInputOption' => 'USER_ENTERED'
);

// 提交數據
$spreadsheet_id = '1wYky3SQg6cdjZ6rgmd3BFGacXSzNLvFhJybbui5hFSY';
$range = '202308!A2:F2';
$requestBody = new Google_Service_Sheets_ValueRange();
$requestBody->setValues($data);
$response = $service->spreadsheets_values->append($spreadsheet_id, $range, $requestBody, $options);

if ($response) {
	$info['code'] = 200;
    $info['response'] = 'ขอบคุณสำหรับความคิดเห็นของคุณ! อย่าลืมเข้าร่วม SYT@LINE (ค้นหา @sytofficial) เพื่อตรวจสอบผลการจับรางวัล เราจะประกาศรายชื่อผู้โชคดีในต้นเดือนพฤษภาคม'; // Form submission successful!
} else {
	$info['code'] = 199;
    $info['response'] = 'การส่งแบบฟอร์มล้มเหลว'; // Form submission failed.
}


}


/*---------- 2. 查詢近期賽事 -------------*/
if ((isset($_REQUEST["act"])) && ($_REQUEST["act"] == "query_fixtures")) {

$league_id = 4;
$season = 2024;
 
$start_date = date('Y-m-d', strtotime('-7 day'));
$end_date = date('Y-m-d', strtotime('+10 days', strtotime($start_date)));

if (!file_exists($cache_dir)) {
  mkdir($cache_dir, 0755, true);
}
$cache_filename = 'fixtures_' . $league_id . '_' . $season;
$cache_file = $cache_dir . $cache_filename . '.json';

if (!file_exists($cache_file) || json_decode(file_get_contents($cache_file), true)['timestamp'] < time()-300) {
  $url = "https://api-football-v1.p.rapidapi.com/v3/fixtures?league=" . $league_id . "&season=" . $season . "&from=" . $start_date . "&to=" . $end_date;
$fixtures = json_decode(api_get($url), true);
$fixtures['timestamp'] = time();
  file_put_contents($cache_file, json_encode($fixtures));

} else {
  $fixtures = json_decode(file_get_contents($cache_file), true);
}

$all_round = $fixtures['response'];
// 加入usort函數以timestamp排序
usort($all_round, function($a, $b) {
    return $a['fixture']['timestamp'] - $b['fixture']['timestamp'];
});


$games = array();

$finished_games = array();
$ongoing_games = array();
$upcoming_games = array();


$i=0;
foreach($all_round AS $round) { 
   
$sql = "SELECT * FROM `saba_teams_mapping` WHERE LOWER(`team`) = :team AND league_id = :league_id";
$league_team_away = $website_db->run($sql, array(":team"=> strtolower($round['teams']['away']['name']), ":league_id"=> $league_id ))->fetch();
$league_team_home = $website_db->run($sql, array(":team"=> strtolower($round['teams']['home']['name']), ":league_id"=> $league_id ))->fetch();

$team_name_away = $round['teams']['away']['name'];
if(isset($league_team_away) && is_array($league_team_away)) { 
	  $team_name_away = $league_team_away['name'];
}
$team_name_home = $round['teams']['home']['name'];
if(isset($league_team_home) && is_array($league_team_home)) { 
	  $team_name_home = $league_team_home['name'];
}
	

  //$team_name_home = $round['teams']['home']['name'];
	//$team_name_away = $round['teams']['away']['name'];
	
	$games[$i]['away'] = $team_name_away;
	$games[$i]['home'] = $team_name_home;
	$games[$i]['status'] = $round['fixture']['status']['long'];
	$games[$i]['short'] = $round['fixture']['status']['short'];

    
  $games[$i]['html'] = '
  <a class="scoreboard" target="_blank" href="/uefa2024/match-score/?league=' . $league_id . '&season=' . $season . '&fixture=' . $round['fixture']['id'] . '">
    <div class="scoreboard-content">
      <div class="scoreboard-date">' . date("Y-m-d H:i", $round['fixture']['timestamp']) . '</div>
      <div class="scoreboard-match">
        <div style="width: 100%">
          <div class="team">
            <img src="' . $league_team_home['img'] . '">
		        <div class="label" style=" align-items: center;">
              <div class="name">' . $team_name_home . '</div>
              <div class="score" style="text-align-last: end">' . $round['goals']['home'] . '</div>
		        </div>
          </div>
	        <div class="team">
            <img src="' . $league_team_away['img'] . '">
		        <div class="label" style=" align-items: center;">
              <div class="name">' . $team_name_away . '</div>
		          <div class="score"  style="text-align-last: end">' . $round['goals']['away'] . '</div>
		        </div>
          </div>
        </div>
      </div>
      <div class="league-name">' . $round['league']['name'] . '<br>' . $round['league']['round'] . '</div>
	  </div>
    <ul class="scoreboard-links">
      <li><span><a href="#">Match Hub</a></span></li>
    </ul>
  </a>';
  
  
	$status = $round['fixture']['status']['short'];
	 if ($status != 'PST' && $status != 'TBD') {
        if ($status == 'NS') {
            $upcoming_games[] = $games[$i];
        } elseif ($status == 'FT' || $status == 'AET' || $status == 'PEN' ) {
            $finished_games[] = $games[$i];
        } else {
            $ongoing_games[] = $games[$i];
        }
	 }
		
		

$i++;
  }

  $info['code'] = 200;
  $info['games'] = $games;
  $info['finished_games'] = $finished_games;
  $info['ongoing_games'] = $ongoing_games;
  $info['upcoming_games'] = $upcoming_games;
  
}



/*---------- 3. 單場比賽結果 -------------*/
if ((isset($_REQUEST["act"])) && ($_REQUEST["act"] == "query_game_info")) {
  $league_id = 39;
	$url = "https://api-football-v1.p.rapidapi.com/v3/fixtures?id=" . $_REQUEST['id'];
	$fixture = json_decode(api_get($url), true);


//英文隊名翻譯成泰文 start
  $all_round = $fixture['response'];
  foreach($all_round AS $round) { 
    $sql = "SELECT * FROM `syt_league_teams` WHERE LOWER(`name`) = :name AND league_id = :league_id";
    $league_team_away = $website_db->run($sql, array(":name"=> strtolower($round['teams']['away']['name']), ":league_id"=> $league_id ))->fetch();
    $league_team_home = $website_db->run($sql, array(":name"=> strtolower($round['teams']['home']['name']), ":league_id"=> $league_id ))->fetch();
    
    $team_name_away = $round['teams']['away']['name'];
    if(isset($league_team_away) && is_array($league_team_away)) { 
      $team_name_away = $league_team_away['th_name'];
      }
    $team_name_home = $round['teams']['home']['name'];
    if(isset($league_team_home) && is_array($league_team_home)) { 
      $team_name_home = $league_team_home['th_name'];
      }  
  }    
//英文隊名翻譯成泰文 end

	$info['code'] = 200;
	$info['data'] = $fixture['response'][0];
	$info['status'] = $fixture['response'][0]['fixture']['status']['long'];
	$info['team_away_th'] = $team_name_away;
	$info['team_home_th'] = $team_name_home;

//抓出比賽日期轉成Y/M/D
  $info['data2'] = $info['data']['fixture'];
  $time =strtotime($info['data2']['date']);
  $info['date'] = date("Y/m/d" ,$time);

if($fixture['response'][0]['fixture']['status']['short'] != "NS") {
//抓比賽的足球陣式
  $formation['data'] = $info['data']['lineups'];
  $home_formation = $formation['data'][0]['formation'];
  $away_formation = $formation['data'][1]['formation'];
  $info['home_formation'] = $home_formation;
	$info['away_formation'] = $away_formation;

//抓進球時間資料 
$goal = array();
$i=0;


  $goals_info = $info['data']['events'];
  foreach($goals_info AS $g) {
    if($g['type']=='Goal'){
      $goal[$i]['time']=$g['time']['elapsed'];
      $goal[$i]['team']=$g['team']['id'];
      $goal[$i]['detail']=$g['detail'] ;
      $goal[$i]['player']=$g['player']['name'];
      
      $i++ ;
    }
  }
  $info['goals']=$goal;
}

$away_id = $info['data']['teams']['away']['id'];
$home_id = $info['data']['teams']['home']['id'];


  $info['away_id']=$away_id;
  $info['home_id']=$home_id;
}


/*---------- 4. 排名 -------------*/ 
if ((isset($_REQUEST["act"])) && ($_REQUEST["act"] == "query_standings_info")) {
	//$league_id = 39;
	//V3 - Standings by league id

  
    if (!file_exists($cache_dir)) {
        mkdir($cache_dir, 0755, true);
    }
    $cache_filename = 'standings_info_' . $_REQUEST['league'] . '_' . $_REQUEST['season'];
    $cache_file = $cache_dir . $cache_filename . '.json';

    if (!file_exists($cache_file) || json_decode(file_get_contents($cache_file), true)['timestamp'] < time()-300) {
      $url = "https://api-football-v1.p.rapidapi.com/v3/standings?season=" . $_REQUEST['season'] . "&league=" .  $_REQUEST['league'];
      $fixture = json_decode(api_get($url), true);
      $fixture['timestamp'] = time();
        file_put_contents($cache_file, json_encode($fixture));

    } else {
        $fixture = json_decode(file_get_contents($cache_file), true);
    }


  $all_round = $fixture['response'][0]['league']['standings'];

  // 创建一个新数组来存储重命名后的键
  $new_rounds = [];
  $letters = range('A', 'Z');  // 这将生成一个从 A 到 Z 的字母数组

  foreach ($all_round as $index => $group) {
      if (isset($letters[$index])) {  // 确保字母索引存在
          $new_rounds[$letters[$index]] = $group;
      }
  }

  // 现在 $new_rounds 数组的索引已经是字母了，例如 $new_rounds['A'], $new_rounds['B'], ...
  $all_round = $new_rounds;  

  //迴圈把每一隊隊名改成 $league_team['name] 
  foreach($all_round as $group_key => $groups) { 
    foreach($groups as $team_key => $group){
        $sql = "SELECT * FROM `saba_teams_mapping` WHERE LOWER(`team`) = :team AND league_id = :league_id";
        $params = array(":team"=> strtolower($group['team']['name']), ":league_id"=> $_REQUEST['league']);
        $league_team = $website_db->run($sql, $params)->fetch();

        if(isset($league_team) && is_array($league_team)) { 
            $all_round[$group_key][$team_key]['team']['name'] = $league_team['name'];
            $all_round[$group_key][$team_key]['team']['logo'] = $league_team['img']; 
            $all_round[$group_key][$team_key]['team']['tag_name'] = $league_team['tag_name'];
        }
    }
  }
 
  //只回傳前六個隊伍
  $all_round = array_slice($all_round, 0, 6, true);

	$info['code'] = 200;
	$info['data'] = $all_round; 
	
}



/*---------- 5. 最佳射手 -------------*/ 
if ((isset($_REQUEST["act"])) && ($_REQUEST["act"] == "query_topscorers_info")) {
	//$league_id = 39;
  
	//V3 - Standings by league id
	$url = "https://api-football-v1.p.rapidapi.com/v3/players/topscorers?league="  . $_REQUEST['league'] . "&season=" .  $_REQUEST['season'] ;
	$fixture = json_decode(api_get($url), true);


	$info['code'] = 200;
	$info['data'] = $fixture['response'][0]['statistics'][0];
	
}


/*---------- 5. 最佳射手 for 活動頁 -------------*/ 
if ((isset($_REQUEST["act"])) && ($_REQUEST["act"] == "query_topscorers_info_latest_sweepstakes")) {
	//$league_id = 39;
  
	//V3 - Standings by league id
	$url = "https://api-football-v1.p.rapidapi.com/v3/players/topscorers?league="  . $_REQUEST['league'] . "&season=" .  $_REQUEST['season'] ;
	$fixture = json_decode(api_get($url), true);


	$info['code'] = 200;
	$info['data'] = $fixture['response'];
	
}


/*---------- 5. 歐冠最佳射手 -------------*/ 
if ((isset($_REQUEST["act"])) && ($_REQUEST["act"] == "query_uefa_topscorers_info")) {
	$league_id = 2;
	//V3 - Standings by league id
	$url = "https://api-football-v1.p.rapidapi.com/v3/players/topscorers?league="  .$league_id . "&season=" .  $_REQUEST['season'] ;
	$fixture = json_decode(api_get($url), true);



	$info['code'] = 200;
	$info['data'] = $fixture['response'][0]['statistics'][0];
	
}

/*---------- 5. saba ue2024 最佳射手列表 -------------*/ 
if ((isset($_REQUEST["act"])) && ($_REQUEST["act"] == "query_topscorers_list")) {

  if (!file_exists($cache_dir)) {
    mkdir($cache_dir, 0755, true);
  }
  $cache_filename = 'topscorers_' . $_REQUEST['league']  . '_' . $_REQUEST['season'];
  $cache_file = $cache_dir . $cache_filename . '.json';

  if (!file_exists($cache_file) || json_decode(file_get_contents($cache_file), true)['timestamp'] < time()-3600) {
    $url = "https://api-football-v1.p.rapidapi.com/v3/players/topscorers?league="  . $_REQUEST['league'] . "&season=" .  $_REQUEST['season'] ;
    $fixture = json_decode(api_get($url), true);

    $fixture['timestamp'] = time();
    file_put_contents($cache_file, json_encode($fixture));

  } else {
      $fixture = json_decode(file_get_contents($cache_file), true);
  }
  //只回傳前六個隊伍
  $all_player = $fixture['response'];
  $all_player = array_slice($all_player, 0, 6, true);

	$info['code'] = 200;
	$info['data'] = $all_player;
	
}



/*---------- 5. 最佳助攻 -------------*/ 
if ((isset($_REQUEST["act"])) && ($_REQUEST["act"] == "query_topassists_info")) {
	$league_id = 39;
	//V3 - Standings by league id
	$url = "https://api-football-v1.p.rapidapi.com/v3/players/topassists?league="  .$league_id . "&season=" .  $_REQUEST['season'] ;
	$fixture = json_decode(api_get($url), true);


	$info['code'] = 200;
	$info['data'] = $fixture['response'][0]['statistics'][0];
	
}


/*---------- 5. 最佳助攻列表 -------------*/ 
if ((isset($_REQUEST["act"])) && ($_REQUEST["act"] == "query_topassists_list")) {
	//$league_id = 39;
  
	//V3 - Standings by league id
  if (!file_exists($cache_dir)) {
    mkdir($cache_dir, 0755, true);
  }
  $cache_filename = 'topassists_' . $_REQUEST['league']  . '_' . $_REQUEST['season'];
  $cache_file = $cache_dir . $cache_filename . '.json';
  
  if (!file_exists($cache_file) || json_decode(file_get_contents($cache_file), true)['timestamp'] < time()-3600) {
    $url = "https://api-football-v1.p.rapidapi.com/v3/players/topassists?league="  . $_REQUEST['league'] . "&season=" .  $_REQUEST['season'] ;
    $fixture = json_decode(api_get($url), true);

    $fixture['timestamp'] = time();
    file_put_contents($cache_file, json_encode($fixture));

  } else {
      $fixture = json_decode(file_get_contents($cache_file), true);
  }
  //只回傳前六個隊伍
  $all_player = $fixture['response'];
  $all_player = array_slice($all_player, 0, 6, true);

	$info['code'] = 200;
	$info['data'] = $all_player;
	
}



/*---------- 6. 球員資訊列表 -------------*/ 
if ((isset($_REQUEST["act"])) && ($_REQUEST["act"] == "query_player_info")) {
 
  if (!file_exists($cache_dir)) {
    mkdir($cache_dir, 0755, true);
  }
  $cache_filename = 'player_info_' . $_REQUEST['team']  . '_' . $_REQUEST['season'];
  $cache_file = $cache_dir . $cache_filename . '.json';

  if (!file_exists($cache_file) || json_decode(file_get_contents($cache_file), true)['timestamp'] < time()-3600) {
    $url = "https://api-football-v1.p.rapidapi.com/v3/players/?team=" . $_REQUEST['team'] . "&league="  . $_REQUEST['league'] . "&season=" .  $_REQUEST['season'] ;
    $fixture = json_decode(api_get($url), true);

    $fixture['timestamp'] = time();
    file_put_contents($cache_file, json_encode($fixture));
  } else {
      $fixture = json_decode(file_get_contents($cache_file), true);
  }
  
  // 初始化陣列來儲存不同角色的球員
  $players = [
    'Defender' => [],
    'Midfielder' => [],
    'Attacker' => [],
    'Goalkeeper' => []
  ];

  // 遍歷每個球員的資料
  foreach ($fixture['response'] as $player) {
    $position = $player['statistics'][0]['games']['position'];
    
    // 根據角色將球員放入對應的陣列
    switch ($position) {
        case 'Defender':
            $players['Defender'][] = $player;
            break;
        case 'Midfielder':
            $players['Midfielder'][] = $player;
            break;
        case 'Attacker':
            $players['Attacker'][] = $player;
            break;
        case 'Goalkeeper':
            $players['Goalkeeper'][] = $player;
            break;
        default:
            // 如果角色不是上述之一，可以選擇將其忽略或處理其他方式
            break;
    }
  }

  $sql = "SELECT * FROM `saba_teams_mapping` WHERE LOWER(`team`) = :team AND league_id = :league_id";
  $params = array(":team"=> strtolower($fixture['response'][0]['statistics'][0]['team']['name']), ":league_id"=> $_REQUEST['league']);
  $league_team = $website_db->run($sql, $params)->fetch();

  $team['name'] = $league_team['name'];
  $team['img'] = $league_team['img'];

	$info['code'] = 200;
	$info['players'] = $players; 
  $info['team'] = $team;
	
}



/*---------- 7. 隊伍對戰資訊 -------------*/ 
if ((isset($_REQUEST["act"])) && ($_REQUEST["act"] == "query_teams_games_info")) {

  if (!file_exists($cache_dir)) {
    mkdir($cache_dir, 0755, true);
  }
  $cache_filename = 'teams_games_info_' . $_REQUEST['team']  . '_' . $_REQUEST['season'];
  $cache_file = $cache_dir . $cache_filename . '.json';

  if (!file_exists($cache_file) || json_decode(file_get_contents($cache_file), true)['timestamp'] < time()-300) {
    $url = "https://api-football-v1.p.rapidapi.com/v3/fixtures?league=" . $_REQUEST['league'] . "&season="  . $_REQUEST['season'] . "&team=" .  $_REQUEST['team'] ;
    $fixture = json_decode(api_get($url), true);

    $fixture['timestamp'] = time();
    file_put_contents($cache_file, json_encode($fixture));
  } else {
      $fixture = json_decode(file_get_contents($cache_file), true);
  }

  $data = $fixture['response'];
  $game = array();  
 
  foreach($data as $key => $value) {
    //更換時區
    $time = strtotime($value['fixture']['date']);
    
    $game['date'] = date("Y-m-d", $time);
    $game['time'] = date("H:i", $time);
     
    $data[$key]['game'] = $game;

    //更換國旗 & 國名
    $sql = "SELECT * FROM `saba_teams_mapping` WHERE LOWER(`team`) = :team AND league_id = :league_id";
    $params = array(":team"=> strtolower($value['teams']['away']['name']), ":league_id"=> $_REQUEST['league']);
    $league_team = $website_db->run($sql, $params)->fetch();

    $data[$key]['teams']['away']['name'] = $league_team['name'];
    $data[$key]['teams']['away']['logo'] = $league_team['img']; 

    $sql = "SELECT * FROM `saba_teams_mapping` WHERE LOWER(`team`) = :team AND league_id = :league_id";
    $params = array(":team"=> strtolower($value['teams']['home']['name']), ":league_id"=> $_REQUEST['league']);
    $league_team = $website_db->run($sql, $params)->fetch();

    $data[$key]['teams']['home']['name'] = $league_team['name'];
    $data[$key]['teams']['home']['logo'] = $league_team['img']; 
    
  }
 
	$info['code'] = 200;
	$info['data'] = $data;  
}



/*---------- 8. 查詢賽事表 -------------*/
if ((isset($_REQUEST["act"])) && ($_REQUEST["act"] == "query_fixtures_info")) {

  //$league_id = 4;
  //$season = 2020;
   
  $start_date = date('Y-m-d', strtotime('-30 day'));
  $end_date = date('Y-m-d', strtotime('+30 days', strtotime($start_date)));
  //$start_date = date('Y-m-d', strtotime('-3000 day'));
  //$end_date = date('Y-m-d', strtotime('+60 days', strtotime($start_date)));

  if (!file_exists($cache_dir)) {
    mkdir($cache_dir, 0755, true);
  }
  $cache_filename = 'fixtures_info_' . $_REQUEST['league']  . '_' . $_REQUEST['season'];
  $cache_file = $cache_dir . $cache_filename . '.json';
  
  if (!file_exists($cache_file) || json_decode(file_get_contents($cache_file), true)['timestamp'] < time()-1800) {
    //$url = "https://api-football-v1.p.rapidapi.com/v3/fixtures?league=" . $_REQUEST['league'] . "&season=" . $_REQUEST['season'] . "&from=" . $start_date . "&to=" . $end_date;
    $url = "https://api-football-v1.p.rapidapi.com/v3/fixtures?league=" . $_REQUEST['league'] . "&season=" . $_REQUEST['season'] ;
    $fixture = json_decode(api_get($url), true);
    
    $fixture['timestamp'] = time();
    file_put_contents($cache_file, json_encode($fixture));
  } else {
      $fixture = json_decode(file_get_contents($cache_file), true);
  }


  $data = $fixture['response'];

  // 加入usort函數以timestamp排序
  usort($data, function($a, $b) {
      return $a['fixture']['id'] - $b['fixture']['id'];
  });
   
  $GroupStage = array();
  $RoundOf16 = array();
  $QuarterFinals = array();
  $SemiFinals = array();
  $Final = array();
  
  foreach ($data as $key => $value) {
    // 更換時區
    $time = strtotime($value['fixture']['date']);
    $game = [
      'date' => date("Y-m-d", $time),
      'time' => date("H:i", $time)
    ];
    $value['game'] = $game;

    // 更換國旗和國名（away）
    $sql = "SELECT * FROM `saba_teams_mapping` WHERE LOWER(`team`) = :team AND league_id = :league_id";
    $params = array(":team" => strtolower($value['teams']['away']['name']), ":league_id" => $_REQUEST['league']);
    $league_team = $website_db->run($sql, $params)->fetch();
    if ($league_team) {
      $value['teams']['away']['name'] = $league_team['name'];
      $value['teams']['away']['logo'] = $league_team['img'];
    }

    // 更換國旗和國名（home）
    $params = array(":team" => strtolower($value['teams']['home']['name']), ":league_id" => $_REQUEST['league']);
    $league_team = $website_db->run($sql, $params)->fetch();
    if ($league_team) {
      $value['teams']['home']['name'] = $league_team['name'];
      $value['teams']['home']['logo'] = $league_team['img'];
    }

    // 根據賽事階段分類
    $round = strtolower($value['league']['round']);
    if (stripos($round, "group") !== false) {
      $GroupStage[] = $value;
    } elseif (stripos($round, "round of 16") !== false) {
      $RoundOf16[] = $value;
    } elseif (stripos($round, "quarter-finals") !== false) {
      $QuarterFinals[] = $value;
    } elseif ($round === "semi-finals") {
      $SemiFinals[] = $value;  // 只在完全等於 "semi-finals" 時加入
    } elseif ($round === "final") {
        $Final[] = $value;
    }

    //排序16強賽
    $desiredOrder = [1212958, 1215074, 1215077, 1215078, 1214156, 1215075, 1215073, 1215076];

    usort($RoundOf16, function($a, $b) use ($desiredOrder) {
        $pos_a = array_search($a['fixture']['id'], $desiredOrder);
        $pos_b = array_search($b['fixture']['id'], $desiredOrder);
        return $pos_a - $pos_b;
    }); 
    
    //排序8強賽
    $desiredOrder = [1219689,1220308,1219688,1219959];

    usort($QuarterFinals, function($a, $b) use ($desiredOrder) {
        $pos_a = array_search($a['fixture']['id'], $desiredOrder);
        $pos_b = array_search($b['fixture']['id'], $desiredOrder);
        return $pos_a - $pos_b;
    });

    //排序4強賽
    $desiredOrder = [1227539,1225853];

    usort($SemiFinals, function($a, $b) use ($desiredOrder) {
      $pos_a = array_search($a['fixture']['id'], $desiredOrder);
      $pos_b = array_search($b['fixture']['id'], $desiredOrder);
      return $pos_a - $pos_b;
    });
  }
 

// 現在 $GroupStage、$RoundOf16、$QuarterFinals、$SemiFinals、$Final 將分別包含不同賽事的比賽資訊


  $info['code'] = 200;
  $info['GroupStage'] = $GroupStage;
  $info['RoundOf16'] = $RoundOf16;
  $info['QuarterFinals'] = $QuarterFinals;
  $info['SemiFinals'] = $SemiFinals;
  $info['Final'] = $Final;
  $info['data'] = $data;
}

$serialized_info  = json_encode($info);
print_r($serialized_info);