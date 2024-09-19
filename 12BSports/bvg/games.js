// 取得網址參數的函數
function getUrlParameter(url, parameter) {
    var urlSearchParams = new URLSearchParams(new URL(url).search);
    return urlSearchParams.get(parameter);
}

// 首頁查詢賽事資訊
function scoreboard_init() {
		
    jQuery.ajax({
    url:"/games2/api.php",
    type:"POST",
    dataType:'json',
    data:{
        'act':'query_fixtures'
    },
    success:function(Jdata){

        if(Jdata.code==200){
            var new_html = "";

            var finished_games_html = "";
            if(Jdata.finished_games.length > 0) {
                jQuery.each(Jdata.finished_games,function(index,data){ 
                    finished_games_html += data.html;
                });
            }
            
            var ongoing_games_html = "";
            if(Jdata.ongoing_games.length > 0) {
                jQuery.each(Jdata.ongoing_games,function(index,data){ 
                    ongoing_games_html += data.html;
                });
            }
            
            var upcoming_games_html = "";
            if(Jdata.upcoming_games.length > 0) {
                jQuery.each(Jdata.upcoming_games,function(index,data){ 
                    upcoming_games_html += data.html;
                });
            }
            
            if(finished_games_html !=="") {
                new_html += '<div style="padding-right: 20px; border-right: 2px #ffffff40 dotted; margin-right: 10px;">'; 
                new_html += '<div style="padding: 0 0 5px 10px">Hasil Pertandingan</div>'; 
                new_html += '<div style="display: flex;">'; 
                new_html += finished_games_html; 
                new_html += '</div></div>'; 
            }
            if(ongoing_games_html !=="") {
                new_html += '<div style="padding-right: 20px; border-right: 2px #ffffff40 dotted; margin-right: 10px;">'; 
                new_html += '<div style="padding: 0 0 5px 10px">Hasil Pertandingan Terbaru</div>'; 
                new_html += '<div style="display: flex;">'; 
                new_html += ongoing_games_html; 
                new_html += '</div></div>'; 
            }
            if(upcoming_games_html !=="") {
                new_html += '<div style="padding-right: 20px; border-right: 2px #ffffff40 dotted; margin-right: 10px;">'; 
                new_html += '<div style="padding: 0 0 5px 10px">Pertandingan Yang Akan Datang</div>'; 
                new_html += '<div style="display: flex;">'; 
                new_html += upcoming_games_html; 
                new_html += '</div></div>'; 
            }
            
                
                
                jQuery(".scoreboard-warp").html(new_html); 
                
        }

    },
    error: function() {}
    });
}



// 首頁查詢排名資訊
function init_standings_info() {
	//var season = getUrlParameter(location.href, 'season');
	//var league = getUrlParameter(location.href, 'league');
    var season = 2024;
	var league = 4;
	jQuery.ajax({
            url: "/games2/api.php",
            type: "POST",
            dataType: 'json',
            data: { 
              'act'   : 'query_standings_info',
              'season'   : season,
			  'league'   : league,
              },
              success: function (Jdata) {
                
               if(Jdata.code==200) {
					var html = ''; 
                    jQuery.each(Jdata.data,function(index,data){
                        var groupLabel = 'Grup ' + index;

                        html += '<div class="scroll-snap-items">';
                        html += '    <div class="ec-table">';
                        html += '        <div class="ec-table-header">';
                        html += '            <div class="ec-table-cell cell-group">';
                        html += '                <font style="vertical-align: inherit;">';
                        html += '                    <font style="vertical-align: inherit;">' + groupLabel + '</font>';
                        html += '                </font>';
                        html += '            </div>';
                        html += '            <div class="ec-table-cell cell-rank">';
                        html += '                <font style="vertical-align: inherit;">';
                        html += '                    <font style="vertical-align: inherit;">w</font>';
                        html += '                </font>';
                        html += '            </div>';
                        html += '            <div class="ec-table-cell cell-rank">';
                        html += '                <font style="vertical-align: inherit;">';
                        html += '                    <font style="vertical-align: inherit;">L</font>';
                        html += '                </font>';
                        html += '            </div>';
                        html += '            <div class="ec-table-cell cell-rank">';
                        html += '                <font style="vertical-align: inherit;">';
                        html += '                    <font style="vertical-align: inherit;">D</font>';
                        html += '                </font>';
                        html += '            </div>';
                        html += '            <div class="ec-table-cell cell-rank">';
                        html += '                <font style="vertical-align: inherit;">';
                        html += '                    <font style="vertical-align: inherit;">+/-</font>';
                        html += '                </font>';
                        html += '            </div>';
                        html += '            <div class="ec-table-cell cell-rank">';
                        html += '                <font style="vertical-align: inherit;">';
                        html += '                    <font style="vertical-align: inherit;">P</font>';
                        html += '                </font>';
                        html += '            </div>';
                        html += '        </div>';
                        html += '        <div class="ec-table-body">';
                        jQuery.each(data,function(index,teams){
                            html += '            <a href="/teams/team-info?id=' + teams.team.id + '" class="ec-table-row">';
                            html += '                <div class="ec-table-cell cell-group">';
                            html += '                    <ul class="team">';
                            html += '                        <li class="team-logo"><img src="' + teams.team.logo + '"</li>';
                            html += '                        <li class="team-name" tabindex="0" data-toggle="tooltip" title="">';
                            html += '                            <font style="vertical-align: inherit;">';
                            html += '                                <font style="vertical-align: inherit;">' + teams.team.name + '</font>';
                            html += '                            </font>';
                            html += '                        </li>';
                            html += '                    </ul>';
                            html += '                </div>';
                            html += '                <div class="ec-table-cell cell-rank">';
                            html += '                    <font style="vertical-align: inherit;">';
                            html += '                        <font style="vertical-align: inherit;">' + teams.all.win + '</font>';
                            html += '                    </font>';
                            html += '                </div>';
                            html += '                <div class="ec-table-cell cell-rank">';
                            html += '                    <font style="vertical-align: inherit;">';
                            html += '                        <font style="vertical-align: inherit;">' + teams.all.lose + '</font>';
                            html += '                    </font>';
                            html += '                </div>';
                            html += '                <div class="ec-table-cell cell-rank">';
                            html += '                    <font style="vertical-align: inherit;">';
                            html += '                        <font style="vertical-align: inherit;">' + teams.all.draw + '</font>';
                            html += '                    </font>';
                            html += '                </div>';
                            html += '                <div class="ec-table-cell cell-rank">';
                            html += '                    <font style="vertical-align: inherit;">';
                            html += '                        <font style="vertical-align: inherit;">' + teams.goalsDiff + '</font>';
                            html += '                    </font>';
                            html += '                </div>';
                            html += '                <div class="ec-table-cell cell-rank">';
                            html += '                    <font style="vertical-align: inherit;">';
                            html += '                        <font style="vertical-align: inherit;">' + teams.points + '</font>';
                            html += '                    </font>';
                            html += '                </div>';
                            html += '            </a> ';
                        });
                        html += '        </div>';
                        html += '    </div>';
                        html += '</div>';

                    });
                    jQuery(".grid-bxh").html(html);
				}  
            },
         error: function() {}
    });
}


