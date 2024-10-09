var allGames = []; // 用于保存从API获取的所有游戏数据
var currentIndex = 0; // 已加载的数据的当前索引
var increment = 10; // 每次加载数据的数量
var body = jQuery('body');

jQuery(document).ready( function () {

    var adShown = getCookie("adShown");
    if (adShown != "true") {
        showPopup(); 
    }  
 
    if(jQuery('#saba_banner_1').length > 0) {
    jQuery.ajax({
        url:"/bvg/bvg.php",
        type:"POST",
        dataType:'json',
        data:{
            'act'     : "query_banner", 
            'id'      : 'saba_banner_1'
        },
        success:function(Jdata){
            if(Jdata.code==200) {
                // 判断 MatchTime 并设置相应的比赛状态
            var html = '';

            html += '<a href="' + Jdata.data.url + '" target="' + Jdata.data.target + '" rel="noopener">';  
                html +='<img src="https://img.thesabasportsindo.com/' + Jdata.data.content + '" width="' + Jdata.data.width + '" height="' + Jdata.data.height + '" />';
            html +='</a>';
             
                jQuery('#saba_banner_1').html(html);
                // 設置GA4的gtag事件追蹤
                jQuery('#saba_banner_1 a').click(function(e) {
                    console.log('saba_banner_1!');
                    // 防止連結立即導航
                        e.preventDefault();
                        // 用於 GA4 的 gtag 事件追蹤
                        gtag('event', "banner_top", {
                          'event_category': 'banner',
                          'event_label': 'banner_top',
                        });
                  
                        // 實際上導航到該網址
                        window.location.href = this.href;
                  }); 
            } else {
                 
            }
        },
        error:function(){
        }
    }); 
    }

    if (body.hasClass('home')) {
        jQuery.ajax({
            url:"/bvg/bvg.php",
            type:"POST",
            dataType:'json',
            data:{
                'act'     : "query_schedule",
            },
            success:function(Jdata){
                if (Jdata.code == 200) {
                    var html = '';
                
                    // Detect screen width for phone size
                    var isPhone = window.innerWidth <= 767;
                
                    // Set the margin-left for saba-schedule and row based on screen size
                    var sabaScheduleMargin = isPhone ? '20%' : '4px';
                    var rowMarginLeft = isPhone ? '0px' : '0px';
                
                    // saba-schedule div with dynamic margin-left
                    html += '<div class="saba-schedule" style=" margin-left: ' + sabaScheduleMargin + '; ">';
                    html += '<button style="margin-left: 2px;font-size: smaller;" type="button" class="btn btn-dark game-selected" value="Soccer" onclick="update_schedule(this.value)">Soccer</button>';
                    html += '<button style="margin-left: 2px;font-size: smaller;" type="button" class="btn btn-dark" value="Basketball" onclick="update_schedule(this.value)">Basketball</button>';
                    html += '<button style="margin-left: 2px;font-size: smaller;" type="button" class="btn btn-dark" value="Tennis" onclick="update_schedule(this.value)">Tennis</button>';
                    html += '</div>';
                
                    // row div with dynamic margin-left
                    html += '<div class="row" style=" margin-left: ' + rowMarginLeft + ';">';
                    
                    jQuery.each(Jdata.game, function(index, game) {
                        var matchStatus = ''; 
                        if (game.MatchTime === null || game.MatchTime === '') {
                            matchStatus = '<div class="saba-match-status">Coming Soon</div>';
                        } else {
                            matchStatus = '<div class="saba-match-status" style="color: red;">LIVE ' + game.MatchTime + '\'</div>';
                        }
                
                        // saba-score-card
                        html += '<a class="saba-score-card" href="/live-score/?sport=soccer&matchid=' + game.MatchId + '">';
                        html += '<div class="saba-header">';
                        html += '<span class="saba-league-name">' + game.LeagueEn + '</span>';
                        html += '</div>';
                        html += matchStatus;
                        html += '<div class="saba-teams-score">';
                        
                        // Home team details
                        var homeTeamIcon = game.HomeTeam.Icon ? game.HomeTeam.Icon : '/bvg/football2.png';
                        var homeTeamIconHeight = game.HomeTeam.Icon ? '30px' : '25px';
                        html += '<div class="saba-team">';
                        html += '<div class="saba-team-logo"><img style="height: ' + homeTeamIconHeight + ';" src="' + homeTeamIcon + '"></div>';
                        html += '<div class="saba-team-name">' + game.HomeTeam.NameEn + '</div>';
                        html += '</div>';
                
                        // Score
                        html += '<div class="saba-score">' + game.HomeTeam.FullScore + ' - ' + game.AwayTeam.FullScore + '</div>';
                
                        // Away team details
                        var awayTeamIcon = game.AwayTeam.Icon ? game.AwayTeam.Icon : '/bvg/football2.png';
                        var awayTeamIconHeight = game.AwayTeam.Icon ? '30px' : '25px';
                        html += '<div class="saba-team">';
                        html += '<div class="saba-team-logo"><img style="height: ' + awayTeamIconHeight + ';" src="' + awayTeamIcon + '"></div>';
                        html += '<div class="saba-team-name">' + game.AwayTeam.NameEn + '</div>';
                        html += '</div>';
                
                        html += '</div>'; // End of saba-teams-score
                        html += '</a>'; // End of saba-score-card
                    });
                
                    html += '</div>'; // End of row
                    
                    // Append the HTML and set up click tracking
                    jQuery('#saba_score_card_1').html(html).removeClass().addClass('live-Soccer');
                
                    jQuery('.live-Soccer .saba-score-card').click(function(e) {
                        console.log('homescore_Soccer!');
                        e.preventDefault();
                        gtag('event', "homescore_Soccer", {
                            'event_category': 'homescore',
                            'event_label': 'homescore_Soccer',
                        });
                        window.location.href = this.href;
                    });
                }
             else {
                    alert(Jdata.msg);
                }
            },
            error:function(){
            }
        }); 
    }
    else if (window.location.href.indexOf("basketball") !== -1 && window.location.href.indexOf("matchid") === -1) {
        today_items();
        update_livescore_basketball();
    }
    else if (window.location.href.indexOf("basketball") !== -1 && window.location.href.indexOf("matchid") !== -1) {
        const queryParams = new URLSearchParams(window.location.search);
     
        const matchid = queryParams.get("matchid");
        game_detail_basketball(matchid);
    }
    else if (window.location.href.indexOf("tennis") !== -1 && window.location.href.indexOf("matchid") === -1) {
        today_items();
        update_livescore_tennis();
    }
    else if (window.location.href.indexOf("tennis") !== -1 && window.location.href.indexOf("matchid") !== -1) {
        const queryParams = new URLSearchParams(window.location.search);
     
        const matchid = queryParams.get("matchid");
        game_detail_tennis(matchid);
    }
    else if (window.location.href.indexOf("live-score") !== -1 && window.location.href.indexOf("matchid") === -1) {
        let queryParams = new URLSearchParams(window.location.search);
        const url = new URL(window.location.href); 
        const sport = url.searchParams.get("sport"); // 從網址中抓取 sport 的值
        // 检查url中是否已经有soccer参数
        if (!sport) {
            // 直接构建查询字符串，避免使用queryParams.set来避免自动添加"="
            let newQueryString = window.location.search;
            if (newQueryString) {
                newQueryString += "&sport=soccer"; // 已存在其他查询参数，添加"&soccer"
            } else {
                newQueryString = "?sport=soccer"; // 不存在其他查询参数，直接添加"?soccer"
            }
            const newUrl = window.location.pathname + newQueryString;
            window.history.pushState({path:newUrl}, '', newUrl); // 通过history API更新URL而不重新加载页面
        }
     
        today_items();
        update_livescore_soccer();
         
    }
    else if (window.location.href.indexOf("live-score") !== -1 && window.location.href.indexOf("matchid") !== -1) {
        let queryParams = new URLSearchParams(window.location.search);
        const url = new URL(window.location.href); 
        const sport = url.searchParams.get("sport"); // 從網址中抓取 sport 的值
        const matchid = queryParams.get("matchid");
        // 检查url中是否已经有soccer参数
        if (!sport ) {
            // 直接构建查询字符串，避免使用queryParams.set来避免自动添加"="
            let newQueryString = window.location.search;
            if (newQueryString) {
                newQueryString += "&sport=soccer"; // 已存在其他查询参数，添加"&soccer"
            } else {
                newQueryString = "?sport=soccer"; // 不存在其他查询参数，直接添加"?soccer"
            }
            const newUrl = window.location.pathname + newQueryString;
            window.history.pushState({path:newUrl}, '', newUrl); // 通过history API更新URL而不重新加载页面
        }
    
        game_detail_soccer(matchid);
    }

} );


