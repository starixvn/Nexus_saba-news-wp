var membershipEvent = (function ($) {
    function HandleComment() {
        $(document).on('click', '.add_comment', function (e) {
            var self = $(this);
            self.parent().find('.error').html("");
            var commentContent = self.parent().find("textarea[name=comment_value]").val();
            if (!$.isNumeric(commentContent)) {
                self.parent().find('.error').html("Vui lòng nhập bình luận là số");
                self.addClass('error-comment');

            }
        });
    }
    function init() {
        HandleComment();
    }

    return {
        init
    }

})(jQuery);


$(document).ready(function () {
    membershipEvent.init();
});