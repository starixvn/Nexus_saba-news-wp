var scroll = (function ($) {
    var paging = window.paging;
    var pageSize = paging.pageSize;
    var page = paging.page;
    var currentscrollHeight = 0;
    var isLoadData = true;
    var isVideo = window.isVideo;
    var slug = window.slug;
    var type = window.type;


    function GetDataGrid(page) {
        console.log(page);
        if (page > paging.totalPages) {
            isLoadData = false;
            $('.lds-ellipsis').hide();
            return false;
        }

        var url = '/WorldCup2022/GetNewsGrid';
        var data = { slug, page, pageSize, isVideo };
        if (type === 'story_post') {
            var url = '/WorldCup2022/GetStoryPostGrid';
            var data = { slug, page, pageSize };
        }

        $.ajax({
            url: url,
            type: 'POST',
            data: data
        }).done(function (data) {
            if (!data) {
                isLoadData = false;
                $('.lds-ellipsis').hide();
            } else {
                $('.ajax-load').hide();
                $('#news-grid').append(data);
                if (page === 5 && page < paging.totalPages) {
                    const loadMoreHtml = '<div class="load-more-page"><button>Hiển thị thêm</button></div>';
                    $('#news-grid').append(loadMoreHtml);
                    isLoadData = false;
                    $('.lds-ellipsis').hide();
                }
            }
        })
            .fail(function (jqXHR, ajaxOptions, thrownError) {
                console.log('server not responding...');
            });
    }

    function InfiniteScroll() {
        $(window).on("scroll", function () {
            const scrollHeight = $(document).height();
            const scrollPos = Math.floor($(window).height() + $(window).scrollTop());
            const isBottom = scrollHeight - $('.footer').innerHeight() < scrollPos;
            if (isBottom && currentscrollHeight < scrollHeight && isLoadData && page < 50) {
                page++;
                GetDataGrid(page);
                currentscrollHeight = scrollHeight;
            }
        });

        $(document).on('click', '.load-more-page', function () {
            $(this).hide();
            page++;
            GetDataGrid(page);
            $('.lds-ellipsis').show();
            currentscrollHeight = $(document).height();
            isLoadData = true;
        });
    }

    function init() {
        InfiniteScroll();
    }

    return {
        init
    }
})(jQuery);

$(document).ready(function () {
    console.log(1);
    scroll.init();
});
