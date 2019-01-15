const db = require('./DBPromise');

const STATUS = require('./STATUS');
const ErrorHandler = require('./ErrorHandler');
const Helper = require('./Helper');

const Time = require('./Time');
const Text = require('./Text');

const Dossier = {

  /**
   * Get one dossier by ID
   *
   * @param dossierId
   * @returns {Promise}
   *   {success, status, dossiers};
   */
  async getOneById(dossierId) {
    try {
      if (!dossierId) {
        return {
          success: false,
          status: STATUS.INVALID_INPUT_PARAMETERS,
          message: 'Missed parameter "dossierId"',
          dossiers: []
        }
      }

      const dossier = await db.query(
        'SELECT \
           dossier.brandId AS dossierId, \
           dossier.brandName AS dossierName, \
           dossier.brandDesc AS dossierDesc, \
           dossier.brandTimeUpdate AS time, \
           img.imgName AS dossierImage \
         FROM tbl_brand AS dossier\
         LEFT JOIN ctbl_brandimg AS img ON img.brandId = dossier.brandId AND img.imgMain = 1 \
         WHERE brandStatus = 4 AND dossier.brandId = ?',
        [dossierId]
      );
      if (!dossier.length) {
        return {success: true, status: STATUS.NOT_FOUND, message: 'No dossier found for id ' + dossierId, dossiers: []};
      }

      const dossierBuildRes = await this._dossierBuilder(dossier);
      if (!dossierBuildRes.success || !dossierBuildRes.dossiers.length) return ErrorHandler(dossierBuildRes);

      return {success: true, status: STATUS.OK, dossiers: dossierBuildRes.dossiers};
    } catch (err) {
      return ErrorHandler(err);
    }
  },

  /**
   * Translate letter from translit to russian
   * Find all records by letter
   * @param letterTranslit
   * @returns {Promise<*>}
   */
  async getByLetter(letterTranslit) {
    try {
      if (!letterTranslit) {
        return {
          success: false,
          status: STATUS.INVALID_INPUT_PARAMETERS,
          message: 'Missed parameter "letterTranslit"',
          dossiers: []
        }
      }

      const ukrLetterRes = await Text.fromTranslitToUkr(letterTranslit);
      if(!ukrLetterRes.letter) return ukrLetterRes;

      const letter = ukrLetterRes.letter;

      const dossiers = await db.query(
        'SELECT  brandId AS dossierId, brandName AS dossierName \
         FROM tbl_brand WHERE brandName LIKE ? ORDER BY brandName ASC',
        [letter + '%']
      );
      if (!dossiers.length) {
        return {success: true, status: STATUS.NOT_FOUND, message: 'No dossiers found', dossiers: [], letter};
      }

      const dossierBuildRes = await this._dossierBuilder(dossiers);
      if (!dossierBuildRes.success || !dossierBuildRes.dossiers.length) {
        return ErrorHandler(dossierBuildRes);
      }

      return {success: true, status: STATUS.OK, dossiers: dossierBuildRes.dossiers, letter};
    } catch (err) {
      return ErrorHandler(err);
    }
  },

  /**
   * Get for every record, transform it to the array
   * @returns {Promise<*>}
   */
  async getAllIds(){
    try {

      const dossiersTotal = await db.query('SELECT brandId FROM tbl_brand');
      if (!dossiersTotal.length) {
        return {success: true, status: STATUS.NOT_FOUND, message: 'No dossiers found', dossiersIds: []};
      }

      return {success: true, status: STATUS.OK, dossiersIds: dossiersTotal.map(elem => elem.brandId)};
    } catch (err) {
      return ErrorHandler(err);
    }
  },

  /**
   * Builder for dossiers array which includes dossiers objects
   *
   * @param dossierArray {Array} [{...dossierId, dossierName, dossierImg, dossierDesc, time}]
   *
   * @returns {Promise}
   *   {success, status, dossiers}
   * @private
   */
  async _dossierBuilder(dossierArray) {
    try {
      if (!dossierArray || !dossierArray.length) {
        return {
          success: false,
          status: STATUS.INVALID_PARAMETERS,
          message: 'Dossier._dossierBuilder: missed parameter: dossierArray',
          dossiers: []
        }
      }

      let dossiersHandled = dossierArray;

      const timeHandledRes = await Time.toFrontendUaAsync(dossiersHandled);
      if (!timeHandledRes.success) return timeHandledRes;
      dossiersHandled = timeHandledRes.handled;

      // get first letter translit as category
      const categoryHandledRes = await Text.getDossierFirstLetterTranslit(dossiersHandled);
      if (!categoryHandledRes.success) return categoryHandledRes;
      dossiersHandled = timeHandledRes.handled;

      return {success: true, status: STATUS.OK, dossiers: dossiersHandled};
    } catch (err) {
      return ErrorHandler(err);
    }
  },
};

module.exports = Dossier;