const Router = require ('koa-router');
const router = new Router({prefix: '/admin'});

const STATUS = require('../models/STATUS');

router.get('/', async ctx => {
  return ctx.redirect('http://test.ednist.info/admin/');
});

module.exports = router;