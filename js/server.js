// server.js
const express = require("express");
const fs = require("fs");
const path = require("path");

const app = express();
const imageDir = path.join(__dirname, "imagenes");

// Servir archivos estáticos (imágenes y otros)
app.use("/imagenes", express.static(imageDir));

app.get("/imageList", (req, res) => {
  fs.readdir(imageDir, (err, files) => {
    if (err) {
      return res.status(500).send("Error reading image directory.");
    }
    res.json(files.filter((file) => /\.(jpg|jpeg|png|gif)$/i.test(file)));
  });
});

app.listen(3000, () => {
  console.log("Server running on port 3000");
});