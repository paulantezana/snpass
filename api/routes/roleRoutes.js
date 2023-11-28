const express = require("express");
const roleController = require("../controllers/RoleController");
const { verifyUserToken } = require("../middlewares/auth");

const roleRoutes = express.Router();

/**
 * @swagger
 * components:
 *  schemas:
 *    Role:
 *      type: object
 *      properties:
 *        id:
 *          type: integer
 *        description:
 *          type: string
 *          description: Descripción del rol
 */

/**
 * @swagger
 * /role/paginate:
 *   post:
 *     summary: Obtiene una lista paginada de roles
 *     tags: [Role]
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
 *         description: Lista de roles paginada obtenida con éxito
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
 *                     $ref: '#/components/schemas/Role'
 *       500:
 *         description: Error interno del servidor
 */
roleRoutes.post('/role/paginate', verifyUserToken, roleController.paginate);

/**
 * @swagger
 * /role/all:
 *   get:
 *     summary: Obtiene una lista de todos los roles
 *     tags: [Role]
 *     responses:
 *       200:
 *         description: Lista de roles obtenida con éxito
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
 *                     $ref: '#/components/schemas/Role'
 *       500:
 *         description: Error interno del servidor
 */
roleRoutes.get('/role/all', verifyUserToken, roleController.all);

/**
 * @swagger
 * /role/create:
 *   post:
 *     summary: Crea un nuevo rol
 *     tags: [Role]
 *     requestBody:
 *       required: true
 *       content:
 *         application/json:
 *           schema:
 *             $ref: '#/components/schemas/Role'
 *     responses:
 *       201:
 *         description: Rol creado con éxito
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
 *                   example: "Rol creado exitosamente"
 *                 data:
 *                   type: integer
 *                   example: 12345
 *       400:
 *         description: Solicitud incorrecta, verifique los datos del rol
 *       500:
 *         description: Error interno del servidor
 */
roleRoutes.post('/role/create', verifyUserToken, roleController.create);

/**
 * @swagger
 * /role/{id}:
 *   put:
 *     summary: Actualiza un rol existente por su ID
 *     tags: [Role]
 *     parameters:
 *       - in: path
 *         name: id
 *         required: true
 *         description: ID del rol a actualizar
 *         schema:
 *           type: integer
 *     requestBody:
 *       required: true
 *       content:
 *         application/json:
 *           schema:
 *             $ref: '#/components/schemas/Role'
 *     responses:
 *       200:
 *         description: Rol actualizado con éxito
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
 *                   example: "Rol actualizado exitosamente"
 *                 data:
 *                   type: boolean
 *                   example: true
 *       400:
 *         description: Solicitud incorrecta, verifique los datos del rol
 *       404:
 *         description: Rol no encontrado
 *       500:
 *         description: Error interno del servidor
 */
roleRoutes.put('/role/:id', verifyUserToken, roleController.update);

/**
 * @swagger
 * /role/{id}:
 *   get:
 *     summary: Obtiene un rol por su ID
 *     tags: [Role]
 *     parameters:
 *       - in: path
 *         name: id
 *         required: true
 *         description: ID del rol a obtener
 *         schema:
 *           type: integer
 *     responses:
 *       200:
 *         description: Rol obtenido con éxito
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
 *                   example: "Rol obtenido exitosamente"
 *                 data:
 *                   $ref: '#/components/schemas/Role'
 *       404:
 *         description: Rol no encontrado
 *       500:
 *         description: Error interno del servidor
 */
roleRoutes.get('/role/:id', verifyUserToken, roleController.getById);

/**
 * @swagger
 * /role/{id}:
 *   delete:
 *     summary: Elimina un rol por su ID
 *     tags: [Role]
 *     parameters:
 *       - in: path
 *         name: id
 *         required: true
 *         description: ID del rol a eliminar
 *         schema:
 *           type: integer
 *     responses:
 *       204:
 *         description: Rol eliminado con éxito
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
 *                   example: "Rol eliminado exitosamente"
 *                 data:
 *                   type: boolean
 *                   example: true
 *       404:
 *         description: Rol no encontrado
 *       500:
 *         description: Error interno del servidor
 */
roleRoutes.delete('/role/:id', verifyUserToken, roleController.remove);

module.exports = roleRoutes;
