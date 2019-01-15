const db = require('./DBPromise');

const STATUS = require('./STATUS');
const ErrorHandler = require('./ErrorHandler');

const Tag = require('./Tag');
const Time = require('./Time');
const Text = require('./Text');
const Video = require('./Video');

const News = {

  /**
   * Get one news by ID
   *
   * @param newsId
   * @returns {Promise}
   *   {success, status, news};
   */
  async getOneById(newsId) {
    try {
      if (!newsId) {
        return {success: false, status: STATUS.INVALID_INPUT_PARAMETERS, message: 'Missed parameter "newsId"', news: []}
      }

      const newsRes = await db.query(
        'SET SESSION group_concat_max_len = 1000000;\
         SELECT\
           news.newsId,\
           newsHeader,\
           newsSubheader,\
           newsText,\
           newsSeoTitle,\
           newsSeoDesc,\
           newsSeoKeywords,\
           newsTimePublic AS time,\
           newsVideo,\
           img.imgName AS newsImage,\
           cat.categoryTranslit AS category,\
           cat.categoryName AS categoryUkr,\
           (GROUP_CONCAT(tags.tagName)) AS newsTags,\
           (\
               SELECT\
                 CONCAT(\'[\',\
                   GROUP_CONCAT(\
                     CONCAT(\'{\
                       "newsId":"\', news2.newsId, \'",\
                       "newsHeader":"\', news2.newsHeader, \'",\
                       "newsImage":"\', img2.imgName, \'"\
                     }\') SEPARATOR ","\
                   ), \'\
                 ]\')\
               FROM ctbl_connect AS related\
               LEFT JOIN tbl_news AS news2 ON related.newsConnect = news2.newsId\
               LEFT JOIN ctbl_img AS img2 ON img2.newsId = news2.newsId\
               WHERE related.newsId = news.newsId AND img2.imgMain = 1\
           ) AS relatedNews\
         FROM tbl_news AS news\
         LEFT JOIN ctbl_img AS img ON img.newsId = news.newsId AND img.imgMain = 1\
         LEFT JOIN ctbl_tag AS news_tags ON news_tags.newsId = news.newsId\
         LEFT JOIN tbl_tag AS tags ON tags.tagId = news_tags.tagId\
         LEFT JOIN tbl_category AS cat ON cat.categoryId = news.categoryId\
         WHERE news.newsId = ?;',
        [newsId]
      );
      if(!newsRes[1].length){
        return {success: true, status: STATUS.NOT_FOUND, message: 'No news found for id ' + newsId, news: []};
      }

      const newsBuldRes = await this._newsBuilder(newsRes[1]);
      if(!newsBuldRes.success || !newsBuldRes.news.length) return ErrorHandler(newsBuldRes);

      return {success: true, status: STATUS.OK, news: newsBuldRes.news};
    } catch (err) {
      return ErrorHandler(err);
    }
  },

  /**
   * Get list of latest news ordered by time (newest first)
   *
   * @param limit
   * @param offset
   * @returns {Promise}
   *   {success, status, news}
   */
  async getFeed(limit = 50, offset = 0){
    try {
      const newsRes = await db.query(
        'SELECT\
           news.newsId,\
           newsHeader,\
           newsSubheader,\
           newsTimePublic AS time,\
           img.imgName AS newsImage, \
           cat.categoryTranslit AS category, \
           cat.categoryName AS categoryUkr, \
           (\
               SELECT\
                 (GROUP_CONCAT(tags.tagName)) AS newsTags\
               FROM ctbl_tag AS news_tags\
               LEFT JOIN tbl_tag AS tags ON tags.tagId = news_tags.tagId\
               WHERE news_tags.newsId = news.newsId\
           ) AS newsTags\
         FROM tbl_news AS news\
         LEFT JOIN ctbl_img AS img ON img.newsId = news.newsId AND img.imgMain = 1\
         LEFT JOIN tbl_category AS cat ON cat.categoryId = news.categoryId\
         WHERE newsStatus = 4\
         ORDER BY newsTimePublic DESC\
         LIMIT ? OFFSET ?',
        [limit, offset]
      );
      if(!newsRes.length) return {success: true, status: STATUS.NOT_FOUND, news: [], message: 'No news found'};

      const newsBuldRes = await this._newsBuilder(newsRes);
      if(!newsBuldRes.success || !newsBuldRes.news.length) return ErrorHandler(newsBuldRes);

      return {success: true, status: STATUS.OK, news: newsBuldRes.news};
    } catch (err) {
      return ErrorHandler(err);
    }
  },

  /**
   * Get list of the most readable news for latest 24 hours, ordered by time (newest first)
   *
   * @param limit
   * @param offset
   * @returns {Promise}
   *   {success, status, news}
   */
  async getPopularForDay(limit = 50, offset = 0){
    try {
      const newsRes = await db.query(
        'SELECT\
           news.newsId,\
           newsHeader,\
           newsSubheader,\
           newsTimePublic AS time,\
           img.imgName AS newsImage, \
           cat.categoryTranslit AS category, \
           cat.categoryName AS categoryUkr, \
           (\
               SELECT\
                 (GROUP_CONCAT(tags.tagName)) AS newsTags\
               FROM ctbl_tag AS news_tags\
               LEFT JOIN tbl_tag AS tags ON tags.tagId = news_tags.tagId\
               WHERE news_tags.newsId = news.newsId\
           ) AS newsTags\
         FROM tbl_news AS news\
         LEFT JOIN ctbl_img AS img ON img.newsId = news.newsId AND img.imgMain = 1\
         LEFT JOIN tbl_category AS cat ON cat.categoryId = news.categoryId \
         WHERE newsStatus = 4 AND newsTimePublic > (NOW() - INTERVAL 24 HOUR)\
         ORDER BY countVisits DESC, newsTimePublic DESC\
         LIMIT ? OFFSET ?',
        [limit, offset]
      );
      if(!newsRes.length) return {success: true, status: STATUS.NOT_FOUND, news: [], message: 'No news found'};

      const newsBuldRes = await this._newsBuilder(newsRes);
      if(!newsBuldRes.success || !newsBuldRes.news.length) return ErrorHandler(newsBuldRes);

      return {success: true, status: STATUS.OK, news: newsBuldRes.news};
    } catch (err) {
      return ErrorHandler(err);
    }
  },

  /**
   * Get news list marked as "main", ordered by time (newest first)
   *
   * @param limit
   * @param offset
   * @returns {Promise}
   *   {success, status, news}
   */
  async getMain(limit = 50, offset = 0){
    try {
      const newsRes = await db.query(
        'SELECT\
           news.newsId,\
           newsHeader,\
           newsSubheader,\
           newsTimePublic AS time,\
           img.imgName AS newsImage, \
           cat.categoryTranslit AS category, \
           cat.categoryName AS categoryUkr, \
           (\
               SELECT\
                 (GROUP_CONCAT(tags.tagName)) AS newsTags\
               FROM ctbl_tag AS news_tags\
               LEFT JOIN tbl_tag AS tags ON tags.tagId = news_tags.tagId\
               WHERE news_tags.newsId = news.newsId\
           ) AS newsTags\
         FROM tbl_news AS news\
         LEFT JOIN ctbl_img AS img ON img.newsId = news.newsId AND img.imgMain = 1\
         LEFT JOIN tbl_category AS cat ON cat.categoryId = news.categoryId  \
         WHERE newsStatus = 4 AND newsMain = 1\
         ORDER BY newsTimePublic DESC\
         LIMIT ? OFFSET ?',
        [limit, offset]
      );
      if(!newsRes.length) return {success: true, status: STATUS.NOT_FOUND, news: [], message: 'No news found'};

      const newsBuldRes = await this._newsBuilder(newsRes);
      if(!newsBuldRes.success || !newsBuldRes.news.length) return ErrorHandler(newsBuldRes);

      return {success: true, status: STATUS.OK, news: newsBuldRes.news};
    } catch (err) {
      return ErrorHandler(err);
    }
  },

  /**
   * Get news list marked as "exclusive", ordered by time (newest first)
   *
   * @param limit
   * @param offset
   * @returns {Promise}
   *   {success, status, news}
   */
  async getExclusive(limit = 50, offset = 0){
    try {
      const newsRes = await db.query(
        'SELECT\
           news.newsId,\
           newsHeader,\
           newsSubheader,\
           newsTimePublic AS time,\
           img.imgName AS newsImage, \
           cat.categoryTranslit AS category, \
           cat.categoryName AS categoryUkr, \
           (\
               SELECT\
                 (GROUP_CONCAT(tags.tagName)) AS newsTags\
               FROM ctbl_tag AS news_tags\
               LEFT JOIN tbl_tag AS tags ON tags.tagId = news_tags.tagId\
               WHERE news_tags.newsId = news.newsId\
           ) AS newsTags\
         FROM tbl_news AS news\
         LEFT JOIN ctbl_img AS img ON img.newsId = news.newsId AND img.imgMain = 1\
         LEFT JOIN tbl_category AS cat ON cat.categoryId = news.categoryId \
         WHERE newsStatus = 4 AND newsExclusive = 1\
         ORDER BY newsTimePublic DESC\
         LIMIT ? OFFSET ?',
        [limit, offset]
      );
      if(!newsRes.length) return {success: true, status: STATUS.NOT_FOUND, news: [], message: 'No news found'};

      const newsBuldRes = await this._newsBuilder(newsRes);
      if(!newsBuldRes.success || !newsBuldRes.news.length) return ErrorHandler(newsBuldRes);

      return {success: true, status: STATUS.OK, news: newsBuldRes.news};
    } catch (err) {
      return ErrorHandler(err);
    }
  },

  /**
   * Get news list by category ordered by time (newest first)
   *
   * @param category
   * @param limit
   * @param offset
   *
   * @returns {Promise}
   *   {success, status, news}
   */
  async getByCategory(category, limit = 50, offset = 0) {
    try {
      if(!category || !category.length || typeof category !== 'string'){
        return ErrorHandler({success: false, status: STATUS.INVALID_PARAMETERS, message: 'Category is invalid or not specified', news: []})
      }

      const newsRes = await db.query(
        'SELECT\
           news.newsId,\
           newsHeader,\
           newsSubheader,\
           newsTimePublic AS time,\
           img.imgName AS newsImage, \
           cat.categoryTranslit AS category, \
           cat.categoryName AS categoryUkr, \
           (\
               SELECT\
                 (GROUP_CONCAT(tags.tagName)) AS newsTags\
               FROM ctbl_tag AS news_tags\
               LEFT JOIN tbl_tag AS tags ON tags.tagId = news_tags.tagId\
               WHERE news_tags.newsId = news.newsId\
           ) AS newsTags\
         FROM tbl_news AS news\
         LEFT JOIN ctbl_img AS img ON img.newsId = news.newsId AND img.imgMain = 1\
         LEFT JOIN tbl_category AS cat ON cat.categoryId = news.categoryId\
         WHERE newsStatus = 4 AND categorySearch LIKE ?\
         ORDER BY newsTimePublic DESC\
         LIMIT ? OFFSET ?',
        [`%${category}%`, limit, offset]
      );
      if(!newsRes.length) return {success: true, status: STATUS.NOT_FOUND, news: [], message: 'No news found'};

      const newsBuldRes = await this._newsBuilder(newsRes);
      if(!newsBuldRes.success || !newsBuldRes.news.length) return ErrorHandler(newsBuldRes);

      return {success: true, status: STATUS.OK, news: newsBuldRes.news};
    } catch (err) {
      return ErrorHandler(err);
    }
  },

  async getByType(type, limit = 50, offset = 0){
    try {
      if(!type || !type.length || typeof type !== 'string'){
        return ErrorHandler({success: false, status: STATUS.INVALID_PARAMETERS, message: 'Type is invalid or not specified', news: []})
      }

      // unfortunately, there is no table reference for types in DB
      let typeNum;
      switch (type){
        case 'news': typeNum = 0; break;
        case 'publications': typeNum = 1; break;
        case 'analytics': typeNum = 5; break;
        default: return ErrorHandler({success: false, status: STATUS.INVALID_PARAMETERS, message: 'Type not in allowed range', news: []})
      }

      const newsRes = await db.query(
        'SELECT\
           news.newsId,\
           newsHeader,\
           newsSubheader,\
           newsTimePublic AS time,\
           img.imgName AS newsImage, \
           cat.categoryTranslit AS category,\
           cat.categoryName AS categoryUkr, \
           (\
               SELECT\
                 (GROUP_CONCAT(tags.tagName)) AS newsTags\
               FROM ctbl_tag AS news_tags\
               LEFT JOIN tbl_tag AS tags ON tags.tagId = news_tags.tagId\
               WHERE news_tags.newsId = news.newsId\
           ) AS newsTags\
         FROM tbl_news AS news\
         LEFT JOIN ctbl_img AS img ON img.newsId = news.newsId AND img.imgMain = 1\
         LEFT JOIN tbl_category AS cat ON cat.categoryId = news.categoryId \
         WHERE newsStatus = 4 AND newsType = ?\
         ORDER BY newsTimePublic DESC\
         LIMIT ? OFFSET ?',
        [typeNum, limit, offset]
      );
      if(!newsRes.length) return {success: true, status: STATUS.NOT_FOUND, news: [], message: 'No news found'};

      const newsBuldRes = await this._newsBuilder(newsRes);
      if(!newsBuldRes.success || !newsBuldRes.news.length) return ErrorHandler(newsBuldRes);

      return {success: true, status: STATUS.OK, news: newsBuldRes.news};
    } catch (err) {
      return ErrorHandler(err);
    }
  },

  /**
   * Search news by search string
   * @param searchStr
   * @returns {Promise}
   *
   */
  async search(searchStr){
    searchStr = searchStr.trim();
    try {
      if(!searchStr || !searchStr.length || typeof searchStr !== 'string'){
        return ErrorHandler({success: false, status: STATUS.INVALID_PARAMETERS, message: 'Search string is invalid or not specified', news: []})
      }

      const newsRes = await db.query(
        'SELECT \
           news.newsId,\
           newsHeader,\
           newsSubheader,\
           newsTimePublic AS time,\
           img.imgName AS newsImage, \
           cat.categoryTranslit AS category,\
           cat.categoryName AS categoryUkr, \
           (\
               SELECT\
                 (GROUP_CONCAT(tags.tagName)) AS newsTags\
               FROM ctbl_tag AS news_tags\
               LEFT JOIN tbl_tag AS tags ON tags.tagId = news_tags.tagId\
               WHERE news_tags.newsId = news.newsId\
           ) AS newsTags\
         FROM tbl_search AS search\
         LEFT JOIN tbl_news AS news ON search.newsId = news.newsId\
         LEFT JOIN ctbl_img AS img ON img.newsId = news.newsId AND img.imgMain = 1\
         LEFT JOIN tbl_category AS cat ON cat.categoryId = news.categoryId \
         WHERE newsStatus = 4 AND newsSearch LIKE ?\
         ORDER BY newsTimePublic DESC\
         LIMIT 30',
        [`%${searchStr}%`]
      );
      if(!newsRes.length) return {success: true, status: STATUS.NOT_FOUND, news: [], message: 'No news found'};

      const newsBuldRes = await this._newsBuilder(newsRes);
      if(!newsBuldRes.success || !newsBuldRes.news.length) return ErrorHandler(newsBuldRes);

      return {success: true, status: STATUS.OK, news: newsBuldRes.news};
    } catch (err) {
      return ErrorHandler(err);
    }
  },

  /**
   * Search news by hashtag
   * @param hashtag
   * @param limit
   * @param offset
   * @returns {Promise}
   */
  async searchByHashtag(hashtag, limit = 50, offset = 0){
    try {
      if(!hashtag || !hashtag.length || typeof hashtag !== 'string'){
        return ErrorHandler({success: false, status: STATUS.INVALID_PARAMETERS, message: 'hashtag is invalid or not specified', news: []})
      }
      hashtag = hashtag.trim();

      const newsRes = await db.query(
        'SELECT\
           news.newsId,\
           newsHeader,\
           newsSubheader,\
           newsTimePublic AS time,\
           img.imgName AS newsImage, \
           cat.categoryTranslit AS category, \
           cat.categoryName AS categoryUkr, \
           (\
               SELECT\
                 (GROUP_CONCAT(tags.tagName)) AS newsTags\
               FROM ctbl_tag AS news_tags\
               LEFT JOIN tbl_tag AS tags ON tags.tagId = news_tags.tagId\
               WHERE news_tags.newsId = news.newsId\
           ) AS newsTags\
         FROM tbl_news AS news\
         LEFT JOIN ctbl_img AS img ON img.newsId = news.newsId AND img.imgMain = 1\
         LEFT JOIN tbl_category AS cat ON cat.categoryId = news.categoryId\
         WHERE newsStatus = 4 AND \
           news.newsId IN (SELECT newsId FROM ctbl_tag WHERE tagId = (SELECT tagId FROM tbl_tag WHERE tagName = ? LIMIT 1)) \
         ORDER BY newsTimePublic DESC\
         LIMIT ? OFFSET ?',
        [hashtag, limit, offset]
      );
      if(!newsRes.length) return {success: true, status: STATUS.NOT_FOUND, news: [], message: 'No news found'};

      const newsBuldRes = await this._newsBuilder(newsRes);
      if(!newsBuldRes.success || !newsBuldRes.news.length) return ErrorHandler(newsBuldRes);

      return {success: true, status: STATUS.OK, news: newsBuldRes.news};
    } catch (err) {
      return ErrorHandler(err);
    }
  },

  /**
   * Builder for news array which includes news objects
   *
   * @param news {Array} [{...newsText, tags, }]
   *
   * @returns {Promise}
   *   {success, status, news}
   * @private
   */
  async _newsBuilder(news){
    try {
      if(!news || !news.length){
        return {success: false, status: STATUS.INVALID_PARAMETERS, message: 'News._newsBuilder: missed parameter: newsArray', news: []}
      }

      let newsHandled = news;

      const tagsHandledRes = await Tag.fromStrToArrayAsync(newsHandled);
      if(!tagsHandledRes.success) return tagsHandledRes;
      newsHandled = tagsHandledRes.news;

      const timeHandledRes = await Time.toFrontendUaAsync(newsHandled);
      if(!timeHandledRes.success) return timeHandledRes;
      newsHandled = timeHandledRes.handled;

      const videoHandledRes =  await Video.transformVideoLink(newsHandled);
      if(!videoHandledRes.success) return videoHandledRes;
      newsHandled = videoHandledRes.news;

      const parsedTextRes =  await Text.separateNewsTextAndRelativeLinkAsync(newsHandled);
      if(!parsedTextRes.success) return parsedTextRes;
      newsHandled = parsedTextRes.handled;

      newsHandled = newsHandled.map(newsObj => {
        if(newsObj.relatedNews) {
          try {
            newsObj.relatedNews = JSON.parse(newsObj.relatedNews);
          } catch (e) {
            newsObj.relatedNews = [];
          }
        }
        return newsObj
      });

      return {success: true, status: STATUS.OK, news: newsHandled};
    } catch (err) {
      return ErrorHandler(err);
    }
  },

  getWithImages(numberToRetrieve) {

  },

  getWithVideos(numberToRetrieve) {

  },

  getPopularTags(limit = 5){
    db.query(
      'SELECT \
         tagids.tagId, \
         tags.tagName,\
         COUNT(tagids.tagId) AS total\
       FROM ctbl_tag AS tagids\
       LEFT JOIN tbl_tag AS tags \
         ON tags.tagId = tagids.tagId\
       GROUP BY tagId \
       ORDER BY total DESC\
       LIMIT ?',
      [limit]
    )
  }
};

module.exports = News;