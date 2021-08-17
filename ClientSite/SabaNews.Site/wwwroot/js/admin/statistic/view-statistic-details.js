var viewDetails = (function ($) {
    function getViewDetails(viewSort) {
        var createdDate = $('input[name="CreatedDate"]').val();
        var isVideo = $('input[name="IsVideo"]').attr("checked");

        $.ajax({
            url: "GetViewDetails",
            method: "POST",
            data: {
                createdDate: new Date(createdDate).toISOString(),
                isVideo: isVideo ? true : false,
                viewSort: viewSort
            },
            success: function (result) {
                $("#grid-details").html(result);
                initSortViewEvent();
            }
        });
    }

    function initSortViewEvent() {
        $("#view-sort").on("click", function () {
            var currentType = $(this).attr("sort-type");

            if (!currentType) {
                currentType = "desc";
                getViewDetails(currentType);
                return;
            }

            currentType = currentType === "desc" ? "asc" : "desc";
            getViewDetails(currentType);
        });
    }

    function init() {
        getViewDetails();
    }

    return { init };
})(jQuery);

$(document).ready(function () {
    viewDetails.init();
});