const STATUS = require('./STATUS');

const Helper = {

  /**
   * Get unique random values from 1 to specified
   * @param array
   * @param total
   */
  getRandomUniqueFromArray(array, total = 4) {
    return new Promise((res, rej) => {
      if (!array || !array.length || array.length < total) {
        return rej({
          success: false,
          status: STATUS.INVALID_PARAMETERS,
          message: 'Helper.getRandomUnique: missed param array or array length < total',
          random: []
        })
      }

      const random = [];

      for (let i = 0; i < total; i++) {
        const randIndex = Math.floor(Math.random() * array.length);
        const randomElem = array.splice(randIndex, 1)[0];
        random.push(randomElem);
      }

      return res({success: true, status: STATUS.OK, random});
    });
  },
};

module.exports = Helper;