// const Service = {
//    path: 'http://localhost/snpass/admin',
//    apiPath: 'http://localhost/snpass/admin',
// };

const Service = {
    path: 'https://snpass.paulantezana.com/admin',
    apiPath: 'https://snpass.paulantezana.com/admin',
};

class RequestApi {
    static setHeaders(options) {
        if (options.method === 'POST' || options.method === 'PUT' || options.method === 'DELETE') {
            if (!(options.body instanceof FormData)) {
                options.headers = {
                    Accept: 'application/json',
                    'Content-Type': 'application/json; charset=utf-8',
                    ...options.headers,
                };
                options.body = JSON.stringify(options.body);
            } else {
                // newOptions.body is FormData
                options.headers = {
                    Accept: 'application/json',
                    ...options.headers,
                };
            }
        }
        return options;
    };

    static fetch(path, options) {
        const defaultOptions = {
            headers: {
                // Authorization: `Bearer ${tk}`,
            },
        };

        NProgress.start();

        const newOptions = RequestApi.setHeaders({...defaultOptions, ...options}); // format
        const url = Service.apiPath + path; // uri request

        // request api
        return fetch(url, newOptions)
            .then(response => {
                NProgress.done();
                return response.json(); // Return response
            }).catch(err => {
                NProgress.done();
                window.location.replace(Service.path  + '/500?message=' + err.message);
                return err;
            })
    }

    static fetchText(path, options) {
        const url = Service.apiPath + path; // uri request

        NProgress.start();

        // request api
        return fetch(url, options)
            .then(response => {
                NProgress.done();
                return response.text();
            }).catch(err => {
                NProgress.done();
                window.location.replace(Service.path  + '/500?message=' + err.message);
                return err;
            })
    }
}
