const STATUS = require('./STATUS');

const Video = {

  /**
   * Transform video link right way to correct show on frontend
   * from "https://youtu.be/Ohy_60NCZWA" to "https://www.youtube.com/embed/Ohy_60NCZWA"
   * @param newsArr
   * @returns {Promise}
   *   {success, status, news}
   */
  transformVideoLink(newsArr){
    return new Promise((res, rej) => {
      if(!newsArr || !newsArr.length){
        return rej({success: false, status: STATUS.INVALID_PARAMETERS, message: 'Video.transformVideoLink: missed parameter: newsArray', news: []})
      }

      const newsWithHandledVideos = newsArr.map(newsObj => {
        if(newsObj.newsVideo){
          const linkStr = newsObj.newsVideo.split("/").pop();
          newsObj.newsVideo = 'https://www.youtube.com/embed/' + linkStr;
        }

        return newsObj;
      });

      return res({success: true, status: STATUS.OK, news: newsWithHandledVideos});
    });
  }
};

module.exports = Video;