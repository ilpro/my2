<? $lit = ''; $k=0; $i=0; ?>
            <? foreach($page_data['brands_all'] as $brandItem) { $k++; ?>
            <?if ($k == 1) echo "<div class='dossier-row'>" ?>

                <? $sortField = ($brandItem['brandSortName']!='') ? $brandItem['brandSortName'] : $brandItem['brandName'] ; ?>
                <? $currentLit = mb_substr($sortField, 0, 1,'UTF-8'); ?>

                <? if( $lit != $currentLit && $k !=1 ) { $i++?>
                        </ul>
                        </div>
                    </div>
            <?if ($i % 4 == 0) echo "<div class='clearfix'></div>
                    </div><div class='dossier-row'>" ?>
                <? } ?>

                <? if( $lit != $currentLit ) { ?>
                    <? $lit = $currentLit; ?>

                    <div class="item pull-left">
                        <div class="pull-left letter"><?=strtoupper($currentLit);?></div>
                        <div class="pull-left list">
                        <ul>

                <? } ?>

                        <li><a href="/dossier/<?=$brandItem['brandId'];?>"><?=$brandItem['brandName'];?></a></li>

                <? if( $k == count($page_data['brands_all']) ) { ?>
                        </ul>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    </div>
                <? } ?>
            <? } ?>