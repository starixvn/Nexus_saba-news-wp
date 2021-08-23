var viewDetails = (function ($) {
    function getViewDetails(viewSort, pageIndex = 1, pageSize = 10) {
        var createdDate = $('input[name="CreatedDate"]').val();
        var isVideo = $('input[name="IsVideo"]').attr("checked");

        $.ajax({
            url: "GetViewDetails",
            method: "POST",
            data: {
                createdDate: new Date(createdDate).toISOString(),
                isVideo: isVideo ? true : false,
                viewSort: viewSort,
                pageIndex: pageIndex,
                pageSize: pageSize
            },
            success: function (result) {
                $("#grid-details").html(result);
                initSortViewEvent();
                initPagingIndexEvent();
                initPagingSizeEvent();
            }
        });
    }

    function initPagingIndexEvent() {
        $('.paging').on('click', function () {
            var pageIndex = $(this).data('page');
            var viewSort = $("#view-sort").attr("sort-type");
            var pageSize = $('#page-size').val();
            getViewDetails(viewSort, pageIndex, pageSize);
        });
    }

    function initPagingSizeEvent() {
        $('#page-size').on('change', function () {
            var pageSize = $(this).val();
            var viewSort = $("#view-sort").attr("sort-type");
            getViewDetails(viewSort, 1, pageSize);
        });
    }

    function initSortViewEvent() {
        $("#view-sort").on("click", function () {
            var currentType = $(this).attr("sort-type");
            var pageSize = $('#page-size').val();
            if (!currentType) {
                currentType = "desc";
                getViewDetails(currentType, 1, pageSize);
                return;
            }

            currentType = currentType === "desc" ? "asc" : "desc";
            getViewDetails(currentType, 1, pageSize);
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