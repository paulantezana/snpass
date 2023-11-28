import { getToken } from "./Auth";

const codeMessage = {
    200: "El servidor devolvió con éxito los datos solicitados. ",
    201: "Datos nuevos o modificados son exitosos. ",
    202: "Una solicitud ha ingresado a la cola de fondo (tarea asíncrona). ",
    204: "Eliminar datos con éxito. ",
    400: "La solicitud se envió con un error. El servidor no realizó ninguna operación para crear o modificar datos. ",
    401: "El usuario no tiene permiso (token, nombre de usuario, contraseña es incorrecta). ",
    403: "El usuario está autorizado, pero el acceso está prohibido. ",
    404: "La solicitud se realizó a un registro que no existe y el servidor no funcionó. ",
    406: "El formato de la solicitud no está disponible. ",
    410: "El recurso solicitado se elimina permanentemente y no se obtendrá de nuevo. ",
    422: "Al crear un objeto, se produjo un error de validación. ",
    500: "El servidor tiene un error, por favor revise el servidor. ",
    502: "Error de puerta de enlace. ",
    503: "El servicio no está disponible, el servidor está temporalmente sobrecargado o mantenido. ",
    504: "La puerta de enlace agotó el tiempo. ",
};

const API_PATH = 'http://localhost:3000/';

export class RequestApi {
    static setHeaders(options) {
        const token = getToken();

        if (!(options.body instanceof FormData)) {
            options.headers = {
                Accept: "application/json",
                Authorization: `Bearer ${token}`,
                "Content-Type": "application/json; charset=utf-8",
                ...options.headers,
            };
            options.body = JSON.stringify(options.body);
        } else {
            options.headers = {
                Accept: "application/json",
                Authorization: `Bearer ${token}`,
                ...options.headers,
            };
        }
        return options;
    }

    static checkStatus(response) {
        if (response.status >= 200 && response.status < 300) {
            return response;
        }

        const errortext = codeMessage[response.status] || response.statusText;
        //   SnMessage.danger({
        //     content: `Error de solicitud ${response.status}: ${response.url} ${errortext}`,
        //   });
        let error = new Error(errortext);
        error.name = response.status;
        error.response = response;
        throw error;
    }

    static fetch(path, options = {}) {
        const newOptions = RequestApi.setHeaders(options);

        return fetch(API_PATH + path, newOptions)
            .then(RequestApi.checkStatus)
            .then((response) => {
                return response.json();
            })
            .catch(e => {
                //   SnMessage.danger({ content: `ERROR FATAL JSON: ${e}` });
                return e;
            })
            .finally(e => {
            });
    }
}