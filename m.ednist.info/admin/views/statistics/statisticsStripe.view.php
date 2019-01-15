<div class="some-user" data-userId="<?=$item['userId']?>">
    <table>
        <tr>
            <td class="user_name"><?=$item['userName']?></td>
            <td class="news_count"><?=$item['newsCount']['countNews']?></td>
            <td class="news_visits">
                <? if(isset($item['newsCount']['countVisits'])){
                    echo $item['newsCount']['countVisits'];
                }else{
                 echo 0;   
                }?>
               
            </td>
            <td class="news_efficiency">
                  <? if(isset($item['newsCount']['countVisits'])){
                    echo round(($item['newsCount']['countVisits']/$item['newsCount']['countNews']),2);
                }else{
                 echo 0;   
                }?>
            </td>
        </tr>
    </table>
 
</div>