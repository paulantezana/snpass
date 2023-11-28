const { passwordPaginate, passwordGetById, passwordCreate, passwordUpdate, passwordDelete, passwordAll } = require("../services/PasswordService");
const Result = require("../models/result");

const paginate = async (req, res) => {
  let result = new Result()
  try {
    let body = req.body;

    const data = await passwordPaginate(body);
    result.data = data;
    result.success = true;

    res.json(result);
  } catch (error) {
    result.message = error.message;
    return res.status(500).json(result);
  }
};

const all = async (req, res) => {
  let result = new Result()
  try {
    const data = await passwordAll();
    result.data = data;
    result.success = true;

    res.json(result);
  } catch (error) {
    result.message = error.message;
    return res.status(500).json(result);
  }
};

const create = async (req, res) => {
  let result = new Result()
  try {
    let body = req.body;

    const response = await passwordCreate(body);

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
    const passwordId = req.params.id;

    const response = await passwordUpdate(body, passwordId);

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
    const passwordId = req.params.id;

    const response = await passwordDelete(passwordId);

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
    const passwordId = req.params.id;

    const response = await passwordGetById(passwordId);

    result.success = true;
    result.data = response;
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
  all,
};
