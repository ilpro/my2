const cheerio = require('cheerio');

const STATUS = require('./STATUS');
const Helper = require('./Helper');

const Text = {
  _translitToUkr: {
    a: 'а',
    b: 'б',
    v: 'в',
    h: 'г',
    g: 'ґ',
    d: 'д',
    e: 'е',
    ye: 'є',
    zh: 'ж',
    z: 'з',
    y: 'и',
    i: 'і',
    yi: 'ї',
    k: 'к',
    l: 'л',
    m: 'м',
    n: 'н',
    o: 'о',
    p: 'п',
    r: 'р',
    s: 'с',
    t: 'т',
    u: 'у',
    f: 'ф',
    kh: 'х',
    ts: 'ц',
    ch: 'ч',
    sh: 'ш',
    shch: 'щ',
    yu: 'ю',
    ya: 'я',
  },

  _ukrToTranslit: {
    'а': 'a',
    'б': 'b',
    'в': 'v',
    'г': 'h',
    'ґ': 'g',
    'д': 'd',
    'е': 'e',
    'є': 'ye',
    'ж': 'zh',
    'з': 'z',
    'и': 'y',
    'і': 'i',
    'ї': 'yi',
    'к': 'k',
    'л': 'l',
    'м': 'm',
    'н': 'n',
    'о': 'o',
    'п': 'p',
    'р': 'r',
    'с': 's',
    'т': 't',
    'у': 'u',
    'ф': 'f',
    'х': 'kh',
    'ц': 'ts',
    'ч': 'ch',
    'ш': 'sh',
    'щ': 'shch',
    'ю': 'yu',
    'я': 'ya',
  },

  /**
   * Transfrom one letter from translit to ukrainian variant
   * @param translitLetter
   * @returns {Promise}
   */
  fromTranslitToUkr(translitLetter) {
    return new Promise((res, rej) => {
      if (!translitLetter || typeof translitLetter !== 'string') {
        return rej({
          success: false,
          status: STATUS.INVALID_PARAMETERS,
          message: 'Helper.fromTranslitToUkr: missed param translitLetter',
          letter: false
        })
      }

      let ukrLetter = this._translitToUkr[translitLetter.toLocaleLowerCase()];
      if (!ukrLetter) {
        return rej({
          success: false,
          status: STATUS.INVALID_PARAMETERS,
          message: 'Helper.fromTranslitToUkr: translitLetter not in allowed range',
          letter: false
        })
      }

      return res({success: true, status: STATUS.OK, letter: ukrLetter});
    });
  },

  /**
   * Get first letter for each of dossiers
   * eg: Жук Иван Владимирович => Ж => zh
   * @param arr
   * @returns {Promise}
   */
  getDossierFirstLetterTranslit(arr) {
    return new Promise((res, rej) => {
      if (!arr || !arr.length) {
        return rej({
          success: false,
          status: STATUS.INVALID_PARAMETERS,
          message: 'Text.getDossierFirstLetterTranslit: missed parameter: arr',
          handled: []
        })
      }

      const handled = arr.map(obj => {
        if (obj.dossierName) {
          obj.categoryUkr = obj.dossierName.trim()[0].toLowerCase();
          obj.category = this._ukrToTranslit[obj.categoryUkr];
        }

        return obj;
      });

      return res({success: true, status: STATUS.OK, handled})
    })
  },

  /**
   * Retrieves news related link and text from news text
   *
   * @param arr
   * @returns {Promise}
   *   {}
   */
  separateNewsTextAndRelativeLinkAsync(arr) {
    return new Promise((res, rej) => {
      if (!arr || !arr.length) {
        return rej({
          success: false,
          status: STATUS.INVALID_PARAMETERS,
          message: 'Text.separateTextAndRelativeLinkAsync: missed parameter: newsArray',
          handled: []
        })
      }

      const handled = arr.map(obj => {
        if (obj.newsText) {
          const $ = cheerio.load(obj.newsText, {decodeEntities: false});
          const elem = $('.also .aText a');

          if (obj.length) {
            const relatedLinkArr = elem.attr('href').split("/");
            obj.relatedLink = relatedLinkArr[relatedLinkArr.length - 1];
            obj.relatedText = elem.text();
            $('table.also').replaceWith('');
          }

          obj.newsText = $.html();
        }

        return obj;
      });

      return res({success: true, status: STATUS.OK, handled})
    })
  },
};

module.exports = Text;