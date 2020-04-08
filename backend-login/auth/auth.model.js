const mongoose = require("mongoose");
const Schema = mongoose.Schema;
mongoose.set("useCreateIndex", true);

const userSchema = new Schema(
  {
    mat: {
      type: String,
      required: true,
      trim: true,
      unique: true
    },
    type: {
      type: String,
      required: true,
      enum: ["Student", "Admin", "Professor"]
    },
    password: {
      type: String,
      required: true
    }
  },
  {
    timestamps: true
  }
);

module.exports = userSchema;
