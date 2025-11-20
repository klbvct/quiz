const express = require('express');
const cors = require('cors');

const app = express();
const PORT = process.env.PORT || 3000;

app.use(cors());
app.use(express.json());

app.get('/', (req, res) => {
  res.send('Check project is running');
});

app.post('/check', (req, res) => {
  const input = req.body && req.body.input;
  const ok = typeof input === 'string' && input.trim() !== '';
  res.json({ ok });
});

app.listen(PORT, () => {
  console.log(`Server is running on http://localhost:${PORT}`);
});