function update_schedule(type){
    var query ='';
    if(type == 'Soccer') {
        query = "query_live_score_soccer";
    } else if(type == 'Basketball') {
        query = "query_live_score_basketball";
    } else if(type == 'Tennis') {
        query = "query_live_score_tennis";
    }
    console.log(123);

    jQuery.ajax({
        url: "/bvg/bvg.php",
        type: "POST",
        dataType: 'json',
        data: {
            'act'       :  query,
            'type'      :  type,
            'schedule'  :  1
        },
        success: function(Jdata) {
            if (Jdata.code == 200) {
                
                var html = '';
                var isPhone = window.innerWidth <= 767;
                
                // Set the margin-left for saba-schedule and row based on screen size
                var sabaScheduleMargin = isPhone ? '20%' : '4px';
                var rowMarginLeft = isPhone ? '0px' : '0px';
                
                html += '<div class="saba-schedule" style=" margin-left: ' + sabaScheduleMargin + '; ">';
                // 为每个按钮添加判断，根据type动态添加game-selected类
                    html += '<button style="margin-left: 2px;font-size: smaller;" type="button" class="btn btn-dark ' + (type == 'Soccer' ? 'game-selected' : '') + '" value="Soccer" onclick="update_schedule(this.value)">Soccer</button>';
                    html += '<button style="margin-left: 2px;font-size: smaller;" type="button" class="btn btn-dark ' + (type == 'Basketball' ? 'game-selected' : '') + '" value="Basketball" onclick="update_schedule(this.value)">Basketball</button>';
                    html += '<button style="margin-left: 2px;font-size: smaller;" type="button" class="btn btn-dark ' + (type == 'Tennis' ? 'game-selected' : '') + '" value="Tennis" onclick="update_schedule(this.value)">Tennis</button>';
                html += '</div>';
                html += '<div class="row" style=" margin-left: ' + rowMarginLeft + ';">';
                if(type == 'Soccer' || type == 'Basketball') {
                    jQuery.each(Jdata.game, function(index, game) {

                        var matchStatus = ''; 
                        if (game.MatchTime === null || game.MatchTime === '') {
                            matchStatus = '<div class="saba-match-status">Coming Soon</div>';
                        }    
                        else {
                            matchStatus = '<div class="saba-match-status" style="color: red;">LIVE ' + game.MatchTime + '\'</div>';
                        }
                            if(type == 'Soccer') {
                                html += '<a class="saba-score-card"  href="/live-score/?sport=soccer&matchid=' + game.MatchId +'">';
                            } else {                                 
                                html += '<a class="saba-score-card"  href="/live-score/?sport=basketball&matchid=' + game.MatchId +'">';
                            }
                            html += '<div class="saba-header" >';
                                html += '<span class="saba-league-name">' + game.LeagueEn + '</span>';
                            html += '</div>';
                            html += '' + matchStatus + '';
                            html += '<div class="saba-teams-score">';
                                html += '<div class="saba-team">';
                                    if(type == 'Soccer') {
                                        var homeTeamIcon = game.HomeTeam.Icon ? game.HomeTeam.Icon : '/bvg/football2.png';
                                    } else {
                                        var homeTeamIcon = game.HomeTeam.Icon ? game.HomeTeam.Icon : '/bvg/basketball.jpg';
                                    }
                                    var homeTeamIconHeight = game.HomeTeam.Icon ? '30px' : '25px'; // 设置图标高度
                                    html += '<div class="saba-team-logo"><img style="height: ' + homeTeamIconHeight + ';" src="' + homeTeamIcon + '"></div>';
                                    html += '<div class="saba-team-name">' + game.HomeTeam.NameEn + '</div>';
                                html += '</div>';
                                html += '<div class="saba-score">' + game.HomeTeam.FullScore + ' - ' + game.AwayTeam.FullScore + '</div>';
                                html += '<div class="saba-team">';
                                    if(type == 'Soccer') {
                                        var awayTeamIcon = game.AwayTeam.Icon ? game.AwayTeam.Icon : '/bvg/football2.png';
                                    } else {
                                        var awayTeamIcon = game.AwayTeam.Icon ? game.AwayTeam.Icon : '/bvg/basketball.jpg';
                                    }
                                    var awayTeamIconHeight = game.AwayTeam.Icon ? '30px' : '25px'; // 设置图标高度
                                    html += '<div class="saba-team-logo"><img style="height: ' + awayTeamIconHeight + ';" src="' + awayTeamIcon + '"></div>';
                                    html += '<div class="saba-team-name">' + game.AwayTeam.NameEn + '</div>';
                                html += '</div>';
                            html += '</div>';
                        html += '</a>'; 
                    });
                } else if(type == 'Tennis') {
                    var homeTeamIcon =  '/bvg/tennis.png';
                    var awayTeamIcon =  '/bvg/tennis.png';
                    jQuery.each(Jdata.game, function(index, game) {

                        var matchStatus = ''; 
                        if (game.MatchTime === null || game.MatchTime === '') {
                            matchStatus = '<div class="saba-match-status">Coming Soon</div>';
                        }    
                        else {
                            matchStatus = '<div class="saba-match-status" style="color: red;">LIVE</div>';
                        }

                            html += '<a class="saba-score-card"  href="/live-score/?sport=tennis&matchid=' + game.MatchId +'">';
                        
                            html += '<div class="saba-header" >';
                                html += '<span class="saba-league-name">' + game.LeagueEn + '</span>';
                            html += '</div>';
                            html += '' + matchStatus + '';
                            html += '<div class="saba-teams-score">';
                                html += '<div class="saba-team">';
                                    if(game.Type == 1) {
                                        HomeName = game.HomePlayers.PlayerNameEn1;
                                        AwayName = game.AwayPlayers.PlayerNameEn1;
                                    } else {
                                        HomeName = game.HomePlayers.PlayerNameEn1 + ' / ' + game.HomePlayers.PlayerNameEn2;
                                        AwayName = game.AwayPlayers.PlayerNameEn1 + ' / ' + game.AwayPlayers.PlayerNameEn2;
                                    }
                                    html += '<div class="saba-team-logo"><img style="height:25px;" src="' + homeTeamIcon + '"></div>';
                                    html += '<div class="saba-team-name">' + HomeName + '</div>';
                                html += '</div>';
                                html += '<div class="saba-score">' + game.HomePlayers.TotalScore + ' - ' + game.AwayPlayers.TotalScore + '</div>';
                                html += '<div class="saba-team">';
                                      
                                    html += '<div class="saba-team-logo"><img style="height:25px;" src="' + awayTeamIcon + '"></div>';
                                    html += '<div class="saba-team-name">' + AwayName + '</div>';
                                html += '</div>';
                            html += '</div>';
                        html += '</a>';
                    });
                }
                html += '</div>';
                    jQuery('#saba_score_card_1').html(html).removeClass().addClass('live-'+type);

                    jQuery('.live-' + type + ' .saba-score-card').click(function(e) {
                        console.log('homescore_' + type + '!');
                        // 防止連結立即導航
                            e.preventDefault();
                            // 用於 GA4 的 gtag 事件追蹤
                            gtag('event', "homescore_" + type, {
                              'event_category': 'homescore',
                              'event_label': 'homescore_' + type,
                            });
                      
                            // 實際上導航到該網址
                            window.location.href = this.href;
                      });
                      
            } else {
                alert(Jdata.msg);
            }
        },
        error: function() {
            // 错误处理
        }
    });

}


function today_items(){
    // 检测URL参数 
    var hasBasketball =  false;
    var hasTennis = false;

    const url = new URL(window.location.href);  
    const sport = url.searchParams.get("sport"); // 從網址中抓取 sport 的值
    if(sport == 'basketball') {
        hasBasketball = true;
    }
    if(sport == 'tennis') {
        hasTennis = true;
    }

    jQuery.ajax({
        url:"/bvg/bvg.php",
        type:"POST",
        dataType:'json',
        data:{
            'act'     : "query_dates", 
        },
        success:function(Jdata){
            if(Jdata.code==200) {
                
                var html ='';
                html += '<div class="nav-row lg ">';
                    html += '<a class="nav-link ' + (!hasBasketball && !hasTennis ? 'active' : '') + '" aria-current="page" href="/live-score/" data-value="soccer">SOCCER</a>';
                    html += '<a class="nav-link ' + (hasBasketball ? 'active' : '') + '" href="?sport=basketball" data-value="basketball">BASKETBALL</a>'; 
                    html += '<a class="nav-link ' + (hasTennis ? 'active' : '') + '" href="?sport=tennis" data-value="tennis">TENNIS</a>';
                html += '</div>';
                    
                html += '<div class="my-3">';
                if(!hasBasketball && !hasTennis) {
                    html += '<div class="games">';
                        html += '<button type="button" class="btn btn-dark game-selected" value="all" onclick="update_game(this.value)">ALL</button>';
                        html += '<button type="button" class="btn btn-dark" value="ENG" onclick="update_game(this.value)">Premier League</button>';
                        html += '<button type="button" class="btn btn-dark" value="FRA" onclick="update_game(this.value)">Liga Prancis</button>';
                        html += '<button type="button" class="btn btn-dark" value="GER  onclick="update_game(this.value)"">Liga Jerman</button>';
                        html += '<button type="button" class="btn btn-dark" value="ITA" onclick="update_game(this.value)">Liga Italia</button>';
                        html += '<button type="button" class="btn btn-dark" value="SPA" onclick="update_game(this.value)">Liga Spanyol</button>';
                        html += '<button type="button" class="btn btn-dark" value="IND" onclick="update_game(this.value)">Liga Indonesia</button>';
                    html += '</div>';
                }
                html += '<div class="date">'; 
                jQuery.each(Jdata.dates, function(index, date) {
                    // 根据是否为今天以及是否被选中，动态设置标签类
                    var badgeClass = date.isToday && date.sel_date ? 'badge-danger' : 'badge-secondary';
                    var displayText = date.isToday ? '<span class="badge ' + badgeClass + '">TODAY</span>' : date.week;
                    var btnClass = date.sel_date ? "btn-selected btn-light" : "btn-light";
                    html += '<button type="button" class="btn ' + btnClass + '" value="' + date.real_date + '" onclick="change_date(this.value)">' + date.day + '<span style="display: block;">' + displayText + '</span></button>'; 
                });
                html += '</div>';

                    jQuery('#livescore_items').html(html);
            } else {
                alert(Jdata.msg);
            }
        },
        error:function(){
        }
    }); 
}

// 在全局作用域中定义变量来存储上一次查询时的参数
var lastGame = null;
var lastDate = null;

function update_livescore_soccer(game, date) {
    // 默认参数处理
    game = game || '0'; // 如果game未定义，则默认为'0'
    date = date || '0'; // 如果date未定义，则默认为'0'

    // 检查参数是否有变化或allGames是否为空
    if (allGames.length === 0 || game !== lastGame || date !== lastDate) {
        jQuery.ajax({
            url: "/bvg/bvg.php",
            type: "POST",
            dataType: 'json',
            data: {
                'act': "query_live_score_soccer",
                'date': date,
                'game': game,
                'type': 'Soccer'
            },
            success: function(Jdata) {
                if (Jdata.code == 200) {
                    allGames = Jdata.games; // 更新游戏数据
                    currentIndex = 0; // 重置当前索引
                    jQuery('#livescore').empty(); // 清空现有的游戏数据显示
                    loadMoreGames_soccer(); // 加载首批游戏数据
                    hideModal();
                } else {
                    alert(Jdata.msg);
                }
            },
            error: function() {
                // 错误处理
            }
        });

        // 更新上一次查询时使用的参数
        lastGame = game;
        lastDate = date;
    } else {
        // 如果参数没有变化，并且已有数据，直接加载更多数据
        loadMoreGames_soccer();
    }
}


