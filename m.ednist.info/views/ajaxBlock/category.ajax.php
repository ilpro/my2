<?
$banner .= '
<div class="category-sidebar">
    <div class="block-head">
        <a href="/category/resonans" title="Резонанс">
            ' . $title . '
        </a>
    </div>
    <div class="news-list" id="append-category">';
            foreach($newsList as $item){
                include 'sprite/categoryItem.view.php';
            }
$banner .= '</div>
</div>
';