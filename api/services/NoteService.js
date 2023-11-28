const pool = require('../config/database');

const notePaginate = async ({ page, limit }) => {
  const connection = await pool.getConnection();
  const offset = (page - 1) * limit;

  const [rows] = await connection.query("SELECT * FROM notes LIMIT ? OFFSET ?", [limit, offset]);

  connection.release();

  return rows;
}

const noteAll = async () => {
  const connection = await pool.getConnection();

  const [rows] = await connection.query("SELECT * FROM notes");

  connection.release();

  return rows;
}

const noteGetById = async (id) => {
  const connection = await pool.getConnection();

  const [rows] = await connection.query("SELECT * FROM notes WHERE id = ?", [id]);

  connection.release();

  return rows[0] ?? {};
}

const noteCreate = async (note) => {
  const connection = await pool.getConnection();

  const { description } = note;

  const [result] = await connection.query(
    "INSERT INTO notes (description) VALUES (?)",
    [description]
  );

  const createdNoteId = result.insertId;
  connection.release();

  return createdNoteId;
};

const noteUpdate = async (note, id) => {
  const connection = await pool.getConnection();

  const { description } = note;

  await connection.query(
    "UPDATE notes SET description = ? WHERE id = ?",
    [description, id]
  );

  connection.release();

  return true;
};

const noteDelete = async (id) => {
  const connection = await pool.getConnection();

  await connection.query("DELETE FROM notes WHERE id = ?", [id]);

  connection.release();

  return true;
};

module.exports = {
  notePaginate,
  noteGetById,
  noteCreate,
  noteUpdate,
  noteDelete,
  noteAll,
};
