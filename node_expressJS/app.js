
const express = require('express');
const app = express();
const port = 3000;

const authenticationFilter = require('./authentication');

// Add an authentication filter to every routes
app.use(authenticationFilter);

app.get('/', (req, res) => {
  res.send('Your are connected to the portail!');
})

app.listen(port, () => {
  console.log(`Example app listening at http://localhost:${port}`);
})
