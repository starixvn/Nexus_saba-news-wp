var blog = (function ($) {
    var paging = window.paging;
    var pageSize = paging.pageSize;
    var page = paging.page;
    var slugs = window.slugs;
    var isVideo = window.isVideo;
    var keyword = window.keyword;
    var type = window.type;
    var currentscrollHeight = 0;
    var isLoadData = true;
    var windowWidth = window.innerWidth;
    
    function GetDataGrid(page) {
        if (page > paging.totalPages) {
            isLoadData = false;
            $('.lds-ellipsis').hide();
            return false;
        }
        
        var url = '/api/post/category-posts';
        var data = { slugs, page, pageSize, type, isVideo };
        if (type === "Search" || type === "Tag") {
            url = '/api/post/type-posts';
            data = { keyword, type, page, pageSize }
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
            $('#category-grid').append(data);
            if (page === 5 && page < paging.totalPages) {
                
                const loadMoreHtml = '<div class="load-more-page"><button>Hiển thị thêm</button></div>';
                if (isVideo) {
                    $('.block-main').append(loadMoreHtml);
                } else {
                    $('#category-grid').append(loadMoreHtml);
                }
                
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
            const mobileScroll = windowWidth > 768 ? 0 : $('.sidebar-posts').innerHeight();
            const isBottom = scrollHeight - $('.footer').innerHeight() - mobileScroll < scrollPos;
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
    blog.init();
});