// 首頁查詢最佳射手資訊
function topscorers_init() {
    var season = 2024;
	var league = 4;
    jQuery.ajax({
    url:"/games2/api.php",
    type:"POST",
    dataType:'json',
    data:{
        'act':'query_topscorers_list',
        'season'   : season,
        'league'   : league
    },
    success:function(Jdata){

        if(Jdata.code==200){
            var html = ''; 

                html += '<div  class="scroll-snap-items ec-block-bxh" >';
                html += '    <div class="ec-table"  >';
                html += '        <div class="ec-table-header">';
                html += '            <div class="ec-table-cell cell-group">';
                html += '                <font style="vertical-align: inherit;">';
                html += '                    <font style="vertical-align: inherit;">Pemain</font>';
                html += '                </font>';
                html += '            </div>';
                html += '            <div class="ec-table-cell cell-rank">';
                html += '                <font style="vertical-align: inherit;">';
                html += '                    <font style="vertical-align: inherit;">Skor</font>';
                html += '                </font>';
                html += '            </div>';
                html += '            <div class="ec-table-cell cell-rank">';
                html += '                <font style="vertical-align: inherit;">';
                html += '                    <font style="vertical-align: inherit;">Assist</font>';
                html += '                </font>';
                html += '            </div>';
                html += '        </div>';
                html += '        <div class="ec-table-body">';
                var i = 1;
                jQuery.each(Jdata.data,function(index,players){

                    html += '            <div class="ec-table-row">';
                    html += '                <div class="ec-table-cell cell-group">';
                    html += '                    <ul class="team">';
                    html += '                        <li class="top-rank">' + i + '</li>';
                    html += '                        <li class="team-top-logo"><img src="' + players.player.photo + '"</li>';
                    html += '                        <li class="team-name" tabindex="0" data-toggle="tooltip" title="">';
                    html += '                            <font style="vertical-align: inherit;">';
                    html += '                                <font style="vertical-align: inherit;">' + players.player.name + '</font>';
                    html += '                            </font>';
                    html += '                        </li>';
                    html += '                    </ul>';
                    html += '                </div>';
                    html += '                <div class="ec-table-cell cell-rank">';
                    html += '                    <font style="vertical-align: inherit;">';
                    html += '                    <font style="vertical-align: inherit;">' + (players.statistics[0].goals.total || 0) + '</font>';
                    html += '                    </font>';
                    html += '                </div>';
                    html += '                <div class="ec-table-cell cell-rank">';
                    html += '                    <font style="vertical-align: inherit;">';
                    html += '                    <font style="vertical-align: inherit;">' + (players.statistics[0].goals.assists || 0) + '</font>';
                    html += '                    </font>';
                    html += '                </div>';
                    html += '            </div> ';
                    i++;
                });
                html += '        </div>';
                html += '    </div>';
                html += '</div>';

 

                jQuery(".top-foot").html(html);
            
                 
        }

    },
    error: function() {}
    });
}



// 首頁查詢最佳Assist手資訊
function topassists() {
    var season = 2024;
	var league = 4;
    jQuery.ajax({
    url:"/games2/api.php",
    type:"POST",
    dataType:'json',
    data:{
        'act':'query_topassists_list',
        'season'   : season,
        'league'   : league
    },
    success:function(Jdata){

        if(Jdata.code==200){
            var html = ''; 

                html += '<div  class="scroll-snap-items ec-block-bxh" >';
                html += '    <div class="ec-table" >';
                html += '        <div class="ec-table-header" >';
                html += '            <div class="ec-table-cell cell-group">';
                html += '                <font style="vertical-align: inherit;">';
                html += '                    <font style="vertical-align: inherit;">Pemain</font>';
                html += '                </font>';
                html += '            </div>';
                html += '            <div class="ec-table-cell cell-rank">';
                html += '                <font style="vertical-align: inherit;">';
                html += '                    <font style="vertical-align: inherit;">Assist</font>';
                html += '                </font>';
                html += '            </div>';
                html += '            <div class="ec-table-cell cell-rank">';
                html += '                <font style="vertical-align: inherit;">';
                html += '                    <font style="vertical-align: inherit;">Skor</font>';
                html += '                </font>';
                html += '            </div>';

                html += '        </div>';
                html += '        <div class="ec-table-body">';
                var i = 1;
                jQuery.each(Jdata.data,function(index,players){
                    html += '            <div class="ec-table-row">';
                    html += '                <div class="ec-table-cell cell-group">';
                    html += '                    <ul class="team">';
                    html += '                        <li class="top-rank">' + i + '</li>';
                    html += '                        <li class="team-top-logo"><img src="' + players.player.photo + '"</li>';
                    html += '                        <li class="team-name" tabindex="0" data-toggle="tooltip" title="">';
                    html += '                            <font style="vertical-align: inherit;">';
                    html += '                                <font style="vertical-align: inherit;">' + players.player.name + '</font>';
                    html += '                            </font>';
                    html += '                        </li>';
                    html += '                    </ul>';
                    html += '                </div>';
                    html += '                <div class="ec-table-cell cell-rank">';
                    html += '                    <font style="vertical-align: inherit;">';
                    html += '                    <font style="vertical-align: inherit;">' + (players.statistics[0].goals.assists || 0) + '</font>';
                    html += '                    </font>';
                    html += '                </div>';
                    html += '                <div class="ec-table-cell cell-rank">';
                    html += '                    <font style="vertical-align: inherit;">';
                    html += '                    <font style="vertical-align: inherit;">' + (players.statistics[0].goals.total || 0) + '</font>';
                    html += '                    </font>';
                    html += '                </div>'; 
                    html += '            </div> ';
                    i++;
                });
                html += '        </div>';
                html += '    </div>';
                html += '</div>';

 

                jQuery(".top-assists").html(html);
            
                 
        }

    },
    error: function() {}
    });
}



// 首頁查詢隊伍列表資訊
function init_teams_info() {
	//var season = getUrlParameter(location.href, 'season');
	//var league = getUrlParameter(location.href, 'league');
    var season = 2024;
	var league = 4;
	jQuery.ajax({
            url: "/games2/api.php",
            type: "POST",
            dataType: 'json',
            data: { 
              'act'   : 'query_standings_info',
              'season'   : season,
			  'league'   : league,
              },
              success: function (Jdata) {
                
               if(Jdata.code==200) {
					var html = ''; 
                    jQuery.each(Jdata.data,function(index,data){
                        var groupLabel = 'Grup ' + index;
 
                        html += '<div class="item_group">';
                        html += '    <div class="ue24_gtable_team">';
                        html += '        <div class="ue24_gtable_team_header">';
                        html += '            <div class="cell-group">';
                        html += '                <font style="vertical-align: inherit;">' + groupLabel + '</font>';
                        html += '            </div>';
                        html += '        </div>';
                        html += '        <div class="table-body">';
                        jQuery.each(data,function(index,teams){
                            //連結會是目前的網址加上 teams.team.tag_name
                            html += '            <a href="/teams/team-info?id=' + teams.team.id + '">'; 
                            html += '                <div class="ue24_gtable_team_row">';
                            html += '                    <div class="ue24_cell-group cell-group">';
                            html += '                        <ul class="team">';
                            html += '                            <li class="team_logo"><img src="' + teams.team.logo + '">';
                            html += '                            </li>';
                            html += '                            <li class="team-name" tabindex="0" data-toggle="tooltip" title="">';
                            html += '                                <font style="vertical-align: inherit;">' + teams.team.name + '</font>';
                            html += '                            </li>';
                            html += '                        </ul>';
                            html += '                    </div>';
                            html += '                </div>';
                            html += '            </a>'; 
                        });
                        html += '        </div>';
                        html += '    </div>';
                        html += '</div>';

                    });
                    jQuery(".item_flex_main").html(html);
				}  
            },
         error: function() {}
    });
}


