var profile = (function ($) {
    var postID = $('input[name=postID]').val();
    var isLogin = ($('input[name=isLogin]').val()).toLowerCase() === 'true';
    console.log(isLogin);
    console.log($('input[name=isLogin]').val());
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
                        if (self.hasClass("active")) {
                            self.removeClass("active");
                        } else {
                            self.addClass("active");
                        }
                    }
                }
            });
        })
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
