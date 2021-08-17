$(document).ready(function () {
    var currentPath = window.location.pathname;
    $('.menu-item').each(function (i, e) {
        if (currentPath.indexOf($(e).find('a').attr('href')) !== - 1) {
            $(e).addClass('active');
        }
    });
});