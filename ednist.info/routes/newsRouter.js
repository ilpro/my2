const Router = require ('koa-router');
const router = new Router({prefix: '/news'});

const STATUS = require('../models/STATUS');
const News = require('../models/NewsProxy');

router.get('/', async ctx => {
  const newsRes = await News.getFeed();

  if(!newsRes.success) return ctx.render('error/internal');
  if(newsRes.status === STATUS.NOT_FOUND) return ctx.render('error/notFound');  // checks for errors

  return ctx.render('newsList', {page: 'Стрiчка новин', news: newsRes.news});
});

router.get('/popular', async ctx => {
  const newsRes = await News.getPopularForDay();

  if(!newsRes.success) return ctx.render('error/internal');
  if(newsRes.status === STATUS.NOT_FOUND) return ctx.render('error/notFound');  // checks for errors

  return ctx.render('newsList', {page: 'Популярнi', news: newsRes.news});
});

router.get('/main', async ctx => {
  const newsRes = await News.getMain();

  if(!newsRes.success) return ctx.render('error/internal');
  if(newsRes.status === STATUS.NOT_FOUND) return ctx.render('error/notFound');  // checks for errors

  return ctx.render('newsList', {page: 'Головнi новини', news: newsRes.news});
});

router.get('/exclusive', async ctx => {
  const newsRes = await News.getExclusive();

  if(!newsRes.success) return ctx.render('error/internal');
  if(newsRes.status === STATUS.NOT_FOUND) return ctx.render('error/notFound');  // checks for errors

  return ctx.render('newsList', {page: 'Ексклюзив', news: newsRes.news});
});

// news by category
router.get('/ukraine', async ctx => {
  const newsRes = await News.getByCategory('ukraine');

  if(!newsRes.success) return ctx.render('error/internal');
  if(newsRes.status === STATUS.NOT_FOUND) return ctx.render('error/notFound');  // checks for errors

  return ctx.render('newsList', {page: 'Україна', news: newsRes.news});
});

router.get('/politics', async ctx => {
  const newsRes = await News.getByCategory('politics');

  if(!newsRes.success) return ctx.render('error/internal');
  if(newsRes.status === STATUS.NOT_FOUND) return ctx.render('error/notFound');  // checks for errors

  return ctx.render('newsList', {page: 'Політика', news: newsRes.news});
});

router.get('/economics', async ctx => {
  const newsRes = await News.getByCategory('economics');

  if(!newsRes.success) return ctx.render('error/internal');
  if(newsRes.status === STATUS.NOT_FOUND) return ctx.render('error/notFound');  // checks for errors

  return ctx.render('newsList', {page: 'Економіка та бізнес', news: newsRes.news});
});

router.get('/society', async ctx => {
  const newsRes = await News.getByCategory('society');

  if(!newsRes.success) return ctx.render('error/internal');
  if(newsRes.status === STATUS.NOT_FOUND) return ctx.render('error/notFound');  // checks for errors

  return ctx.render('newsList', {page: 'Суспільство', news: newsRes.news});
});

router.get('/world', async ctx => {
  const newsRes = await News.getByCategory('world');

  if(!newsRes.success) return ctx.render('error/internal');
  if(newsRes.status === STATUS.NOT_FOUND) return ctx.render('error/notFound');  // checks for errors

  return ctx.render('newsList', {page: 'Світ', news: newsRes.news});
});

router.get('/accidents', async ctx => {
  const newsRes = await News.getByCategory('accidents');

  if(!newsRes.success) return ctx.render('error/internal');
  if(newsRes.status === STATUS.NOT_FOUND) return ctx.render('error/notFound');  // checks for errors

  return ctx.render('newsList', {page: 'Події', news: newsRes.news});
});

router.get('/science', async ctx => {
  const newsRes = await News.getByCategory('science');

  if(!newsRes.success) return ctx.render('error/internal');
  if(newsRes.status === STATUS.NOT_FOUND) return ctx.render('error/notFound');  // checks for errors

  return ctx.render('newsList', {page: 'Наука та технології', news: newsRes.news});
});

