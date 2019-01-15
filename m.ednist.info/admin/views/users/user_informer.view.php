
    <?if(isset($item['inNews'])&&$item['inNews']!=false){?>
    <div class="some_user_informer">
        <span class="informer-user-name"><?=$item['userName'];?>: </span> 
   <? foreach ($item['inNews'] as $value) {
        echo '<span class="informer-news-name">'.$value['newsName'].'</strong><br />';
    }?>
    </div>
      <hr/>
    <?}
    
?>
