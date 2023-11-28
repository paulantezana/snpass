const express = require("express");
const noteController = require("../controllers/NoteController");
const { verifyUserToken } = require("../middlewares/auth");

const noteRoutes = express.Router();

/**
 * @swagger
 * components:
 *  schemas:
 *    Note:
 *      type: object
 *      properties:
 *        id:
 *          type: integer
 *        description:
 *          type: string
 *          description: Descripción del servicio
 */

/**
 * @swagger
 * /note/paginate:
 *   post:
 *     summary: Obtiene una lista paginada de servicios
 *     tags: [Note]
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
 *                     $ref: '#/components/schemas/Note'
 *       500:
 *         description: Error interno del servidor
 */
noteRoutes.post('/note/paginate', verifyUserToken, noteController.paginate);

/**
 * @swagger
 * /note/all:
 *   get:
 *     summary: Obtiene una lista de todos los servicios
 *     tags: [Note]
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
 *                     $ref: '#/components/schemas/Note'
 *       500:
 *         description: Error interno del servidor
 */
noteRoutes.get('/note/all', verifyUserToken, noteController.all);

/**
 * @swagger
 * /note/create:
 *   post:
 *     summary: Crea un nuevo servicio
 *     tags: [Note]
 *     requestBody:
 *       required: true
 *       content:
 *         application/json:
 *           schema:
 *             $ref: '#/components/schemas/Note'
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
noteRoutes.post('/note/create', verifyUserToken, noteController.create);

/**
 * @swagger
 * /note/{id}:
 *   put:
 *     summary: Actualiza un servicio existente por su ID
 *     tags: [Note]
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
 *             $ref: '#/components/schemas/Note'
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
noteRoutes.put('/note/:id', verifyUserToken, noteController.update);

/**
 * @swagger
 * /note/{id}:
 *   get:
 *     summary: Obtiene un servicio por su ID
 *     tags: [Note]
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
 *                   $ref: '#/components/schemas/Note'
 *       404:
 *         description: Servicio no encontrado
 *       500:
 *         description: Error interno del servidor
 */
noteRoutes.get('/note/:id', verifyUserToken, noteController.getById);

/**
 * @swagger
 * /note/{id}:
 *   delete:
 *     summary: Elimina un servicio por su ID
 *     tags: [Note]
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
noteRoutes.delete('/note/:id', verifyUserToken, noteController.remove);

module.exports = noteRoutes;
