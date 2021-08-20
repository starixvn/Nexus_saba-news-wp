var viewDetails = (function ($) {
    function getViewDetails(viewSort, pageIndex = 1) {
        var createdDate = $('input[name="CreatedDate"]').val();
        var isVideo = $('input[name="IsVideo"]').attr("checked");

        $.ajax({
            url: "GetViewDetails",
            method: "POST",
            data: {
                createdDate: new Date(createdDate).toISOString(),
                isVideo: isVideo ? true : false,
                viewSort: viewSort,
                pageIndex: pageIndex
            },
            success: function (result) {
                $("#grid-details").html(result);
                initSortViewEvent();
                initPagingEvent();
            }
        });
    }

    function initPagingEvent() {
        $('.paging').on('click', function () {
            var pageIndex = $(this).data('page');
            var viewSort = $("#view-sort").attr("sort-type");
            getViewDetails(viewSort, pageIndex);
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