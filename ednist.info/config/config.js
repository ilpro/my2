const config = require('./config.json');

// if server runs locally, NODE_ENV variable will not exists, so we set environment === 'development'
// if server runs via mocha, environment === 'test' (see 'scripts' in package.json)
// if server runs via Heroku.com, environment === 'production' by default
const env = process.env.NODE_ENV || 'development';
module.exports = config[env];