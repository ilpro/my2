<div class="brandconnect-content" id="brandconnectInlay">      <div class="head">            <div class="clear"></div>            <?            include Config::getAdminRoot() . "/views/block/logo_item.view.php";            include Config::getAdminRoot() . "/views/block/menu_item.view.php";            ?>        </div>    <div class="inputblock">    <label>               <form method="post" id="formSave" class="mkUploadForm">            <h3>Добавить новую связь</h3>            <input type="text" id="connectName" name="connectName" class="newsarea enterfield" placeholder="Введити имя связи..."/>            <div id="newBrandConnect" class="btn low" style="width: 195px;">Добавить</div>        </form>             </label>   </div>    <table id="list_brand_connects">        <tr>            <td class="td_main_name">Имя</td>            <td class="td_main">Редактировать</td>            <td class="td_main">Удалить</td>        </tr>    </table></div>