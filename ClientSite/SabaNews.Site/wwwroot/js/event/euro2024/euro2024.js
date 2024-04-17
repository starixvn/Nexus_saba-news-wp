var euro2024 = (function ($) {
    function GetPollDetail(questId) {
        $.ajax({
            type:'POST',
            data: {questId: questId, themeId: 1},
            url: "/Post/GetPollData",
            success: function(res) {
                $('.home-poll').html(res);
            }
        })
    };
    function UpdatePollData() {
        $(document).on('click', '.vote-answer', function () {
            var questionId = $(this).data('questid');
            var checked = $('input[name="poll-radio-' + questionId + '"]:checked');
            if (questionId <= 0 || checked.length <= 0) {
                $('#announcement .content').html('Bạn chưa chọn câu trả lời!');
                $('#announcement').modal('show');
                return false;
            }
            var answerId = checked.val();
            var themeId = 1;
            $.ajax({
                type: "POST",
                url: "/Post/VotePoll",
                data: { questId: questionId, answerId, themeId },
                success: function (res) {
                    if (typeof res.isSuccessful == "undefined") {
                        $('.home-poll').html(res);
                    } else {
                        $('#announcement .content').html('Đã có lỗi xảy ra, vui lòng thử lại!');
                        $('#announcement').modal('show');
                    }
                }
            });
        });

        $(document).on('click', '.view-result', function () {
            var questionId = $(this).data('questid');
            $('.poll-main-div-' + questionId).hide();
            $('.poll-result-' + questionId).show();
        });

        $(document).on('click', '.vote-section', function () {
            var questionId = $(this).data('questid');
            $('.poll-main-div-' + questionId).show();
            $('.poll-result-' + questionId).hide();
        });
        $(document).on('click', '.polllabel', function () {
            $(this).parent().parent().find(".poll-inputs").prop('checked', true);
        });

        $(document).on('click', '.poll-element', function () {
            $('.vote-answer').removeClass('disabled');
            $(this).find(".radio-input").prop('checked', true);
        })


        $(document).on('change', '.radio-input', function () {
            $('.poll-element').removeClass('active');
            $(this).parent().addClass('active');
            $('.vote-answer').removeClass('disabled');
        });
    }
    function init() {
        GetPollDetail();
        UpdatePollData();
    }

    return {
        init
    }
})(jQuery);

$(document).ready(function () {
    euro2024.init();
});