// 首頁查詢隊伍介紹-陣容資訊
function init_teams_player_info() {
	var team = getUrlParameter(location.href, 'id');

    var season = 2024;
	var league = 4;

	jQuery.ajax({
            url: "/games2/api.php",
            type: "POST",
            dataType: 'json',
            data: { 
              'act'   : 'query_player_info',
              'season'   : season,
			  'league'   : league,
              'team'    : team
              },
              success: function (Jdata) {
                
               if(Jdata.code==200) {
                    jQuery("#country_name").html(Jdata.team.name);
                    jQuery("#team_logo").attr("src", Jdata.team.img);

                    var roleTranslations = {
                        'Defender': 'Defender',
                        'Midfielder': 'Gelandang',
                        'Attacker': 'Forward',
                        'Goalkeeper': 'Kiper'
                    };

					var html = '';
                    jQuery.each(Jdata.players,function(index,data){ 
                        var chineseRole = roleTranslations[index] || index;
                        html += '<div class="pos_title">' + chineseRole  + '</div>';
                        html += '<div class="ec-block-bxh">';
                        html += '	<div class="ec-table">';
                        html += '		<div class="play-table-header">';
                        html += '			<div class="ec-table-cell cell-group">';
                        html += '				<font style="vertical-align: inherit;">';
                        html += '					<font style="vertical-align: inherit;">Pemain</font>';
                        html += '				</font>';
                        html += '			</div>';
                        html += '			<div class="ec-table-cell cell-age">';
                        html += '				<font style="vertical-align: inherit;">';
                        html += '					<font style="vertical-align: inherit;">Umur</font>';
                        html += '				</font>';
                        html += '			</div>';
                        html += '			<div class="ec-table-cell cell-height">';
                        html += '				<font style="vertical-align: inherit;">';
                        html += '					<font style="vertical-align: inherit;">Tinggi</font>';
                        html += '				</font>';
                        html += '			</div>';
                        html += '			<div class="ec-table-cell cell-weight">';
                        html += '				<font style="vertical-align: inherit;">';
                        html += '					<font style="vertical-align: inherit;">Berat</font>';
                        html += '				</font>';
                        html += '			</div>';
                        html += '			<div class="ec-table-cell cell-number">';
                        html += '				<font style="vertical-align: inherit;">';
                        html += '					<font style="vertical-align: inherit;">Cedera</font>';
                        html += '				</font>';
                        html += '			</div>';
                        html += '		</div>';
                        html += '		<div class="ec-table-body">';
                        jQuery.each(data,function(index,p){
                            html += '			<div class="ec-table-row">';
                            html += '				<div class="ec-table-cell cell-group">';
                            html += '					<ul class="team">';
                            html += '						<li class="team-top-logo"><img decoding="async"';
                            html += '								src="' + p.player.photo + '"> </li>';
                            html += '						<li class="team-name" tabindex="0" data-toggle="tooltip" title="">';
                            html += '							<font style="vertical-align: inherit;">';
                            html += '								<font style="vertical-align: inherit;">' + p.player.name + '</font>';
                            html += '							</font>';
                            html += '						</li>';
                            html += '					</ul>';
                            html += '				</div>';
                            html += '				<div class="ec-table-cell cell-age">';
                            html += '					<font style="vertical-align: inherit;">';
                            html += '						<font style="vertical-align: inherit;">' + p.player.age + '</font>';
                            html += '					</font>';
                            html += '				</div>';
                            html += '				<div class="ec-table-cell cell-height">';
                            html += '					<font style="vertical-align: inherit;">';
                            html += '						<font style="vertical-align: inherit;">' + p.player.height + '</font>';
                            html += '					</font>';
                            html += '				</div>';
                            html += '				<div class="ec-table-cell cell-weight">';
                            html += '					<font style="vertical-align: inherit;">';
                            html += '						<font style="vertical-align: inherit;">' + p.player.weight + '</font>';
                            html += '					</font>';
                            html += '				</div>';
                            html += '				<div class="ec-table-cell cell-rank">';
                            html += '					<font style="vertical-align: inherit;">';
                            // 如果  p.player.weight 值false 顯示"否", 否則顯示"是"
                            html += '						<font style="vertical-align: inherit;">' + (p.player.injured ? 'YES' : 'NO') + '</font>'; 
                            html += '					</font>';
                            html += '				</div>';
                            html += '			</div>';
                        });
                        html += '		</div>';
                        html += '	</div>';
                        html += '</div>';
                    });

                    jQuery(".team_player_list").html(html); 

                    var html_detail = ''; 
                    html += '<div class="pos_title"></div>';
                    html_detail += '<div class="ec-block-bxh">';
                    html_detail += '	<div class="ec-table">';
                    html_detail += '		<div class="play-table-header">';
                    html_detail += '			<div class="ec-table-cell cell-group">';
                    html_detail += '				<font style="vertical-align: inherit;">';
                    html_detail += '					<font style="vertical-align: inherit;">Pemain</font>';
                    html_detail += '				</font>';
                    html_detail += '			</div>';

                    html_detail += '			<div class="ec-table-cell cell-number">';
                    html_detail += '				<font style="vertical-align: inherit;">';
                    html_detail += '					<font style="vertical-align: inherit;">Cedera</font>';
                    html_detail += '				</font>';
                    html_detail += '			</div>';

                    html_detail += '			<div class="ec-table-cell cell-number">';
                    html_detail += '				<font style="vertical-align: inherit;">';
                    html_detail += '					<font style="vertical-align: inherit;">Rating</font>';
                    html_detail += '				</font>';
                    html_detail += '			</div>';
                    html_detail += '			<div class="ec-table-cell cell-number">';
                    html_detail += '				<font style="vertical-align: inherit;">';
                    html_detail += '					<font style="vertical-align: inherit;">Tembakan</font>';
                    html_detail += '				</font>';
                    html_detail += '			</div>';
                    html_detail += '			<div class="ec-table-cell cell-number">';
                    html_detail += '				<font style="vertical-align: inherit;">';
                    html_detail += '					<font style="vertical-align: inherit;">Tembakan tepat</font>';
                    html_detail += '				</font>';
                    html_detail += '			</div>';
                    html_detail += '			<div class="ec-table-cell cell-number">';
                    html_detail += '				<font style="vertical-align: inherit;">';
                    html_detail += '					<font style="vertical-align: inherit;">Skor</font>';
                    html_detail += '				</font>';
                    html_detail += '			</div>';
                    html_detail += '			<div class="ec-table-cell cell-number">';
                    html_detail += '				<font style="vertical-align: inherit;">';
                    html_detail += '					<font style="vertical-align: inherit;">Assist</font>';
                    html_detail += '				</font>';
                    html_detail += '			</div>';
                    html_detail += '			<div class="ec-table-cell cell-number">';
                    html_detail += '				<font style="vertical-align: inherit;">';
                    html_detail += '					<font style="vertical-align: inherit;">Bertahan</font>';
                    html_detail += '				</font>';
                    html_detail += '			</div>';
                    html_detail += '			<div class="ec-table-cell cell-number">';
                    html_detail += '				<font style="vertical-align: inherit;">';
                    html_detail += '					<font style="vertical-align: inherit;">Umpan</font>';
                    html_detail += '				</font>';
                    html_detail += '			</div>';
                    html_detail += '			<div class="ec-table-cell cell-number">';
                    html_detail += '				<font style="vertical-align: inherit;">';
                    html_detail += '					<font style="vertical-align: inherit;">Kartu kuning</font>';
                    html_detail += '				</font>';
                    html_detail += '			</div>';
                    html_detail += '			<div class="ec-table-cell cell-number">';
                    html_detail += '				<font style="vertical-align: inherit;">';
                    html_detail += '					<font style="vertical-align: inherit;">Kartu merah</font>';
                    html_detail += '				</font>';
                    html_detail += '			</div>';
                    html_detail += '		</div>';
                    html_detail += '		<div class="ec-table-body">';
                    jQuery.each(Jdata.players,function(index,data){  
                        var chineseRole = roleTranslations[index] || index;
                        jQuery.each(data,function(index,p){
                            
                            html_detail += '			<div class="ec-table-row">';
                            html_detail += '				<div class="ec-table-cell cell-group">';
                            html_detail += '					<ul class="team">';
                            html_detail += '						<li class="team-top-logo"><img decoding="async"';
                            html_detail += '								src="' + p.player.photo + '"> </li>';
                            html_detail += '						<li class="team-name" tabindex="0" data-toggle="tooltip" title="">';
                            html_detail += '							<font style="vertical-align: inherit;">';
                            html_detail += '								<font style="vertical-align: inherit;">' + p.player.name + '</font>';
                            html_detail += '							</font>';
                            html_detail += '						</li>';
                            html_detail += '					</ul>';
                            html_detail += '				</div>';

                            html_detail += '				<div class="ec-table-cell cell-height">';
                            html_detail += '					<font style="vertical-align: inherit;">';
                            html_detail += '						<font style="vertical-align: inherit;">' + chineseRole + '</font>';
                            html_detail += '					</font>';
                            html_detail += '				</div>'; 
                            html_detail += '				<div class="ec-table-cell cell-weight">';
                            html_detail += '					<font style="vertical-align: inherit;">';
                            html_detail += '						<font style="vertical-align: inherit;">' + (p.statistics[0].games.rating ? parseFloat(p.statistics[0].games.rating).toFixed(2) : '0.00') + '</font>';
                            html_detail += '					</font>';
                            html_detail += '				</div>';

                            html_detail += '				<div class="ec-table-cell cell-weight">';
                            html_detail += '					<font style="vertical-align: inherit;">';
                            html_detail += '						<font style="vertical-align: inherit;">' + (p.statistics[0].shots.total || 0) + '</font>';
                            html_detail += '					</font>';
                            html_detail += '				</div>';
                            html_detail += '				<div class="ec-table-cell cell-weight">';
                            html_detail += '					<font style="vertical-align: inherit;">';
                            html_detail += '						<font style="vertical-align: inherit;">' + (p.statistics[0].shots.on || 0) + '</font>';
                            html_detail += '					</font>';
                            html_detail += '				</div>';
                            html_detail += '				<div class="ec-table-cell cell-weight">';
                            html_detail += '					<font style="vertical-align: inherit;">';
                            html_detail += '						<font style="vertical-align: inherit;">' + (p.statistics[0].goals.total || 0) + '</font>';
                            html_detail += '					</font>';
                            html_detail += '				</div>';
                            html_detail += '				<div class="ec-table-cell cell-weight">';
                            html_detail += '					<font style="vertical-align: inherit;">';
                            html_detail += '						<font style="vertical-align: inherit;">' + (p.statistics[0].goals.assists || 0) + '</font>';
                            html_detail += '					</font>';
                            html_detail += '				</div>';
                            html_detail += '				<div class="ec-table-cell cell-weight">';
                            html_detail += '					<font style="vertical-align: inherit;">';
                            html_detail += '						<font style="vertical-align: inherit;">' + (p.statistics[0].goals.saves || 0) + '</font>';
                            html_detail += '					</font>';
                            html_detail += '				</div>';
                            html_detail += '				<div class="ec-table-cell cell-weight">';
                            html_detail += '					<font style="vertical-align: inherit;">';
                            html_detail += '						<font style="vertical-align: inherit;">' + (p.statistics[0].passes.total || 0) + '</font>';
                            html_detail += '					</font>';
                            html_detail += '				</div>';
                            html_detail += '				<div class="ec-table-cell cell-weight">';
                            html_detail += '					<font style="vertical-align: inherit;">';
                            html_detail += '						<font style="vertical-align: inherit;">' + (p.statistics[0].cards.yellow || 0) + '</font>';
                            html_detail += '					</font>';
                            html_detail += '				</div>';
                            html_detail += '				<div class="ec-table-cell cell-weight">';
                            html_detail += '					<font style="vertical-align: inherit;">';
                            html_detail += '						<font style="vertical-align: inherit;">' + (p.statistics[0].cards.red || 0) + '</font>';
                            html_detail += '					</font>';
                            html_detail += '				</div>';
                            html_detail += '			</div>';
                        });
                    });
                    html_detail += '		</div>';
                    html_detail += '	</div>';
                    html_detail += '</div>';
                

                 jQuery(".team_player_data-container").html(html_detail); 

				}  
            },
         error: function() {}
    });
}



