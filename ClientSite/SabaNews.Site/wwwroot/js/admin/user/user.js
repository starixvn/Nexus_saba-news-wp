var adminUser = (function ($) {
    var page = 1;
    var pageSize = 10;
    function GetDataUserGrid(dateSort) {
        var fromDate = $('#FromDate').val();
        var toDate = $('#ToDate').val();
        var search = $('#search').val();
        if (!isValidFilter(fromDate, toDate)) {
            return false;
        }
        $.ajax({
            url: "/Admin/User/GetDataUser",
            type: 'POST',
            data: { fromDate, toDate, search, dateSort, page, pageSize},
            success: function (res) {
                $('.user-grid').html(res);
                initSortEvent();
                initPaging();
            }
        });
    }

    function isValidFilter(fromDate, toDate) {
        if (!fromDate) {
            $('#error').show();
            $('#error').html('From Date is required');
            return false;
        }

        if (!toDate) {
            $('#error').show();
            $('#error').html('To Date is required');
            return false;
        }

        if (fromDate > toDate) {
            $('#error').show();
            $('#error').html('From Date must bigger than To Date');
            return false;
        }

        $('#error').hide();
        return true;
    }

    function initEventSubmitBtn() {
        $("#filter").on("submit", function (e) {
            e.preventDefault();
            GetDataUserGrid();
        });
    }

    function initSortEvent() {
        $("#date-sort").on("click", function () {
            var dateType = $(this).attr("sort-type");
            if (!dateType) {
                dateType = "desc";
                GetDataUserGrid(dateType);
                return;
            }

            dateType = dateType === "desc" ? "asc" : "desc";
            GetDataUserGrid(dateType);
        });
    }

    function initPaging() {
        
        $('.paging').on('click', function () {
            page = $(this).data('page');
            var dateSort = $("#date-sort").attr("sort-type");
            GetDataUserGrid(dateSort);

        });

        $('#page-size').on('change', function () {
            pageSize = $(this).val();
            page = 1;
            var dateSort = $("#date-sort").attr("sort-type");
            GetDataUserGrid(dateSort);
        });
    }

    function init() {
        GetDataUserGrid();
        initEventSubmitBtn();
    }

    return {
        init
    }

})(jQuery);

$(document).ready(function () {
    adminUser.init();
});