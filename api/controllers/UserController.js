const bcrypt = require('bcrypt');
const jwt = require('jsonwebtoken');

const Result = require("../models/result");

const { userPaginate, userGetById, userCreate, userUpdate, userDelete, userLogin } = require("../services/UserService");
const { JWT_SECRET } = require('../config/constants');

const paginate = async (req, res) => {
  let result = new Result()
  try {
    let body = req.body;
    
    const data = await userPaginate(body);
    result.data = data;
    result.success = true;

    res.json(result);
  } catch (error) {
    return res.status(500).json({ message: "Something goes wrong" });
  }
};

const create = async (req, res) => {
  let result = new Result()
  try {
    let body = req.body;

    body.password = await bcrypt.hash(body.password, 10);

    const response = await userCreate(body);

    result.success = true;
    result.data = response;
    return res.status(201).json(result);
  } catch (error) {
    result.message = error.message;
    return res.status(500).json(result);
  }
};

const update = async (req, res) => {
  let result = new Result()
  try {
    let body = req.body;
    const userId = req.params.id;

    body.password = await bcrypt.hash(body.password, 10);

    const response = await userUpdate(body, userId);

    result.success = true;
    result.data = response;
    return res.status(200).json(result);
  } catch (error) {
    result.message = error.message;
    return res.status(500).json(result);
  }
};

const remove = async (req, res) => {
  let result = new Result()
  try {
    const userId = req.params.id;

    const response = await userDelete(userId);

    result.success = true;
    result.data = response;
    return res.status(204).json(result);
  } catch (error) {
    result.message = error.message;
    return res.status(500).json(result);
  }
};

const getById = async (req, res) => {
  let result = new Result()
  try {
    const userId = req.params.id;

    const response = await userGetById(userId);

    result.success = true;
    result.data = response;
    return res.status(200).json(result);
  } catch (error) {
    result.message = error.message;
    return res.status(500).json(result);
  }
};


const login = async (req, res) => {
  let result = new Result()
  try {
    const body = req.body;
    
    const user = await userLogin(body.user_name);

    if (!(user?.id > 0)) {
      result.message = 'Usuario invalido';
      return res.status(401).json(result);
    }

    const passwordResult = await bcrypt.compare(body.password, user.password);
    if (!passwordResult) {
      result.message = 'Contraseña invalido';
      return res.status(401).json(result);
    }

    const token = jwt.sign({ id: user.id, user_role_id: user.role_id }, JWT_SECRET, { expiresIn: '24h' });

    delete user.password;

    result.message = 'Login exitoso'
    result.success = true;
    result.data = { user, token };
    return res.status(200).json(result);
  } catch (error) {
    result.message = error.message;
    return res.status(500).json(result);
  }
};

module.exports = {
  paginate,
  create,
  update,
  remove,
  getById,
  login,
};
