var poll = (function ($) {
    var questionId = 0;
    var answerId = 0;
    function CheckAndInsertPoll() {
        var poolKeyword = 'wpdevart_pol'
        var re = new RegExp(poolKeyword, 'gi');
        var postContent = $('.content').html();
        var results = new Array();
        while (re.exec(postContent)) {
            results.push(re.lastIndex);
        }
        $.each(results, function (index, value) {
            var test = postContent.indexOf("]", value);
            var str = postContent.substring(value - poolKeyword.length - 1, test + 1);
            var attrs = str.split('"');
            if (attrs.length >= 5) {
                $.ajax({
                    type: "POST",
                    url: "Post/GetPollData",
                    data: { questID: attrs[1], themeID: attrs[3], attrClass: attrs[5] },
                    success: function (res) {
                        var html = '<div class="question_' + attrs[1] + '" data-theme="' + attrs[3] + '" data-class="' + attrs[5] + '">';
                        html += res;
                        html += "</div>";
                        postContent = postContent.replace(str, html);
                        $('.content').html(postContent);
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
            if (questionId == null || answerId == null) {
                return false;
            }
            var themeId = $('.question_' + questionId).data('theme');
            var attrClass = $('.question_' + questionId).data('class');
            $.ajax({
                type: "POST",
                url: "Post/VotePollSurvey",
                data: { questId: questionId, answerId, themeId, attrClass},
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

