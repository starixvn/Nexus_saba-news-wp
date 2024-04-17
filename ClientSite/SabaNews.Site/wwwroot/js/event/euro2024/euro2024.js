var euro2024 = (function ($) {
    function GetPollDetail() {
        $.ajax({
            type:'POST',
            url: "/Euro2024/GetPollData",
            success: function(res) {
                $('.euro-poll').html(res);
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
                url: "/Euro2024/VotePoll",
                data: { questId: questionId, answerId, themeId },
                success: function (res) {
                    if (typeof res.isSuccessful == "undefined") {
                        $('.euro-poll').html(res);
                    } else {
                        $('#announcement .content').html('Đã có lỗi xảy ra, vui lòng thử lại!');
                        $('#announcement').modal('show');
                    }
                }
            });
        });

        $(document).on('click', '.view-result', function () {
            $('.poll-main').hide();
            $('.poll-result').show();
        });

        $(document).on('click', '.vote-section', function () {
            $('.poll-main').show();
            $('.poll-result').hide();
        });
        $(document).on('click', '.polllabel', function () {
            $(this).parent().parent().find(".poll-inputs").prop('checked', true);
        });

        $(document).on('click', '.poll-element', function () {
            console.log(1);
            $('.poll-element').removeClass('checked');
            $(this).addClass('checked');
            $('.vote-answer').removeClass('disabled');
            $(this).find(".radio-input").prop('checked', true);
        })
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