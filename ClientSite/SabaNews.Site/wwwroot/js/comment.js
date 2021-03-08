var comment = (function ($) {
    var postID = $('input[name=postID]').val();
    var filterType = 'like';

    function RenderListComment(postID, page, filterType) {
        $.ajax({
            type: "GET",
            url: "/comments",
            data: { postID: postID, page: page, filterType: filterType },
            success: function (response) {
                $('#comment').html(response);
                HanldePaging();
                HandleFilter();
                HandleShowChildrenComment();
            }
        });
    };

    function HanldePaging() {
        $('.comment-pagination').on('click', function () {
            var self = $(this);
            var page = self.data("page");
            RenderListComment(postID, page, filterType);
            $('html,body').animate({
                scrollTop: $("#comment").offset().top
            }, 'slow');
        });

        $('.type_page').on('keypress', function (event) {
            var keycode = (event.keyCode ? event.keyCode : event.which);
            if (keycode === 13) {
                var current = parseInt($(this).parent().data('current'));
                var total = parseInt($(this).parent().data('total'));
                var value = $(this).val();
                if (Math.floor(value) == value && $.isNumeric(value) && value != current && value >= 1 && value <= total) {
                    var page = value;
                    RenderListComment(postID, page, filterType);
                    $('html,body').animate({
                        scrollTop: $("#comment").offset().top
                    }, 'slow');
                }
            }
        });
    };

    function HandleFilter() {
        $('.filter_comment a').on('click', function () {
            var self = $(this);
            filterType = self.data('type');
            if (!self.hasClass('active')) {
                RenderListComment(postID, 1, filterType)
            }
        });
    };

    function HandleShowChildrenComment() {
        $('.view_all_reply').on('click', function () {
            var self = $(this)
            var commentID = self.data('cmtid');
            self.parent().parent().find('.sub_comment').html('');
            $.ajax({
                type: "GET",
                url: "/comments/getchildren",
                data: { postID: postID, commentID: commentID },
                dataType: "json",
                success: function (response) {
                    self.parent().hide();
                    var data = response.data;
                    var html = '';
                    $.each(data, function (i, val) {
                        html += RenderSubComment(val);
                    });
                    self.parent().parent().find('.sub_comment').append(html);
                }
            });
        });
    };

    function HandleCreateComment() {
        //show sub reply form process
        $(document).on('click', '.link_reply', function () {
            var checkSubCmt = $(this).parent().parent().parent();
            var Subcmt = '';
            if (checkSubCmt.hasClass('comment_item')) {

                Subcmt = checkSubCmt.find('.sub_comment'); $(this).parent();
                if (Subcmt.find('.comment_reply_wrapper.rep_parent').length === 0) {
                    $('.comment_reply_wrapper').remove();
                    Subcmt.prepend(RenderReplyForm('rep_parent'));

                } else {
                    if (Subcmt.find('.comment_reply_wrapper').is(':visible')) {
                        Subcmt.find('.comment_reply_wrapper').hide();
                    } else {
                        Subcmt.find('.comment_reply_wrapper').show();
                    }
                }
            } else {
                Subcmt = $(this).parent();
                if (Subcmt.find('.comment_reply_wrapper').length === 0) {
                    $('.comment_reply_wrapper').remove();
                    Subcmt.append(RenderReplyForm);
                } else {
                    if (Subcmt.find('.comment_reply_wrapper').is(':visible')) {
                        Subcmt.find('.comment_reply_wrapper').hide();
                    } else {
                        Subcmt.find('.comment_reply_wrapper').show();
                    }
                }
            }
        });

        //show limit of comment value
        $(document).on('keyup', 'textarea[name=comment_value]', function () {
            var valLength = $(this).val().length;
            var noteTextCount = $(this).parent().find('.note-text-count');
            if (valLength > 0) {
                noteTextCount.show();
                noteTextCount.find('.counter_word').html(valLength);
            } else {
                noteTextCount.hide();
                noteTextCount.find('.counter_word').html(0);
            }
        });

        $(document).on('click', 'textarea[name=comment_value]', function () {
            var isLogin = (data.isLogin.toLowerCase() === 'true');
            if (!isLogin) {
                auth.GetModalLogin();
            }
        });

        $(document).on('click', '.add_comment', function () {
            var self = $(this);
            var commentContent = self.parent().find("textarea[name=comment_value]").val();
            if ($.trim(commentContent) === "") {
                self.parent().find("textarea[name=comment_value]").focus();
                return false;
            }
            if (commentContent.length >= 1500) {
                return false;
            }

            commentContent = commentContent.replace(/\n\r?/g, '<br />');

            $.ajax({
                type: "POST",
                url: "/comments/create",
                data: { postID: postID, parent: 0, commentContent: commentContent },
                success: function (response) {
                    if (response.isSuccessful) {
                        var comment = response.comment;
                        var html = "";
                        var liked = comment.isLiked ? 'unlike' : '';
                        var likeCount = comment.commentLikeCount > 0 ? comment.commentLikeCount : "";
                        html += '<div class="comment_item">';
                        html += '<div class="content_comment">';
                        html += '<p class="full_content"><span class="nickname">' + comment.commentAuthor + '</span> ' + comment.commentContent + '</p>';
                        html += '<p class="block_like_web width_common">';
                        html += '<a href="javascript:;" class="link_thich ' + liked + '" data-commentid="' + comment.commentID + '">';
                        html += '<span class="total_like" href="javascript:;">' + likeCount + '</span>';
                        html += '<i class="ic ic-like" ></i >'
                        html += '</a > ';
                        html += '<a href="javascript:;" class="link_reply" data-id="' + comment.commentID + '" data-parent="' + comment.commentID + '">Trả lời</a>';
                        html += '<span class="time">1 giây trước</span>';
                        html += '</p>';
                        html += '</div>';
                        html += '<div class="sub_comment"></div>';
                        html += '</div>';
                        $('.list_comments').prepend(html);
                        self.parent().find("textarea[name=comment_value]").val('');
                        self.parent().find('.comment-content-box').find('.note-text-count').hide();
                    }
                }
            });
        });

        //add sub comment
        $(document).on('click', '.add_sub_comment', function () {
            var self = $(this);
            var commentContent = self.parent().find("textarea[name=comment_value]").val();
            if ($.trim(commentContent) === "") {
                self.parent().find("textarea[name=comment_value]").focus();
                return false;
            }
            commentContent = commentContent.replace(/\n\r?/g, '<br />');
            var repForm = self.parent().parent();
            var parent = 0;
            var isParent = repForm.hasClass('rep_parent');
            if (isParent) {
                parent = repForm.parent().parent().find('.content_comment').find('.link_reply').data('parent');
            } else {
                parent = repForm.parent().find('.link_reply').data('parent');
            }
            $.ajax({
                type: "POST",
                url: "/comments/create",
                data: { postID: postID, parent: parent, commentContent: commentContent },
                success: function (response) {
                    if (response.isSuccessful) {
                        var html = RenderSubComment(response.comment);
                        if (isParent) {
                            repForm.parent().append(html);
                        } else {
                            repForm.parent().parent().parent().append(html);
                        }
                        $('.comment_reply_wrapper').remove();
                        self.parent().find("textarea[name=comment_value]").val('');
                    }
                }
            });
        });
    };

    function HandleLikeComment() {
        $(document).on('click', '.link_thich', function () {
            var isLogin = (data.isLogin.toLowerCase() === 'true');
            if (!isLogin) {
                $('#requestLogin').modal('show');
                return false;
            }
            var self = $(this);
            var cmtID = $(this).data('commentid');
            var total_like = self.find('.total_like').html();
            var type = 'like';
            if ($(this).hasClass('unlike')) {
                type = 'unlike';
                total_like = parseInt(total_like) - 1;
            } else {
                if (total_like === '') {
                    total_like = 1;
                } else {
                    total_like = parseInt(total_like) + 1;
                }
            }

            $.ajax({
                type: "POST",
                url: "/comments/like",
                data: { commentID: cmtID, type: type },
                dataType: 'json',
                success: function (response) {
                    if (response.isSuccessful) {
                        self.find('.total_like').html(total_like);
                        if (type === 'like') {
                            self.addClass('unlike');
                        } else {
                            self.removeClass('unlike');
                        }
                    }
                }
            });
        });
    };

    function RenderSubComment(val) {
        var html = "";
        var liked = val.isLiked ? 'unlike' : '';
        var commentTime = typeof val.commentTime === "undefined" ? "1 giây trước" : val.commentTime;
        var likeCount = val.commentLikeCount > 0 ? val.commentLikeCount : "";
        html += '<div class="sub_comment_item comment_item">';
        html += '<p class="full_content"><span class="nickname">' + val.commentAuthor + '</span> ' + val.commentContent + '</p>';
        html += '<p class="block_like_web width_common">';
        html += '<a href="javascript:;" class="link_thich ' + liked + '" data-commentid="' + val.commentID + '">';
        html += '<span class="total_like" href="javascript:;">' + likeCount + '</span>';
        html += '<i class="ic ic-like" ></i >'
        html += '</a > ';
        html += '<a href="javascript:;" class="link_reply" data-id="' + val.commentID + '" data-parent="' + val.commentParent + '">Trả lời</a>';
        html += '<span class="time">' + commentTime + '</span>';
        html += '</p>';
        html += '</div>';
        return html;
    };

    function RenderReplyForm(type = 0) {
        var isLogin = (data.isLogin.toLowerCase() === 'true');
        var html = '';
        var replySubmit = '';
        if (isLogin) {
            replySubmit = '<input type="button" name="add_comment" class="add_sub_comment" value="Gửi" />';
        }
        html += '<div class="comment_reply_wrapper ' + type + '">';
        html += '<form>';
        html += '<div class="comment-content-box">';
        html += '<textarea rows="3" name="comment_value" id="comment_value" placeholder=" Ý kiến của bạn"></textarea>';
        html += '<span class="note-text-count" style="transform-origin: 0px 0px; opacity: 1; transform: scale(1, 1);"><span class="counter_word">0</span> / 1500 ký tự</span>';
        html += '</div>';
        html += replySubmit
        html += '</form>';
        html += '</div>';
        return html;
    };

    function init() {
        RenderListComment(postID, 1, filterType);
        HandleCreateComment();
        HandleLikeComment();
    }

    return {
        init
    }
})(jQuery);

$(document).ready(function () {
    comment.init();
});
