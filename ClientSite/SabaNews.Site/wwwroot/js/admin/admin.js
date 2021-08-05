$(document).ready(function () {
    var currentPath = window.location.pathname;
    $('.menu-item').each(function (i, e) {
        if ($(e).find('a').attr('href').indexOf(currentPath) !== - 1) {
            $(e).addClass('active');
        }
    });
});