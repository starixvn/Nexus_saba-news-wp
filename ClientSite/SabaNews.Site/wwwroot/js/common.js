var _viewResource = {
    ConfirmPasswordEqualTo: 'Xác thực mật khẩu sai',
    ConfirmPasswordRequired: 'Hãy xác thực mật khẩu',
    EmailInvalid: 'Email không có thật',
    EmailRequired: 'Hãy nhập email',
    NameRequired: 'Hãy nhập tên',
    PasswordMinLength: 'Mật khẩu ít nhất 6 kí tự',
    PasswordRequired: 'Hãy nhập mật khẩu'
};

var _htmlResource = {
    ButtonCloseModal: '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>',
    ForgotPassword: '<div>Hãy kiểm tra email <b>{0}</b></div><div>Để xem hướng dẫn lấy lại mật khẩu.</div>',
    RegisterSuccessfully: '<h3>Đăng ký thành công</h3><div>Cảm ơn Quý độc giả đã đăng ký tài khoản tại <a href="https://saba.vn/">Saba.vn</a>. Chúng tôi đã gửi email đến địa chỉ {0} để xác thực và kích hoạt tài khoản.</div>',
    RemindCheckMail: '<div>Vui lòng kiểm tra cả phần Thư rác/Spam nhé.</div>',
    ResetPasswordSuccessfully: '<h3>Đổi mật khẩu thành công</h3>'
};

function GetErrorPopup(popupId, msg) {
    $(popupId + ' .modal-title').removeClass('title-success').addClass('title-error');
    $(popupId + ' .title').html('<i class="icon-title icon-error-admin"></i> Error');
    $(popupId + ' .modal-body').html(msg);
    $(popupId).modal('show');
}

function GetSuccessPopup(popupId, msg) {
    $(popupId + ' .modal-title').removeClass('title-error').addClass('title-success');
    $(popupId + ' .title').html('<i class="icon-title icon-success-admin"></i> Successful');
    $(popupId + ' .modal-body').html(msg);
    $(popupId).modal('show');
}

function formatString(...args) {
    let text = args[0];
    for (let i = 1; i < args.length; i++) {
        let regEx = new RegExp('\\{' + (i - 1) + '\\}', 'gm');
        text = text.replace(regEx, args[i]);
    }
    return text;
};