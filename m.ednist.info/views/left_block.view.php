<?
    $page = $_POST['page'];
    $id = $_POST['pageId'];
?>


<div class="left-bar">
        <ul class="nav nav-pills nav-stacked">
            <li<?=$page==''?" class='active'":'';?>><a alt='Головна сторінка' title='Головна сторінка' href="/">ГОЛОВНА</a></li>
            <li<?=$page=='about'?" class='active'":'';?>><a alt='Про нас' title='Про нас' href="/about">ПРО НАС</a></li>
            <li<?=$page=='newsline'?" class='active'":'';?>><a alt='Стрічка новин' title='Стрічка новин' href="/newsline">СТРІЧКА</a></li>
            <li<?=$page=='important'?" class='active'":'';?>><a alt='Головні, важливі новини' title='Головні, важливі новини' href="/important">ВАЖЛИВО!</a></li>
            <li<?=$page=='exclusive'?" class='active'":'';?>><a alt='Ексклюзивне' title='Стрічка новин' href="/exclusive">ЕКСКЛЮЗИВ</a></li>
            <li<?=$page=='public'?" class='active'":'';?>><a alt='Публікації' title='Публікації' href="/public">ПУБЛІКАЦІЇ</a></li>
            <li<?=$page=='photo'?" class='active'":'';?>><a alt='Фотоархів, галереї фото' title='Фотоархів, галереї фото' href="/photo">ФОТО</a></li>
            <li<?=$page=='video'?" class='active'":'';?>><a alt='Відеоархів, відео' title='Відеоархів, відео' href="/video">ВІДЕО</a></li>
            <li<?=$page=='dossier'?" class='active'":'';?>><a alt='Досье, біографії' title='Досье, біографії' href="/dossier">ДОСЬЄ</a></li>

            <?
            $title = "Новости"; $catId=0;
            foreach($static_data['categories'] as $itm) {
                $title = $itm['categoryName'];
                $catId = $itm['categoryId']>0?$itm['categoryId']:0;
                $linkCat = "/category/$itm[categoryTranslit]";
?>
                <li<?=($itm['categoryTranslit']==$id)?" class='active'":'';?>>
                        <a alt='<?=$itm['categoryName'];?>' title='<?=$itm['categoryName'];?>' href='<?=$linkCat;?>'><?=$itm['categoryName'];?></a>
                </li>
<?
            }
            ?>

        </ul>
    </div>