function change_date(date) {
    showModal();
    var game_selected = jQuery('.games .btn.game-selected'); // 抓取現在選擇的比賽種類
    var game_value = game_selected.val(); // 抓取現在選擇的比賽種類的值
    const urlParams = new URLSearchParams(window.location.search);
    const hasBasketball = urlParams.has('basketball');
    const hasTennis = urlParams.has('tennis');

    var activeLink = document.querySelector('.nav-row.lg .nav-link.active'); 
    var activeValue = activeLink.getAttribute('data-value'); 
    console.log('被選取的連結值是：', activeValue);
    jQuery.ajax({
        url:"/bvg/bvg.php",
        type:"POST",
        dataType:'json',
        data:{
            'act'       : "query_dates",
            'date'      : date  
        },
        success:function(Jdata){
            if(Jdata.code==200) {
                
                var html ='';
                html += '<div class="nav-row lg ">';
                    html += '<a class="nav-link ' + (!hasBasketball && !hasTennis ? 'active' : '') + '" aria-current="page" href="/live-score/" data-value="soccer">SOCCER</a>';
                    html += '<a class="nav-link ' + (hasBasketball ? 'active' : '') + '" href="?sport=basketball" data-value="basketball">BASKETBALL</a>'; 
                    html += '<a class="nav-link ' + (hasTennis ? 'active' : '') + '" href="?sport=tennis" data-value="tennis">TENNIS</a>';
                html += '</div>';
                    
                html += '<div class="my-3">';
                if(!hasBasketball && !hasTennis) {
                    html += '<div class="games">';
                    // 定义一个数组包含所有的按钮值
                    var buttons = [
                        {value: "all", text: "ALL"},
                        {value: "ENG", text: "Premier League"},
                        {value: "FRA", text: "Liga Prancis"},
                        {value: "GER", text: "Liga Jerman"},
                        {value: "ITA", text: "Liga Italia"},
                        {value: "SPA", text: "Liga Spanyol"},
                        {value: "IND", text: "Liga Indonesia"}
                    ];
            
                    // 遍历按钮数组生成按钮HTML
                    buttons.forEach(function(button) {
                        var isSelected = game_value === button.value ? " game-selected" : ""; // 检查是否选中
                        html += '<button type="button" class="btn btn-dark' + isSelected + '" value="' + button.value + '" onclick="update_game(this.value)">' + button.text + '</button>';
                    });
                }
                html += '</div>';

                html += '<div class="date">'; 
                jQuery.each(Jdata.dates, function(index, date) {
                    // 根据是否为今天以及是否被选中，动态设置标签类
                    var badgeClass = date.isToday && date.sel_date ? 'badge-danger' : 'badge-secondary';
                    var displayText = date.isToday ? '<span class="badge ' + badgeClass + '">TODAY</span>' : date.week;
                    var btnClass = date.sel_date ? "btn-selected btn-light" : "btn-light";
                    html += '<button type="button" class="btn ' + btnClass + '" value="' + date.real_date + '" onclick="change_date(this.value)">' + date.day + '<span style="display: block;">' + displayText + '</span></button>'; 
                });
                html += '</div>';

                    jQuery('#livescore_items').html(html);

                    var date_selected = jQuery('.btn-selected'); // 抓取現在選擇的日期
                    var date_value = date_selected.val(); // 抓取現在選擇的日期值
                    
                    if(activeValue == 'soccer') {
                        update_livescore_soccer(game_value, date_value);
                    } else if(activeValue == 'basketball') {
                        update_livescore_basketball(date_value);
                    } else if(activeValue == 'tennis') {
                        update_livescore_tennis(date_value);
                    }

                   
            } else {
                alert(Jdata.msg);
            }
        },
        error:function(){
        }
    }); 
     
}

function update_game(LeagueEn){

    var date_selected = jQuery('.btn-selected'); // 抓取現在選擇的日期
    var date_value = date_selected.val(); // 抓取現在選擇的日期值

    jQuery('.games .btn').removeClass('game-selected');
    jQuery('.games .btn[value="' + LeagueEn + '"]').addClass('game-selected');

    update_livescore_soccer(LeagueEn, date_value);

}
 
function loadMoreGames_soccer() {
    // 从已有的游戏数据中截取一部分来显示
    var gamesToShow = allGames.slice(currentIndex, currentIndex + increment);
    
    var html ='';

    html += '<div class="score">';
        //card Start
        var status_style = '';
        jQuery.each(gamesToShow, function(index, game) {
             
            var matchStatus = ''; 
            if (game.Status === 60 ) {
                matchStatus = 'Extension';
                status_style = 'color: orange;';
            } 
            else if(game.Status ===50) {  
                matchStatus = 'Finish ' + game.Schedule + '';
                status_style = 'color: blue;';
            }
            else if(game.Status === 30) {
                matchStatus = 'LIVE 2H ' + game.MatchTime + '\'';
            }
            else if(game.Status === 20) {
                matchStatus = 'LIVE HT';
            }
            else if(game.Status === 10) {
                matchStatus = 'LIVE 1H ' + game.MatchTime + '\'';
            }
            else {
                matchStatus = '' + game.Schedule + '';
            }
            
            var homeTeamIcon = game.HomeTeam.Icon ? game.HomeTeam.Icon : '/bvg/football2.png';
            var awayTeamIcon = game.AwayTeam.Icon ? game.AwayTeam.Icon : '/bvg/football2.png';

            //html += '<div class="saba-score-card-2" id="' + game.MatchId + '">';
            html += '<div class="saba-score-card-2 saba-score-card-hover" onclick="location.href=\'/live-score/?sport=soccer&matchid=' + game.MatchId + '\';" style="cursor: pointer;" id="' + game.MatchId + '">';
            html += '<div class="flex-only flex-col justify-center px-2.5">';
                html += '    <div class="mb-1 flex-only gap-1 flex-row" style="border-bottom: inset; margin-bottom: 12px;">';
                html += '        <div class="flex-only min-w-0 grow flex-col gap-x-1 gap-y-[1px] lg:flex-row">';
                html += '            <div class="flex shrink-0 gap-x-1 saba-match-status-2"><span class="text-sm" style="' + status_style + '">' + matchStatus + '</span></div>';
                html += '            <p class="h-4 overflow-hidden text-sm" style="margin-bottom: 10px;font-weight:bold;">' + game.LeagueEn + '</p>';
                html += '        </div>';
                html += '        <div class="flex flex-row items-center">';
                html += '            <div class="w-[20px] text-center text-sm"><img src="/bvg/images/yellow-card.svg"></div>';
                html += '            <div class="w-[20px] text-center text-sm"><img src="/bvg/images/red-card.svg"></div>';
                html += '            <div class="w-[20px] text-center text-sm"><img src="/bvg/images/corner.svg"></img></div>';
                html += '            <div class="w-[18px] text-center text-sm">1H</div>';
                html += '            <div class="w-[18px] text-center text-sm">FT</div>';
                html += '        </div>';
                html += '    </div>';
                html += '    <div class="grid gap-y-1.5">';
                html += '        <div class="flex-only flex-row justify-between">';
                html += '            <div class="flex-only grow min-w-0 items-center gap-x-2"><img decoding="async" class="h-5 w-5 object-fill" src="' + homeTeamIcon + '" alt="flag 1">';
                html += '                <span class="h-4 overflow-hidden text-ellipsis text-sm text-gray-800" style="-webkit-line-clamp: 1; display: -webkit-box; -webkit-box-orient: vertical;">' + game.HomeTeam.NameEn + '</span>';
                html += '            </div>';
                html += '            <div class="relative flex items-center space-x-2.5 shrink-0">';
                html += '                <div class="flex shrink-0 flex-row space-x-2.5 items-center">';
                html += '                    <div class="w-[20px] text-center text-sm">' + (game.AwayTeam.YellowCard != 0 ? game.AwayTeam.YellowCard : '') + '</div>';
                html += '                    <div class="w-[20px] text-center text-sm">' + (game.AwayTeam.RedCard != 0 ? game.AwayTeam.RedCard : '') + '</div>';
                html += '                    <div class="w-[20px] text-center text-sm">' + (game.AwayTeam.CornerKick != 0 ? game.AwayTeam.CornerKick : '') + '</div>';
                
                html += '                    <div class="w-[18px] text-center text-sm">' + game.HomeTeam.FullScore + '</div>';
                html += '                    <div class="w-[18px] text-center text-sm">' + game.HomeTeam.FullScore + '</div>';
                html += '                </div>';
                html += '            </div>';
                html += '        </div>';
                html += '        <div class="flex-only flex-row justify-between">';
                html += '            <div class="flex-only grow min-w-0 items-center gap-x-2"><img decoding="async" class="h-5 w-5 object-fill" src="' + awayTeamIcon + '" alt="flag 2">';
                html += '                <span class="h-4 overflow-hidden text-sm" style="-webkit-line-clamp: 1; display: -webkit-box; -webkit-box-orient: vertical;">' + game.AwayTeam.NameEn + '</span>';
                html += '            </div>';
                html += '            <div class="relative flex items-center space-x-2.5 shrink-0">';
 
                html += '                <div class="flex shrink-0 flex-row space-x-2.5 items-center">';
                html += '                    <div class="w-[20px] text-center text-sm">' + (game.AwayTeam.YellowCard != 0 ? game.AwayTeam.YellowCard : '') + '</div>';
                html += '                    <div class="w-[20px] text-center text-sm">' + (game.AwayTeam.RedCard != 0 ? game.AwayTeam.RedCard : '') + '</div>';
                html += '                    <div class="w-[20px] text-center text-sm">' + (game.AwayTeam.CornerKick != 0 ? game.AwayTeam.CornerKick : '') + '</div>';
                
                html += '                    <div class="w-[18px] text-center text-sm">' + game.AwayTeam.HalfScore + '</div>';
                html += '                    <div class="w-[18px] text-center text-sm">' + game.AwayTeam.FullScore + '</div>';
                html += '                </div>';
                html += '            </div>';
                html += '        </div>';
                html += '    </div>';
                html += '</div>';
            html += '</div>';

        });
            
        //card end 
    html += '</div>'; 

    jQuery('#livescore').append(html);

    // 更新当前索引，为下次加载准备
    currentIndex += gamesToShow.length;
}



// 检查当前页面的URL路径是否包含 'live-score'
if (window.location.pathname.includes('live-score')) {
    jQuery(window).scroll(function() {
        // 判断用户是否滚动到页面底部
        if (jQuery(window).scrollTop() + jQuery(window).height() >= jQuery(document).height() - 100) {
            // 判断是否还有更多数据待加载
            if (currentIndex < allGames.length) {
                if (window.location.href.indexOf("basketball") !== -1) {
                    loadMoreGames_basketball(); // 加载更多游戏数据
                } 
                else if (window.location.href.indexOf("tennis") !== -1) {
                    loadMoreGames_tennis(); // 加载更多游戏数据
                }
                else {
                    loadMoreGames_soccer(); // 加载更多游戏数据
                }
            }
        }
    });
}


