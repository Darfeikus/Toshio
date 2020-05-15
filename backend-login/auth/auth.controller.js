const User = require("./auth.dao");
const jwt = require("jsonwebtoken");
const bcrypt = require("bcryptjs");
const SECRET_KEY = "quierobesaratoñito";

exports.createUser = (req, res, next) => {
  const newUser = {
    mat: req.body.mat,
    type: req.body.type,
    password: bcrypt.hashSync(req.body.password)
  };

  User.create(newUser, (err, user) => {
    if (err && err.code == 11000) {
      return res.status(409).send("La matrícula ya existe");
    }
    const expiresIn = 8 * 60 * 60;
    const accessToken = jwt.sign({ id: user.id }, SECRET_KEY, {
      expiresIn
    });
    const dataUser = {
      mat: user.mat,
      type: user.type,
      accessToken,
      expiresIn
    };
    res.send({ dataUser });
  });
};

exports.loginUser = (req, res, next) => {
  const userData = {
    mat: req.body.mat,
    password: req.body.password
  };
  User.findOne({ mat: userData.mat }, (err, user) => {
    if (err) {
      return res.status(500).send("Server error");
    }
    if (!user) {
      // puede regresar de igual manera un 'User not found' tendremos que checar la seguridad al respecto
      res.status(409).send({ message: "Something went wrong" });
    } else {
      const resultPassword = bcrypt.compareSync(
        userData.password,
        user.password
      );
      if (resultPassword) {
        const expiresIn = 8 * 60 * 60;
        const accessToken = jwt.sign({ id: user.id }, SECRET_KEY, {
          expiresIn
        });
        const dataUser = {
          mat: user.mat,
          accessToken,
          expiresIn
        };
        res.send({ dataUser });
      } else {
        // Contraseña incorrecta
        res.status(409).send({ message: "Something went wrong" });
      }
    }
  });
};
