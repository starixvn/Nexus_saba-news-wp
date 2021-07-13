$(document).ready(function () {

    CKEDITOR.replace('emailContent');
    CKEDITOR.editorConfig = function (config) {
        // Define changes to default configuration here. For example:
        // config.language = 'fr';
        // config.uiColor = '#AADC6E';
        config.syntaxhighlight_lang = 'csharp';
        config.syntaxhighlight_hideControls = true;
        config.languages = 'vi';
        config.filebrowserBrowseUrl = '/js/ckfinder/ckfinder.html';
        config.filebrowserImageBrowseUrl = '/js/ckfinder/ckfinder.html?Types=Images';
        config.filebrowserFlashBrowseUrl = '/js/ckfinder/ckfinder.html?Types=Flash';
        config.filebrowserUploadUrl = '/js/ckfinder/core/connector/aspx/connector.aspx?command=QuickUpload&type=File';
        config.filebrowserImageUploadUrl = 'Data';
        config.filebrowserFlashUploadUrl = '/js/ckfinder/core/connector/aspx/connector.aspx?command=QuickUpload&type=Flash';

        CKFinder.setupCKEditor(null, '/js/ckfinder/');
    };
});