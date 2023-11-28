const jwt = require('jsonwebtoken');
const { JWT_SECRET } = require('../config/constants');
const Result = require('../models/result');

exports.verifyUserToken = (req, res, next) => {
    let result = new Result();

    let token = req.headers.authorization;
    if (!token) {
        result.message = 'Access Denied / Unauthorized request';
        return res.status(401).send(result);
    }

    try {
        token = token.split(' ')[1] // Remove Bearer from string

        if (token === 'null' || !token) {
            result.message = 'Unauthorized request';
            return res.status(401).send(result);
        }

        let verifiedUser = jwt.verify(token, JWT_SECRET);   // config.TOKEN_SECRET => 'secretKey'
        if (!verifiedUser){
            result.message = 'Unauthorized request';
            return res.status(401).send(result);
        }

        req.user = verifiedUser; // user_id & user_type_id
        next();
    } catch (error) {
        result.message = 'Invalid Token';
        res.status(400).send(result);
    }
}

// exports.IsUser = async (req, res, next) => {
//     if (req.user.user_type_id === 0) {
//         next();
//     }
//     return res.status(401).send("Unauthorized!");   
// }
// exports.IsAdmin = async (req, res, next) => {
//     if (req.user.user_type_id === 1) {
//         next();
//     }
//     return res.status(401).send("Unauthorized!");

// }