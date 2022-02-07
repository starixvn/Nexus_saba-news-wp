var membershipEvent = (function ($) {
    var isLogin = checkLogin.toLowerCase() === 'true';
    function HandleSubmitNumber() {
        $('#membership-event-form').on('submit', function (e) {
            e.preventDefault();
            var number1 = $('#number1').val();
            var number2 = $('#number2').val();
            var number3 = $('#number3').val();
            var isValid = ValidateFormEnterLuckynumber(number1, number2, number3);
            if (!isValid) {
                return false;
            }
            var luckyNumber = number1 + number2 + number3;
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
            var number1 = $('#number1').val();
            var number2 = $('#number2').val();
            var number3 = $('#number3').val();
            var isValid = ValidateFormEnterLuckynumber(number1, number2, number3);
            if (!isValid) {
                return false;
            }
            $('#register').modal('show');
        });
        $('#register .btn-accept').on('click', function () {
            $('#membership-event-form').submit();
        });
       

        $('.input-field-event').keypress(function () {
            var maxlength = $(this).attr("maxlength");
            this.value = this.value.substring(0, maxlength - 1);
        })
    }

    function ValidateFormEnterLuckynumber(number1, number2, number3) {
        var luckyNumber = number1 + number2 + number3;
        if (!isLogin) {
            $('#notice .modal-body  .text-event-modal').html("Vui lòng đăng nhập để tham gia sự kiện");
            $('#notice').unbind('hidden.bs.modal');
            $('#notice').modal('show');
            return false;
        }
        if (!$.isNumeric(number1) || !$.isNumeric(number2) || !$.isNumeric(number3) || !$.isNumeric(luckyNumber)) {
            $('#notice .modal-body  .text-event-modal').html("Vui lòng nhập số may mắn là số!");
            $('#notice').unbind('hidden.bs.modal');
            $('#notice').modal('show');
            return false;
        }
        if (luckyNumber < 0 && luckyNumber > 999) {
            $('#notice .modal-body  .text-event-modal').html("Số may mắn phải là số nằm trong khoảng từ 000 đến 999");
            $('#notice').unbind('hidden.bs.modal');
            $('#notice').modal('show');
            return false;
        }
        return true;
    }

    function HandleUI() {
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
        HandleUI();
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