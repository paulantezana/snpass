const express = require("express");
const passwordController = require("../controllers/PasswordController");
const { verifyUserToken } = require("../middlewares/auth");

const passwordRoutes = express.Router();

/**
 * @swagger
 * components:
 *  schemas:
 *    Password:
 *      type: object
 *      properties:
 *        id:
 *          type: integer
 *        web_site:
 *          type: string
 *          description: Url del sitio web
 *        user_name:
 *          type: string
 *          description: Nombre de usuario del sitio web
 *        pass:
 *          type: string
 *          description: Contraseña del sition web
 */

/**
 * @swagger
 * /password/paginate:
 *   post:
 *     summary: Obtiene una lista paginada de servicios
 *     tags: [Password]
 *     requestBody:
 *       required: true
 *       content:
 *         application/json:
 *           schema:
 *             type: object
 *             properties:
 *               page:
 *                 type: integer
 *               limit:
 *                 type: integer
 *     responses:
 *       200:
 *         description: Lista de servicios paginada obtenida con éxito
 *         content:
 *           application/json:
 *             schema:
 *               type: object
 *               properties:
 *                 success:
 *                   type: boolean
 *                 message:
 *                   type: string
 *                 data:
 *                   type: array
 *                   items:
 *                     $ref: '#/components/schemas/Password'
 *       500:
 *         description: Error interno del servidor
 */
passwordRoutes.post('/password/paginate', verifyUserToken, passwordController.paginate);

/**
 * @swagger
 * /password/all:
 *   get:
 *     summary: Obtiene una lista de todos los servicios
 *     tags: [Password]
 *     responses:
 *       200:
 *         description: Lista de servicios obtenida con éxito
 *         content:
 *           application/json:
 *             schema:
 *               type: object
 *               properties:
 *                 success:
 *                   type: boolean
 *                 message:
 *                   type: string
 *                 data:
 *                   type: array
 *                   items:
 *                     $ref: '#/components/schemas/Password'
 *       500:
 *         description: Error interno del servidor
 */
passwordRoutes.get('/password/all', verifyUserToken, passwordController.all);

/**
 * @swagger
 * /password/create:
 *   post:
 *     summary: Crea un nuevo servicio
 *     tags: [Password]
 *     requestBody:
 *       required: true
 *       content:
 *         application/json:
 *           schema:
 *             $ref: '#/components/schemas/Password'
 *     responses:
 *       201:
 *         description: Servicio creado con éxito
 *         content:
 *           application/json:
 *             schema:
 *               type: object
 *               properties:
 *                 success:
 *                   type: boolean
 *                   example: true
 *                 message:
 *                   type: string
 *                   example: "Servicio creado exitosamente"
 *                 data:
 *                   type: integer
 *                   example: 12345
 *       400:
 *         description: Solicitud incorrecta, verifique los datos del servicio
 *       500:
 *         description: Error interno del servidor
 */
passwordRoutes.post('/password/create', verifyUserToken, passwordController.create);

/**
 * @swagger
 * /password/{id}:
 *   put:
 *     summary: Actualiza un servicio existente por su ID
 *     tags: [Password]
 *     parameters:
 *       - in: path
 *         name: id
 *         required: true
 *         description: ID del servicio a actualizar
 *         schema:
 *           type: integer
 *     requestBody:
 *       required: true
 *       content:
 *         application/json:
 *           schema:
 *             $ref: '#/components/schemas/Password'
 *     responses:
 *       200:
 *         description: Servicio actualizado con éxito
 *         content:
 *           application/json:
 *             schema:
 *               type: object
 *               properties:
 *                 success:
 *                   type: boolean
 *                   example: true
 *                 message:
 *                   type: string
 *                   example: "Servicio actualizado exitosamente"
 *                 data:
 *                   type: boolean
 *                   example: true
 *       400:
 *         description: Solicitud incorrecta, verifique los datos del servicio
 *       404:
 *         description: Servicio no encontrado
 *       500:
 *         description: Error interno del servidor
 */
passwordRoutes.put('/password/:id', verifyUserToken, passwordController.update);

/**
 * @swagger
 * /password/{id}:
 *   get:
 *     summary: Obtiene un servicio por su ID
 *     tags: [Password]
 *     parameters:
 *       - in: path
 *         name: id
 *         required: true
 *         description: ID del servicio a obtener
 *         schema:
 *           type: integer
 *     responses:
 *       200:
 *         description: Servicio obtenido con éxito
 *         content:
 *           application/json:
 *             schema:
 *               type: object
 *               properties:
 *                 success:
 *                   type: boolean
 *                   example: true
 *                 message:
 *                   type: string
 *                   example: "Servicio obtenido exitosamente"
 *                 data:
 *                   $ref: '#/components/schemas/Password'
 *       404:
 *         description: Servicio no encontrado
 *       500:
 *         description: Error interno del servidor
 */
passwordRoutes.get('/password/:id', verifyUserToken, passwordController.getById);

/**
 * @swagger
 * /password/{id}:
 *   delete:
 *     summary: Elimina un servicio por su ID
 *     tags: [Password]
 *     parameters:
 *       - in: path
 *         name: id
 *         required: true
 *         description: ID del servicio a eliminar
 *         schema:
 *           type: integer
 *     responses:
 *       204:
 *         description: Servicio eliminado con éxito
 *         content:
 *           application/json:
 *             schema:
 *               type: object
 *               properties:
 *                 success:
 *                   type: boolean
 *                   example: true
 *                 message:
 *                   type: string
 *                   example: "Servicio eliminado exitosamente"
 *                 data:
 *                   type: boolean
 *                   example: true
 *       404:
 *         description: Servicio no encontrado
 *       500:
 *         description: Error interno del servidor
 */
passwordRoutes.delete('/password/:id', verifyUserToken, passwordController.remove);

module.exports = passwordRoutes;