/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ 足球比賽詳細 ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
function game_detail_soccer(id){
    jQuery.ajax({
        url:"/bvg/bvg.php",
        type:"POST",
        dataType:'json',
        data:{
            'act'   : "query_game_detail_soccer",
            'id'    : id 
        },
        success:function(Jdata){
            if(Jdata.code==200) {

                var homeTeamIcon = Jdata.game.HomeTeam.Icon ? Jdata.game.HomeTeam.Icon : '/bvg/football2.png';
                var awayTeamIcon = Jdata.game.AwayTeam.Icon ? Jdata.game.AwayTeam.Icon : '/bvg/football2.png';

                // 计算HomeTeam的射门占比
                var totalShots = Jdata.game.HomeTeam.OnGoal + Jdata.game.AwayTeam.OnGoal;
                var homeTeamShotPercentage = totalShots > 0 ? Math.round((Jdata.game.HomeTeam.OnGoal / totalShots) * 100) : 0; // 避免除以零

                var game_status = '';
                if (Jdata.game.Status === 50) {
                    game_status = 'Full Time';
                } else if (Jdata.game.Status === 30) {
                    game_status = '2nd Half ' + Jdata.game.MatchTime + '\'';
                }
                else if (Jdata.game.Status === 20) {
                    game_status = 'Half Time';
                }
                else if (Jdata.game.Status === 10) {
                    game_status = '1st Half ' + Jdata.game.MatchTime + '\'';
                }
                 
                var html ='';

                html += '<div class="score">';

                    html += '<div class="saba-score-card-2" >';
                        html +='<div class="mh">';
                            html +='<div data-testid="match-detail_team-name_home" class="nh">';
                                html +='<img alt="Mallorca" loading="lazy" width="50" height="50" decoding="async" data-nimg="1" class="badgeContainer" src="' + homeTeamIcon + '"  >';
                                html +='<span class="oh">' + Jdata.game.HomeTeam.NameEn + '</span>';
                            html +='</div>';
                            
                            html +='<div class="th">'; 
                            html +='<span id="SEV__status" class="xh">' + Jdata.game.DateShareZh + '</span>';
                            html +='<span style="font-size:13px;color:red;font-weight:bold">' + Jdata.game.LeagueEn + '</span>';
                                html +='<span id="score-or-time" class="uh vh">' + Jdata.game.HomeTeam.FullScore + ' - ' + Jdata.game.AwayTeam.FullScore + '</span>';
                                html +='<span id="SEV__status" class="xh">' + game_status + '</span>';
                            html +='</div>';
                            
                            html +='<div data-testid="match-detail_team-name_away" class="nh">';
                                html +='<img alt="Real Sociedad" loading="lazy" width="50" height="50" decoding="async" data-nimg="1" class="badgeContainer" src="' + awayTeamIcon + '" >';
                                html +='<span class="oh">' + Jdata.game.AwayTeam.NameEn + '</span>';
                            html +='</div>';
                        html +='</div>';
                    

                        html += '<div class="statistics-row">';                       
                        html += '    <div class="statistics">';
                        html += '        <div class="statistics-item">';
                        html += '            <div>';
                        html += '                <img src="/bvg/images/corner.svg">';
                        html += '            </div>';
                        html += '            <div class="">' + Jdata.game.HomeTeam.CornerKick + '</div>';
                        html += '        </div>';
                        html += '        <div class="statistics-item">';
                        html += '            <div>';
                        html += '                <img src="/bvg/images/red-card.svg">';
                        html += '            </div>';
                        html += '            <div class="">' + Jdata.game.HomeTeam.RedCard + '</div>';
                        html += '        </div>';
                        html += '        <div class="statistics-item">';
                        html += '            <div>';
                        html += '                <img src="/bvg/images/yellow-card.svg">';
                        html += '            </div>';
                        html += '            <div class="">' + Jdata.game.HomeTeam.YellowCard + '</div>';
                        html += '        </div>';
                        html += '    </div>';
                        html += '    <div class="statistics-content">';
                        html += '        <div class="statistics-list">';
                        html += '            <div>Shots On Goal</div>';
                        html += '            <div class="statistics-list-content">';
                        html += '                <div class="">' + Jdata.game.HomeTeam.OnGoal + '</div>';
                        html += '                <div class="statistics-bar">';
                        html += '                    <div class="statistics-bar-foreground" style="width:' + homeTeamShotPercentage + '%;"></div>';
                        html += '                </div>';
                        html += '                <div class="">' + Jdata.game.AwayTeam.OnGoal + '</div>';
                        html += '            </div>';
                        html += '        </div>';
                        html += '        <div class="statistics-list">';
                        html += '            <div>Possession (%)</div>';
                        html += '            <div class="statistics-list-content">';
                        html += '                <div class="">' + Jdata.game.HomeTeam.ControlRate + '</div>';
                        html += '                <div class="statistics-bar">';
                        html += '                    <div class="statistics-bar-foreground" style="width: ' + Jdata.game.HomeTeam.ControlRate + '%;"></div>';
                        html += '                </div>';
                        html += '                <div class="">' + Jdata.game.AwayTeam.ControlRate + '</div>';
                        html += '            </div>';
                        html += '        </div>';
                        html += '    </div>';
                        html += '    <div class="statistics">';
                        html += '        <div class="statistics-item">';
                        html += '            <div>';
                        html += '                <img src="/bvg/images/yellow-card.svg">';
                        html += '            </div>';
                        html += '            <div class="">' + Jdata.game.AwayTeam.YellowCard + '</div>';
                        html += '        </div>';
                        html += '        <div class="statistics-item">';
                        html += '            <div>';
                        html += '                <img src="/bvg/images/red-card.svg">';
                        html += '            </div>';
                        html += '            <div class="">' + Jdata.game.AwayTeam.RedCard + '</div>';
                        html += '        </div>';
                        html += '        <div class="statistics-item">';
                        html += '            <div>';
                        html += '                <img src="/bvg/images/corner.svg">';
                        html += '            </div>';
                        html += '            <div class="">' + Jdata.game.AwayTeam.CornerKick + '</div>';
                        html += '        </div>';
                        html += '    </div>';
                        html += '</div>';
                        jQuery.each(Jdata.events, function(index, event) {
                        
                            var homecontent = '';
                            var awaycontent = '';
                            var homeicon = '';
                            var awayicon = '';
                            var goal ='<svg xmlns="http://www.w3.org/2000/svg" width="15" height="14" name="FootballGoal"><g transform="translate(.63)" fill="none" fill-rule="evenodd"><circle cx="7" cy="7" r="6" fill="#FDFDFD"></circle><path fill="#222" d="M7 0h.28H7a6.8 6.8 0 01.56.02l.14.01.25.03h.02l.04.01.22.04.13.02.14.03.13.03.24.06.08.03.23.07.04.01.53.2.03.01c.95.42 1.8 1.03 2.47 1.8a5.96 5.96 0 01.62.82l.08.13.06.1c.42.7.72 1.49.87 2.32l.02.07a7.47 7.47 0 01.07 1.85 6.97 6.97 0 01-.94 2.9l-.03.05-.08.13.1-.18-.2.34-.03.04-.37.5-.04.03c-.12.16-.26.3-.4.45l-.05.05-.12.12-.4.35-.07.05-.19.15-.04.02v.01l-.25.17.25-.18a7.3 7.3 0 01-1.67.9l-.05.02-.22.08-.06.02a5.9 5.9 0 01-.37.1l-.16.04a6.95 6.95 0 01-3.11.01l-.06-.01-.15-.04-.09-.02-.03-.01a6.16 6.16 0 01-.24-.07l-.09-.03-.2-.07-.06-.02a7.96 7.96 0 01-.24-.1h-.03c-.5-.22-.96-.48-1.38-.79h-.01l-.04-.03-.2-.16.24.18a6.66 6.66 0 01-.82-.7l-.05-.04a6.47 6.47 0 01-.4-.45l-.04-.04A6.97 6.97 0 01.03 7.66a7.5 7.5 0 010-1.34l.02-.13.01-.11.04-.27.02-.11c.16-.82.45-1.59.87-2.28l.06-.1.08-.13A6.94 6.94 0 014.22.58l.04-.02.51-.2.06-.01.23-.08.06-.01.25-.07c.05 0 .09-.02.13-.03l.14-.03.13-.02L6 .07h.06L6.3.03h.14A3.85 3.85 0 017 0zm1.88 1.3L7.44 2.45a.7.7 0 01-.8.05l-.08-.05L5.12 1.3a6 6 0 00-2.96 2.16l.65 1.72a.7.7 0 01-.2.78L2.54 6 1 7.02v.2a5.96 5.96 0 001.14 3.29l1.83-.09a.7.7 0 01.68.43l.03.09.49 1.77a5.94 5.94 0 003.66 0l.49-1.77a.7.7 0 01.62-.51h.09l1.84.08A5.96 5.96 0 0013 7.02l-1.54-1.01a.7.7 0 01-.3-.75l.03-.08.65-1.72A6.01 6.01 0 008.88 1.3zM7.4 4.5l1.84 1.33c.24.18.35.5.25.79l-.7 2.16a.7.7 0 01-.66.48H5.86a.7.7 0 01-.66-.48l-.7-2.16a.7.7 0 01.25-.78L6.59 4.5a.7.7 0 01.82 0z"></path></g></svg>';
                            var yellowcard ='<svg xmlns="http://www.w3.org/2000/svg" width="11" height="14" name="FootballYellowCard"><rect width="10" height="14" x="2" rx="2" transform="translate(-1.68)" fill="#FFCE00" fill-rule="evenodd"></rect></svg>';
                            var redcard ='<svg xmlns="http://www.w3.org/2000/svg" width="11" height="14" name="FootballRedCard"><rect width="10" height="14" x="2" rx="2" transform="translate(-1.66)" fill="#DF2357" fill-rule="evenodd"></rect></svg>';

                            if (event.Type == 1 && event.TeamType == 1) {
                                homecontent = 'GOAL';
                                homeicon = goal;
                            } else if (event.Type == 1 && event.TeamType == 2) {
                                awaycontent = 'GOAL';
                                awayicon = goal;
                            } else if (event.Type == 3 && event.TeamType == 1) {
                                homecontent = 'Yellow Card';
                                homeicon = yellowcard;
                            } else if (event.Type == 3 && event.TeamType == 2) {
                                awaycontent = 'Yellow Card';
                                awayicon = yellowcard;
                            } else if (event.Type == 4 && event.TeamType == 1) {
                                homecontent = 'Red Card';
                                homeicon = redcard;
                            } else if (event.Type == 4 && event.TeamType == 2) {
                                awaycontent = 'Red Card';
                                awayicon = redcard;
                            }

                            var displayTimePoint;

                            // 判断event.TimePoint的值
                            if (event.TimePoint == 45 || event.TimePoint.toString().startsWith("45+")) {
                                displayTimePoint = "45+";
                            } else if (event.TimePoint == 90 || event.TimePoint.toString().startsWith("90+")) {
                                displayTimePoint = "90+";
                            } else {
                                displayTimePoint = event.TimePoint + "'";
                            }

                            html += '<div class="summary">';
                            html += '    <div class="event">';

                            html += '        <div class="ht homeContent">'; 
                            html += '            <div class="ot homePlayer">';
                            html += '                <span class="it" id="home-player__FootballGoal">' + homecontent + '</span>';
                            html += '           </div>';
                            html += '           <div class="mt homeIcon">';
                            html += '                <span class="Xq">';
                            html += '' + homeicon + '';
                            html += '                </span>';
                            html += '            </div>';
                            html += '        </div>';

                            html += '        <div class="kt">';
                            html += '            <span style="font-size: 15px;font-weight: bold;">' + displayTimePoint + '</span>'; 
                            html += '        </div>';

                            html += '        <div class="ht awayContent">'; 
                            html += '            <div class="mt awayIcon">';
                            html += '                <span class="Xq">';
                            html += '' + awayicon + '';
                            html += '                </span>';
                            html += '            </div>';
                            html += '            <div class="ot awayPlayer">';
                            html += '                <span class="it" id="away-player__FootballYellowCard">' + awaycontent + '</span>';
                            html += '            </div>';
                            html += '        </div>';

                            html += '    </div>';
                            html += '</div>';
                    });

                        
                    html += '</div>';
                html += '</div>';



                jQuery('#livescore').html(html);
            } else {
                alert(Jdata.msg);
            }
        },
        error:function(){
        }
    }); 

}
  

/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ 以下籃球 ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */

