var video = (function ($) {
    var paging = window.paging;
    var pageSize = paging.pageSize;
    var page = paging.page;
    var isVideo = window.isVideo;
    var slugs = window.slugs;
    var type = window.type;

    function GetVideo() {
        page = 1;
        $.ajax({
            url: '/WorldCup2022/GetNewsGrid',
            type: 'POST',
            data: { slugs, page, pageSize, isVideo, type }
            }).done(function (data) {
                $('#video-grid').html(data);
            })
            .fail(function (jqXHR, ajaxOptions, thrownError) {
                console.log('server not responding...');
            });
    }

    function HandleBtn() {
        $(document).on('click', '#btn-showall', function () {
            slugs.slug1 = 'video-world-cup-2022';
            GetVideo();
        });
        $(document).on('click', '#btn-showhighlight', function () {
            slugs.slug1 = 'video-highlights-world-cup-2022';
            GetVideo();
        });
        $(document).on('click', '#btn-showguess', function () {
            slugs.slug1 = 'video-du-doan-world-cup-2022';
            GetVideo();
        });

        $('.btn-play-orthers').on('click', function () {
            var url = $(this).data('url');
            window.location = url;
        });
        $('.play-orthers').on('click', function () {
            var url = $(this).data('url');
            window.location = url;
        });
    }

    function init() {
        HandleBtn();
    }

    return {
        init
    }
})(jQuery);

$(document).ready(function () {
    video.init();
});
