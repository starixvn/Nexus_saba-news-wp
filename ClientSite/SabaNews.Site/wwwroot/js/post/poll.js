var poll = (function ($) {
    var questionId = 0;
    var answerId = 0;
    function CheckAndInsertPoll() {
        var pollKeyword = 'wpdevart_pol'
        var re = new RegExp(pollKeyword, 'gi');
        var postContent = $('.content').html();
        var results = new Array();
        while (re.exec(postContent)) {
            results.push(re.lastIndex);
        }
        $.each(results, function (index, value) {
            var endPoll = postContent.indexOf("]", value);
            var str = postContent.substring(value - pollKeyword.length - 1, endPoll + 1);
            var attrs = str.split('"');
            if (attrs.length >= 5) {
                var attrClass = typeof attrs[5] != "undefined" ? attrs[5] : "";
                $.ajax({
                    type: "POST",
                    url: "Post/GetPollData",
                    data: { questID: attrs[1], themeID: attrs[3] },
                    success: function (res) {
                        var content = $('.content').html();
                        var html = '<div class="pollevent ' + attrClass + ' question_' + attrs[1] + '" data-theme="' + attrs[3] + '" data-class="' + attrClass + '">';
                        html += res;
                        html += "</div>";
                        $('.content').html(content.replace(str, html));
                    }
                })
            }
        });
    }

    function UpdatePollData() {
        $(document).on('click', '.poll_inputs', function () {
            answerId = $(this).val();
        });
        $(document).on('click', '.vote_answer', function () {
            questionId = $(this).data('questid');
            var checked = $(this).parent().parent().find('.poll_inputs:checked');
            if (questionId <= 0 || answerId <= 0 || checked.length <= 0) {
                $('#announcement .content').html('Bạn chưa chọn câu trả lời!');
                $('#announcement').modal('show');
                return false;
            }
            console.log(questionId, answerId);
            var themeId = $('.question_' + questionId).data('theme');
            $.ajax({
                type: "POST",
                url: "Post/VotePoll",
                data: { questId: questionId, answerId, themeId},
                success: function (res) {
                    if (typeof res.isSuccessful == "undefined") {
                        $('.question_' + questionId).html(res);
                    } 
                }
            });
        });

        $(document).on('click', '.view_result', function () {
            questionId = $(this).data('questid');
            $('.poll_main_div_' + questionId).hide();
            $('.poll_result_' + questionId).show();
        });

        $(document).on('click', '.vote_section', function () {
            questionId = $(this).data('questid');
            $('.poll_main_div_' + questionId).show();
            $('.poll_result_' + questionId).hide();
        });
        $(document).on('click', '.polllabel', function () {
            $(this).parent().parent().find(".poll_inputs").prop('checked', true);
        });
    }
    function init() {
        CheckAndInsertPoll();
        UpdatePollData();
    }

    return {
        init
    }
    
})(jQuery);

$(document).ready(function () {
    poll.init();
});

