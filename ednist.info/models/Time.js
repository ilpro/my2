const moment = require('moment');
const STATUS = require('./STATUS');

const Time = {

  /**
   * Transform DB timestamp into frontend format like '7 ноября 2018, 11:36'
   * @param arr
   * @returns {Promise}
   */
  toFrontendUaAsync(arr){
    return new Promise((res, rej) => {
      if(!arr || !arr.length){
        return rej({success: false, status: STATUS.INVALID_PARAMETERS, message: 'Time.toFrontendUaAsyncNews: missed parameter: newsArray', handled: []})
      }

      moment.locale('uk');

      const handled = arr.map(elem => {
        if(elem.time){
          elem.time = moment(elem.time).format('D MMMM YYYY, HH:mm');
        }
        return elem;
      });

      return res({success: true, status: STATUS.OK, handled});
    });
  },
};

module.exports = Time;