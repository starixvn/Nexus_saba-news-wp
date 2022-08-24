var storyPostDetail = (function ($) {
    function HandleGetImages() {
        var images = [];
        var imgBlocks = $('.story-data .wp-block-image');
        imgBlocks.map(value => {
            var image = $(value).find('img').attr("src");
            var title = $(value).find('figcaption').html();
            var block = { image, title };
            images.push(block);
        });
        var html = '';
        console.log(images);
        
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
