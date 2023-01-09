var fixtureHomepage = (function ($) {
    function GetDataGrid() {
        $.ajax({
            url: "/Admin/HomepageManagement/GetFixtureGrid",
            type: 'POST',
            success: function (res) {
                $('.data-grid').html(res);
            }
        });
    }

    function init() {
        GetDataGrid();
       
    }

    return {
        init
    }
})(jQuery);



$(document).ready(function () {
    fixtureHomepage.init();
});