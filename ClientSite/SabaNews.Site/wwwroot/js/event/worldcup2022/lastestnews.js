var lastestnews = (function ($) {
    var paging = window.paging;
    var pageSize = paging.pageSize;
    var page = paging.page;
    var currentscrollHeight = 0;
    var isLoadData = true;
    var numRow = 1;
    var isMobile = false;
    var total = 0;

    function GetDataGrid(page) {
        if (page > paging.totalPages) {
            isLoadData = false;
            $('.lds-ellipsis').hide();
            return false;
        }

        $.ajax({
            url: '/WorldCup2022/GetNewsGrid',
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
    function showStandings() {
        if (isMobile === "False") {
            for (var i = 1; i <= total; i++) {
                if (i != numRow) {
                    $('#standing-' + i).hide();
                }
                else {
                    $('#standing-' + i).show();
                }
            }
        }
    }

    function getStandings() {
        $.ajax({
            url: '/WorldCup2022/GetStandings',
            type: 'GET'
        }).done(function (data) {
            $('#standingWC').html(data);

            total = $(".btn-next-standing").data('total');
            isMobile = $("#standingWC").data('ismobile');
            showStandings();
           
            $('.btn-back-standing').on('click', function () {
                numRow--;
                if (numRow >= 1) {
                    showStandings();
                }
                else {
                    numRow++;
                }
            });

            $('.btn-next-standing').on('click', function () {
                numRow++;
                var total = $(this).data("total");
                if (numRow < total) {
                    showStandings();
                }
                else {
                    numRow--;
                }
            });
        })
    }

    function getTopScorers() {
        $.ajax({
            url: '/WorldCup2022/GetTopScorers',
            type: 'GET',
        }).done(function (data) {
            $('#topScorersWC').html(data);
        })
    }



    function init() {
        InfiniteScroll();
        getStandings();
        getTopScorers();
    }

    return {
        init
    }
})(jQuery);

$(document).ready(function () {
    lastestnews.init();
});