// 首頁查詢隊伍賽事資訊
function init_teams_games_info() {
	var team = getUrlParameter(location.href, 'id');

    var season = 2024;
	var league = 4;

	jQuery.ajax({
            url: "/games2/api.php",
            type: "POST",
            dataType: 'json',
            data: { 
              'act'   : 'query_teams_games_info',
              'season'   : season,
			  'league'   : league,
              'team'    : team
              },
              success: function (Jdata) {
                
               if(Jdata.code==200) {
 
					var html = '';
                    jQuery.each(Jdata.data,function(index,data){ 
                        html += '<div class="ue24_match-date">' + data.game.date + '</div>'; 
                        html += '<div class="eu24-match-container">';  
                        html += '    <div  class="ue24_match_block">';
                        html += '        <a href="/uefa2024/match-score?league=' + data.league.id+ '&season=' + data.league.season+ '&fixture=' + data.fixture.id+ '" class="ue24_scoreboard">';
                        html += '           <div class="ue24_match-status"><span>' + data.game.time + '</span></div>';
                        html += '           <div class="ue24_score-content">';
                        html += '               <div class="ue24_matchscore_team left-ue24_matchscore_team">';
                        html += '                   <div class="ue24_mobile-score-display">';
                        html += '                       <div class="ue24_final-score">';
                        html += '                           <span>' + (data.goals.home || 0) + ' (' + (data.score.halftime.home || 0) + ')</span>';
                        html += '                       </div>';
                        html += '                   </div>';
                        html += '                   <span class="ue24_matchscore_team-name">' + data.teams.home.name + '</span>';
                        html += '                   <img src="' + data.teams.home.logo + '" alt="">';
                        html += '               </div>';
                        html += '               <div class="ue24_matchscore_block">';
                        html += '                   <div class="ue24_matchscore_block_p">';
                        html += '                       <span class="ue24_final-score"><span>' + (data.goals.home || 0) + '</span><span>-</span><span>' + (data.goals.away || 0) + '</span></span>';
                        html += '                       <span class="overtime-score">HT:' + (data.score.halftime.home || 0) + '-' + (data.score.halftime.away || 0) + '</span>';
                        html += '                   </div>';
                        html += '               </div>';
                        html += '               <div class="ue24_matchscore_team right-ue24_matchscore_team">';
                        html += '                   <img src="' + data.teams.away.logo + '" alt="">';
                        html += '                   <span class="ue24_matchscore_team-name">' + data.teams.away.name + '</span>';
                        //html += '                 <span class="victory-label">W</span>';
                        html += '                   <div class="ue24_mobile-score-display">';
                        html += '                       <div class="ue24_final-score">';
                        html += '                           <span>' + (data.goals.away || 0) + ' (' + (data.score.halftime.away || 0) + ')</span>';
                        html += '                       </div>';
                        html += '                   </div>';
                        html += '               </div>';
                        html += '           </div>';
                        html += '       </a>';
                        html += '   </div>'; 
                        html += '</div>'; 

                    });

                    jQuery(".eu24-schedule-container").html(html); 
 
				}  
            },
         error: function() {}
    });
}



