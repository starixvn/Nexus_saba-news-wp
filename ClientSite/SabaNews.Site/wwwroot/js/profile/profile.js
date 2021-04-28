var profile = (function ($) {
    var postID = $('input[name=postID]').val();
    var isLogin = ($('input[name=isLogin]').val()).toLowerCase() === 'true';
    function save_post() {
        $('.save_post').on('click', function () {
            if (!isLogin) {
                $('#requestLogin').modal('show');
                return false;
            }
            var self = $(this);
            $.ajax({
                type: "POST",
                url: "/Profile/SavePost",
                data: { postID: postID },
                success: function (response) {
                    if (response.isSuccessful) {
                        if ($('.save_post').hasClass("active")) {
                            $('.save_post').removeClass("active");
                        } else {
                            self.addClass("active");
                            $('.save_post').addClass("active");
                        }
                    }
                }
            });
        });

        $('.copy-clipboard').on('click', function () {
            var self = $(this);
            var dummy = document.createElement('input'),
                text = window.location.href;

            document.body.appendChild(dummy);
            dummy.value = text;
            dummy.select();
            document.execCommand('copy');
            document.body.removeChild(dummy);
            $(this).parent().find('.tip').show();
            setTimeout(function () {
                self.parent().find('.tip').hide();
            }, 1000);
           
        });

        $('.mobile-socical-header input[name=keyword]').on('keypress', function () {
            var val = $(this).val();
            $('.header .top-header').find('input[name=keyword]').val(val);
        });

        $('.mobile-socical-header .btn-search').on('click', function () {
            var keyword = $(this).val();
            if ($.trim(keyword) === '') {
                e.preventDefault();
                $(this).focus();
                return false;
            }
            $(this).parent().submit();
        });
    }


    function init() {
        save_post();
    }

    return {
        init
    }
})(jQuery);


$(document).ready(function () {
    profile.init();
});
