const Router = require ('koa-router');
const router = new Router();

router.get('/rss', async ctx => {
  return ctx.render('rss', {page: 'RSS'});
});

router.get('/about', async ctx => {
  return ctx.render('about');
});

router.get('/contacts', async ctx => {
  return ctx.render('contacts');
});

router.get('/jobs', async ctx => {
  return ctx.render('jobs');
});


router.get('/use-material', async ctx => {
  return ctx.render('use-material');
});




module.exports = router;