$(document).ready(function () {
    var activeMenu = $('input[name="activeMenu"]').val();
    $('.menu-item').each(function (i, e) {
        if ($(e).find('a').text() === activeMenu) {
            $(e).addClass('active');
        }
    });
});