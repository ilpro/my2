const Router = require ('koa-router');
const router = new Router();

const STATUS = require('../models/STATUS');
const News = require('../models/NewsProxy');
const Dossier = require('../models/DossierProxy');

router.get('/', async ctx => {
  const getMainRes = await News.getMain(10);
  const getPopularRes = await News.getPopularForDay(4);
  const getDossierRes = await Dossier.getRandom(4);
  const getFeedRes = await News.getFeed(27);
  const getPoliticsRes = await News.getByCategory('politics', 4);
  const getResonanceRes = await News.getByCategory('resonance', 3);

  const resData = {
    listMain: getMainRes.news,
    listPopular: getPopularRes.news,
    listRandomDossiers: getDossierRes.dossiers,
    listFeed: getFeedRes.news,
    listPolitics: getPoliticsRes.news,
    listResonance: getResonanceRes.news,
  };

  return ctx.render('main', {page: 'Головна', ...resData});
});

router.get('/search/:searchStr', async ctx => {
  const searchStr = ctx.params.searchStr;
  const newsRes = await News.search(searchStr);

  if(!newsRes.success) return ctx.render('error/internal');
  if(newsRes.status === STATUS.NOT_FOUND) return ctx.render('error/searchNotFound', {searchStr});  // checks for errors

  const metaData = {title: 'пошук ' + searchStr, keyWords: [searchStr]};
  return ctx.render('newsList', {page: 'Пошук: ' + searchStr, news: newsRes.news, metaData});
});

router.get('/tags/:hashtag', async ctx => {
  const searchStr = ctx.params.hashtag;
  const newsRes = await News.searchByHashtag(searchStr);

  if(!newsRes.success) return ctx.render('error/internal');
  if(newsRes.status === STATUS.NOT_FOUND) return ctx.render('error/searchNotFound', {searchStr});  // checks for errors

  const metaData = {title: 'пошук ' + searchStr, keyWords: [searchStr]};
  return ctx.render('newsList', {page: 'Пошук: ' + searchStr,news: newsRes.news, metaData});
});

module.exports = router;