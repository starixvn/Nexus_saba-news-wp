<?php
ini_set('error_reporting', E_ALL & ~E_DEPRECATED & ~E_STRICT & ~E_NOTICE);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

include_once('../Connections/db_pdo.php');
include_once('inc/inc_tools.php');

$url = "https://api-football-v1.p.rapidapi.com/v3/fixtures?league=39&season=2022&from=2023-02-10&to=2023-02-12";

$fixtures = json_decode(api_get($url), true);
$all_round = $fixtures['response'];

//d($fixtures);
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title></title>
    
    <style>
	body {
		font-family: helvetica,arial,sans-serif;
	}
	.scoreboard-content a, 
	.scoreboard-content a:hover {
		text-decoration: none;
		color: #000;
	}
	
	.scoreboard-previous {
		background-color: #fff;
		background-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAC5CAYAAADpjFWRAAAAfklEQVQ4T+2TRw6EQBDEhgxLTkv4/0NRWaj4AEfm4Klzyw7hfpH+WEiMVCszcq1CKI1K6yfURqPVGp1WLwzGqDUJs7ForcLf2LR249A6P3w3eMMDbEIuNAOoh4ToCFAUWdEWoDJSo/djO/LTAlUAIqEZ6qEjQFtU9kRHg9QYLl5jD0L82NApAAAAAElFTkSuQmCC);
		background-position: 100% 80%;
		background-repeat: no-repeat;
		cursor: pointer;
		display: inline-block;
		height: 100%;
		position: sticky;
		top: 0;
		width: 6rem;
		z-index: 1;
		left: 0;
		border: none;
		outline: none;
	}
	
	.scoreboard-previous svg {
	  -webkit-transform: rotate(-180deg);
	  transform: rotate(-180deg);
	}
	
	.scoreboard-previous svg, .scoreboard-next svg {
		position: relative;
		width: 1rem;
		z-index: 2;
	}
	
	.scoreboard-previous svg path, .scoreboard-next svg path {
	  stroke: #CCC;
	}
	.scoreboard-previous:hover svg path, .scoreboard-next:hover svg path {
	  stroke: #c2002f;
	}
	
	.scoreboard-previous::after {
	  background: #000;
	  content: "";
	  -webkit-filter: blur(20px);
	  filter: blur(20px);
	  height: 80%;
	  opacity: .1;
	  position: absolute;
	  right: -1rem;
	  top: 15%;
	  -webkit-transform: matrix(-1,0,0,1,0,0);
	  transform: matrix(-1,0,0,1,0,0);
	  width: 2rem;
	}
	
	.scoreboard-previous::before {
	  background: #fff;
	  content: "";
	  height: 100%;
	  left: 0;
	  position: absolute;
	  top: 0;
	  width: calc(100% - 1px);
	  z-index: 1;
	}
	
	
	
	.scoreboard-next {
		background-color: #fff;
		background-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAC5CAYAAADpjFWRAAAAfklEQVQ4T+2TRw6EQBDEhgxLTkv4/0NRWaj4AEfm4Klzyw7hfpH+WEiMVCszcq1CKI1K6yfURqPVGp1WLwzGqDUJs7ForcLf2LR249A6P3w3eMMDbEIuNAOoh4ToCFAUWdEWoDJSo/djO/LTAlUAIqEZ6qEjQFtU9kRHg9QYLl5jD0L82NApAAAAAElFTkSuQmCC);
		background-position: 100% 80%;
		background-repeat: no-repeat;
		cursor: pointer;
		display: inline-block;
		height: 100%;
		position: sticky;
		top: 0;
		width: 6rem;
		z-index: 1;
		background-position: 0 80%;
		left: calc(100% - 6rem);
		border: none;
		outline: none;
	}
	
	.scoreboard-next::before {

    background: #000;
    content: "";
    -webkit-filter: blur(20px);
    filter: blur(20px);
    height: 80%;
    left: -1rem;
    opacity: .1;
    position: absolute;
    top: 15%;
    -webkit-transform: matrix(-1,0,0,1,0,0);
    transform: matrix(-1,0,0,1,0,0);
    width: 2rem;

	}

	.scoreboard-next::after {
		background: #fff;
		content: "";
		height: 100%;
		left: 1px;
		position: absolute;
		top: 0;
		width: calc(100% - 1px);
		z-index: 1;
	}
	
	
	.scoreboard-container {
		scroll-behavior: smooth;
		margin: 0 auto 1rem;
		-webkit-overflow-scrolling: touch;
		overflow-x: auto;
		overflow-y: hidden;
		position: relative;
		width: 100%;
		display: flex;
		height: 180px;
		border: 0px red solid;
		
	}
	
        .scoreboard {
			width: 300px;
			min-width: 300px;
            border-radius: .25rem;
            box-shadow: 0 .16rem .33rem rgba(0,0,0,.1);
            background-color: #fff;
            border: 1px solid #e6e6e6;
            display: flex;
			flex-direction: column;
            height: 90%;
            text-transform: uppercase;
            -webkit-transition: all .1s ease;
            -o-transition: all .1s ease;
            transition: all .1s ease;
			position: relative;
			word-wrap: break-word;
			background-clip: border-box;
			margin-left: 10px;


        }
		
		.scoreboard-warp {
			width: 100%;
			display: flex;
			position: absolute;
			top: 0;
			padding-left: 8.5rem;
		}
        
        .scoreboard-content {
            flex: 2 1;
            padding: 1.08rem 1.66rem;
            /*width: 66.667%;*/
			font-size: 12px
        }
		
		.scoreboard-content a {
			flex-direction: column;
			width: 100%;
			display: flex;
			height: 100%;
			justify-content: space-between;
		}

        .scoreboard-links {
            align-items: center;
            border-left: .2rem dotted #bfbfbf;
            display: -ms-flexbox;
            display: flex;
            flex: 1 1;
            flex-wrap: wrap;
            height: 100%;
            list-style: none;
            margin: 0;
            position: relative;
            width: 33.333%;
			display: none;
        }

        .scoreboard-date {
            color: grey;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: .07em;
			margin-bottom: 10px;
        }

        .scoreboard-match {
			display: flex;
			justify-content: space-between;
        }
		
		
        .scoreboard-match .label {
			display: flex; 
			justify-content: space-between; 
			width: 100%;
		}
        .scoreboard-match .team {
            align-items: center;
            display: flex;
            justify-content: flex-start;
            letter-spacing: .05em;
			margin-bottom: 5px;
        }

        .scoreboard-match .team img{
            margin-right: .66rem;
            display: inline-block;
            flex: 0 0 1.83333rem;
            height: auto;
            width: 30px;
        }

        .scoreboard-match .team .name {
            
        }
		
		.scoreboard-content .league-name {
			font-size: .6rem;
			letter-spacing: .07em;
			color: grey;
		}
		
	@media only screen and (max-width: 480px) {
		.scoreboard-previous, .scoreboard-next {
			display: none;
		}
		
		.scoreboard-warp {
		  padding-left: 0;
		}
		
	}
    </style>