function update_livescore_basketball(date) {
    // 默认参数处理
    date = date || '0'; // 如果date未定义，则默认为'0'

    // 检查参数是否有变化或allGames是否为空
    if (allGames.length === 0  || date !== lastDate) {
        jQuery.ajax({
            url: "/bvg/bvg.php",
            type: "POST",
            dataType: 'json',
            data: {
                'act': "query_live_score_basketball",
                'date': date,
                'type': 'Basketball'
            },
            success: function(Jdata) {
                if (Jdata.code == 200) {
                    allGames = Jdata.games; // 更新游戏数据
                    currentIndex = 0; // 重置当前索引
                    jQuery('#livescore').empty(); // 清空现有的游戏数据显示
                    loadMoreGames_basketball(); // 加载首批游戏数据
                    hideModal();
                } else {
                    alert(Jdata.msg);
                }
            },
            error: function() {
                // 错误处理
            }
        });

        // 更新上一次查询时使用的参数 
        lastDate = date;
    } else {
        // 如果参数没有变化，并且已有数据，直接加载更多数据
        loadMoreGames_basketball();
    }
}



function loadMoreGames_basketball() {
    // 从已有的游戏数据中截取一部分来显示
    var gamesToShow = allGames.slice(currentIndex, currentIndex + increment);
    
    var html ='';

    html += '<div class="score">';
        //card Start
        
        jQuery.each(gamesToShow, function(index, game) {
             
            var matchStatus = ''; 
            var animation = '';
            if (game.MatchStatus === 63) {
                matchStatus = '<div class="saba-match-status-2 col-2"  style="color: orange;">Extension</div>';
            } 
            else if(game.MatchStatus ===50) {  
                matchStatus = '<div class="saba-match-status-2 col-2" style="color: blue;">Finish ' + game.Schedule + '</div>';
            }
            else if(game.MatchStatus ===40) {  
                matchStatus = '<div class="saba-match-status-2 col-2" style="color: red;">OT</div>';
            }
            else if(game.MatchStatus === 11) {
                matchStatus = '<div class="saba-match-status-2 col-2" style="color: red;">Q1 ' + game.MatchTime + '</div>';
                animation = '';
            }
            else if(game.MatchStatus === 12) {
                matchStatus = '<div class="saba-match-status-2 col-2" style="color: red;">END Q1</div>';
            }
            else if(game.MatchStatus === 13) {
                matchStatus = '<div class="saba-match-status-2 col-2" style="color: red;">Q2 ' + game.MatchTime + '</div>';
                animation = '';
            }
            else if(game.MatchStatus === 14) {
                matchStatus = '<div class="saba-match-status-2 col-2" style="color: red;">END Q2</div>';
            }
            else if(game.MatchStatus === 31) {
                matchStatus = '<div class="saba-match-status-2 col-2" style="color: red;">Q3 ' + game.MatchTime + '</div>';
                animation = '';
            }
            else if(game.MatchStatus === 32) {
                matchStatus = '<div class="saba-match-status-2 col-2" style="color: red;">END Q3</div>';
            }
            else if(game.MatchStatus === 33) {
                matchStatus = '<div class="saba-match-status-2 col-2" style="color: red;">Q4 ' + game.MatchTime + '</div>';
                animation = '';
            }
            else {
                matchStatus = '<div class="saba-match-status-2 col-2"  >' + game.Schedule + '</div>';
            }
            
            var homeTeamIcon = game.HomeTeam.Icon ? game.HomeTeam.Icon : '/bvg/basketball.jpg';
            var awayTeamIcon = game.AwayTeam.Icon ? game.AwayTeam.Icon : '/bvg/basketball.jpg';
            
            html += '<div class="saba-score-card-2 saba-score-card-hover" onclick="location.href=\'/live-score/?sport=basketball&matchid=' + game.MatchId + '\';" style="cursor: pointer;" id="' + game.MatchId + '">';            
                html += '<div class="flex-only min-h-[120px] flex-col justify-center  px-2.5 lg:min-h-[112px]">';
                html += '    <div class="flex-only mb-1 flex-row justify-between gap-x-2.5 border-b border-solid border-gray-200 pb-2" style="border-bottom: inset;margin-bottom: 12px;">';
                html += '        <div class="flex-only min-w-0 grow flex-col gap-x-1 gap-y-[1px] lg:flex-row" >';
                html += '            <div class="flex-only shrink-0 gap-x-1"><span class=" font-bold text-[#E20A0A]">' + matchStatus + '</span>';
                html += '            </div>'; 
                html += '               <p style="font-size: 14px;font-weight: bold;">' + game.LeagueEn + '</p>'; 
                html += '        </div>';
                html += '        <div class="flex shrink-0 items-center space-x-3">';
                if(game.HomeTeam.ScoreOne !=0 || game.AwayTeam.ScoreOne !=0){
                html += '            <div class="flex flex-row items-center">';
                html += '                <div class="w-7 text-center uppercase text-gray-300">Q1</div>';
                html += '                <div class="w-7 text-center uppercase text-gray-300">Q2</div>';
                html += '                <div class="w-7 text-center uppercase text-gray-300">Q3</div>';
                html += '                <div class="w-7 text-center uppercase text-gray-300">Q4</div>';
                html += '            </div>';
                html += '            <div class="w-7 text-right  uppercase text-gray-300">FT</div>';
                }
                html += '        </div>';
                html += '    </div>';
                html += '    <div class="grid gap-y-1">';
                html += '        <div class="flex justify-between">';
                html += '            <div class="flex h-[26px] items-center gap-x-2"><img class="h-5 w-5 object-fill"';
                html += '                    src="' + homeTeamIcon + '" alt="flag 1" style="height: 25px; width: auto;"><span';
                html += '                    class="text-gray-800" style="font-size: 14px;">' + game.HomeTeam.NameEn + '</span></div>';
                html += '            <div class="relative flex items-center space-x-3">';
                if(game.HomeTeam.ScoreOne !=0 || game.AwayTeam.ScoreOne !=0){
                html += '                <div class="flex shrink-0 flex-row items-center">';
                    html += '                    <div class="w-7 text-center uppercase text-gray-500">' + (game.HomeTeam.ScoreOne > 0 ? game.HomeTeam.ScoreOne : '-') + '</div>';
                    html += '                    <div class="w-7 text-center uppercase text-gray-500">' + (game.HomeTeam.ScoreTwo > 0 ? game.HomeTeam.ScoreTwo : '-') + '</div>';
                    html += '                    <div class="w-7 text-center uppercase text-gray-500">' + (game.HomeTeam.ScoreThree > 0 ? game.HomeTeam.ScoreThree : '-') + '</div>';
                    html += '                    <div class="w-7 text-center uppercase text-gray-500">' + (game.HomeTeam.ScoreFour > 0 ? game.HomeTeam.ScoreFour : '-') + '</div>';                    
                html += '                </div>';
                html += '                <div class="w-7 text-right  uppercase text-gray-700 ' + animation + '" style="font-weight: bold;">' + game.HomeTeam.FullScore + '</div>';
                }
                html += '            </div>';
                html += '        </div>';
                html += '        <div class="flex justify-between">';
                html += '            <div class="flex h-[26px] items-center gap-x-2"><img class="h-5 w-5 object-fill"';
                html += '                    src="' + awayTeamIcon + '" alt="flag 2" style="height: 25px; width: auto;"><span';
                html += '                    class="text-gray-800" style="font-size: 14px;">' + game.AwayTeam.NameEn + '</span></div>';
                html += '            <div class="relative flex items-center space-x-3">';
                if(game.HomeTeam.ScoreOne !=0 || game.AwayTeam.ScoreOne !=0){
                html += '                <div class="flex shrink-0 flex-row items-center">'; 
                    html += '                    <div class="w-7 text-center uppercase text-gray-500">' + (game.AwayTeam.ScoreOne > 0 ? game.AwayTeam.ScoreOne : '-') + '</div>';
                    html += '                    <div class="w-7 text-center uppercase text-gray-500">' + (game.AwayTeam.ScoreTwo > 0 ? game.AwayTeam.ScoreTwo : '-') + '</div>';
                    html += '                    <div class="w-7 text-center uppercase text-gray-500">' + (game.AwayTeam.ScoreThree > 0 ? game.AwayTeam.ScoreThree : '-') + '</div>';
                    html += '                    <div class="w-7 text-center uppercase text-gray-500">' + (game.AwayTeam.ScoreFour > 0 ? game.AwayTeam.ScoreFour : '-') + '</div>';                    
                html += '                </div>';
                html += '                <div class="w-7 text-right uppercase text-gray-700 ' + animation + '" style="font-weight: bold;">' + game.AwayTeam.FullScore + '</div>';
                }
                html += '            </div>';
                html += '        </div>';
                html += '    </div>';

             

                html += '</div>';
            html += '</div>';

        }); 

   
        //card end 
    html += '</div>'; 

    jQuery('#livescore').append(html);

    // 更新当前索引，为下次加载准备
    currentIndex += gamesToShow.length;
}



