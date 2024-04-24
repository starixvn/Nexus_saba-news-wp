var euro2024 = (function ($) {
    function GetPollDetail() {
        if($('.euro-poll').length == 0)
        {
            return false;
        }
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

    function GetTopScorerData()
    {
        if($('.euro-top-scorer').length == 0)
        {
            return false;
        }
        $.ajax({
            url: '/Euro2024/GetTopScorers',
            type: 'POST',
        }).done(function (data) {
            $('.euro-top-scorer').html(data);
        })
    }

    function getStandings() {
        if($('.euro-top-standing').length === 0) {
            return false;
        }
        $.ajax({
            url: '/Euro2024/GetStandings',
            type: 'GET',
            beforeSend: function() {
            },
        }).done(function (data) {
            $('.euro-top-standing').html(data);
        })
    }
    function HandleStandingTab () {
        $(document).on('click', '.nav-tab-js li', function(){
            console.log( $(this).attr('data-id'));
            $('.nav-tab-js li').removeClass('active');
            $(this).addClass('active');
            let currentTab = $(this).attr('data-id');
            $('.tab-content .wc-table-body').hide();
            $('.' + currentTab).show();
            return false;
        });
    }
    function init() {
        GetPollDetail();
        UpdatePollData();
        GetTopScorerData();
        getStandings();
        HandleStandingTab();
    }

    return {
        init
    }
})(jQuery);

$(document).ready(function () {
    euro2024.init();
});