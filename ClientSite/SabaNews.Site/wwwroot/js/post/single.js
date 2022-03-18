$(window).on("load", function () {
    var postId = $("#postID").val();
    var isVideo = ($("#isVideo").val().toLowerCase() === 'true');
    $.ajax({
        url: "/api/post/update-post-view",
        type: "POST",
        data: { postId, isVideo },
        success: function (res) {
            console.log(res);
        }
    });
})