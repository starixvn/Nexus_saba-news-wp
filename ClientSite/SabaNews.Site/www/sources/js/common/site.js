const _rootUrl = document.getElementById('RootUrl') ? document.getElementById('RootUrl').value : '/';
let _xsrfToken = null;

export default class Site {
    static getXsrfToken() {
        if (!_xsrfToken && document.querySelector('input[name=__RequestVerificationToken]')) {
            _xsrfToken = document.querySelector('input[name=__RequestVerificationToken]').value;
        }

        return _xsrfToken;
    }

    static handleAxiosError(error) {
        if (error.response) {
            let url;
            switch (error.response.status) {
                case 401:
                    url = 'auth/logout';
                    break;

                case 404:
                    url = 'error/PageNotFound';
                    break;

                case 405:
                    url = 'error/AccessDenied';
                    break;

                case 500:
                    url = 'error/InternalError';
                    break;
            }

            //window.location = _rootUrl + url;
        }
    }

    static resolveClientUrl(relativeUrl) {
        return _rootUrl + relativeUrl;
    }

    static lockNavigator() {
        let lockResolver;
        if (navigator && navigator.locks && navigator.locks.request) {
            const promise = new Promise(res => {
                lockResolver = res;
            });

            navigator.locks.request('livescore_lock_navigator', { mode: 'shared' }, () => promise);
        }
    }
}