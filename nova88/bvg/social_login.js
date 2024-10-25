jQuery(document).ready(function($) {

  get_login_url();

  // 檢查 URL 中的 loggedout 參數
  let queryParams = getQueryParams(window.location.href);
  if (queryParams.loggedout === 'true') {
    initLineSDK().then(() => {
      lineLogout();
    });
  }

    // 如果網址有 goto 參數，而且goto 參數不包含 /login/，將其設置為登入後的重定向 URL
    if (queryParams.goto && queryParams.goto.indexOf('/login/') === -1){
      // 轉換 goto 參數為相對 URL
      var goto = decodeURIComponent(queryParams.goto);
      jQuery('#goto').val(goto);
    } else {
      jQuery('#goto').val('/');
    }
    


  // 找到 .user-logged-out 超連結，取得原本網址並加上目前網址到goto參數
  var userLoggedOut = jQuery('.user-logged-out');
  if (userLoggedOut.length) {
    userLoggedOut.each(function() {
      var href = jQuery(this).attr('href');
      jQuery(this).attr('href', href + '?goto=' + encodeURIComponent(window.location.href));
    });
  }





});


// 移除所有網址參數
function removeUrlParameters() {
  const url = window.location.href;
  const cleanUrl = url.split("?")[0];
  window.history.replaceState({}, document.title, cleanUrl);
}

// 解析網址參數
function getQueryParams(url) {
  let queryParams = {};
  let queryString = url.split('?')[1];

  if (!queryString) {
    return queryParams;
  }

  queryString.split('&').forEach(param => {
    let [key, value] = param.split('=');
    queryParams[key] = value;
  });

  return queryParams;
}


function checkLoginStatus_home() {
	jQuery(".elementor-form").hide();
	// 使用AJAX發送請求檢查用戶的登錄狀態
	var xhr = new XMLHttpRequest();
	xhr.onreadystatechange = function() {
	  if (xhr.readyState == 4 && xhr.status == 200) {
		// 解析JSON數據
		var data = JSON.parse(xhr.responseText);
		// 更新登錄狀態元素的內容
		if (data.user_id !== 0) {
		  jQuery('#login-status').html('已登入：' + data.user_name);
		  jQuery(".elementor-form").parent().parent().css("display", 'block');
		  jQuery(".elementor-form").css("display", 'block');
		  jQuery('#form-field-fullname').val(data.user_name);
		} else {
			jQuery('#login-status').show();
		}
		//jQuery('#login-status').html(data.user_id);
	  }
	};
	xhr.open('GET', '/wp-content/themes/hello-elementor/check-login-status.php', true);
	xhr.send();
  }

function get_login_url() {
  if(jQuery("#google-login").length == 0) return;
  console.log('get_login_url!');
  jQuery.ajax({
    url: '/bvg/bvg.php',
    type: 'post',
    data: {
      'act': 'get_login_url',
    },
    success: function(Jdata) {
      // console.log(response);
      jQuery('#google-login').prop('href', Jdata.data.google);
    }
  });
}