</head>
  <body>
  <div class="container-fluid">
  <div style="flex-direction: row;">
  <section class="scoreboard-container">
  
  <button class="scoreboard-previous" onclick="scoreboard_go('previous')"><svg viewBox="0 0 7 18" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1 1L6 9L1 17" stroke="#B22435"></path></svg></button>
<div class="scoreboard-warp">
  <?php foreach($all_round AS $round) { ?>
    



  <div class="scoreboard">
    <div class="scoreboard-content">
	<a href="#">
      <div class="scoreboard-date"><?php echo date("Y-m-d H:i", $round['fixture']['timestamp']);?></div>
      <div class="scoreboard-match">
        <div style="width: 100%">
		<div class="team">
          <img src="<?php echo $round['teams']['away']['logo'];?>">
		  <div class="label">
          <div class="name"><?php echo $round['teams']['away']['name'];?></div>
		  <div class="score">0</div>
		  </div>
        </div>
        <div class="team">
          <img src="<?php echo $round['teams']['home']['logo'];?>">
		  <div class="label">
          <div class="name"><?php echo $round['teams']['home']['name'];?></div>
          <div class="score">0</div>
		  </div>
        </div>
        </div>
		
		
      </div>
      <div class="league-name"><?php echo $round['league']['name'] . '<br>' . $round['league']['round'];?></div>
      </a>
	  </div>
    <ul class="scoreboard-links">
      <li><span><a href="#">Match Hub</a></span></li>
    </ul>
  </div>



    <?php } ?>
    </div>
<button class="scoreboard-next"  onclick="scoreboard_go('next')"><svg viewBox="0 0 7 18" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1 1L6 9L1 17" stroke="#B22435"></path></svg></button>
</section>
</div>
</div>

<script>
function scoreboard_go(act) {
	if(act=="next") {
	$(".scoreboard-container").scrollLeft($(".scoreboard-container").scrollLeft() + 200); 
	} else {
	$(".scoreboard-container").scrollLeft($(".scoreboard-container").scrollLeft() - 200); 	
	}
}
</script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
</body>
</html>

