const pool = require('../config/database');

const userPaginate = async ({ page, limit }) => {
  const connection = await pool.getConnection();
  const offset = (page - 1) * limit;

  const [rows] = await connection.query("SELECT * FROM users LIMIT ? OFFSET ?", [limit, offset]);
  
  connection.release();

  return rows;
}

const userGetById = async (id) => {
  const connection = await pool.getConnection();

  const [rows] = await connection.query("SELECT * FROM users WHERE id = ?", [id]);

  connection.release();

  return rows[0] ?? {};
}

const userCreate = async (user) => {
  const connection = await pool.getConnection();

  const { user_name, password, full_name, gender, email, phone, role_id } = user;

  const [result] = await connection.query(
    "INSERT INTO users (user_name, password, full_name, gender, email, phone, role_id) VALUES (?, ?, ?, ?, ?, ?, ?)",
    [user_name, password, full_name, gender, email, phone, role_id]
  );

  const createdUserId = result.insertId;
  connection.release();

  return createdUserId;
};

const userUpdate = async (user, id) => {
  const connection = await pool.getConnection();

  const { user_name, password, full_name, gender, email, phone, role_id } = user;

  await connection.query(
    "UPDATE users SET user_name = ?, password = ?, full_name = ?, gender = ?, email = ?, phone = ?, role_id = ? WHERE id = ?",
    [user_name, password, full_name, gender, email, phone, role_id, id]
  );

  connection.release();

  return true;
};

const userDelete = async (id) => {
  const connection = await pool.getConnection();

  await connection.query("DELETE FROM users WHERE id = ?", [id]);

  connection.release();

  return true;
};

const userLogin = async (user_name) => {
  const connection = await pool.getConnection();

  const [rows] = await connection.query("SELECT * FROM users WHERE user_name = ?", [user_name]);

  connection.release();

  return rows[0] ?? {};
};

module.exports = {
  userPaginate,
  userGetById,
  userCreate,
  userUpdate,
  userDelete,
  userLogin,
};
