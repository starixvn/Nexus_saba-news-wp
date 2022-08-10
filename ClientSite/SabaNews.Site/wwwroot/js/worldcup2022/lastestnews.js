var lastestnews = (function ($) {
    var paging = window.paging;
    var pageSize = paging.pageSize;
    var page = paging.page;
    var currentscrollHeight = 0;
    var isLoadData = true;
    var numRow = 0;

    function GetDataGrid(page) {
        if (page > paging.totalPages) {
            isLoadData = false;
            $('.lds-ellipsis').hide();
            return false;
        }

        $.ajax({
            url: '/world-cup-2022/news-posts',
            type: 'POST',
            data: { page, pageSize }
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

    function getStandings() {
        $.ajax({
            url: '/world-cup-2022/get-standing',
            type: 'POST',
            data: { numRow }
        }).done(function (data) {
            $('#standingWC').html(data);

            $('#btn-back-standing').on('click', function () {
                numRow--;
                if (numRow >= 0) {
                    getStandings();
                }
                else {
                    numRow++;
                }
            });

            $('#btn-next-standing').on('click', function () {
                numRow++;
                var total = $(this).data("total");
                if (numRow < total) {
                    getStandings();
                }
                else {
                    numRow--;
                }
            });
        })
    }

    function init() {
        InfiniteScroll();
        getStandings();
    }

    return {
        init
    }
})(jQuery);

$(document).ready(function () {
    lastestnews.init();
});