// 查詢賽事表
function init_fixtures_info() {
	var team = getUrlParameter(location.href, 'id');

    var season = 2024;
	var league = 4;

	jQuery.ajax({
            url: "/games2/api.php",
            type: "POST",
            dataType: 'json',
            data: { 
              'act'   : 'query_fixtures_info',
              'season'   : season,
			  'league'   : league 
              },
              success: function (Jdata) {
                
                if(Jdata.code==200) {
 
                    // 分組存儲比賽資料，使用日期作為 key
                    let groupedByDate = {};

                    // 分組遍歷 GroupStage 中的比賽
                    jQuery.each(Jdata.GroupStage, function (index, data) {
                        const gameDate = data.game.date;
                        if (!groupedByDate[gameDate]) {
                            groupedByDate[gameDate] = [];
                        }
                        groupedByDate[gameDate].push(data);
                    });

                    // 構建 HTML
                    let html = "";
                    for (const date in groupedByDate) {
                        if (groupedByDate.hasOwnProperty(date)) {
                            // 每個日期區塊的標題
                            html += '<div class="ue24_match-date">' + date + '</div>'; 
                            html += '<div class="eu24-match-container">'; 
                            // 每個日期下的比賽 
                            groupedByDate[date].forEach(function (data) {
                                html += '    <div  class="ue24_match_block">';
                                html += '        <a href="/uefa2024/match-score?league=' + data.league.id+ '&season=' + data.league.season+ '&fixture=' + data.fixture.id+ '" class="ue24_scoreboard">';
                                html += '           <div class="ue24_match-status"><span>' + data.game.time + '</span></div>';
                                html += '           <div class="ue24_score-content">';
                                html += '               <div class="ue24_matchscore_team left-ue24_matchscore_team">';
                                html += '                   <div class="ue24_mobile-score-display">';
                                html += '                       <div class="ue24_final-score">';
                                html += '                           <span>' + (data.goals.home || 0) + ' (' + (data.score.halftime.home || 0) + ')</span>';
                                html += '                       </div>';
                                html += '                   </div>';
                                html += '                   <span class="ue24_matchscore_team-name">' + data.teams.home.name + '</span>';
                                html += '                   <img src="' + data.teams.home.logo + '" alt="">';
                                html += '               </div>';
                                html += '               <div class="ue24_matchscore_block">';
                                html += '                   <div class="ue24_matchscore_block_p">';
                                html += '                       <span class="ue24_final-score"><span>' + (data.goals.home || 0) + '</span><span>-</span><span>' + (data.goals.away || 0) + '</span></span>';
                                html += '                       <span class="overtime-score">HT:' + (data.score.halftime.home || 0) + '-' + (data.score.halftime.away || 0) + '</span>';
                                html += '                   </div>';
                                html += '               </div>';
                                html += '               <div class="ue24_matchscore_team right-ue24_matchscore_team">';
                                html += '                   <img src="' + data.teams.away.logo + '" alt="">';
                                html += '                   <span class="ue24_matchscore_team-name">' + data.teams.away.name + '</span>';
                                //html += '                 <span class="victory-label">w</span>';
                                html += '                   <div class="ue24_mobile-score-display">';
                                html += '                       <div class="ue24_final-score">';
                                html += '                           <span>' + (data.goals.away || 0) + ' (' + (data.score.halftime.away || 0) + ')</span>';
                                html += '                       </div>';
                                html += '                   </div>';
                                html += '               </div>';
                                html += '           </div>';
                                html += '       </a>';
                                html += '   </div>'; 
                            });
  
                            html += '</div>';
                        }
                    }

                    // 將生成的 HTML 放入容器中
                    jQuery("#eu24_GroupStage_schedule").html(html);

                    //////////////////////////////////////////////// 以上小組賽 ////////////////////////////////////////////////					

                    // 分組存儲比賽資料，使用日期作為 key
                    let RoundOf16ByDate = {};

                    // 分組遍歷 GroupStage 中的比賽
                    jQuery.each(Jdata.RoundOf16, function (index, data) {
                        const gameDate = data.game.date;
                        if (!RoundOf16ByDate[gameDate]) {
                            RoundOf16ByDate[gameDate] = [];
                        }
                        RoundOf16ByDate[gameDate].push(data);
                    });

                    // 構建 HTML
                    let html_16 = "";
                    for (const date in RoundOf16ByDate) {
                        if (RoundOf16ByDate.hasOwnProperty(date)) {
                            // 每個日期區塊的標題
                            html_16 += '<div class="ue24_match-date">' + date + '</div>'; 
                            html_16 += '<div class="eu24-match-container">'; 
                            // 每個日期下的比賽
                            RoundOf16ByDate[date].forEach(function (data) {
                                html_16 += '    <div class="ue24_match_block">';
                                html_16 += '        <a href="/uefa2024/match-score?league=' + data.league.id+ '&season=' + data.league.season+ '&fixture=' + data.fixture.id+ '" class="ue24_scoreboard">';
                                html_16 += '        <div class="ue24_match-status"><span>' + data.game.time + '</span></div>';
                                html_16 += '        <div class="ue24_score-content">';
                                html_16 += '            <div class="ue24_matchscore_team left-ue24_matchscore_team">';
                                html_16 += '               <div class="ue24_mobile-score-display">';
                                html_16 += '                   <div class="ue24_final-score">';
                                html_16 += '                       <span>' + (data.goals.home || 0) + ' (' + (data.score.halftime.home || 0) + ')</span>';
                                html_16 += '                   </div>';
                                html_16 += '               </div>';
                                html_16 += '               <span class="ue24_matchscore_team-name">' + data.teams.home.name + '</span>';
                                html_16 += '               <img src="' + data.teams.home.logo + '" alt="">';
                                html_16 += '            </div>';
                                html_16 += '            <div class="ue24_matchscore_block">';
                                html_16 += '                <div class="ue24_matchscore_block_p">';
                                html_16 += '                    <span class="ue24_final-score"><span>' + (data.goals.home || 0) + '</span><span>-</span><span>' + (data.goals.away || 0) + '</span></span>';
                                html_16 += '                    <span class="overtime-score">HT:' + (data.score.halftime.home || 0) + '-' + (data.score.halftime.away || 0) + '</span>';
                                html_16 += '                </div>';
                                html_16 += '            </div>';
                                html_16 += '            <div class="ue24_matchscore_team right-ue24_matchscore_team">';
                                html_16 += '                <img src="' + data.teams.away.logo + '" alt="">';
                                html_16 += '                <span class="ue24_matchscore_team-name">' + data.teams.away.name + '</span>';
                                //html_16 += '                <span class="victory-label">w</span>';
                                html_16 += '                <div class="ue24_mobile-score-display">';
                                html_16 += '                   <div class="ue24_final-score">';
                                html_16 += '                       <span>' + (data.goals.away || 0) + ' (' + (data.score.halftime.away || 0) + ')</span>';
                                html_16 += '                   </div>';
                                html_16 += '               </div>';
                                html_16 += '            </div>';
                                html_16 += '        </div>';
                                html_16 += '   </a>';
                                html_16 += '   </div>'; 
                            });
  
                            html_16 += '</div>';
                        }
                    }

                    // 將生成的 HTML 放入容器中
                    jQuery("#eu24_RoundOf16_schedule").html(html_16);

                    //////////////////////////////////////////////// 以上16強 ////////////////////////////////////////////////					

                    // 分組存儲比賽資料，使用日期作為 key
                    let QuarterFinalsByDate = {};

                    // 分組遍歷 GroupStage 中的比賽
                    jQuery.each(Jdata.QuarterFinals, function (index, data) {
                        const gameDate = data.game.date;
                        if (!QuarterFinalsByDate[gameDate]) {
                            QuarterFinalsByDate[gameDate] = [];
                        }
                        QuarterFinalsByDate[gameDate].push(data);
                    });

                    // 構建 HTML
                    let html_8 = "";
                    for (const date in QuarterFinalsByDate) {
                        if (QuarterFinalsByDate.hasOwnProperty(date)) {
                            // 每個日期區塊的標題
                            html_8 += '<div class="ue24_match-date">' + date + '</div>'; 
                            html_8 += '<div class="eu24-match-container">'; 
                            // 每個日期下的比賽
                            QuarterFinalsByDate[date].forEach(function (data) {
                                html_8 += '    <div class="ue24_match_block">';
                                html_8 += '        <a href="/uefa2024/match-score?league=' + data.league.id+ '&season=' + data.league.season+ '&fixture=' + data.fixture.id+ '" class="ue24_scoreboard">';
                                html_8 += '        <div class="ue24_match-status"><span>' + data.game.time + '</span></div>';
                                html_8 += '        <div class="ue24_score-content">';
                                html_8 += '            <div class="ue24_matchscore_team left-ue24_matchscore_team">';
                                html_8 += '               <div class="ue24_mobile-score-display">';
                                html_8 += '                   <div class="ue24_final-score">';
                                html_8 += '                       <span>' + (data.goals.home || 0) + ' (' + (data.score.halftime.home || 0) + ')</span>';
                                html_8 += '                   </div>';
                                html_8 += '               </div>';
                                html_8 += '               <span class="ue24_matchscore_team-name">' + data.teams.home.name + '</span>';
                                html_8 += '               <img src="' + data.teams.home.logo + '" alt="">';
                                html_8 += '            </div>';
                                html_8 += '            <div class="ue24_matchscore_block">';
                                html_8 += '                <div class="ue24_matchscore_block_p">';
                                html_8 += '                    <span class="ue24_final-score"><span>' + (data.goals.home || 0) + '</span><span>-</span><span>' + (data.goals.away || 0) + '</span></span>';
                                html_8 += '                    <span class="overtime-score">HT:' + (data.score.halftime.home || 0) + '-' + (data.score.halftime.away || 0) + '</span>';
                                html_8 += '                </div>';
                                html_8 += '            </div>';
                                html_8 += '            <div class="ue24_matchscore_team right-ue24_matchscore_team">';
                                html_8 += '                <img src="' + data.teams.away.logo + '" alt="">';
                                html_8 += '                <span class="ue24_matchscore_team-name">' + data.teams.away.name + '</span>';
                                //html_8 += '                <span class="victory-label">w</span>';
                                html_8 += '                <div class="ue24_mobile-score-display">';
                                html_8 += '                   <div class="ue24_final-score">';
                                html_8 += '                       <span>' + (data.goals.away || 0) + ' (' + (data.score.halftime.away || 0) + ')</span>';
                                html_8 += '                   </div>';
                                html_8 += '               </div>';
                                html_8 += '            </div>';
                                html_8 += '        </div>';
                                html_8 += '   </a>';
                                html_8 += '   </div>'; 
                            });
  
                            html_8 += '</div>';
                        }
                    }

                    // 將生成的 HTML 放入容器中
                    jQuery("#eu24_QuarterFinals_schedule").html(html_8);

                    //////////////////////////////////////////////// 以上8強 ////////////////////////////////////////////////

                    // 分組存儲比賽資料，使用日期作為 key
                    let SemiFinalsByDate = {};

                    // 分組遍歷 GroupStage 中的比賽
                    jQuery.each(Jdata.SemiFinals, function (index, data) {
                        const gameDate = data.game.date;
                        if (!SemiFinalsByDate[gameDate]) {
                            SemiFinalsByDate[gameDate] = [];
                        }
                        SemiFinalsByDate[gameDate].push(data);
                    });

                    // 構建 HTML
                    let html_4 = "";
                    for (const date in SemiFinalsByDate) {
                        if (SemiFinalsByDate.hasOwnProperty(date)) {
                            // 每個日期區塊的標題
                            html_4 += '<div class="ue24_match-date">' + date + '</div>'; 
                            html_4 += '<div class="eu24-match-container">'; 
                            // 每個日期下的比賽
                            SemiFinalsByDate[date].forEach(function (data) {
                                html_4 += '    <div class="ue24_match_block">';
                                html_4 += '        <a href="/uefa2024/match-score?league=' + data.league.id+ '&season=' + data.league.season+ '&fixture=' + data.fixture.id+ '" class="ue24_scoreboard">';
                                html_4 += '        <div class="ue24_match-status"><span>' + data.game.time + '</span></div>';
                                html_4 += '        <div class="ue24_score-content">';
                                html_4 += '            <div class="ue24_matchscore_team left-ue24_matchscore_team">';
                                html_4 += '               <div class="ue24_mobile-score-display">';
                                html_4 += '                   <div class="ue24_final-score">';
                                html_4 += '                       <span>' + (data.goals.home || 0) + ' (' + (data.score.halftime.home || 0) + ')</span>';
                                html_4 += '                   </div>';
                                html_4 += '               </div>';
                                html_4 += '               <span class="ue24_matchscore_team-name">' + data.teams.home.name + '</span>';
                                html_4 += '               <img src="' + data.teams.home.logo + '" alt="">';
                                html_4 += '            </div>';
                                html_4 += '            <div class="ue24_matchscore_block">';
                                html_4 += '                <div class="ue24_matchscore_block_p">';
                                html_4 += '                    <span class="ue24_final-score"><span>' + (data.goals.home || 0) + '</span><span>-</span><span>' + (data.goals.away || 0) + '</span></span>';
                                html_4 += '                    <span class="overtime-score">HT:' + (data.score.halftime.home || 0) + '-' + (data.score.halftime.away || 0) + '</span>';
                                html_4 += '                </div>';
                                html_4 += '            </div>';
                                html_4 += '            <div class="ue24_matchscore_team right-ue24_matchscore_team">';
                                html_4 += '                <img src="' + data.teams.away.logo + '" alt="">';
                                html_4 += '                <span class="ue24_matchscore_team-name">' + data.teams.away.name + '</span>';
                                //html_4 += '                <span class="victory-label">w</span>';
                                html_4 += '                <div class="ue24_mobile-score-display">';
                                html_4 += '                   <div class="ue24_final-score">';
                                html_4 += '                       <span>' + (data.goals.away || 0) + ' (' + (data.score.halftime.away || 0) + ')</span>';
                                html_4 += '                   </div>';
                                html_4 += '               </div>';
                                html_4 += '            </div>';
                                html_4 += '        </div>';
                                html_4 += '   </a>';
                                html_4 += '   </div>'; 
                            });
  
                            html_4 += '</div>';
                        }
                    }

                    // 將生成的 HTML 放入容器中
                    jQuery("#eu24_SemiFinals_schedule").html(html_4);

                    //////////////////////////////////////////////// 以上4強 ////////////////////////////////////////////////

                    // 分組存儲比賽資料，使用日期作為 key
                    let FinalByDate = {};

                    // 分組遍歷 GroupStage 中的比賽
                    jQuery.each(Jdata.Final, function (index, data) {
                        const gameDate = data.game.date;
                        if (!FinalByDate[gameDate]) {
                            FinalByDate[gameDate] = [];
                        }
                        FinalByDate[gameDate].push(data);
                    });

                    // 構建 HTML
                    let html_2 ="";
                    for (const date in FinalByDate) {
                        if (FinalByDate.hasOwnProperty(date)) {
                            // 每個日期區塊的標題
                            html_2 += '<div class="ue24_match-date">' + date + '</div>'; 
                            html_2 += '<div class="eu24-match-container">'; 
                            // 每個日期下的比賽
                            FinalByDate[date].forEach(function (data) {
                                html_2 += '    <div class="ue24_match_block">';
                                html_2 += '        <a href="/uefa2024/match-score?league=' + data.league.id+ '&season=' + data.league.season+ '&fixture=' + data.fixture.id+ '" class="ue24_scoreboard">';
                                html_2 += '        <div class="ue24_match-status"><span>' + data.game.time + '</span></div>';
                                html_2 += '        <div class="ue24_score-content">';
                                html_2 += '            <div class="ue24_matchscore_team left-ue24_matchscore_team">';
                                html_2 += '               <div class="ue24_mobile-score-display">';
                                html_2 += '                   <div class="ue24_final-score">';
                                html_2 += '                       <span>' + (data.goals.home || 0) + ' (' + (data.score.halftime.home || 0) + ')</span>';
                                html_2 += '                   </div>';
                                html_2 += '               </div>';
                                html_2 += '               <span class="ue24_matchscore_team-name">' + data.teams.home.name + '</span>';
                                html_2 += '               <img src="' + data.teams.home.logo + '" alt="">';
                                html_2 += '            </div>';
                                html_2 += '            <div class="ue24_matchscore_block">';
                                html_2 += '                <div class="ue24_matchscore_block_p">';
                                html_2 += '                    <span class="ue24_final-score"><span>' + (data.goals.home || 0) + '</span><span>-</span><span>' + (data.goals.away || 0) + '</span></span>';
                                html_2 += '                    <span class="overtime-score">HT:' + (data.score.halftime.home || 0) + '-' + (data.score.halftime.away || 0) + '</span>';
                                html_2 += '                </div>';
                                html_2 += '            </div>';
                                html_2 += '            <div class="ue24_matchscore_team right-ue24_matchscore_team">';
                                html_2 += '                <img src="' + data.teams.away.logo + '" alt="">';
                                html_2 += '                <span class="ue24_matchscore_team-name">' + data.teams.away.name + '</span>';
                                //html_2 += '                <span class="victory-label">w</span>';
                                html_2 += '                <div class="ue24_mobile-score-display">';
                                html_2 += '                   <div class="ue24_final-score">';
                                html_2 += '                       <span>' + (data.goals.away || 0) + ' (' + (data.score.halftime.away || 0) + ')</span>';
                                html_2 += '                   </div>';
                                html_2 += '               </div>';
                                html_2 += '            </div>';
                                html_2 += '        </div>';
                                html_2 += '   </a>';
                                html_2 += '   </div>'; 
                            });
  
                            html_2 += '</div>';
                        }
                    }

                    // 將生成的 HTML 放入容器中
                    jQuery("#eu24_Final_schedule").html(html_2);

                    //////////////////////////////////////////////// 以上冠軍賽 ////////////////////////////////////////////////
				}  
            },
         error: function() {}
    });
}