/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ 籃球比賽詳細 ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
function game_detail_basketball(id){
    jQuery.ajax({
        url:"/bvg/bvg.php",
        type:"POST",
        dataType:'json',
        data:{
            'act'   : "query_game_detail_basketball",
            'id'    : id 
        },
        success:function(Jdata){
            if(Jdata.code==200) {

                var homeTeamIcon = Jdata.game.HomeTeam.Icon ? Jdata.game.HomeTeam.Icon : '/bvg/basketball.jpg';
                var awayTeamIcon = Jdata.game.AwayTeam.Icon ? Jdata.game.AwayTeam.Icon : '/bvg/basketball.jpg';

                // 计算HomeTeam的各項數據
                // 三分球
                var total3pt = Jdata.game.HomeTeam.ThreePoint + Jdata.game.AwayTeam.ThreePoint;
                var homeTeam3pt = total3pt > 0 ? Math.round((Jdata.game.HomeTeam.ThreePoint / total3pt) * 100) : 50; // 避免除以零
                // 二分球
                var total2pt = Jdata.game.HomeTeam.TwoPoint + Jdata.game.AwayTeam.TwoPoint;
                var homeTeam2pt = total2pt > 0 ? Math.round((Jdata.game.HomeTeam.TwoPoint / total2pt) * 100) : 50; // 避免除以零
                // 罚球
                var totalFT = Jdata.game.HomeTeam.FreeThrow + Jdata.game.AwayTeam.FreeThrow;
                var homeTeamFT = totalFT > 0 ? Math.round((Jdata.game.HomeTeam.FreeThrow / totalFT) * 100) : 50; // 避免除以零
                // 罚球命中率
                var totalFTpercent = Jdata.game.HomeTeam.FreeThrowPercentage + Jdata.game.AwayTeam.FreeThrowPercentage;
                var homeTeamFTpercent = totalFTpercent > 0 ? Math.round((Jdata.game.HomeTeam.FreeThrowPercentage / totalFTpercent) * 100) : 50; // 避免除以零

                var game_status = '';
                if (Jdata.game.MatchStatus === 50) {
                    game_status = 'Full Time';
                } else if (Jdata.game.MatchStatus === 11) {
                    game_status = 'Q1 ' + Jdata.game.MatchTime;
                }
                else if (Jdata.game.MatchStatus === 12) {
                    game_status = 'End Q1';
                }
                else if (Jdata.game.MatchStatus === 13) {
                    game_status = 'Q2 ' + Jdata.game.MatchTime;
                }
                else if (Jdata.game.MatchStatus === 14) {
                    game_status = 'End Q2';
                }
                else if (Jdata.game.MatchStatus === 31) {
                    game_status = 'Q3 ' + Jdata.game.MatchTime;
                }
                else if (Jdata.game.MatchStatus === 32) {
                    game_status = 'End Q3';
                }
                else if (Jdata.game.MatchStatus === 33) {
                    game_status = 'Q4 ' + Jdata.game.MatchTime;
                }
                else if (Jdata.game.MatchStatus === 40) {
                    game_status = 'OT';
                }
                else if (Jdata.game.MatchStatus === 63) {
                    game_status = 'Extension';
                }
                
                // 假设Jdata.game.Schedule的值是 "2024-03-01T06:00:00"
                var fullDateTime = Jdata.game.Schedule;
                // 使用split方法以"T"为分隔符分割字符串，并取第一部分，即日期
                var dateOnly = fullDateTime.split('T')[0];
 
                var html ='';

                html += '<div class="score">';

                    html += '<div class="saba-score-card-2" >';
                        html +='<div class="mh">';
                            html +='<div data-testid="match-detail_team-name_home" class="nh">';
                                html +='<img alt="Mallorca" loading="lazy" width="50" height="50" decoding="async" data-nimg="1" class="badgeContainer" src="' + homeTeamIcon + '"  >';
                                html +='<span class="oh">' + Jdata.game.HomeTeam.NameEn + '</span>';
                            html +='</div>';
                            
                            html +='<div class="th">'; 
                            html += '<span id="SEV__status" class="xh">' + dateOnly + '</span>';
                            html +='<span style="font-size:13px;color:red;font-weight:bold">' + Jdata.game.LeagueEn + '</span>';
                                html +='<span id="score-or-time" class="uh vh">' + Jdata.game.HomeTeam.FullScore + ' - ' + Jdata.game.AwayTeam.FullScore + '</span>';
                                html +='<span id="SEV__status" class="xh"  style="font-weight: bold;color: red;">' + game_status + '</span>';
                            html +='</div>';
                            
                            html +='<div data-testid="match-detail_team-name_away" class="nh">';
                                html +='<img alt="Real Sociedad" loading="lazy" width="50" height="50" decoding="async" data-nimg="1" class="badgeContainer" src="' + awayTeamIcon + '" >';
                                html +='<span class="oh">' + Jdata.game.AwayTeam.NameEn + '</span>';
                            html +='</div>';
                        html +='</div>';
                    

                        html += '<div class="statistics-row">';                       
                        html += '    <div class="statistics-2">';
                        html += '        <div class="statistics-item-2">';
                        html += '            <p>Fouls</p>';
                        html += '            <div style="text-align-last: center;">' + Jdata.game.HomeTeam.Foul + '</div>';
                        html += '        </div>';
                        html += '        <div class="statistics-item-2">';
                        html += '            <p>Time Outs</p>';
                        html += '            <div style="text-align-last: center;">' + Jdata.game.HomeTeam.TimeOut + '</div>';
                        html += '        </div>'; 
                        html += '    </div>';
                        html += '    <div class="statistics-content">';
                        html += '        <div class="statistics-list">';
                        html += '            <div>3 PT</div>';
                        html += '            <div class="statistics-list-content">';
                        html += '                <div class="">' + Jdata.game.HomeTeam.ThreePoint + '</div>';
                        html += '                <div class="statistics-bar">';
                        html += '                    <div class="statistics-bar-foreground" style="width:' + homeTeam3pt + '%;"></div>';
                        html += '                </div>';
                        html += '                <div class="">' + Jdata.game.AwayTeam.ThreePoint + '</div>';
                        html += '            </div>';
                        html += '        </div>';
                        html += '        <div class="statistics-list">';
                        html += '            <div>2 PT</div>';
                        html += '            <div class="statistics-list-content">';
                        html += '                <div class="">' + Jdata.game.HomeTeam.TwoPoint + '</div>';
                        html += '                <div class="statistics-bar">';
                        html += '                    <div class="statistics-bar-foreground" style="width: ' + homeTeam2pt + '%;"></div>';
                        html += '                </div>';
                        html += '                <div class="">' + Jdata.game.AwayTeam.TwoPoint + '</div>';
                        html += '            </div>';
                        html += '        </div>';
                        html += '        <div class="statistics-list">';
                        html += '            <div>Free Throws</div>';
                        html += '            <div class="statistics-list-content">';
                        html += '                <div class="">' + Jdata.game.HomeTeam.FreeThrow + '</div>';
                        html += '                <div class="statistics-bar">';
                        html += '                    <div class="statistics-bar-foreground" style="width: ' + homeTeamFT + '%;"></div>';
                        html += '                </div>';
                        html += '                <div class="">' + Jdata.game.AwayTeam.FreeThrow + '</div>';
                        html += '            </div>';
                        html += '        </div>';
                        html += '        <div class="statistics-list">';
                        html += '            <div>Free Throws %</div>';
                        html += '            <div class="statistics-list-content">';
                        html += '                <div>' + Jdata.game.HomeTeam.FreeThrowPercentage + '</div>';
                        html += '                <div class="statistics-bar">';
                        html += '                    <div class="statistics-bar-foreground" style="width: ' + homeTeamFTpercent + '%;"></div>';
                        html += '                </div>';
                        html += '                <div>' + Jdata.game.AwayTeam.FreeThrowPercentage + '</div>';
                        html += '            </div>';
                        html += '        </div>';
                        html += '    </div>';
                        html += '    <div class="statistics-2">';
                        html += '        <div class="statistics-item-2">';
                        html += '            <p>Time Outs</p>';
                        html += '            <div style="text-align-last: center;">' + Jdata.game.AwayTeam.TimeOut + '</div>';
                        html += '        </div>'; 
                        html += '        <div class="statistics-item-2">';
                        html += '            <p>Fouls</p>';
                        html += '            <div style="text-align-last: center;">' + Jdata.game.AwayTeam.Foul + '</div>';
                        html += '        </div>';
                        html += '    </div>';
                        html += '</div>';
                 
                        if(Jdata.game.HomeTeam.ScoreOne!=0 || Jdata.game.AwayTeam.ScoreOne!=0){
                        html += '<div class="summary">';
                        html += '    <div class="event">';
                        html += '        <div class="ht homeContent">'; 
                        html += '            <div class="ot homePlayer">';
                        html += '                <span class="it" id="home-point">' + Jdata.game.HomeTeam.ScoreOne + '</span>';
                        html += '           </div>'; 
                        html += '        </div>';
                        html += '        <div class="kt">';
                        html += '            <span style="font-size: 15px;font-weight: bold;">Q1</span>'; 
                        html += '        </div>';
                        html += '        <div class="ht awayContent">';  
                        html += '            <div class="ot awayPlayer">';
                        html += '                <span class="it" id="away-ponit">' + Jdata.game.AwayTeam.ScoreOne + '</span>';
                        html += '            </div>';
                        html += '        </div>';
                        html += '    </div>';
                        html += '</div>';
                        }

                        if(Jdata.game.HomeTeam.ScoreTwo!=0 || Jdata.game.AwayTeam.ScoreTwo!=0){
                            html += '<div class="summary">';
                            html += '    <div class="event">';
                            html += '        <div class="ht homeContent">'; 
                            html += '            <div class="ot homePlayer">';
                            html += '                <span class="it" id="home-point">' + Jdata.game.HomeTeam.ScoreTwo + '</span>';
                            html += '           </div>'; 
                            html += '        </div>';
                            html += '        <div class="kt">';
                            html += '            <span style="font-size: 15px;font-weight: bold;">Q2</span>'; 
                            html += '        </div>';
                            html += '        <div class="ht awayContent">';  
                            html += '            <div class="ot awayPlayer">';
                            html += '                <span class="it" id="away-ponit">' + Jdata.game.AwayTeam.ScoreTwo + '</span>';
                            html += '            </div>';
                            html += '        </div>';
                            html += '    </div>';
                            html += '</div>';
                        }

                        if(Jdata.game.HomeTeam.ScoreThree!=0 || Jdata.game.AwayTeam.ScoreThree!=0){
                            html += '<div class="summary">';
                            html += '    <div class="event">';
                            html += '        <div class="ht homeContent">'; 
                            html += '            <div class="ot homePlayer">';
                            html += '                <span class="it" id="home-point">' + Jdata.game.HomeTeam.ScoreThree + '</span>';
                            html += '           </div>'; 
                            html += '        </div>';
                            html += '        <div class="kt">';
                            html += '            <span style="font-size: 15px;font-weight: bold;">Q3</span>'; 
                            html += '        </div>';
                            html += '        <div class="ht awayContent">';  
                            html += '            <div class="ot awayPlayer">';
                            html += '                <span class="it" id="away-ponit">' + Jdata.game.AwayTeam.ScoreThree + '</span>';
                            html += '            </div>';
                            html += '        </div>';
                            html += '    </div>';
                            html += '</div>';
                        }

                        if(Jdata.game.HomeTeam.ScoreFour!=0 || Jdata.game.AwayTeam.ScoreFour!=0){
                            html += '<div class="summary">';
                            html += '    <div class="event">';
                            html += '        <div class="ht homeContent">'; 
                            html += '            <div class="ot homePlayer">';
                            html += '                <span class="it" id="home-point">' + Jdata.game.HomeTeam.ScoreFour + '</span>';
                            html += '           </div>'; 
                            html += '        </div>';
                            html += '        <div class="kt">';
                            html += '            <span style="font-size: 15px;font-weight: bold;">Q4</span>'; 
                            html += '        </div>';
                            html += '        <div class="ht awayContent">';  
                            html += '            <div class="ot awayPlayer">';
                            html += '                <span class="it" id="away-ponit">' + Jdata.game.AwayTeam.ScoreFour + '</span>';
                            html += '            </div>';
                            html += '        </div>';
                            html += '    </div>';
                            html += '</div>';
                        }

                        if(Jdata.game.HomeTeam.ScoreExtra!=0 || Jdata.game.AwayTeam.ScoreExtra!=0){
                            html += '<div class="summary">';
                            html += '    <div class="event">';
                            html += '        <div class="ht homeContent">'; 
                            html += '            <div class="ot homePlayer">';
                            html += '                <span class="it" id="home-point">' + Jdata.game.HomeTeam.ScoreExtra + '</span>';
                            html += '           </div>'; 
                            html += '        </div>';
                            html += '        <div class="kt">';
                            html += '            <span style="font-size: 15px;font-weight: bold;">OT</span>'; 
                            html += '        </div>';
                            html += '        <div class="ht awayContent">';  
                            html += '            <div class="ot awayPlayer">';
                            html += '                <span class="it" id="away-ponit">' + Jdata.game.AwayTeam.ScoreExtra + '</span>';
                            html += '            </div>';
                            html += '        </div>';
                            html += '    </div>';
                            html += '</div>';
                        }

                    html += '</div>';
                html += '</div>';



                jQuery('#livescore').html(html);
            } else {
                alert(Jdata.msg);
            }
        },
        error:function(){
        }
    }); 

}
 


