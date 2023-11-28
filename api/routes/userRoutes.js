const express = require("express");
const userController = require("../controllers/UserController");
const { verifyUserToken } = require("../middlewares/auth");

const userRoutes = express.Router();

/**
 * @swagger
 * components:
 *  schemas:
 *    User:
 *      type: object
 *      properties:
 *        id:
 *          type: integer
 *        user_name:
 *          type: string
 *          description: nombre de usuario
 *        password:
 *          type: string
 *          description: contraseña
 *        full_name:
 *          type: string
 *          description: nombre completo
 *        gender:
 *          type: string
 *          description: genero 0 no especificado, 1 masculino, 2 femenino
 *          enum: ['0', '1', '2'] # Valores permitidos para gender
 *        email:
 *          type: string
 *          description: correo
 *        phone:
 *          type: string
 *          description: celular
 *        role_id:
 *          type: integer
 *          description: id rol
 *    UserPaginate:
 *      type: object
 *      properties:
 *        page:
 *          type: integer
 *        limit:
 *          type: integer
 */


/**
 * @swagger
 * /user/paginate:
 *   post:
 *     summary: Obtiene una lista paginada de usuarios
 *     tags: [User]
 *     requestBody:
 *       required: true
 *       content:
 *         application/json:
 *           schema:
 *             $ref: '#/components/schemas/UserPaginate'
 *     responses:
 *       200:
 *         description: Lista de usuarios paginada obtenida con éxito
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
 *                     $ref: '#/components/schemas/User'  # Reemplaza con la referencia correcta
 *       500:
 *         description: Error interno del servidor
 */
userRoutes.post('/user/paginate', verifyUserToken , userController.paginate);

/**
 * @swagger
 * /user/create:
 *   post:
 *     summary: Crea un nuevo usuario
 *     tags: [User]
 *     requestBody:
 *       required: true
 *       content:
 *         application/json:
 *           schema:
 *             $ref: '#/components/schemas/User'
 *     responses:
 *       201:
 *         description: Usuario creado con éxito
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
 *                   example: "Usuario creado exitosamente"
 *                 data:
 *                   type: integer
 *                   example: 12345
 *       400:
 *         description: Solicitud incorrecta, verifique los datos del usuario
 *       500:
 *         description: Error interno del servidor
 */
userRoutes.post('/user/create', userController.create);

/**
 * @swagger
 * /user/{id}:
 *   put:
 *     summary: Actualiza un usuario existente por su ID
 *     tags: [User]
 *     parameters:
 *       - in: path
 *         name: id
 *         required: true
 *         description: ID del usuario a actualizar
 *         schema:
 *           type: integer
 *     requestBody:
 *       required: true
 *       content:
 *         application/json:
 *           schema:
 *             $ref: '#/components/schemas/User'
 *     responses:
 *       200:
 *         description: Usuario actualizado con éxito
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
 *                   example: "Usuario actualizado exitosamente"
 *                 data:
 *                   type: boolean
 *                   example: true
 *       400:
 *         description: Solicitud incorrecta, verifique los datos del usuario
 *       404:
 *         description: Usuario no encontrado
 *       500:
 *         description: Error interno del servidor
 */

userRoutes.put('/user/:id', verifyUserToken, userController.update);

/**
 * @swagger
 * /user/{id}:
 *   get:
 *     summary: Obtiene un usuario por su ID
 *     tags: [User]
 *     parameters:
 *       - in: path
 *         name: id
 *         required: true
 *         description: ID del usuario a obtener
 *         schema:
 *           type: integer
 *     responses:
 *       200:
 *         description: Usuario obtenido con éxito
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
 *                   example: "Usuario obtenido exitosamente"
 *                 data:
 *                   $ref: '#/components/schemas/User'
 *       404:
 *         description: Usuario no encontrado
 *       500:
 *         description: Error interno del servidor
 */
userRoutes.get('/user/:id', verifyUserToken, userController.getById);

/**
 * @swagger
 * /user/{id}:
 *   delete:
 *     summary: Elimina un usuario por su ID
 *     tags: [User]
 *     parameters:
 *       - in: path
 *         name: id
 *         required: true
 *         description: ID del usuario a eliminar
 *         schema:
 *           type: integer
 *     responses:
 *       204:
 *         description: Usuario eliminado con éxito
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
 *                   example: "Usuario eliminado exitosamente"
 *                 data:
 *                   type: boolean
 *                   example: true
 *       404:
 *         description: Usuario no encontrado
 *       500:
 *         description: Error interno del servidor
 */
userRoutes.delete('/user/:id', verifyUserToken, userController.remove);

/**
 * @swagger
 * /user/login:
 *   post:
 *     summary: Iniciar sesión de usuario
 *     tags: [User]
 *     requestBody:
 *       required: true
 *       content:
 *         application/json:
 *           schema:
 *             type: object
 *             properties:
 *               user_name:
 *                 type: string
 *                 description: Nombre de usuario
 *               password:
 *                 type: string
 *                 description: Contraseña del usuario
 *             required:
 *               - username
 *               - password
 *     responses:
 *       200:
 *         description: Inicio de sesión exitoso
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
 *                   example: "Login exitoso"
 *                 data:
 *                   type: object
 *                   properties:
 *                    user:
 *                      type: object
 *                      example: {}
 *                    token:
 *                      type: string
 *                      example: 'c6a1c6a5165'
 *       401:
 *         description: Credenciales incorrectas
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
 *                   example: "Login exitoso"
 *                 data:
 *                   type: any
 *       500:
 *         description: Error interno del servidor
 */
userRoutes.post('/user/login', userController.login);

module.exports = userRoutes;