<?

Config::incJavaScript('jquery-1.8.2.min.js');

Config::incJavaScript('tinymce/js/tinymce/tinymce.min.js');


db::sql("SELECT newsText FROM tbl_news WHERE newsId = '423'");

$resultat = db::query();

db::sql("SELECT newsText FROM tbl_news WHERE newsId = '422'");

$resultat2 = db::query();

require_once "head.view.php";

?>

<a id="btn">GO</a>

<textarea id="mytest"><?= $resultat[0]['newsText']; ?></textarea>

<script>

    $(function () {

        tinymce.init({

            selector: "textarea#mytest",

            height: 500,

            language: 'ru',

            plugins: [

                "advlist autolink lists link image charmap print preview anchor",

                "searchreplace visualblocks code fullscreen",

                //  "insertdatetime media table contextmenu paste moxiemanager"

            ],

            toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"

        });


        $('#btn').bind('click', function () {

            //tinyMCE.execCommand('mceSetContent',false,<?=json_encode($resultat2[0]['newsText']);?>);

            tinyMCE.activeEditor.selection.setContent(<?=json_encode(html_entity_decode($resultat2[0]['newsText']));?>);

            var contnt = tinyMCE.activeEditor.selection.getContent();

            alert(contnt);

        });


    })

</script>