router.get('/internet', async ctx => {
  const newsRes = await News.getByCategory('internet');

  if(!newsRes.success) return ctx.render('error/internal');
  if(newsRes.status === STATUS.NOT_FOUND) return ctx.render('error/notFound');  // checks for errors

  return ctx.render('newsList', {page: 'Інтернет', news: newsRes.news});
});

router.get('/culture', async ctx => {
  const newsRes = await News.getByCategory('culture');

  if(!newsRes.success) return ctx.render('error/internal');
  if(newsRes.status === STATUS.NOT_FOUND) return ctx.render('error/notFound');  // checks for errors

  return ctx.render('newsList', {page: 'Шоу-біз та культура', news: newsRes.news});
});

router.get('/sport', async ctx => {
  const newsRes = await News.getByCategory('sport');

  if(!newsRes.success) return ctx.render('error/internal');
  if(newsRes.status === STATUS.NOT_FOUND) return ctx.render('error/notFound');  // checks for errors

  return ctx.render('newsList', {page: 'Спорт', news: newsRes.news});
});

router.get('/infographics', async ctx => {
  const newsRes = await News.getByCategory('infographics');

  if(!newsRes.success) return ctx.render('error/internal');
  if(newsRes.status === STATUS.NOT_FOUND) return ctx.render('error/notFound');  // checks for errors

  return ctx.render('newsList', {page: 'Інфографіка', news: newsRes.news});
});

router.get('/resonance', async ctx => {
  const newsRes = await News.getByCategory('resonance');

  if(!newsRes.success) return ctx.render('error/internal');
  if(newsRes.status === STATUS.NOT_FOUND) return ctx.render('error/notFound');  // checks for errors

  return ctx.render('newsList', {page: 'Резонанс', news: newsRes.news});
});

router.get('/war', async ctx => {
  const newsRes = await News.getByCategory('war');

  if(!newsRes.success) return ctx.render('error/internal');
  if(newsRes.status === STATUS.NOT_FOUND) return ctx.render('error/notFound');  // checks for errors

  return ctx.render('newsList', {page: 'Війна', news: newsRes.news});
});

router.get('/kyiv', async ctx => {
  const newsRes = await News.getByCategory('kyiv');

  if(!newsRes.success) return ctx.render('error/internal');
  if(newsRes.status === STATUS.NOT_FOUND) return ctx.render('error/notFound');  // checks for errors

  return ctx.render('newsList', {page: 'Київ', news: newsRes.news});
});

router.get('/interview', async ctx => {
  const newsRes = await News.getByCategory('interview');

  if(!newsRes.success) return ctx.render('error/internal');
  if(newsRes.status === STATUS.NOT_FOUND) return ctx.render('error/notFound');  // checks for errors

  return ctx.render('newsList', {page: 'Iнтерв’ю', news: newsRes.news});
});


// news by type
router.get('/analytics', async ctx => {
  const newsRes = await News.getByType('analytics');

  if(!newsRes.success) return ctx.render('error/internal');
  if(newsRes.status === STATUS.NOT_FOUND) return ctx.render('error/notFound');  // checks for errors

  return ctx.render('newsList', {page: 'Аналітика', news: newsRes.news});
});

router.get('/publications', async ctx => {
  const newsRes = await News.getByType('publications');

  if(!newsRes.success) return ctx.render('error/internal');
  if(newsRes.status === STATUS.NOT_FOUND) return ctx.render('error/notFound');  // checks for errors

  return ctx.render('newsList', {page: 'Публікації', news: newsRes.news});
});

router.get('/:newsId', async ctx => {
  const newsRes = await News.getOneById(ctx.params.newsId);

  if(!newsRes.success) return ctx.render('error/internal');
  if(newsRes.status === STATUS.NOT_FOUND) return ctx.render('error/notFound');  // checks for errors

  const metaData = {
    title: newsRes.news[0].newsHeader,
    content: newsRes.news[0].newsSubheader,
    keyWords: [newsRes.news[0].newsTags],
    image: 'https://www.ednist.info/media/images/' + newsRes.news[0].newsId + '/main/800.jpg',
  };
  return ctx.render('news', {news: newsRes.news[0], metaData});
});

module.exports = router;