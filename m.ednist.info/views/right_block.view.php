<div class="right-block">

    <a href="/newsline" title='Переглянути новини у вигляді стрічки' class="list-link">СТРІЧКА</a>

    <div class="right-list" id="ajax-right">

    <?
        if( !empty($page_data['last_news']) ) {
            foreach ($page_data['last_news'] as $item) {
                include "sprite/newsRight.view.php";
            }
        }
    ?>

    </div>

</div>