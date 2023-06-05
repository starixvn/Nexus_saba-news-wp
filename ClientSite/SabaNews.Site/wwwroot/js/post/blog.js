var blog = (function ($) {
    var paging = window.paging;
    var pageSize = paging.pageSize;
    var page = paging.page;
    var slug = window.slug;
    var isVideo = window.isVideo;
    var keyword = window.keyword;
    var type = window.type;
    var currentscrollHeight = 0;
    var isLoadData = true;
    var windowWidth = window.innerWidth;
    var firstPagePosts = $('#category-grid .page-1').map(function () {
        return this.value;
    }).get();

    function GetDataGrid(page) {
        if (page > paging.totalPages) {
            isLoadData = false;
            $('.lds-ellipsis').hide();
            return false;
        }

        var url = '/api/post/category-posts';
        var data = { slug, page, pageSize, type, isVideo, firstPagePosts };
        if (type === "Search" || type === "Tag" || type === "Author") {
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
            $('.banner-category-scroll').html($('.banner-scroll').html());
        })
        .fail(function (jqXHR, ajaxOptions, thrownError) {
            console.log('server not responding...');
        });
    }

    function GeneralBannerHtml(src, link) {
        var html = "";
        if (src !== "") {
            link = link === "" ? "javascript:;" : link;
            html += '<a href="' + link + '">';
            html += '<img loading="lazy test" src="' + src + '" asp-append-version="true" class="img-res">';
            html += '</a>';
        }
        return html;
    }

    function InfiniteScroll() {
        $(window).on("scroll", function () {
            const scrollHeight = $(document).height();
            const scrollPos = Math.floor($(window).height() + $(window).scrollTop());
            const bottomHeight = windowWidth > 768 
                            ? $('#category-grid').innerHeight() + $('.header').innerHeight() +  100
                            : scrollHeight - $('.block-extension').innerHeight() -  $('.footer').innerHeight();
            const isBottom = bottomHeight < scrollPos;
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

    function GetDataSidebar() {
        if($('.video-sidebar').length === 0) {
            return false;
        }

        $.ajax({
            type: 'POST',
            url: '/api/GetVideo',
            success: function(res) {
                $('.video-sidebar').html(res);
            }
        })
    }



    function init() {
        InfiniteScroll();
        GetDataSidebar();
    }

    return {
        init
    }
})(jQuery);

$(document).ready(function () {
    blog.init();
});
