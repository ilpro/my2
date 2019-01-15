'use strict';
const mysql = require('mysql');

module.exports = mysql.createPool({
  host: 'localhost',
  user: 'root',
  password: 'Gh6M54Bk',
  database: 'youproud.com',
  multipleStatements: true
});