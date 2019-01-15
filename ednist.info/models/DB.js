'use strict';
const mysql = require('mysql');
const config = require('../config/config');

const DB = mysql.createPool({
  connectionLimit: 100,
  host: config.DB_URI,
  user: config.DB_user,
  password: config.DB_password,
  database: config.DB_name,
  multipleStatements: true,
});

module.exports = DB;