/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ 以下網球 ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */

function update_livescore_tennis(date) {
    // 默认参数处理
    date = date || '0'; // 如果date未定义，则默认为'0'

    // 检查参数是否有变化或allGames是否为空
    if (allGames.length === 0 ||   date !== lastDate) {
        jQuery.ajax({
            url: "/bvg/bvg.php",
            type: "POST",
            dataType: 'json',
            data: {
                'act': "query_live_score_tennis",
                'date': date,
                'type': 'Tennis'
            },
            success: function(Jdata) {
                if (Jdata.code == 200) {
                    allGames = Jdata.games; // 更新游戏数据
                    currentIndex = 0; // 重置当前索引
                    jQuery('#livescore').empty(); // 清空现有的游戏数据显示
                    loadMoreGames_tennis(); // 加载首批游戏数据
                    hideModal();
                } else {
                    alert(Jdata.msg);
                }
            },
            error: function() {
                // 错误处理
            }
        });
 
        // 更新上一次查询时使用 
        lastDate = date;
    } else {
        // 如果参数没有变化，并且已有数据，直接加载更多数据
        loadMoreGames_tennis();
    }
}




function loadMoreGames_tennis() {
    // 从已有的游戏数据中截取一部分来显示
    var gamesToShow = allGames.slice(currentIndex, currentIndex + increment);
    
    var html ='';

    html += '<div class="score">';
        //card Start
        
        jQuery.each(gamesToShow, function(index, game) {
             
            var matchStatus = ''; 
            var animation = '';
            if (game.MatchStatus === 3 ) {
                matchStatus = '<div class="saba-match-status-2 col-2"  style="color: blue;">Finish ' + game.Schedule + '</div>';
            } 
            else if(game.MatchStatus ===1) {  
                matchStatus = '<div class="saba-match-status-2 col-2">' + game.Schedule + '</div>';
            }
            else if(game.MatchStatus ===2) {  
                if(game.HomePlayers.ScoreOfSetFour == '6' || game.AwayPlayers.ScoreOfSetFour == '6') {
                    matchStatus = '<div class="saba-match-status-2 col-2" style="color: red;">5th Set</div>';
                    animation = '';
                }
                else if(game.HomePlayers.ScoreOfSetThree == '6' || game.AwayPlayers.ScoreOfSetThree == '6') {
                    matchStatus = '<div class="saba-match-status-2 col-2" style="color: red;">4th Set</div>';
                    animation = '';
                }
                else if(game.HomePlayers.ScoreOfSetTwo == '6' || game.AwayPlayers.ScoreOfSetTwo == '6') {
                    matchStatus = '<div class="saba-match-status-2 col-2" style="color: red;">3rd Set</div>';
                    animation = '';
                }
                else if(game.HomePlayers.ScoreOfSetOne == '6' || game.AwayPlayers.ScoreOfSetOne == '6') {
                    matchStatus = '<div class="saba-match-status-2 col-2" style="color: red;">2nd Set</div>';
                    animation = '';
                }
                else {
                    matchStatus = '<div class="saba-match-status-2 col-2" style="color: red;">1st Set</div>';
                    animation = '';
                }
            }
            else {
                matchStatus = '<div class="saba-match-status-2 col-2"  >' + game.Schedule + '</div>';
            }
            
            var homeTeamIcon = '/bvg/tennis.png';
            var awayTeamIcon = '/bvg/tennis.png';

            var homePlayer = '';
            var awayPlayer = '';
            if(game.Type === 1){
                homePlayer = game.HomePlayers.PlayerNameEn1;
                awayPlayer = game.AwayPlayers.PlayerNameEn1;
            }
            else if(game.Type === 2){
                homePlayer = game.HomePlayers.PlayerNameEn1 + ' / ' + game.HomePlayers.PlayerNameEn2;
                awayPlayer = game.AwayPlayers.PlayerNameEn1 + ' / ' + game.AwayPlayers.PlayerNameEn2;
            }

             
            var homeset ='';
            var awayset ='';
            if(game.HomePlayers.ScoreOfSetOne != 0 || game.AwayPlayers.ScoreOfSetOne != 0){
                homeset = game.HomePlayers.ScoreOfSetOne;
                awayset = game.AwayPlayers.ScoreOfSetOne;
            }
            if(game.HomePlayers.ScoreOfSetTwo != 0 || game.AwayPlayers.ScoreOfSetTwo != 0){
                homeset = game.HomePlayers.ScoreOfSetTwo;
                awayset = game.AwayPlayers.ScoreOfSetTwo;
            }
            if(game.HomePlayers.ScoreOfSetThree != 0 || game.AwayPlayers.ScoreOfSetThree != 0){
                homeset = game.HomePlayers.ScoreOfSetThree;
                awayset = game.AwayPlayers.ScoreOfSetThree;
            }
            if(game.HomePlayers.ScoreOfSetFour != 0 || game.AwayPlayers.ScoreOfSetFour != 0){
                homeset = game.HomePlayers.ScoreOfSetFour;
                awayset = game.AwayPlayers.ScoreOfSetFour;
            }
            if(game.HomePlayers.ScoreOfSetFive != 0 || game.AwayPlayers.ScoreOfSetFive != 0){
                homeset = game.HomePlayers.ScoreOfSetFive;
                awayset = game.AwayPlayers.ScoreOfSetFive;
            }


            html += '<div class="saba-score-card-2 saba-score-card-hover" onclick="location.href=\'/live-score/?sport=tennis&matchid=' + game.MatchId + '\';" style="cursor: pointer;" id="' + game.MatchId + '">';            
                html += '<div class="flex-only min-h-[120px] flex-col justify-center  px-2.5 lg:min-h-[112px]">';
                html += '    <div class="flex-only mb-1 flex-row justify-between gap-x-2.5 border-b border-solid border-gray-200 pb-2" style="border-bottom: inset;margin-bottom: 12px;">';
                html += '        <div class="flex-only min-w-0 grow flex-col gap-x-1 gap-y-[1px] lg:flex-row" >';
                html += '            <div class="flex-only shrink-0 gap-x-1"><span class=" font-bold text-[#E20A0A]">' + matchStatus + '</span>';
                html += '            </div>'; 
                html += '               <p style="font-size: 14px;font-weight: bold;">' + game.LeagueEn + '</p>'; 
                html += '        </div>'; 
                html += '    </div>';
                html += '    <div class="grid gap-y-1">';
                html += '        <div class="flex justify-between">';
                html += '            <div class="flex h-[26px] items-center gap-x-2"><img class="h-5 w-5 object-fill"';
                html += '                    src="' + homeTeamIcon + '" alt="flag 1" style="height: 25px; width: auto;"><span';
                html += '                    class="text-gray-800" style="font-size: 14px;">' + homePlayer + '</span></div>';
                html += '            <div class="relative flex items-center space-x-3">';
                html += '                <div class="flex h-4 shrink-0 flex-row items-center justify-center border-r border-solid border-[#9DA1AA]">';
                html += '                    <div class="w-7 text-center uppercase text-gray-500 ' + animation +'">' + game.HomePlayers.ScoreOfCurrentSet + '</div>';
                html += '                    <div class="w-7 text-center uppercase text-red-100"  style="color: red;font-weight: bold; border-right: groove;">' + homeset + '</div>';
                html += '                </div>';
                html += '                <div class="w-3 text-right uppercase text-gray-700" style="margin-left: 8px;">' + game.HomePlayers.TotalScore + '</div>';
                html += '            </div>';
                html += '        </div>';
                html += '        <div class="flex justify-between">';
                html += '            <div class="flex h-[26px] items-center gap-x-2"><img class="h-5 w-5 object-fill"';
                html += '                    src="' + awayTeamIcon + '" alt="flag 1" style="height: 25px; width: auto;"><span';
                html += '                    class="text-gray-800" style="font-size: 14px;">' + awayPlayer + '</span></div>';
                html += '            <div class="relative flex items-center space-x-3">';
                html += '                <div class="flex h-4 shrink-0 flex-row items-center justify-center border-r border-solid border-[#9DA1AA]">';
                html += '                    <div class="w-7 text-center uppercase text-gray-500 ' + animation +'">' + game.AwayPlayers.ScoreOfCurrentSet + '</div>';
                html += '                    <div class="w-7 text-center uppercase text-red-100"  style="color: red;font-weight: bold; border-right: groove;">' + awayset + '</div>';
                html += '                </div>';
                html += '                <div class="w-3 text-right  uppercase text-gray-700"  style="margin-left: 8px;" >' + game.AwayPlayers.TotalScore + '</div>';
                html += '            </div>';
                html += '        </div>';
                html += '    </div>'; 
                html += '</div>';
            html += '</div>';

        }); 

   
        //card end 
    html += '</div>'; 

    jQuery('#livescore').append(html);

    // 更新当前索引，为下次加载准备
    currentIndex += gamesToShow.length;
}



