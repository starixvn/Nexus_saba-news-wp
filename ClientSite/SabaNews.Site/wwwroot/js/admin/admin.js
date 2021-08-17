$(document).ready(function () {
    var activeMenu = $('input[name="activeMenu"]').val();
    console.log(activeMenu);
    $('.menu-item').each(function (i, e) {
        console.log($(e).find('a').text());
        if ($(e).find('a').text() === activeMenu) {
            $(e).addClass('active');
        }
    });
});