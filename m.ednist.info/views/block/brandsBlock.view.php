<div class="center-block full-width">
    <div class="block-title">ДОСЬЄ</div>
    <div class="news-block dossier-block">
        <div class="news-preview">
            <div id="append">
                <?
                if ($page_data['brands_bottom'])
                    foreach ($page_data['brands_bottom'] as $item)
                        include "views/sprite/brand.view.php";
                ?>
            </div>

           
        </div>
    </div>
</div>       
