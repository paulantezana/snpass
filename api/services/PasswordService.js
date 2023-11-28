const pool = require('../config/database');

const passwordPaginate = async ({ page, limit }) => {
  const connection = await pool.getConnection();
  const offset = (page - 1) * limit;

  const [rows] = await connection.query("SELECT * FROM passwords LIMIT ? OFFSET ?", [limit, offset]);

  connection.release();

  return rows;
}

const passwordAll = async () => {
  const connection = await pool.getConnection();

  const [rows] = await connection.query("SELECT * FROM passwords");

  connection.release();

  return rows;
}

const passwordGetById = async (id) => {
  const connection = await pool.getConnection();

  const [rows] = await connection.query("SELECT * FROM passwords WHERE id = ?", [id]);

  connection.release();

  return rows[0] ?? {};
}

const passwordCreate = async (password) => {
  const connection = await pool.getConnection();

  const { web_site, user_name, pass } = password;

  const [result] = await connection.query(
    "INSERT INTO passwords (web_site, user_name, pass) VALUES (?,?,?)",
    [web_site, user_name, pass]
  );

  const createdPasswordId = result.insertId;
  connection.release();

  return createdPasswordId;
};

const passwordUpdate = async (password, id) => {
  const connection = await pool.getConnection();

  const { web_site, user_name } = password;

  await connection.query(
    "UPDATE passwords SET web_site = ?, user_name = ?, pass = ? WHERE id = ?",
    [web_site, user_name, pass, id]
  );

  connection.release();

  return true;
};

const passwordDelete = async (id) => {
  const connection = await pool.getConnection();

  await connection.query("DELETE FROM passwords WHERE id = ?", [id]);

  connection.release();

  return true;
};

module.exports = {
  passwordPaginate,
  passwordGetById,
  passwordCreate,
  passwordUpdate,
  passwordDelete,
  passwordAll,
};
