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
                if (page === 3 && page < paging.totalPages) {
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
            firstPagePosts = [];
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
        $(window).scroll(function () {
            const scrollHeight = $('#category-grid').height();
            const scrollPos = Math.floor($(window).height() + $(window).scrollTop());
            const bottomHeight = windowWidth > 768 
                            ? $('#category-grid').innerHeight() + $('.header').innerHeight() +  100
                            : $(document).height() - $('.block-extension').innerHeight() -  $('.footer').innerHeight();
            const isBottom = bottomHeight < scrollPos;
            if (isBottom &&  currentscrollHeight < scrollHeight && isLoadData && page < 50) {
                currentscrollHeight = scrollHeight;
                page++;
                GetDataGrid(page);
            }
        });

        $(document).on('click', '.load-more-page', function () {
            $(this).hide();
            page++;
            GetDataGrid(page);
            $('.lds-ellipsis').show();
            currentscrollHeight = $('#category-grid').height();
            isLoadData = true;
        });
    }

    function GetVideoSidebar() {
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

    function GetPostSidebar() {
        if($('#guess-section').length === 0) {
            return false;
        }
        var slug = $('#guess-section').data('slug');
        var limitItem = $('#guess-section').data('limit');
        $.ajax({
            type: 'POST',
            url: '/api/GetCategorySidebar',
            data: {slug, limitItem },
            success: function(res) {
                $('#guess-section').html(res);
            }
        })
    }

    function DetectRowsInElement () {
        const cards = document.querySelector('.group-parent-sub');
        var link = window.location.pathname;
        let row = 0;
        if(cards == null){
            return
        }
        [...cards.children].forEach((el) => {
            var wappedCate = "";
            if (!el.previousElementSibling || el.offsetLeft < el.previousElementSibling.offsetLeft) {
                row++;
            }
            if(row > 1){
                var cate = $(el).find("a");
                wappedCate = '<h1 class="heading-submenu"><a href="'+ cate.attr("href") +'">'+ cate.text() +'</a></h1>';
                $('.list-dropdown-category').append(wappedCate);
                $('.button-dropdown-category').show();
            }
            el.classList.add(`${row > 1 ? 'wrapped' : 'no-wrapped'}`);
             $('.wrapped').hide();
        });
        $('.button-dropdown-category').click(function () {
            $('.list-dropdown-category').toggleClass('toggle-show');
        })

        $(window).click(function (e) {
            var elmClass = e.target.className;
            if (!elmClass !== 'button-dropdown-category' && elmClass !== 'icon-button-dot') {
                $('.list-dropdown-category').removeClass('toggle-show');
            }
        });
        $('.list-dropdown-category').find('a').each(function() {
            $(this).parent().toggleClass('active', $(this).attr('href') == link);
        });
    }
    function init() {
        InfiniteScroll();
        GetVideoSidebar();
        GetPostSidebar();
        DetectRowsInElement();
    }

    return {
        init
    }
})(jQuery);

$(document).ready(function () {
    blog.init();
});
