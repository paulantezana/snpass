// import "./bootstrap";
// import "express-async-errors";
const express = require('express');
const cors = require('cors');
const cookieParser = require('cookie-parser');
require('dotenv').config()

const swaggerUi = require('swagger-ui-express');
const swaggerJsdoc = require('swagger-jsdoc');
const path = require('path')

const routes = require('./routes')

const app = express();

const swaggerSpec = {
    definition: {
      openapi: '3.0.0',
      info: {
        title: 'SnPass-API',
        version: '1.0.0',
      },
    },
    apis: [`${path.join(__dirname, './routes/*.js')}`], // files containing annotations as above
};

app.use(
    cors({
        // credentials: true,
        // origin: process.env.FRONTEND_URL
    })
)
app.use(cookieParser());
app.use(express.json());
// app.use('/public', express.static(uploadConfig.directory));

app.use(routes);

app.use('/', swaggerUi.serve, swaggerUi.setup(swaggerJsdoc(swaggerSpec)))

// app.use(async (err, req, res, _) => {
//     if (err instanceof AppError) {
//         logger.warn(err);
//         return res.status(err.statusCode).json({ error: err.message });
//     }

//     logger.error(err);
//     return res.status(500).json({ error: "Internal server error" });
// });

app.listen(3000, () => {
    console.log(`Example app listening on port 3000`)
})
