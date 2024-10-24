var loadData = (function(jQuery) {

    function GetVideoData() {
        jQuery.ajax({
            url:"/wp-data.php",
            type:"POST",
            data:{
                'act'     : "home_video"
            },
            dataType:'json',
            success:function(Jdata){
                var numVideos = 0;
                var html = '<div class="block block-video">';
                    html += '<h2 class="block__title">Video</h2>';
                    html += '<div class="group-video">';
                        html += '<div class="box-vertical">';
                jQuery.each(Jdata.data, function(index, post) {
                    numVideos++;
                    var display = numVideos == 1 ? "block" : "none";
                    html += '<div style="display:'+ display +';" class="box-play-video" id="slide-'+ post.ID +'">';
                    html += '<input type="hidden" name="iframe_value" value="'+ post.post_content +'" />';
                    html += '<div class="img-item">';
                    html += '<a title="'+ post.post_title +'" class="box-vertical-image hover-image">'
                    html += '<img src="'+ post.post_title +'" alt="@post.PostTitle" class="img-res lazy"  />'
                });
                jQuery('.home-video').html(html);
            }, error:function(){
            }
        })
    };
    function init() {
        GetVideoData();
    }

    return {
        init
    }
})(jQuery);

jQuery( function () {
    loadData.init();
})