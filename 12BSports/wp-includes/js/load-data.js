var loadData = (function(jQuery) {

    function GetVideoData() {
        console.log(123);
        jQuery.ajax({
            url:"/wp-data.php",
            type:"POST",
            dataType:'json',
            data:{
                'act'     : "get_home_video"
            },
            success:function(Jdata){
                console.log(Jdata);
            }, error:function(){

            }
        })
    }
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