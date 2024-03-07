var pollAdmin = (function ($) {
    var id = 0;
    
    function GetDataGrid() {
        $('.poll-list').select2();
        $('.poll-list').on('change', function(){
            var question = $(this).find(':selected').data('question');
            $(this).parent().parent().find('.poll-question').html(question);
        })

        $('.btn-save-poll').on('click', function() {
            var pollId = $('.poll-list').find(':selected').val();
            var self = $(this);
            self.prop('disabled', true);
            $.ajax({
                type: 'POST',
                data:{ pollId },
                url: "/Admin/HomepageManagement/UpdatePoll",
                success: function(res) {
                    if (res.isSuccessful) {
                        GetSuccessPopup('#NoticeModal', 'Update Poll successfully!');
                        setTimeout(function () {
                            $('#NoticeModal').modal('hide');
                            GetDataGrid();
                        }, 1000);
                    } else {
                        self.prop('disabled', false);
                        var mess = response.message == '' ? 'An error occurred while add league, please try again' : response.message;
                        GetErrorPopup('#NoticeModal', mess);
                    }
                    self.prop('disabled', false);

                }
            })
        });
    };

    function init() {
        GetDataGrid();
    }

    return {
        init
    }
})(jQuery);



$(document).ready(function () {
    pollAdmin.init();
});