/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ 網球比賽詳細 ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
function game_detail_tennis(id){
    jQuery.ajax({
        url:"/bvg/bvg.php",
        type:"POST",
        dataType:'json',
        data:{
            'act'   : "query_game_detail_tennis",
            'id'    : id 
        },
        success:function(Jdata){
            if(Jdata.code==200) {

                var homeTeamIcon =  '/bvg/tennis.png';
                var awayTeamIcon =  '/bvg/tennis.png';

                var homePlayer = '';
                var awayPlayer = '';
                if(Jdata.game.Type === 1){
                    homePlayer = Jdata.game.HomePlayers.PlayerNameEn1;
                    awayPlayer = Jdata.game.AwayPlayers.PlayerNameEn1;
                }
                else if(Jdata.game.Type === 2){
                    homePlayer = Jdata.game.HomePlayers.PlayerNameEn1 + ' / ' + Jdata.game.HomePlayers.PlayerNameEn2;
                    awayPlayer = Jdata.game.AwayPlayers.PlayerNameEn1 + ' / ' + Jdata.game.AwayPlayers.PlayerNameEn2;
                }

                // 假设Jdata.game.Schedule的值是 "2024-03-01T06:00:00"
                var fullDateTime = Jdata.game.Schedule;
                // 使用split方法以"T"为分隔符分割字符串，并取第一部分，即日期
                var dateOnly = fullDateTime.split('T')[0];

                var matchStatus = '';
                if (Jdata.game.MatchStatus === 3 ) {
                    matchStatus = 'Finish ' + dateOnly ;
                } 
                else if(Jdata.game.MatchStatus ===1) {  
                    matchStatus = dateOnly ;
                }
                else if(Jdata.game.MatchStatus ===2) {  
                    if(Jdata.game.HomePlayers.ScoreOfSetFour == '6' || Jdata.game.AwayPlayers.ScoreOfSetFour == '6') {
                        matchStatus = '5th Set'; 
                    }
                    else if(Jdata.game.HomePlayers.ScoreOfSetThree == '6' || Jdata.game.AwayPlayers.ScoreOfSetThree == '6') {
                        matchStatus = '4th Set'; 
                    }
                    else if(Jdata.game.HomePlayers.ScoreOfSetTwo == '6' || Jdata.game.AwayPlayers.ScoreOfSetTwo == '6') {
                        matchStatus = '3rd Set'; 
                    }
                    else if(Jdata.game.HomePlayers.ScoreOfSetOne == '6' || Jdata.game.AwayPlayers.ScoreOfSetOne == '6') {
                        matchStatus = '2nd Set'; 
                    }
                    else {
                        matchStatus = '1st Set'; 
                    }
                }
                else {
                    matchStatus = dateOnly;
                }
                 
                var html ='';

                html += '<div class="score">';

                    html += '<div class="saba-score-card-2" >';
                        html +='<div class="mh">';
                            html +='<div data-testid="match-detail_team-name_home" class="nh">';
                                html +='<img alt="Mallorca" loading="lazy" width="50" height="50" decoding="async" data-nimg="1" class="badgeContainer" src="' + homeTeamIcon + '"  >';
                                html +='<span class="oh">' + homePlayer + '</span>';
                            html +='</div>';
                            
                            html +='<div class="th">'; 
                            html +='<span id="SEV__status" class="xh">' + dateOnly + '</span>';
                            html +='<span style="font-size:13px;color:red;font-weight:bold">' + Jdata.game.LeagueEn + '</span>';
                                html +='<span id="score-or-time" class="uh vh">' + Jdata.game.HomePlayers.TotalScore + ' - ' + Jdata.game.AwayPlayers.TotalScore + '</span>';
                                html +='<span id="SEV__status" class="xh" style="color: red;font-weight: bold; ">' + matchStatus + '</span>';
                            html +='</div>';
                            
                            html +='<div data-testid="match-detail_team-name_away" class="nh">';
                                html +='<img alt="Real Sociedad" loading="lazy" width="50" height="50" decoding="async" data-nimg="1" class="badgeContainer" src="' + awayTeamIcon + '" >';
                                html +='<span class="oh">' + awayPlayer + '</span>';
                            html +='</div>';
                        html +='</div>';

                        if(Jdata.game.HomePlayers.ScoreOfSetOne!=0 || Jdata.game.AwayPlayers.ScoreOfSetOne!=0){
                            html += '<div class="summary">';
                            html += '    <div class="event">';
                            html += '        <div class="ht homeContent">'; 
                            html += '            <div class="ot homePlayer">';
                            html += '                <span class="it" id="home-point">' + Jdata.game.HomePlayers.ScoreOfSetOne + '</span>';
                            html += '           </div>'; 
                            html += '        </div>';
                            html += '        <div class="kt">';
                            html += '            <span style="font-size: 15px;font-weight: bold;">1st Set</span>'; 
                            html += '        </div>';
                            html += '        <div class="ht awayContent">';  
                            html += '            <div class="ot awayPlayer">';
                            html += '                <span class="it" id="away-ponit">' + Jdata.game.AwayPlayers.ScoreOfSetOne + '</span>';
                            html += '            </div>';
                            html += '        </div>';
                            html += '    </div>';
                            html += '</div>';
                        }
                        
                        if(Jdata.game.HomePlayers.ScoreOfSetTwo!=0 || Jdata.game.AwayPlayers.ScoreOfSetTwo!=0){
                            html += '<div class="summary">';
                            html += '    <div class="event">';
                            html += '        <div class="ht homeContent">'; 
                            html += '            <div class="ot homePlayer">';
                            html += '                <span class="it" id="home-point">' + Jdata.game.HomePlayers.ScoreOfSetTwo + '</span>';
                            html += '           </div>'; 
                            html += '        </div>';
                            html += '        <div class="kt">';
                            html += '            <span style="font-size: 15px;font-weight: bold;">2nd Set</span>'; 
                            html += '        </div>';
                            html += '        <div class="ht awayContent">';  
                            html += '            <div class="ot awayPlayer">';
                            html += '                <span class="it" id="away-ponit">' + Jdata.game.AwayPlayers.ScoreOfSetTwo + '</span>';
                            html += '            </div>';
                            html += '        </div>';
                            html += '    </div>';
                            html += '</div>';
                        }

                        if(Jdata.game.HomePlayers.ScoreOfSetThree!=0 || Jdata.game.AwayPlayers.ScoreOfSetThree!=0){
                            html += '<div class="summary">';
                            html += '    <div class="event">';
                            html += '        <div class="ht homeContent">'; 
                            html += '            <div class="ot homePlayer">';
                            html += '                <span class="it" id="home-point">' + Jdata.game.HomePlayers.ScoreOfSetThree + '</span>';
                            html += '           </div>'; 
                            html += '        </div>';
                            html += '        <div class="kt">';
                            html += '            <span style="font-size: 15px;font-weight: bold;">3rd Set</span>'; 
                            html += '        </div>';
                            html += '        <div class="ht awayContent">';  
                            html += '            <div class="ot awayPlayer">';
                            html += '                <span class="it" id="away-ponit">' + Jdata.game.AwayPlayers.ScoreOfSetThree + '</span>';
                            html += '            </div>';
                            html += '        </div>';
                            html += '    </div>';
                            html += '</div>';
                        }

                        if(Jdata.game.HomePlayers.ScoreOfSetFour!=0 || Jdata.game.AwayPlayers.ScoreOfSetFour!=0){
                            html += '<div class="summary">';
                            html += '    <div class="event">';
                            html += '        <div class="ht homeContent">'; 
                            html += '            <div class="ot homePlayer">';
                            html += '                <span class="it" id="home-point">' + Jdata.game.HomePlayers.ScoreOfSetFour + '</span>';
                            html += '           </div>'; 
                            html += '        </div>';
                            html += '        <div class="kt">';
                            html += '            <span style="font-size: 15px;font-weight: bold;">4th Set</span>'; 
                            html += '        </div>';
                            html += '        <div class="ht awayContent">';  
                            html += '            <div class="ot awayPlayer">';
                            html += '                <span class="it" id="away-ponit">' + Jdata.game.AwayPlayers.ScoreOfSetFour + '</span>';
                            html += '            </div>';
                            html += '        </div>';
                            html += '    </div>';
                            html += '</div>';
                        }

                        if(Jdata.game.HomePlayers.ScoreOfSetFive!=0 || Jdata.game.AwayPlayers.ScoreOfSetFive!=0){
                            html += '<div class="summary">';
                            html += '    <div class="event">';
                            html += '        <div class="ht homeContent">'; 
                            html += '            <div class="ot homePlayer">';
                            html += '                <span class="it" id="home-point">' + Jdata.game.HomePlayers.ScoreOfSetFive + '</span>';
                            html += '           </div>'; 
                            html += '        </div>';
                            html += '        <div class="kt">';
                            html += '            <span style="font-size: 15px;font-weight: bold;">5th Set</span>'; 
                            html += '        </div>';
                            html += '        <div class="ht awayContent">';  
                            html += '            <div class="ot awayPlayer">';
                            html += '                <span class="it" id="away-ponit">' + Jdata.game.AwayPlayers.ScoreOfSetFive + '</span>';
                            html += '            </div>';
                            html += '        </div>';
                            html += '    </div>';
                            html += '</div>';
                        }
                    html += '</div>';
                html += '</div>';



                jQuery('#livescore').html(html);
            } else {
                alert(Jdata.msg);
            }
        },
        error:function(){
        }
    }); 

}
  



// 显示模态框函数
function showModal() {
    document.getElementById('loadingModal').style.display = 'flex';
}

// 隐藏模态框函数
function hideModal() {
    document.getElementById('loadingModal').style.display = 'none';
}

if (window.location.href.indexOf("live-score") !== -1){
    function checkURLAndUpdateScores() {
        const url = new URL(window.location.href);
        var date_selected = jQuery('.btn-selected'); // 抓取現在選擇的日期
        var date_value = date_selected.val(); // 抓取現在選擇的日期值
        const matchId = url.searchParams.get("matchid"); // 從網址中抓取 matchid 的值
        const sport = url.searchParams.get("sport"); // 從網址中抓取 sport 的值
        allGames = [];
        if (sport=='basketball') {
            if (matchId) {
                game_detail_basketball(matchId);
            } else {
                update_livescore_basketball('', date_value);
            }
        } else if (sport=="tennis") {
            if (matchId) {
                game_detail_tennis(matchId);
            } else {
                update_livescore_tennis('', date_value);
            }
        } else if (sport=="soccer") {
            if (matchId) {
                game_detail_soccer(matchId);
            } else {
                update_livescore_soccer('', date_value);
            }
        }
        console.log(sport);
    }


    // 每分钟执行一次 checkURLAndUpdateScores 函数
    setInterval(checkURLAndUpdateScores, 30000); 
 
    // 獲取按鈕
    var mybutton = document.getElementById("backToTopButton");

    // 當用戶向下滾動 20px 從頁面頂端時，顯示按鈕
    window.onscroll = function() {
        scrollFunction();
    };

    function scrollFunction() {
        if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
            mybutton.style.display = "block";
        } else {
            mybutton.style.display = "none";
        }
    }

    // 當用戶點擊按鈕，返回頁面頂部
    mybutton.onclick = function() {
        window.scrollTo({top: 0, behavior: 'smooth'});
    };
}



function showPopup() {
    jQuery.ajax({
        url: "/bvg/bvg.php",
        type: "POST",
        dataType: 'json',
        data: {
            'act': "query_popup_ad", 
            'id' : 'saba_banner_2'
        },
        success: function(Jdata) {
            if (Jdata.code == 200) {
                var html_popup ='';
                html_popup += '<a target="' + Jdata.data.target + '" href="' + Jdata.data.url + '" class="imgwhd ad-bnimg gxr popup-ad">';
                    html_popup += '<img class="popad-img" src="https://img.thesabasportsindo.com/' + Jdata.data.content + '">';
                html_popup += '</a>';
                html_popup += '<div class="banner-button-wrap-text">';
                    html_popup += '<div class="cookie-message" onclick="handleNoShowTodayClick()">Jangan munculkan hari ini</div>';
                    html_popup += '<div class="close-btn" onclick="hideModal()">Tutup</div>';
                html_popup += '</div>';
            
                jQuery('.popad-wrapper').html(html_popup); 
                jQuery('.popoutbg.modal-container').addClass('show-modal');

                jQuery('a.popup-ad').click(function(e) {
                    console.log('banner_popup!');
                    // 防止連結立即導航
                        e.preventDefault();
                        // 用於 GA4 的 gtag 事件追蹤
                        gtag('event', "banner_popup", {
                          'event_category': 'banner',
                          'event_label': 'banner_popup',
                        });
                  
                        // 實際上導航到該網址
                        window.location.href = this.href;
                  });
            } else { 
            }
        },
        error: function() {
            // 错误处理
        }
    });
    
    
}

function setCookie(name, value, days) {
    var expires = "";
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days*24*60*60*1000));
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + (value || "")  + expires + "; path=/";
}

function getCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0;i < ca.length;i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
}

// 为"今天不再显示"按钮添加点击事件监听器
function handleNoShowTodayClick() {
    setCookie("adShown", "true", 1);
    hideModal();
}

function hideModal() {
    modal.style.display = 'none';
}
 
 