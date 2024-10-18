const express = require('express');
const bodyParser = require('body-parser');
const { getMesas, getMesaById, createMesa, updateMesa, deleteMesa } = require('./mesaController');
const mongoose = require('mongoose');

const app = express();
const port = process.env.PORT || 3000;

// Conexión a MongoDB
const uri = "mongodb+srv://mvilceb:noliu111@cluster0.iqwdukh.mongodb.net/?retryWrites=true&w=majority";
const dbName = "restaurante30";

mongoose.connect(uri, {
  useNewUrlParser: true,
  useUnifiedTopology: true,
  dbName: dbName
}).then(() => {
  console.log("Conectado a MongoDB!");
}).catch(err => {
  console.error("Erro de conexión a MongoDB:", err);
});

// Middlewares
app.use(bodyParser.json());

// Rutas para CRUD de mesas
app.get('/api/mesas', getMesas);
app.get('/api/mesas/:id', getMesaById);
app.post('/api/mesas', createMesa);
app.put('/api/mesas/:id', updateMesa);
app.delete('/api/mesas/:id', deleteMesa);

app.listen(port, () => {
  console.log(`Servidor escuchando en http://localhost:${port}`);
});