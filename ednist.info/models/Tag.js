const STATUS = require('./STATUS');

const Tag = {

  /**
   * Transform string like 'usa, canada, clinton' to array like ['usa', 'canada', 'clinton']
   * @param newsArr
   * @returns {Promise} - array
   */
  fromStrToArrayAsync(newsArr){
    return new Promise((res, rej) => {
      if(!newsArr || !newsArr.length){
        return rej({success: false, status: STATUS.INVALID_PARAMETERS, message: 'Tags.fromStrToArrayAsync: missed parameter: newsArray', news: []})
      }

      const newsWithHandledTags = newsArr.map(newsObj => {
        if(newsObj.newsTags){
          newsObj.newsTags = newsObj.newsTags.split(',')
        } else {
          newsObj.newsTags = [];
        }

        return newsObj;
      });

      return res({success: true, status: STATUS.OK, news: newsWithHandledTags});
    });
  }
};

module.exports = Tag;