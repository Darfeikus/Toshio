"use strict";
const cors = require("cors");
const express = require("express");
const bodyParser = require("body-parser");
const authRoutes = require("./auth/auth.routes");
const properties = require("./config/properties");
const DB = require("./config/db");

// init db
DB();

const app = express();
const router = express.Router();

const bodyParserJSON = bodyParser.json();
const bodyParserURLEncoded = bodyParser.urlencoded({ extended: true });

app.use(bodyParserJSON);
app.use(bodyParserURLEncoded);

app.use(cors());

app.use("/api", router);
authRoutes(router);

router.get("/", (req, res) => {
  res.send("API Home");
});

app.use(router);
app.listen(properties.PORT, () =>
  console.log(`Server Up & Running on Port ${properties.PORT}`)
);
