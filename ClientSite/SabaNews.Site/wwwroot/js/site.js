var setUp = {
    HandleIframe: function () {
        //Desktop & Mobile
        $('.match-schedule iframe').css('width', '100%');
        $('.play_video iframe').attr('allow', 'autoplay');
        $('.btn-play-video').on('click', function () {
            $('.play_video').html('');
            $('.img-item').show();
            $('.swiper-slide').removeClass("active");
            $('.slide-video .card-item').first().addClass("active");
            var iframe = $(this).parent().parent().find("input[name=iframe_value]").val();
            var found = $.parseHTML(iframe);
            if (typeof found[2] === "undefined") { return false; }
            iframe = $(($.parseHTML(found[2].outerHTML)));
            iframe.attr('allow', 'autoplay');
            var src = iframe.attr('src');
            var autoplay = src.indexOf("autoplay");
            if (autoplay > -1) {
                src = src.replace("autoplay=0", "autoplay=1");
                src = src.replace("autoplay=1", "autoplay=1");
            }
            else {
                var symbol = src.indexOf("?") > -1 ? "&" : "?";
                src = src + symbol + "autoplay=1"
            }
                
            iframe.attr('src', src );

            $(this).parent().parent().find('.play_video').html(iframe);
            $(this).parent().hide();
            $(this).parent().addClass('active');
        });
        //Desktop
        $('.image-slider').on('click', function () {
            var id = $(this).data('id');
            $('.swiper-slide').hide();
            var slide = $('#slide-' + id);
            slide.show();
            slide.find('.img-item').hide();
            $('.slide-video .card-item').removeClass("active");
            $(this).parent().addClass("active");

            $('.play_video').html('');
            $('.swiper-slide').removeClass("active");
            var iframe = slide.find("input[name=iframe_value]").val();
            var found = $.parseHTML(iframe);
            if (typeof found[2] === "undefined") { return false; }
            iframe = $(($.parseHTML(found[2].outerHTML)));
            iframe.attr('allow', 'autoplay');
            var src = iframe.attr('src');
            var autoplay = src.indexOf("autoplay");
            if (autoplay > -1) {
                src = src.replace("autoplay=0", "autoplay=1");
                src = src.replace("autoplay=1", "autoplay=1");
            }
            else {
                var symbol = src.indexOf("?") > -1 ? "&" : "?";
                src = src + symbol + "autoplay=1"
            }

            iframe.attr('src', src);
            slide.find('.play_video').html(iframe);
           
        });
    },
    //Desktop & Mobile
    HandleSearch: function () {
        $('.search-input button').on('click', function (e) {
            var keyword = $('input[name=keyword]').val();
            if ($.trim(keyword) === '') {
                e.preventDefault();
                $('input[name=keyword]').focus();
            }
        });
    },
    //Desktop & Mobile
    HandleUI: function () {
        if ($(window).innerWidth() < 768) {
            $('.block-guess .block__content .card-vertical').addClass("card-horizontal").removeClass("card-vertical");
        }
    },
    HandleComment: function () {
        //<div class="sub_comment">
        //    <div class="sub_comment_item comment_item">
        //        <p class="full_content"><span class="nickname">Hank</span> test comment</p>
        //        <p class="block_like_web width_common">
        //            <a href="javascript:;" class="link_thich">like</a>
        //            <a href="javascript:;" class="link_reply">Trả lời</a>
        //            <span class="time">1 giờ trước</span>
        //        </p>
        //    </div>
        //</div>
        $(document).on('click', '.view_all_reply', function () {
            var self = $(this)
            var postID = self.data('postid');
            var commentID = self.data('cmtid');
            var html = '<div class="sub_comment">';
            $.ajax({
                type: "GET",
                url: "/comments/getchildren",
                data: { postID: postID, commentID: commentID },
                dataType: "json",
                success: function (response) {
                    self.parent().hide();
                    var data = response.data;
                    $.each(data, function (i, val) {
                        html += '<div class="sub_comment_item comment_item">';
                        html += '<p class="full_content"><span class="nickname">' + val.commentAuthor + '</span> ' + val.commentContent + '</p>';
                        html += '<p class="block_like_web width_common">';
                        html += '<a href="javascript:;" class="link_thich">like</a>';
                        html += '<a href="javascript:;" class="link_reply">Trả lời</a>';
                        html += '<span class="time">' + val.commentTime + '</span>';
                        html += '</p>';
                        html += '</div>';
                    });
                    html += '</div>';
                    self.parent().parent().find('.content_comment').append(html);

                }
            });
        });

        $(document).on("click", ".comment-pagination", function () {
            var self = $(this);
            var postID = $("input[name=postID]").val();
            var page = self.data("page");
            $.ajax({
                type: "GET",
                url: "/comments/paging",
                data: { postID: postID, page: page },
                success: function (response) {
                    $('.list_comments').html(response);
                }
            });
        });

        $('.add_comment').on("click", function () {
            var self = $(this);
            var comment_value = self.parent().find("textarea[name=comment_value]").val();
            if ($.trim(comment_value) === "") {
                self.parent().find("textarea[name=comment_value]").focus();
                return false;
            }

        });
    },
    
    init: function () {
        this.HandleIframe();
        this.HandleSearch();
        this.HandleUI();
        this.HandleComment();
    }
}

$(document).ready(function () {
    setUp.init();
})