<?

$avat = isset($item['authorImg']) ? "<img src='" . $ini['url.media'] . "author/" . $item['authorId'] . "/60.jpg' />" : '';

?>

<div class="authorStripe row all cla" data-mkauthor='{"authorId":"<?= $item['authorId'] ?>"}'>

    <div class="check">

        <input type="checkbox" id='b<?= $item['authorId'] ?>'/>

        <label for='b<?= $item['authorId'] ?>'><span></span></label>

    </div>


    <div class="avatar go"><?= $avat ?></div>

    <div class="identifier go"><p><?= $item['authorId'] ?></p></div>

    <div class="intro go"><p><?= $item['authorName'] ?></p></div>

    <!--div class="icon-type go"><i class="list"></i></div-->

    <div class="clear"></div>


</div>