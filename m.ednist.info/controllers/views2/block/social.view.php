<script type="text/javascript">


      
        Share = {
            vkontakte: function (purl, ptitle, pimg, text) {
                url = 'http://vkontakte.ru/share.php?';
                url += 'url=' + encodeURIComponent(purl);
                url += '&title=' + encodeURIComponent(ptitle);
                url += '&image=' + encodeURIComponent(pimg);
                url += '&noparse=true';
                Share.popup(url);
            },
            google: function(purl,ptitle,pimg,text){
                url='https://plus.google.com/share?';
                url+='url='+ encodeURIComponent(purl);
                url += '&title=' + encodeURIComponent(ptitle);
                url += '&image=' + encodeURIComponent(pimg);
                url += '&noparse=true';
                Share.popup(url);
            },
            facebook: function (purl, ptitle, pimg, text) {
                url = 'http://www.facebook.com/sharer.php?s=100';
                url += '&p[title]=' + encodeURIComponent(ptitle);
                url += '&p[summary]=' + encodeURIComponent(text);
                url += '&p[url]=' + encodeURIComponent(purl);
                url += '&p[images][0]=' + encodeURIComponent(pimg);
                Share.popup(url);
            },
            twitter: function (purl, ptitle) {
                url = 'http://twitter.com/share?';
                url += 'text=' + encodeURIComponent(ptitle);
                url += '&url=' + encodeURIComponent(purl);
                url += '&counturl=' + encodeURIComponent(purl);
                Share.popup(url);
            },
            popup: function (url) {
                window.open(url, '', 'toolbar=0,status=0,width=626,height=436');
            }
        };

</script>
<?
if ((isset($page_data['material']) && $id)) {
    $type = "news";
    $header = htmlspecialchars(addslashes($page_data['material']['newsHeader']));
    $url = "http://" . $_SERVER['HTTP_HOST'] . "/". $type."/". $page_data['material']['newsId'];
   // $address = "http://" . $_SERVER['HTTP_HOST'] . "/media/socimage_" .  $type . "_" . $page_data['material']['newsId'] . ".jpg";
}
if (isset($page_data['brand']) && $id) {
    $typeImg = "brand";
    $type="dossier";
    $header = htmlspecialchars(addslashes($page_data['brand']['brandName']));
    $url = "http://" . $_SERVER['HTTP_HOST'] . "/". $type."/". $page_data['brand']['brandId'];
  //  $address = "http://" . $_SERVER['HTTP_HOST'] . "/media/socimage_" .  $typeImg . "_" . $page_data['brand']['brandId'] . ".jpg";
}


//$address = $ini['url.media'] . "images/" . $page_data['material']['newsId'] . "/main/400.jpg";


?>

<div class="social-add">
    <div class="pull-left">
        Поділитися:
    </div>
    <ul>
        <li class="first-row">
            <a onclick="Share.facebook('<?= $url; ?>', '<?= $header ?>', '<?= $address ?>')" title="Поділитися у «Facebook»" href="javascript:void(0)">
                <i class="ico ico-fb"></i>
            </a>
        </li>
        <li class="first-row">
            <script type="text/javascript">

                document.write(VK.Share.button({
                    image: "<?=$address;?>",
                    noparse: false,
                    title: "<?=htmlspecialchars($title);?>",
                    description: "<?=htmlspecialchars($description);?>"
                }, {type: "custom", text: "<i class='ico ico-vk' title='Поділитися у «Вконтакте»'></i>"}));

            </script>
        </li>
        <li class="first-row">
            <a onclick="Share.google('<?= $url; ?>', '<?= $header ?>', '<?= $address ?>')" title="Поділитися у «Google+»" href="javascript:void(0)">
                <i class="ico ico-gp"></i>
            </a>
        </li>
        <li>
            <a onclick="Share.twitter('<?= $url; ?>', '<?= $header ?>')" title="Поділитися у «Twitter»" href="javascript:void(0)">
                <i class="ico ico-tw"></i>
            </a>
        </li>
    </ul>
    <div class="clearfix"></div>
</div>