const DB = require( './DB' );

const dbPromiseProxy = new Proxy(DB, {
  get: (DB, prop, receiver) => {
    // intercept only "db.query" method
    const value = Reflect.get(DB, prop);
    if (prop !== 'query') return value;

    return new Proxy(value, {
      // This handler will run when DB.query() is invoked
      // - fn is the function being invoked
      // - thisArg is the receiver object
      // - args is the arguments list
      apply: (fn, thisArg, args) => {
        // return Reflect.apply(fn, thisArg, args);
        return new Promise((res, rej) => {
          const sql = args[0];
          const params = args[1] && args[1].length ? args[1] : undefined;

          DB.query(sql, params, (err, results, fields) => {
            if (err) return rej(err);
            return res(results, fields);
          })
        })
      }
    });
  }
});

module.exports = dbPromiseProxy;


