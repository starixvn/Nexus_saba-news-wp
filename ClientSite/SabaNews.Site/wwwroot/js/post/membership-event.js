var membershipEvent = (function ($) {
    function HandleSubmitNumber() {
        $('#membership-event-form').on('submit', function (e) {
            e.preventDefault();
            var number1 = $('#number1').val();
            var number2 = $('#number2').val();
            var number3 = $('#number3').val();
            if (!$.isNumeric(number1) || !$.isNumeric(number2) || !$.isNumeric(number3)) {
                console.log("invalid number");
                return false;
            }
            var luckyNumber = number1 + number2 + number3;
            if (!$.isNumeric(luckyNumber)) {
                console.log("luckynumber không phải số");
            }
            if (luckyNumber < 0 && luckyNumber > 999) {
                console.log("số quá nhỏ hoặc quá lớn");
                return false;
            }
            $.ajax({
                method: 'POST',
                url: '/membership-event/create',
                data: { luckyNumber },
                success: function (res) {
                    if (res.isSuccessful) {
                        $('#notice .modal-body .text-event-modal').html("Bạn đã chọn số may mắn thành công");
                        $('#notice').bind('hidden.bs.modal', function () {
                            location.reload();
                        });
                    } else {
                        $('#notice .modal-body  .text-event-modal').html(res.message);
                        $('#notice').unbind('hidden.bs.modal');
                    }
                    
                    $('#notice').modal('show');
                   
                }
            });
        });

        $('.btn-submit-number').on('click', function () {
            $('#register').modal('show');
        });
        $('#register .btn-accept').on('click', function () {
            $('#membership-event-form').submit();
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

        $('.header-control ul li a').click(function () {
            $('.header-control ul li a').removeClass("active");
            $(this).addClass("active");
        });

        $('.btn-accept,.btn-outline-event').click(function () {
            $('#register').modal('hide');
        })

        $('.input-field-event').keypress(function () {
            var maxlength = $(this).attr("maxlength");
            this.value = this.value.substring(0, maxlength - 1);
            
            
        })
    }

    var page = 1;
    var pageSize = 10;
    function GetDataMembershipEvent() {
        $.ajax({
            type: "GET",
            url: "/membership-event/getdata",
            data: { page, pageSize },
            success: function (res) {
                $('.data-grid').html(res);
                initPaging();
            }
        });
    }

    function initPaging() {
        $('.paging').on('click', function () {
            page = $(this).data('page');
            GetDataMembershipEvent();
        });
    }

    function init() {
        HandleSubmitNumber();
        GetDataMembershipEvent();
    }

    return {
        init
    }

})(jQuery);


$(document).ready(function () {
    membershipEvent.init();
});