const http = require('http');
const https = require('https');
const path = require('path');

/** Third-party modules */
const Koa = require('koa');
const Router = require('koa-router');
const render = require('koa-ejs');
const serve = require('koa-static');
const MobileDetect = require('mobile-detect');

/** Project modules */
const mainRouter = require('./routes/mainRouter');
const newsRouter = require('./routes/newsRouter');
const dossierRouter = require('./routes/dossierRouter');
const staticPagesRouter = require('./routes/staticPagesRouter');
const adminRouter = require('./routes/adminRouter');
const {HTTP_PORT, HTTPS_PORT} = require('./config/config.js');

// app.env defaulting to the NODE_ENV or "development"
const app = new Koa();
const router = new Router();

render(app, {
  root: path.join(__dirname, 'views'),
  layout: 'layout',
  viewExt: 'ejs',
  cache: require('lru-cache')(500), // last 500 pages. Caching only view files, not dynamical data passed as 2 arg in ctx.render
  debug: false,
  // cache: false
});

// Koa2 can spin up the same application as both HTTP and HTTPS or on multiple addresses:
const server = http.createServer(
  app.callback()).listen(HTTP_PORT,
  () => console.log('HTTP server is running on port ' + HTTP_PORT)
);
const httpsServer = https.createServer(app.callback()).listen(
  HTTPS_PORT,
  () => console.log('HTTPS server is running on port ' + HTTPS_PORT)
);

app.use(serve(path.join(__dirname,'public')));


// Koa using module from https://github.com/jshttp/http-errors
app.use( async (ctx, next) => {
  try {
    await next()
  } catch(err) {
    console.log(err.status);
    ctx.status = err.status || 500;
    ctx.body = err.message;
  }
});

// if users from mobile browser, redirect them
app.use(async (ctx, next) => {
  const url = ctx.request.url;
  const md = new MobileDetect(ctx.request.header['user-agent']);

  if (md.mobile()) {
    return ctx.redirect('https://m.ednist.info' + url);
  } else {
    return next();
  }
});

app.use(mainRouter.routes()).use(mainRouter.allowedMethods());
app.use(newsRouter.routes()).use(newsRouter.allowedMethods());
app.use(dossierRouter.routes()).use(dossierRouter.allowedMethods());
app.use(staticPagesRouter.routes()).use(staticPagesRouter.allowedMethods());
app.use(adminRouter.routes()).use(dossierRouter.allowedMethods());

module.exports = app;

// Cookies
// ctx.cookies.get(name, [options]);
// ctx.cookies.set(name, value, [options]);

// The recommended namespace for passing information through middleware and to your frontend views.
// ctx.state.user = await User.find(id);


// Errors
// ctx.throw([status], [msg], [properties])
// Helper method to throw an error with a .status property defaulting to 500 that will allow Koa to respond appropriately.
// The following combinations are allowed:
// ctx.throw(400);
// ctx.throw(400, 'name required');
// ctx.throw(400, 'name required', { user: user });

// For example ctx.throw(400, 'name required') is equivalent to:
// const err = new Error('name required');
// err.status = 400;
// err.expose = true;
// throw err;

