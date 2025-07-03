const express = require('express');
const mysql = require('mysql2/promise');
const cors = require('cors');
const app = express();
const port = 3000;

// Middleware
app.use(cors());
app.use(express.json());

// Configuración de la conexión a MySQL
const dbConfig = {
  host: 'localhost',
  user: 'root',
  password: '', // Cambia esto si configuraste una contraseña en Laragon
  database: 'kanban_dashboard',
  port: 3306 // Asegúrate de que este sea el puerto correcto en Laragon
};

// Crear un pool de conexiones
const pool = mysql.createPool(dbConfig);

// Verificar la conexión
pool.getConnection()
  .then(() => console.log('Conectado a la base de datos MySQL'))
  .catch(err => console.error('Error al conectar con la base de datos:', err.message));

// Endpoints para usuarios
app.get('/users', async (req, res) => {
  try {
    const [rows] = await pool.query('SELECT * FROM users');
    res.json(rows);
  } catch (err) {
    res.status(500).json({ error: err.message });
  }
});

app.post('/users', async (req, res) => {
  const { name } = req.body;
  if (!name) return res.status(400).json({ error: 'El nombre es requerido' });
  try {
    const [result] = await pool.query('INSERT INTO users (name) VALUES (?)', [name]);
    res.json({ id: result.insertId, name });
  } catch (err) {
    res.status(500).json({ error: err.message });
  }
});

app.delete('/users/:id', async (req, res) => {
  const { id } = req.params;
  try {
    const [result] = await pool.query('DELETE FROM users WHERE id = ?', [id]);
    if (result.affectedRows === 0) {
      return res.status(404).json({ error: 'Usuario no encontrado' });
    }
    res.json({ message: 'Usuario eliminado' });
  } catch (err) {
    res.status(500).json({ error: err.message });
  }
});

// Endpoints para proyectos
app.get('/projects', async (req, res) => {
  try {
    const [rows] = await pool.query('SELECT * FROM projects');
    res.json(rows);
  } catch (err) {
    res.status(500).json({ error: err.message });
  }
});

app.post('/projects', async (req, res) => {
  const { name, desc, userId, start, end, progress, status } = req.body;
  if (!name || !userId || !start || !end) return res.status(400).json({ error: 'Faltan datos requeridos' });
  try {
    const [result] = await pool.query(
      'INSERT INTO projects (name, `desc`, userId, `start`, `end`, progress, status) VALUES (?, ?, ?, ?, ?, ?, ?)',
      [name, desc, userId, start, end, progress || 0, status || 'todo']
    );
    res.json({ id: result.insertId, name, desc, userId, start, end, progress, status });
  } catch (err) {
    res.status(500).json({ error: err.message });
  }
});

app.put('/projects/:id', async (req, res) => {
  const { id } = req.params;
  const { name, desc, userId, start, end, progress, status } = req.body;
  try {
    const [result] = await pool.query(
      'UPDATE projects SET name = ?, `desc` = ?, userId = ?, `start` = ?, `end` = ?, progress = ?, status = ? WHERE id = ?',
      [name, desc, userId, start, end, progress, status, id]
    );
    if (result.affectedRows === 0) {
      return res.status(404).json({ error: 'Proyecto no encontrado' });
    }
    res.json({ id, name, desc, userId, start, end, progress, status });
  } catch (err) {
    res.status(500).json({ error: err.message });
  }
});

app.delete('/projects/:id', async (req, res) => {
  const { id } = req.params;
  try {
    const [result] = await pool.query('DELETE FROM projects WHERE id = ?', [id]);
    if (result.affectedRows === 0) {
      return res.status(404).json({ error: 'Proyecto no encontrado' });
    }
    res.json({ message: 'Proyecto eliminado' });
  } catch (err) {
    res.status(500).json({ error: err.message });
  }
});

// Endpoints para autenticación
app.get('/auth', async (req, res) => {
  try {
    const [rows] = await pool.query('SELECT isAdminLoggedIn FROM auth WHERE id = 1');
    res.json({ isAdminLoggedIn: rows.length ? rows[0].isAdminLoggedIn : false });
  } catch (err) {
    res.status(500).json({ error: err.message });
  }
});

app.post('/auth', async (req, res) => {
  const { isAdminLoggedIn } = req.body;
  try {
    const [result] = await pool.query('UPDATE auth SET isAdminLoggedIn = ? WHERE id = 1', [isAdminLoggedIn]);
    if (result.affectedRows === 0) {
      await pool.query('INSERT INTO auth (id, isAdminLoggedIn) VALUES (1, ?)', [isAdminLoggedIn]);
    }
    res.json({ isAdminLoggedIn });
  } catch (err) {
    res.status(500).json({ error: err.message });
  }
});

// Iniciar el servidor
app.listen(port, () => {
  console.log(`Servidor corriendo en http://localhost:${port}`);
});