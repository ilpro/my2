const STATUS = require('./STATUS');
const ErrorHandler = require('./ErrorHandler');

const Dossier = require('./Dossier');
const Helper = require('./Helper');

const {cacheDossierMaxAge, cacheDossierListMaxAge, cacheClearInterval, newsNumToCache} = require('../config/config.js');
const LRU = require("lru-cache");
const dossierCache = LRU({maxAge: cacheDossierMaxAge});
const dossierListCache = LRU({maxAge: cacheDossierListMaxAge});
const dossiersTotalNum = LRU({maxAge: cacheDossierListMaxAge});

const DossierProxy = {
  _dossierCache: dossierCache, // cache for single dossiers
  _dossiersByLetters: dossierListCache, // cache for list of dossiers
  _dossiersTotalNum: dossiersTotalNum, // cache for total count of dossiers

  /**
   * Check if dossier exists in cache
   * If not - get dossier from DB and cache it
   *
   * @param dossierId
   * @returns {Promise<{success: boolean, status: string, dossiers: ({length}|Object|*)}>}
   */
  async getOneById(dossierId) {
    try {
      if (!dossierId) {
        return {success: false, status: STATUS.INVALID_INPUT_PARAMETERS, message: 'Missed parameter "dossierId"'}
      }

      const cachedDossier = this._dossierCache.get(dossierId);
      if (cachedDossier) {
        return {success: true, status: STATUS.OK, dossiers: cachedDossier};
      }

      const dossierRes = await Dossier.getOneById(dossierId);
      if(!dossierRes.dossiers) return dossierRes;

      this._dossierCache.set(dossierId, dossierRes.dossiers);

      return dossierRes;
    } catch (err) {
      return ErrorHandler(err);
    }
  },

  /**
   * Get all dossiers which start by specified letter
   * @param letter
   * @returns {Promise<{success: boolean, status: string, dossiers: ({length}|Object|*)}>}
   */
  async getByLetter(letter) {
    try {
      if (!letter) {
        return {success: false, status: STATUS.INVALID_INPUT_PARAMETERS, message: 'Missed parameter "letter"'}
      }

      const cachedDossiers = this._dossiersByLetters.get(letter);
      if (cachedDossiers) {
        const stringified = JSON.parse(cachedDossiers);
        return {success: true, status: STATUS.OK, dossiers: stringified.dossiers, letter: stringified.letter};
      }

      const dossierRes = await Dossier.getByLetter(letter);
      if(!dossierRes.dossiers) return dossierRes;

      this._dossiersByLetters.set(letter, JSON.stringify({dossiers: dossierRes.dossiers, letter: dossierRes.letter}));

      return dossierRes;
    } catch (err) {
      return ErrorHandler(err);
    }
  },

  /**
   * Toss random number from 1 to max number of dossier
   * Try to get this dossiers from cache.
   * If they are not exist - get them from db and save into cache
   *
   * @param number
   * @returns {Promise<{success: boolean, status: string, news: ({length}|Object|*)}>}
   */
  async getRandom(number) {
    try {
      if (!number) {
        return {success: false, status: STATUS.INVALID_INPUT_PARAMETERS, message: 'Missed parameter "number"'}
      }

      // get dossiers total count
      let allDossiersIds = this._dossiersTotalNum.get('dossiersTotal');
      if (!allDossiersIds) {
        const allDossiersIdsRes = await Dossier.getAllIds();
        if(!allDossiersIdsRes.dossiersIds) return allDossiersIdsRes;
        allDossiersIds = allDossiersIdsRes.dossiersIds;
        this._dossiersTotalNum.set('dossiersTotal', allDossiersIds);
      }

      // get random from 1 to dossierTotalNum
      const getRandomIdsRes = await Helper.getRandomUniqueFromArray(allDossiersIds, number);
      if(!getRandomIdsRes.random || !getRandomIdsRes.random.length) return getRandomIdsRes;
      const randomIds = getRandomIdsRes.random;

      // get data from cache for random dossiers
      const randomDossiers = [];
      for(let i = 0; i < randomIds.length; i++){
        const dossier = await this.getOneById(randomIds[i]);
        if(dossier.dossiers.length){
          randomDossiers.push(dossier.dossiers[0]);
        }
      }

      return {success: true, status: STATUS.OK, dossiers: randomDossiers};
    } catch (err) {
      return ErrorHandler(err);
    }
  },

  _clearOldValues(){
    this._dossierCache.prune();
    this._dossiersByLetters.prune();
    this._dossiersTotalNum.prune();
  },
};

/** Clear dossier cache */
const clearCacheInterval = setInterval(() => DossierProxy._clearOldValues(), cacheClearInterval);

module.exports = DossierProxy;