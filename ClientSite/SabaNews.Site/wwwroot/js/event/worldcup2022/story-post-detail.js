var storyPostDetail = (function ($) {
    function HandleGetImages() {
        //var html = $.parseHTML(content);
        console.log(content);
        //var imgs = html.find('.wp-block-image');
        //console.log(imgs);
    }
    function init() {
        HandleGetImages();
    }

    return {
        init
    }
})(jQuery);

$(document).ready(function () {
    storyPostDetail.init();
});
