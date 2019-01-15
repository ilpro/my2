<? if($id) { ?>
    <div class="seo-block">
        <p class='social-title'>Цю публікацію можна знайти за такими запитами:</p>
        <div class="center-block-inner">
            <p class='searchWords'>
            <p>
                <?

                $m = strtotime($page_data['material']['newsTimeOrigin']);
                $seoString= $page_data['material']['newsId'].' '.Search::mkSearchDate($m);
                if( $page_data['material']['newsIsVideo']!=0 ) $seoString=$seoString.' видео відео '.$page_data['material']['newsVideoDesc'];
                if( $page_data['material']['newsIsGallery']!=0 ) $seoString=$seoString.' фото галерея ';
                if( $page_data['material']['authorName']!='' ) $seoString=$seoString.' '.$page_data['material']['authorName'];

                foreach ( $page_data['material']['brands'] as $brand ) {
                    $seoString=$seoString.' '.$brand['brandSearch'];
                }
                foreach ( $page_data['material']['tags'] as $tag ) {
                    $seoString=$seoString.' '.$tag['tagSearch'];
                }
                foreach ( $page_data['material']['regions'] as $region ) {
                    $seoString=$seoString.' '.$region['regionSearch'];
                }
                foreach ( $page_data['material']['images'] as $key=>$img ) {
                    if( isset($img['desc']) && $img['desc']!='' && $key!='main' ) $seoString=$seoString.' '.$img['desc'];
                }
                $layout=new Search();
                $seoString=$seoString.' '.implode(" ",$layout->changeLayout($seoString));
                echo $seoString;
                ?>
            </p>
            </p>
        </div>
    </div>


<? } ?>