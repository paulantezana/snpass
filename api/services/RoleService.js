const pool = require('../config/database');

const rolePaginate = async ({ page, limit }) => {
  const connection = await pool.getConnection();
  const offset = (page - 1) * limit;

  const [rows] = await connection.query("SELECT * FROM roles LIMIT ? OFFSET ?", [limit, offset]);

  connection.release();

  return rows;
}

const roleAll = async () => {
  const connection = await pool.getConnection();

  const [rows] = await connection.query("SELECT * FROM roles");

  connection.release();

  return rows;
}

const roleGetById = async (id) => {
  const connection = await pool.getConnection();

  const [rows] = await connection.query("SELECT * FROM roles WHERE id = ?", [id]);

  connection.release();

  return rows[0] ?? {};
}

const roleCreate = async (role) => {
  const connection = await pool.getConnection();

  const { description } = role;

  const [result] = await connection.query(
    "INSERT INTO roles (description) VALUES (?)",
    [description]
  );

  const createdRoleId = result.insertId;
  connection.release();

  return createdRoleId;
};

const roleUpdate = async (role, id) => {
  const connection = await pool.getConnection();

  const { description } = role;

  await connection.query(
    "UPDATE roles SET description = ? WHERE id = ?",
    [description, id]
  );

  connection.release();

  return true;
};

const roleDelete = async (id) => {
  const connection = await pool.getConnection();

  await connection.query("DELETE FROM roles WHERE id = ?", [id]);

  connection.release();

  return true;
};

module.exports = {
  rolePaginate,
  roleGetById,
  roleCreate,
  roleUpdate,
  roleDelete,
  roleAll,
};
