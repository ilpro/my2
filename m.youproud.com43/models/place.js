const db = require('./db');

const Image = require('./image');
const User = require('./user');

const Helper = require('./helper');
const STATUS = require('./status');

const Place = {

  /**
   * Add new or update existing place
   *
   * @param data
   *   {userId, images, videos, message, ?placeId}
   * @returns {Promise.<*>}
   *  return back to client object
   *  if success:
   *    {
   *      success: true,
   *      postId,
   *      user: {
   *        userId,
   *        userName,
   *        userPhoto,
   *        userLastActive,
   *        userBdate,
   *        age,
   *        isBotOnline
   *      },
   *      // if attachments or text was uploaded
   *      message,
   *      images: [
   *        {path, width, height},
   *        {path, width, height}
   *      ],
   *      videos: [
   *        {path, width, height},
   *        {path, width, height}
   *      ]
   *    }
   *  if error: {error}
   */
  async save(data) {
    try {
      const self = this;

      // check if post new or updating existing
      let placeId;
      if(data.placeId){
        throw {success: false, status: STATUS.INTERNAL_ERROR}
        // if update
        // await Image.updateRelativeToPlace(data.postId, data.images);
        // await self._update(data);
        // placeId = data.placeId;
      } else {
        placeId = await self._createNewPlaceAndGetId(data);
      }

      const imageSaveResult = await Image.savePlaceImages(placeId, data.images);

      // prepare response object
      const responseData = {placeId};
      if (data.images && data.images.length > 0) responseData.images = imageSaveResult.images;

      return {success: true, status: STATUS.OK, ...responseData};
    } catch (err) {
      if (err.status === STATUS.INTERNAL_ERROR || !err.status) {
        console.log(err);
        return {success: false, status: STATUS.INTERNAL_ERROR, message: 'Server error'};
      }
      return err;
    }
  },

  _createNewPlaceAndGetId({name = '', location = {}, phone = '', email = '', site = '', details = '', images = []}){
    return new Promise((res, rej) => {
      // set main photo if images array exists
      let mainPhoto = '',width = '', height = '';
      if(images.length > 0){
        mainPhoto = images[0].path;
        width = images[0].width;
        height = images[0].height;
      }


      // remove space after comma
      location.city = location.city ? location.city.toLowerCase().replace(/\s*,\s*/g, ",") : '';
      location.address = location.address ? location.address : '';
      location.lat = location.lat ? +location.lat : 0;
      location.lng = location.lng ? +location.lng : 0;

      db.query(
        'INSERT INTO tbl_city_place \
         (name, city, address, lat, lng, phone, email, site, details, mainPhoto, width, height) \
         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);',
        [name, location.city, location.address, location.lat, location.lng, phone, email, site, details, mainPhoto, width, height],
        (err, rows) => {
        if (err) return rej({
          success: false,
          status: STATUS.INTERNAL_ERROR,
          message: "Error in Place._createNewPlaceAndGetId:\n" + err
        });

        return res(rows.insertId);
      });
    });
  },

  _update({placeId = false, name = '', city = '', address = '', coordinates = '',phone = '', email = '', site = '', details = '', images = []}){
    return new Promise((res, rej) => {
      if (!placeId) {
        return rej({
          success: false,
          status: STATUS.INTERNAL_ERROR,
          message: 'Place._update error: no placeId specified'
        });
      }

      // set main photo if images array exists
      const mainPhoto = images.length > 0 ? images[0].path : '';

      // remove space after comma
      const formattedCity = city.toLowerCase().replace(/\s*,\s*/g, ",");

      db.query(
        'UPDATE tbl_city_place SET \
           name = ?, \
           city = ?, \
           address = ?, \
           coordinates = ?, \
           phone = ?, \
           email = ?, \
           site = ?, \
           details = ?, \
           mainPhoto = ?\
         WHERE placeId = ?;',
        [name, formattedCity, address, JSON.stringify(coordinates), phone, email, site, details, mainPhoto, placeId],
        (err, rows) => {
          if (err) return rej({
            success: false,
            status: STATUS.INTERNAL_ERROR,
            message: "Error in Place._createNewPlaceAndGetId:\n" + err
          });

          return res(rows.insertId);
        });
    });
  },

  /**
   * Get place info by Id
   * @param placeId
   * @returns {Promise} which returns
   *   {
   *     success,
   *     place: {
   *       name,
   *       city,
   *       address,
   *       cooridinates,
   *       phone,
   *       site,
   *       details,
   *       mainPhoto,
   *       images: [
   *         {id, path, width, height},
   *         {id, path, width, height},
   *       ]
   *     }
   *   }
   */
  getById(placeId){
    return new Promise((res, rej) => {
      if (!placeId) {
        return rej({
          success: false,
          status: STATUS.INVALID_INPUT_PARAMETERS,
          message: 'No placeId specified'
        });
      }

      db.query(
        'SELECT \
           placeId,\
           name,\
           city,\
           address,\
           lat, \
           lng,\
           phone,\
           email,\
           site,\
           details,\
           mainPhoto,\
           width,\
           height,\
           (\
             SELECT\
             CONCAT(\'[\', \
               GROUP_CONCAT(\
                 CONCAT(\'{\
                   "id":"\', id, \'",\
                   "path":"\', path, \'",\
                   "width":"\', width, \'",\
                   "height":"\', height, \'"\
                 }\') SEPARATOR ","\
               ), \'\
             ]\') AS images \
             FROM tbl_city_place_image AS t_im\
             WHERE t_im.placeId = ? \
           ) AS images \
         FROM tbl_city_place \
         WHERE placeId = ?;',
        [placeId, placeId],
        (err, rows) => {
          if (err) {
            return rej({success: false, status: STATUS.INTERNAL_ERROR, message: "Error in Place.getById\n" + err});
          }

          if (!rows.length) {
            return rej({success: true, status: STATUS.NOT_FOUND, message: `No place was found for id ${placeId}`});
          }

          return res({success: true, status: STATUS.OK, place: rows[0]})
        });
    })
  },

  /**
   * Get all places
   */
  getAll(){
    return new Promise((res, rej) => {

      db.query(
        'SELECT * FROM tbl_city_place ORDER by date DESC;',
        (err, rows) => {
          if (err) {
            return rej({success: false, status: STATUS.INTERNAL_ERROR, message: "Error in Place.getAll\n" + err});
          }

          if (!rows.length) {
            return rej({success: true, status: STATUS.NOT_FOUND, message: 'No places found'});
          }

          for(let i = 0; i < rows.length; i++){
            const dateObj = new Date(rows[i].date);
            rows[i].date = dateObj.getDate() + "." + (dateObj.getMonth() + 1) + "." + dateObj.getFullYear();
          }

          return res({success: true, status: STATUS.OK, places: rows})
        });
    })
  }
};

module.exports = Place;