
//load comment section when load page
var postID = $("input[name=postID]").val();
$.ajax({
    type: "GET",
    url: "/comments/paging",
    data: { postID: postID, page: 1 },
    success: function (response) {
        $('#comment').html(response);
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

//show children comment
$(document).on('click', '.view_all_reply', function () {
    var self = $(this)
    var postID = self.data('postid');
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

//comment paging
$(document).on('click', '.comment-pagination', function () {
    var self = $(this);
    var postID = $("input[name=postID]").val();
    var page = self.data("page");
    $.ajax({
        type: "GET",
        url: "/comments/paging",
        data: { postID: postID, page: page },
        success: function (response) {
            $('#comment').html(response);
            $('html,body').animate({
                scrollTop: $("#comment").offset().top
            }, 'slow');
        }
    });
});

//validate comment value
$(document).on('keypress', '.type_page', function (event) {
    var keycode = (event.keyCode ? event.keyCode : event.which);
    if (keycode === 13) {
        var current = parseInt($(this).parent().data('current'));
        var total = parseInt($(this).parent().data('total'));
        var value = $(this).val();
        if (Math.floor(value) == value && $.isNumeric(value) && value != current && value >= 1 && value <= total) {
            var postID = $("input[name=postID]").val();
            var page = value;
            $.ajax({
                type: "GET",
                url: "/comments/paging",
                data: { postID: postID, page: page },
                success: function (response) {
                    $('#comment').html(response);
                    $('html,body').animate({
                        scrollTop: $("#comment").offset().top
                    }, 'slow');
                }
            });
        }
    }
});

//add comment
$(document).on("click", '.add_comment', function () {
    var self = $(this);
    var commentContent = self.parent().find("textarea[name=comment_value]").val();
    if ($.trim(commentContent) === "") {
        self.parent().find("textarea[name=comment_value]").focus();
        return false;
    }
    var postID = $("input[name=postID]").val();

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
    var repForm = self.parent().parent();
    var postID = $("input[name=postID]").val();
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

//show reply form process
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

//like process
$(document).on('click', '.link_thich', function () {
    var self = $(this);
    var cmtID = $(this).data('commentid');
    var total_like = self.find('.total_like').html();
    if (total_like === '') {
        total_like = 1;
    } else {
        total_like = parseInt(total_like) + 1;
    }
    $.ajax({
        type: "POST",
        url: "/comments/like",
        data: { commentID: cmtID },
        dataType: 'json',
        success: function (response) {
            if (response.isSuccessful) {
                self.find('.total_like').html(total_like);
                self.addClass('unlike');
            }
        }
    });
});

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
    var html = '';
    html += '<div class="comment_reply_wrapper ' + type + '">';
    html += '<form>';
    html += '<div class="comment-content-box">';
    html += '<textarea rows="3" name="comment_value" id="comment_value" placeholder="comment"></textarea>';
    html += '<span class="note-text-count" style="transform-origin: 0px 0px; opacity: 1; transform: scale(1, 1);"><span class="counter_word">0</span> / 1500 ký tự</span>';
    html += '</div>';
    html += '<input type="button" name="add_comment" class="add_sub_comment" value="Gửi" />';
    html += '</form>';
    html += '</div>';
    return html;
};