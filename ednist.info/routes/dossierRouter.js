const Router = require ('koa-router');
const router = new Router({prefix: '/dossier'});

const STATUS = require('../models/STATUS');
const Dossier = require('../models/DossierProxy');

router.get('/', async ctx => {
  ctx.redirect('/dossier/a');
});

router.get('/:dossierParam', async ctx => {
  // if dossier ID, get dossier by id
  if(+ctx.params.dossierParam){
    const dossierRes = await Dossier.getOneById(ctx.params.dossierParam);

    if(!dossierRes.success) return ctx.render('error/internal');
    if(dossierRes.status === STATUS.NOT_FOUND) return ctx.render('error/notFound');  // checks for errors

    const metaData = {
      title: dossierRes.dossiers[0].dossierName,
      content: dossierRes.dossiers[0].dossierName,
      keyWords: [dossierRes.dossiers[0].dossierName],
      image: 'https://www.ednist.info/media/brand/' + dossierRes.dossiers[0].dossierId + '/main/800.jpg',
    };
    return ctx.render('dossier', {dossier: dossierRes.dossiers[0], metaData});
  }

  // if alphabet letter, get all dossiers by this letter
  const dossierRes = await Dossier.getByLetter(ctx.params.dossierParam);

  if(!dossierRes.success) return ctx.render('error/internal');
  if(dossierRes.status === STATUS.NOT_FOUND) return ctx.render('error/notFound');  // checks for errors

  const metaData = {title: 'досьє за літерою ' + dossierRes.letter.toUpperCase()};
  return ctx.render('dossierList', {dossiers: dossierRes.dossiers, letter: dossierRes.letter, metaData});
});


module.exports = router;