// 查詢分組積分榜資訊
function init_standings_group_detail() {
	//var season = getUrlParameter(location.href, 'season');
	//var league = getUrlParameter(location.href, 'league');
    var season = 2024;
	var league = 4;
	jQuery.ajax({
            url: "/games2/api.php",
            type: "POST",
            dataType: 'json',
            data: { 
              'act'   : 'query_standings_info',
              'season'   : season,
			  'league'   : league,
              },
              success: function (Jdata) {
                
               if(Jdata.code==200) {
					var html = ''; 
                    jQuery.each(Jdata.data,function(index,data){
                        var groupLabel = 'Grup ' + index;
                        var i = 1;
                        html += '<div class="ue24_all_group_table-container">';
                        html += '    <table>';
                        html += '        <caption>' + groupLabel +'</caption>';
                        html += '        <thead>';
                        html += '            <tr>';
                        html += '                <th>#</th>';
                        html += '                <th style=" text-align: left; ">team</th>';
                        html += '                <th>Jumlah Pertandingan</th>';
                        html += '                <th>W</th>'; // Menang
                        html += '                <th>D</th>'; // Seri
                        html += '                <th>L</th>'; // Kalah
                        html += '                <th>Jumlah Gol</th>'; // 进球数
                        html += '                <th>Gol Kebobolan</th>'; // 失球数
                        html += '                <th>+/-</th>'; // Selisih Gol
                        html += '                <th>Poin</th>'; // 積分

                        html += '            </tr>';
                        html += '        </thead>';
                        html += '        <tbody>';
                        jQuery.each(data, function(index, teams) {
                            html += '<tr>';
                            // Use a loop to create each cell with an anchor wrapping its content
                            html += '   <td><a href="/teams/team-info?id=' + teams.team.id + '"><span class="' + (index == 0 ? 'first-width-number' : '') + '">' + (index + 1) + '</span></a></td>';
                            html += '   <td><a href="/teams/team-info?id=' + teams.team.id + '">';
                            html += '       <div class="ue24_cell-group cell-group">';
                            html += '           <ul class="team">';
                            html += '               <li class="team_logo"><img decoding="async" src="' + teams.team.logo + '"></li>';
                            html += '               <li class="team-name" tabindex="0" data-toggle="tooltip" title=""><font style="vertical-align: inherit;">' + teams.team.name + '</font></li>';
                            html += '           </ul>';
                            html += '       </div>';
                            html += '   </a></td>';
                            html += '   <td><a href="/teams/team-info?id=' + teams.team.id + '">' + teams.all.played + '</a></td>';
                            html += '   <td><a href="/teams/team-info?id=' + teams.team.id + '">' + teams.all.win + '</a></td>';
                            html += '   <td><a href="/teams/team-info?id=' + teams.team.id + '">' + teams.all.draw + '</a></td>';
                            html += '   <td><a href="/teams/team-info?id=' + teams.team.id + '">' + teams.all.lose + '</a></td>';
                            html += '   <td><a href="/teams/team-info?id=' + teams.team.id + '">' + teams.all.goals.for + '</a></td>';
                            html += '   <td><a href="/teams/team-info?id=' + teams.team.id + '">' + teams.all.goals.against + '</a></td>';
                            html += '   <td><a href="/teams/team-info?id=' + teams.team.id + '">' + teams.goalsDiff + '</a></td>';
                            html += '   <td><a href="/teams/team-info?id=' + teams.team.id + '">' + teams.points + '</a></td>';
                            html += '</tr>';
                        
                            i++;  
                        });
                        
                        html += '        </tbody>';
                        html += '    </table>';
                        html += '</div>';

                    });
                    jQuery("#eu24_group_game").html(html);
				}  
            },
         error: function() {}
    });
}



