var membershipEvent = (function ($) {
    function HandleAction() {
        $('#membership-event').on('submit', function (e) {
            e.preventDefault();
            var luckyNumber = $('#luckyNumber').val();
            if (luckyNumber == "") {
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