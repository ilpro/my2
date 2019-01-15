const STATUS = require('./STATUS');

const Image = {

  /**
   * Transform string to array
   * @param newsArr
   * @returns {Promise}
   *   {success, status, news}
   */
  fromStrToArrayAsync(newsArr){
    return new Promise((res, rej) => {
      if(!newsArr || !newsArr.length){
        return rej({success: false, status: STATUS.INVALID_PARAMETERS, message: 'Image.fromStrToArrayAsync: missed parameter: newsArray', news: []})
      }

      const newsWithHandledImages = newsArr.map(newsObj => {
        if(newsObj.newsImages){
          newsObj.newsImages = newsObj.newsImages.split(',')
        } else {
          newsObj.newsImages = [];
        }

        return newsObj;
      });

      return res({success: true, status: STATUS.OK, news: newsWithHandledImages});
    });
  }
};

module.exports = Image;