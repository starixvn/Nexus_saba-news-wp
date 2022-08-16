var video = (function ($) {
    var paging = window.paging;
    var pageSize = paging.pageSize;
    var page = paging.page;
    var currentscrollHeight = 0;
    var isLoadData = true;
    var slugs = {
        slug1: 'video-world-cup-2022'
    };

    function GetDataGrid(page) {
        if (page > paging.totalPages) {
            isLoadData = false;
            $('.lds-ellipsis').hide();
            return false;
        }

        $.ajax({
            url: '/WorldCup2022/GetVideoGrid',
            type: 'POST',
            data: { slugs, page, pageSize }
        }).done(function (data) {
            if (!data) {
                isLoadData = false;
                $('.lds-ellipsis').hide();
            } else {
                $('.ajax-load').hide();
                $('#video-grid').append(data);
                if (page === 5 && page < paging.totalPages) {
                    const loadMoreHtml = '<div class="load-more-page"><button>Hiển thị thêm</button></div>';
                    $('#video-grid').append(loadMoreHtml);
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

    function HandleBtn() {
    }

    function init() {
        InfiniteScroll();
        HandleBtn();
    }

    return {
        init
    }
})(jQuery);

$(document).ready(function () {
    video.init();
});
