<div class="item" tabindex="200"
     data-img='{"imgId":"<?= $item['imgId'] ?>", "imgDesc":"<?= $item['imgDesc'] ?>", "imgpath":"<?= $ini['url.media'] ?>images/<?= $newsId ?>/"}'>

    <img src="<?= $ini['url.media'] ?>images/<?= $item['newsId'] ?>/gallery/<?= $item['imgName'] ?>" width="178"/>

    <a href="#" class="del" title="Удалить"><i></i></a>

    <a href="#" class="show" title="Показать"><i></i></a>

    <span class="sill<?= ($item['imgMain'] == 1) ? ' main' : ''; ?>">Главное</span>

</div>