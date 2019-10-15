const Service = {
    path: '/snpass',
    apiPath: '/snpass/admin',
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
        NProgress.start();
        const newOptions = RequestApi.setHeaders({...options}); // format

        return fetch(Service.apiPath + path, newOptions)
            .then(response => {
                return response.json(); // Return response
            }).catch(err => {
                console.warn(err);
                return err;
            }).finally(e=>{
                NProgress.done();
            })
    }

    static fetchText(path, options) {
        const url = Service.apiPath + path; // uri request

        NProgress.start();
        return fetch(url, options)
            .then(response => {
                return response.text();
            }).catch(err => {
                console.warn(err);
                return err;
            }).finally(e => {
                NProgress.done();
            })
    }
}
