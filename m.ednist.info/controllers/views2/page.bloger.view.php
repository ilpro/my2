

<?
if (!$id) {
    ?>
    <div class="center-block full-width">
        <div class="block-title">ДОСЬЕ</div>
        <div class="news-block dossier-block">
            <div class="news-preview">

                <? $lit = '';
                $k = 0;
                ?>
                <? foreach ($page_data['authors_all'] as $authorItem) {
                    $k++;
                    ?>

                    <? $sortField = ($authorItem['authorSearch'] != '') ? $authorItem['authorSearch'] : $authorItem['authorName']; ?>
                    <? $currentLit = mb_substr($sortField, 0, 1, 'UTF-8'); ?>

                <? if ($lit != $currentLit) { ?>
                        </ul>
                    </div>
                <? } ?>

        <? if ($lit != $currentLit) { ?>
            <? $lit = $currentLit; ?>

                    <div class="name-list">
                        <div class="name-list-title"><?= strtoupper($currentLit); ?></div>
                        <ul>

        <? } ?>

                        <li><a href="/bloger/<?= $authorItem['authorId']; ?>"><?= $authorItem['authorName']; ?></a></li>

                <? if ($k == count($page_data['authors_all'])) { ?>
                        </ul>
                    </div>
        <? } ?>
    <? } ?>

            <br/><br/>
            <div class="clear"></div>


            <div id="append">
                <?
                if ($page_data['authors'])
                    foreach ($page_data['authors'] as $item)
                        include "sprite/author.view.php";
                ?>
            </div>

            <?
            if ($page_data['check_author_count'])
                include "btn/btn.more_authors.view.php";
            ?>

        </div>
    </div>
    </div>
<? }
if ($id) {
    ?>
    <div class="center-block-inner">

        <h1 class="title"><?= $page_data['author']['authorName'] ?></h1>

             <? if (NULL !== $page_data['author']['authorImg']): ?>
            <img class="mainimg" src="<?= $ini['url.media'] . "author/" . $id . "/big.jpg"; ?>"
                 alt="БЛОГЕР | <?= $page_data['author']['authorName'] ?>"
                 title="БЛОГЕР | <?= $page_data['author']['authorName'] ?>" width="400">
    <? endif; ?>
    <?= html_entity_decode($page_data['author']['authorDesc']) ?>    

    </div>
    <?
    if (!empty($page_data['news_bottom'])) {
        ?>
        <div class="clear"/>
        <div class="news-block news">
            <h5 class="social-title">Читайте також:</h5>

            <div class="six-block" id="append">

                <?
                include "block/newsBlock.view.php";
                ?>

            </div>

        </div>
        <?
        if ($page_data['check_news_count'])
            include "btn/btn.more_related.view.php";
    }
}
if ($id) {
    ?>
    <?
    include "block/blockComments.view.php";
    ?>

    <div class="clear"></div>
    <p class='social-title'>Цього блогера можна знайти за такими запитами:</p>
    <div class="center-block-inner" style="font-size:15px;">
        <p class='searchWords'>
        <p>
            <?
            $seoString = $page_data['author']['authorSearch'];
            $layout = new Search();
            $seoString = $seoString . ' ' . implode(" ", $layout->changeLayout($seoString));
            echo $seoString;
            ?>
        </p>
    </p>
    </div>

<? } ?>