var membershipEvent = (function ($) {
    function HandleAction() {
        $('#membership-event').on('submit', function (e) {
            e.preventDefault();
            var luckyNumber = $('#luckyNumber').val();
            if (luckyNumber == "" && luckyNumber < 0) {
                return false;
            }
            $.ajax({
                method: 'POST',
                url: '/membership-event/create',
                data: { luckyNumber },
                success: function (res) {
                    if (res.isSuccessful) {
                        $('#Notice .modal-body').html("Bạn đã chọn số may mắn thành công");
                        $('#Notice').bind('hidden.bs.modal', function () {
                            location.reload();
                        });
                    } else {
                        $('#Notice .modal-body').html(res.message);
                        $('#Notice').unbind('hidden.bs.modal');
                    }
                    
                    $('#Notice').modal('show');
                   
                }
            });
        });
        $('.toggle-mobile-event').click(function () {
            $('.header-nav-event,.wrapper-menu-event').toggleClass('active')
        })
        $('.header-nav-event li a, .wrapper-menu-event').click(function () {
            $('.header-nav-event,.wrapper-menu-event').removeClass('active')
        })
        $('#btn-register').on('click', function () {
            $('#register').modal('show');
        })
        $('#btn-guide').on('click', function () {
            $('#guide').modal('show');
        })

        $(".btn-accept,.click-number-event").click(function () {
            $('html, body').animate({
                scrollTop: $("#field-number-event").offset().top
            }, 500);
        });
        $('.click-list-rigister').click(function () {
            $('html, body').animate({
                scrollTop: $("#list-rigister-event").offset().top
            }, 500);
        })


        $('.btn-accept,.btn-outline-event').click(function () {
            $('#register').modal('hide');
        })

    }
    function init() {
        HandleAction();
    }

    return {
        init
    }

})(jQuery);


$(document).ready(function () {
    membershipEvent.init();
});