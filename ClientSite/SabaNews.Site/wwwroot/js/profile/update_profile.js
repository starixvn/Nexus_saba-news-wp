var update_profile = (function ($) {
    function update_saved_post() {
        $('.remove_save_post').on('click', function () {
            var self = $(this);
            var pID = self.data('postid');
            $.ajax({
                type: "POST",
                url: "/Profile/SavePost",
                data: { postID: pID },
                success: function (response) {
                    if (response.isSuccessful) {
                        self.parent().html('');
                    }
                }
            });
        });
    }

    function init() {
        update_saved_post();
    }

    return {
        init
    }
})(jQuery);

$(document).ready(function () {
    update_profile.init();
});