// 查詢分組積分榜淘汰賽資訊
function init_standings_out_detail() {
	var team = getUrlParameter(location.href, 'id');

    var season = 2024;
	var league = 4;

	jQuery.ajax({
            url: "/games2/api.php",
            type: "POST",
            dataType: 'json',
            data: { 
              'act'   : 'query_fixtures_info',
              'season'   : season,
			  'league'   : league 
              },
              success: function (Jdata) {
                
                if(Jdata.code==200) {
  
                    // 分組遍歷 GroupStage 中的比賽
                    i=1;
                    jQuery.each(Jdata.RoundOf16, function (index, data) {
                        var html_16 = '';
                        html_16 += '<a href="/uefa2024/match-score?league=' + data.league.id + '&season=' + data.league.season + '&fixture=' + data.fixture.id + '" class="tournament-eu24-match" target="_blank">';  
                        html_16 += '    <div class="eu24-scoreboard-date">' + data.game.date + '</div>';
                        html_16 += '    <div class="eu24-match-info">';
                        html_16 += '        <div class="eu24-team-info"> <img src="' + data.teams.home.logo + '" alt="Flag"> <span class="eu24-team-name">' + data.teams.home.name + '</span> <span class="eu24-team-score">' + (data.goals.home || 0);
                        if (data.score.penalty.home != null  ) {
                            html_16 += ' (' + data.score.penalty.home + ')';
                        }
                        html_16 += '</span> </div>';
                        html_16 += '        <div class="eu24-team-info"> <img src="' + data.teams.away.logo + '" alt="Flag"> <span class="eu24-team-name">' + data.teams.away.name + '</span> <span class="eu24-team-score">' + (data.goals.away || 0);
                        if (data.score.penalty.away != null ) {
                            html_16 += ' (' + data.score.penalty.away + ')';
                        }
                        html_16 += '</span> </div>';
                        html_16 += '    </div>';
                        html_16 += '</a>';
                        // 將生成的 HTML 放入容器中
                        jQuery("#RoundOf16_" + i).html(html_16);
                        i++;
                    });
                    
  
                    //////////////////////////////////////////////// 以上16強 ////////////////////////////////////////////////					
                    
                    // 分組遍歷 GroupStage 中的比賽
                    i=1;
                    jQuery.each(Jdata.QuarterFinals, function (index, data) {
                        var html_8='';
                        html_8 += '<a href="/uefa2024/match-score?league=' + data.league.id + '&season=' + data.league.season + '&fixture=' + data.fixture.id + '" class="tournament-eu24-match" target="_blank">';  
                        html_8 += '    <div class="eu24-scoreboard-date">' + data.game.date + '</div>';
                        html_8 += '    <div class="eu24-match-info">';
                        html_8 += '        <div class="eu24-team-info"> <img src="' + data.teams.home.logo + '" alt="Flag"> <span class="eu24-team-name">' + data.teams.home.name + '</span> <span class="eu24-team-score">' + (data.goals.home || 0);
                        if (data.score.penalty.home != null  ) {
                            html_8 += ' (' + data.score.penalty.home + ')';
                        }
                        html_8 += '</span> </div>';
                        html_8 += '        <div class="eu24-team-info"> <img src="' + data.teams.away.logo + '" alt="Flag"> <span class="eu24-team-name">' + data.teams.away.name + '</span> <span class="eu24-team-score">' + (data.goals.away || 0);
                        if (data.score.penalty.away != null ) {
                            html_8 += ' (' + data.score.penalty.away + ')';
                        }
                        html_8 += '</span> </div>';
                        html_8 += '    </div>';
                        html_8 += '</div>';
                        // 將生成的 HTML 放入容器中
                        jQuery("#QuarterFinals_"+i).html(html_8);
                        i++;
                    });
 

                    //////////////////////////////////////////////// 以上8強 //////////////////////////////////////////////// 

                    // 分組遍歷 GroupStage 中的比賽
                    i=1;
                    jQuery.each(Jdata.SemiFinals, function (index, data) {
                        var html_4='';
                        html_4 += '<a href="/uefa2024/match-score?league=' + data.league.id + '&season=' + data.league.season + '&fixture=' + data.fixture.id + '" class="tournament-eu24-match" target="_blank">';  
                        html_4 += '    <div class="eu24-scoreboard-date">' + data.game.date + '</div>';
                        html_4 += '    <div class="eu24-match-info">'; 
                        html_4 += '        <div class="eu24-team-info"> <img src="' + data.teams.home.logo + '" alt="Flag"> <span class="eu24-team-name">' + data.teams.home.name + '</span> <span class="eu24-team-score">' + (data.goals.home || 0);
                        if (data.score.penalty.home != null  ) {
                            html_4 += ' (' + data.score.penalty.home + ')';
                        }
                        html_4 += '</span> </div>';
                        html_4 += '        <div class="eu24-team-info"> <img src="' + data.teams.away.logo + '" alt="Flag"> <span class="eu24-team-name">' + data.teams.away.name + '</span> <span class="eu24-team-score">' + (data.goals.away || 0);
                        if (data.score.penalty.away != null ) {
                            html_4 += ' (' + data.score.penalty.away + ')';
                        }
                        html_4 += '</span> </div>';
                        html_4 += '    </div>';
                        html_4 += '</div>';
                        // 將生成的 HTML 放入容器中
                        jQuery("#SemiFinals_"+i).html(html_4);
                        i++;
                    });
 
                    //////////////////////////////////////////////// 以上4強 ////////////////////////////////////////////////
 
                    // 分組遍歷 GroupStage 中的比賽
                    i=1;
                    jQuery.each(Jdata.Final, function (index, data) { 
                        var html_2='';html_2 += '<div class="" style=" margin: 0 auto; position: relative; ">'; 
                        html_2 += '   <img src="https://www.thesabanewscn.com/wp-content/uploads/2024/05/eu24_trophy.svg" style="text-align:center;width:60px;margin-bottom: 10px;">';
                        html_2 += '</div>'; 
                        html_2 += '<a href="/uefa2024/match-score?league=' + data.league.id + '&season=' + data.league.season + '&fixture=' + data.fixture.id + '" class="tournament-eu24-match" target="_blank" style="margin-bottom:100px;">';  
                        html_2 += '   <div class="eu24-scoreboard-date">' + data.game.date + '</div>';
                        html_2 += '   <div class="eu24-match-info">'; 
                        html_2 += '        <div class="eu24-team-info"> <img src="' + data.teams.home.logo + '" alt="Flag"> <span class="eu24-team-name">' + data.teams.home.name + '</span> <span class="eu24-team-score">' + (data.goals.home || 0);
                        if (data.score.penalty.home != null  ) {
                            html_2 += ' (' + data.score.penalty.home + ')';
                        }
                        html_2 += '</span> </div>';
                        html_2 += '        <div class="eu24-team-info"> <img src="' + data.teams.away.logo + '" alt="Flag"> <span class="eu24-team-name">' + data.teams.away.name + '</span> <span class="eu24-team-score">' + (data.goals.away || 0);
                        if (data.score.penalty.away != null ) {
                            html_2 += ' (' + data.score.penalty.away + ')';
                        }
                        html_2 += '</span> </div>';
                        html_2 += '   </div>';
                        html_2 += '</div>';
                        // 將生成的 HTML 放入容器中
                        jQuery("#Final_"+i).html(html_2);
                        i++;
                    });
 

                    //////////////////////////////////////////////// 以上冠軍賽 ////////////////////////////////////////////////
				}  
            },
         error: function() {}
    });
}





function scoreboard_go(act) {
	if(act=="next") {
	jQuery(".scoreboard-container").scrollLeft(jQuery(".scoreboard-container").scrollLeft() + 200); 
	} else {
	jQuery(".scoreboard-container").scrollLeft(jQuery(".scoreboard-container").scrollLeft() - 200); 	
	}
}


jQuery(function(){ 
    if(window.location.href.indexOf("/uefa2024/") > -1) {
		scoreboard_init();

        if(window.location.href.indexOf("/team-info") > -1) {
            init_teams_player_info(); 
            init_teams_games_info();
        } else if(window.location.href.indexOf("/teams/") > -1) {
            init_teams_info();
        } else if(window.location.href.indexOf("/fixtures") > -1) {
            init_fixtures_info();
        } else if(window.location.href.indexOf("/standings") > -1) {
            init_standings_group_detail();
            init_standings_out_detail();
        } 
        //如果url有"match-score",則執行topassists();
        else if(window.location.href.indexOf("/match-score") > -1) {
             
        }
        else {
            topscorers_init();
            topassists();
            init_standings_info();
        }

	}
     
    
});

