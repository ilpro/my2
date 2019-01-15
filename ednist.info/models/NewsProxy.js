const STATUS = require('./STATUS');
const ErrorHandler = require('./ErrorHandler');

const News = require('./News');

const {cacheNewsMaxAge, cacheNewsListMaxAge, cacheClearInterval, newsNumToCache} = require('../config/config.js');
const LRU = require("lru-cache");
const newsCache = LRU({maxAge: cacheNewsMaxAge});
const newsListCache = LRU({maxAge: cacheNewsListMaxAge});

const NewsProxy = {
  _newsCache: newsCache, // cache for single news
  _newsLists: newsListCache, // cache for list of news

  /**
   * Check if news exists in cache
   * If not - get news from DB and cache it
   *
   * @param newsId
   * @returns {Promise<{success: boolean, status: string, news: ({length}|Object|*)}>}
   */
  async getOneById(newsId) {
    try {
      if (!newsId) {
        return {success: false, status: STATUS.INVALID_INPUT_PARAMETERS, message: 'Missed parameter "newsId"'}
      }

      const cachedNews = this._newsCache.get(newsId);
      if (cachedNews) {
        return {success: true, status: STATUS.OK, news: cachedNews};
      }

      const newsRes = await News.getOneById(newsId);
      if(!newsRes.news) return newsRes;

      this._newsCache.set(newsId, newsRes.news);

      return newsRes;
    } catch (err) {
      return ErrorHandler(err);
    }
  },

  /**
   * Check if news feed exists in cache
   * if not - get it from db and save into cache
   *
   * @param limit
   * @param offset
   * @returns {Promise<{success: boolean, status: string, news: ({length}|Object|*)}>}
   */
  async getFeed(limit = 50, offset = 0) {
    try {
      let cachedNews = this._newsLists.get('feed');

      // get fixed number of news to cache if they not in cache
      if(!cachedNews || !cachedNews.length){
        const newsRes = await News.getFeed(newsNumToCache, 0);
        if(!newsRes.news || !newsRes.news.length) return newsRes;

        cachedNews = newsRes.news;
        this._newsLists.set('feed', cachedNews);
      }

      // try ti find news by offset and limit from cache
      const userNews = cachedNews.slice(offset, offset + limit);
      if(userNews.length) return {success: true, status: STATUS.OK, news: userNews};

      // get news history if news don't exist in the cache
      return await News.getFeed(limit, offset);
    } catch (err) {
      return ErrorHandler(err);
    }
  },

  /**
   * Check if popular news exist in cache
   * if not - get it from db and save into cache
   *
   * @param limit
   * @param offset
   * @returns {Promise<{success: boolean, status: string, news: ({length}|Object|*)}>}
   */
  async getPopularForDay(limit = 50, offset = 0) {
    try {
      let cachedNews = this._newsLists.get('popular');

      // get fixed number of news to cache if they not in cache
      if(!cachedNews || !cachedNews.length){
        const newsRes = await News.getPopularForDay(50, 0);
        if(!newsRes.news || !newsRes.news.length) return newsRes;

        cachedNews = newsRes.news;
        this._newsLists.set('popular', cachedNews);
      }

      // try to find news by offset and limit from cache
      const userNews = cachedNews.slice(offset, offset + limit);
      if(userNews.length) return {success: true, status: STATUS.OK, news: userNews};

      // get news history if news don't exist in the cache
      return await News.getPopularForDay(limit, offset);
    } catch (err) {
      return ErrorHandler(err);
    }
  },

  /**
   * Check if main news exist in cache
   * if not - get it from db and save into cache
   *
   * @param limit
   * @param offset
   * @returns {Promise<{success: boolean, status: string, news: ({length}|Object|*)}>}
   */
  async getMain(limit = 10, offset = 0) {
    try {
      let cachedNews = this._newsLists.get('main');

      // get fixed number of news to cache if they not in cache
      if(!cachedNews || !cachedNews.length){
        const newsRes = await News.getMain(50, 0);
        if(!newsRes.news || !newsRes.news.length) return newsRes;

        cachedNews = newsRes.news;
        this._newsLists.set('main', cachedNews);
      }

      // try to find news by offset and limit from cache
      const userNews = cachedNews.slice(offset, offset + limit);
      if(userNews.length) return {success: true, status: STATUS.OK, news: userNews};

      // get news history if news don't exist in the cache
      return await News.getMain(limit, offset);
    } catch (err) {
      return ErrorHandler(err);
    }
  },

  /**
   * Check if exclusive news exist in cache
   * if not - get it from db and save into cache
   *
   * @param limit
   * @param offset
   * @returns {Promise<{success: boolean, status: string, news: ({length}|Object|*)}>}
   */
  async getExclusive(limit = 50, offset = 0) {
    try {
      let cachedNews = this._newsLists.get('exclusive');

      // get fixed number of news to cache if they not in cache
      if(!cachedNews || !cachedNews.length){
        const newsRes = await News.getExclusive(50, 0);
        if(!newsRes.news || !newsRes.news.length) return newsRes;

        cachedNews = newsRes.news;
        this._newsLists.set('exclusive', cachedNews);
      }

      // try to find news by offset and limit from cache
      const userNews = cachedNews.slice(offset, offset + limit);
      if(userNews.length) return {success: true, status: STATUS.OK, news: userNews};

      // get news history if news don't exist in the cache
      return await News.getExclusive(limit, offset);
    } catch (err) {
      return ErrorHandler(err);
    }
  },

  /**
   * Check if news by category exist in cache
   * if not - get it from db and save into cache
   *
   * @param category
   * @param limit
   * @param offset
   * @returns {Promise<{success: boolean, status: string, news: ({length}|Object|*)}>}
   */
  async getByCategory(category, limit = 50, offset = 0) {
    try {
      if(!category || !category.length || typeof category != 'string'){
        return ErrorHandler({success: false, status: STATUS.INVALID_PARAMETERS, message: 'Category is invalid or not specified'})
      }

      let cachedNews = this._newsLists.get(category);

      // get fixed number of news to cache if they not in cache
      if(!cachedNews || !cachedNews.length){
        const newsRes = await News.getByCategory(category, 50, 0);
        if(!newsRes.news || !newsRes.news.length) return newsRes;

        cachedNews = newsRes.news;
        this._newsLists.set(category, cachedNews);
      }

      // try to find news by offset and limit from cache
      const userNews = cachedNews.slice(offset, offset + limit);
      if(userNews.length) return {success: true, status: STATUS.OK, news: userNews};

      // get news history if news don't exist in the cache
      return await News.getByCategory(category, limit, offset);
    } catch (err) {
      return ErrorHandler(err);
    }
  },

  /**
   * Check if news by type exist in cache
   * if not - get it from db and save into cache
   *
   * @param type
   * @param limit
   * @param offset
   * @returns {Promise<{success: boolean, status: string, news: ({length}|Array|*)}>}
   */
  async getByType(type, limit = 50, offset = 0) {
    try {
      if(!type || !type.length || typeof type !== 'string'){
        return ErrorHandler({success: false, status: STATUS.INVALID_PARAMETERS, message: 'Type is invalid or not specified'})
      }

      let cachedNews = this._newsLists.get(type);

      // get fixed number of news to cache if they not in cache
      if(!cachedNews || !cachedNews.length){
        const newsRes = await News.getByType(type, 50, 0);
        if(!newsRes.news || !newsRes.news.length) return newsRes;

        cachedNews = newsRes.news;
        this._newsLists.set(type, cachedNews);
      }

      // try to find news by offset and limit from cache
      const userNews = cachedNews.slice(offset, offset + limit);
      if(userNews.length) return {success: true, status: STATUS.OK, news: userNews};

      // get news history if news don't exist in the cache
      return await News.getByType(type, limit, offset);
    } catch (err) {
      return ErrorHandler(err);
    }
  },

  /**
   * Translate request to News model
   *
   * @param searchStr
   * @returns {Promise}
   *   {success, status, news}
   */
  async search(searchStr) {
    try {
      if(!searchStr || !searchStr.length || typeof searchStr !== 'string'){
        return ErrorHandler({success: false, status: STATUS.INVALID_PARAMETERS, message: 'Search string is invalid or not specified', news: []})
      }
      return await News.search(searchStr);
    } catch (err) {
      return ErrorHandler(err);
    }
  },

  /**
   * Translate request to News model
   *
   * @param hashtag
   * @returns {Promise}
   *   {success, status, news}
   */
  async searchByHashtag(hashtag) {
    try {
      if(!hashtag || !hashtag.length || typeof hashtag !== 'string'){
        return ErrorHandler({success: false, status: STATUS.INVALID_PARAMETERS, message: 'hashtag is invalid or not specified', news: []})
      }
      return await News.searchByHashtag(hashtag);
    } catch (err) {
      return ErrorHandler(err);
    }
  },

  _clearOldValues(){
    this._newsCache.prune();
    this._newsLists.prune();
  },
};

/**
 * Clear news cache every 48 hours
 */
const clearCacheInterval = setInterval(() => NewsProxy._clearOldValues(), cacheClearInterval);

module.exports = NewsProxy;