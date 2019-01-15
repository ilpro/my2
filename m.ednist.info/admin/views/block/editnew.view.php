<div class="news-page box newspage-content" id="newsInlay">
<form method="post" id="formSave" class="mkUploadForm">

<div class="head">
    <div id='newsInlayId' class="numberId" value=""></div>
    <div class="filter">
        <div class="save" type="submit" title='Сохранить материал'><i></i></div>
        <div class="data-top-time">
            <div title='ЗАДАТЬ ДАТУ ПУБЛИКАЦИИ | Новости сортируются по этому параметру и эта дата отображается, в каждой новости. Можно установить дату задним числом, либо будущим. Если будет установлена дата в будущем, новость опубликуется автоматически в заданное время' class="data-text">Дата публикации</div>
            <div class="data-data"><input tabindex="21" type="text" name="newsTimePublic" id="datepublic" value=""/></div>
        </div>
        <div title='Сброс даты публикации до реального значения первичного добавления новости, которое отображается внизу страницы,как "Дата добавления"' class="data-text-reset">Сброс даты</div>
      <!---  <div title='К материалу' class="button-to-material"></div> --->
        <div title='СОСТОЯНИЕ НОВОСТИ | Выбор состояния новости и ее публикация' class="checkboxes selects" style="width:160px;float:left;">
            <select class="newsarea" id="newsStatus" name="newsStatus" style="width:160px;">
                <? foreach ($ini['news.status'] as $i => $k): ?>
                    <option value="<?= $i ?>" data-title="<?= $k; ?>" data-foo="<?= $i ?>"><?= $k; ?></option>
                <? endforeach; ?>
            </select>
        </div>

        <div title='Заполить из базы последние сохраненные значения текстовых полей новости' class="update" title='Отменить редактирование новости (обновить)'><i></i></div>
        <div title='Удалить новость БЕЗВОЗВРАТНО' class="delete" title='Удалить новость'><i></i></div>

        <div class="floatRight">
            <div class="switch-left" title='Показать предыдущую новость'><i></i></div>
            <div class="switch-right" title='Показать следующую новость'><i></i></div>
            <div class="close" title='Закрыть редактирование новости'><i class="close"><span></span></i></div>
        </div>
    </div>
    <div class="clear"></div>
</div>

<div class="content clear">
    <input title='Заголовок материала' type="text" id="newsHeader" name="newsHeader" class="newsarea enterfield needautosave" placeholder="Заголовок"
           tabindex="1" autofocus/>
    <textarea  title='Подзаголовок материала' class="newsarea enterfield needautosave" rows="5" name="newsSubheader" id="newsSubheader" placeholder="Подзаголовок"
              tabindex="2"></textarea>

    <div  title='Тип материала' class="clear checkboxes selects">
        <select class="newsarea" id="newsType" name="newsType">
            <? foreach ($ini['news.type'] as $i => $k): ?>
                <option value="<?= $i ?>" data-foo="typenew<?= $i ?>" data-title="<?= $k; ?>"><?= $k; ?></option>
            <? endforeach; ?>
        </select>

        <input class="flag" type="checkbox" id="main-news" />
        <label title='Сделать материал главным (важным). Материал отобразится в блоке ТОП-новостей на главной странице' for="main-news"><span></span>Главная новость</label>
         &nbsp;&nbsp;&nbsp;
        <input  type="checkbox" id="ukrNet" name="ukrNet" value="0"/>
        <label class="ukrNet" title='Показать в укр.нет' for="ukrNet"><span></span>Показать в укр.нет</label>
    </div>

    <div class="clear checkboxes selects">
        <select title='КАТЕГОРИЯ МАТЕРИАЛА | Выберите наиболее подходящую категорию' class="newsarea" id="categoryName" name="categoryId">
            <option  value="0">Выберите категорию</option>

            <? foreach ($page_data['categories'] as $category) { ?>
                <option value='<?= $category['categoryId']; ?>'><?= $category['categoryName']; ?></option>
            <? } ?>

        </select>
        <input  id="inpVideo" type="checkbox" name="newsIsVideo" value="0"/><label title='Поставьте галочку, чтоб материал ображался, как содеращий видео'  for="inpVideo">
            <span></span>
            Содержит видео
        </label>
        &nbsp;&nbsp;&nbsp;
        <input id="inpGallery" name="newsIsGallery" type="checkbox" value="0"/><label title='Поставьте галочку, чтоб материал отображался,как содержащий фотогалерею (несколько фотографий)' for="inpGallery">
            <span></span>
            Содержит фотогалерею
        </label>
        
         
        <input  type="checkbox" id="isExclusive" name="newsExclusive" value="0"/>
        <label class="isExclusiv" title='Сделать эксклюзивной' for="isExclusive"><span></span>Сделать эксклюзивной</label>&nbsp;&nbsp;&nbsp;
    </div>


    <div  title='Блок добавления изображений' id="newsGallery" class="clear">
        <div class="left">
            <div id="imgAddFromPath" class="btn" title="Нажмите для загрузки изображения из папки" tabindex="4">Из папки</div>
            <input class="image-file" type="file" multiple="multiple">

            <div id="imgAddFromLink" class="btn" title="Нажмите для загрузки изображения по ссылке" tabindex="5">По ссылке</div>
        </div>
        <div class="mkUploadDropbox"></div>
        <div id="gallery"></div>
        <div id="imgDesc" class="clear">
            <textarea id="newsImgAlt" class="newsarea" rows="5" tabindex="6" placeholder="Описание картинки"></textarea>

            <div  title='Нажмите для сохранения описания для выбранного изображения' id="imgBtnDesc" class="btn">Сохранить описание</div>
        </div>
    </div>
    <div  title='Блок добавления видео' id="newsVideo">
        <div class="left">
            <label>
                <h3>Видео</h3>
                <input id='newsVideoUrl' name="newsVideo" type="text" tabindex="7" class="newsarea enterfield"
                       placeholder="Ссылка на видео YouTube"/>
            </label>
            <textarea id='newsVideoDesc' name="newsVideoDesc" class="newsarea enterfield" tabindex="8" rows="4"
                      placeholder="Описание видео"></textarea>
        </div>
        <div id="showVideo"></div>
    </div>



</div>
<div class="inputblock databutton">
   <div  title='Нажмите ддля получения данных из сохраненного черновика' id='addFromNotepad' class="btn" style="padding-top: 28px;">Черновик</div>
  <div  title='Нажмите для получения сохраненных данных из основной базы' id='addFromBD' class="btn">Сохраненный материал</div>
  <div title='После очистки черновика автосейв прекращаетса и ви одержите дание с бази даних. Чтоби начать автосохранения поставьте галочку  в *Писать в черновик*' id='clearNotice' class="btn">Очистить данные с черновика</div>
  <span id="notice_checkbox" >
  <input class="write_in_notice" type="checkbox" id="write_in_notice" />
        <label for="write_in_notice"><span></span>Писать в черновик</label></span>
</div>
    <div  title='ВНИМАНИЕ | Вы работаете в черновике! После нажатия кнопки "Сохранить материал" на верхней панели, данные заголовка, подзаголовка и основного текста материала будут сохранены в основную базу, а этот черновик удален' id="notice_active" value="0">черновик</div>
      <div id="pasteValue" data-paste="<?=$page_data['settingsPasteByCursor'];?>"></div>
    <textarea id="maincontent" name="newsText"></textarea>

<div class="content"><br>

    <div  title='Блок привязки новостей по теме' class="inputblock" id="attach">
        <label>
            <h3>Привязать</h3>
            <input  title='Начните вводить ID материала или текст материала для его поиска...' id='blockConnectInput' type="text" tabindex="10" class="newsarea"
                   placeholder="Начните вводить ID материала или текст материала для его поиска..."/>
            <span id='blockConnectLoading' class="loading"></span>
        </label>
        <span id='blockConnectWrap'></span>
    </div>

    <div title='Блок основного адреса первоисточника' class="inputblock">
        <label>
            <h3>Url первоисточника</h3>
            <input title='Укажите URL-ссылку на основной первоисточник материала' id='newsUrl' name="newsUrl" type="text" tabindex="11" class="newsarea enterfield" placeholder="Укажите URL-ссылку на основной первоисточник материала"
                   style="width: 619px;"/>

            <div id="newsUrlBtn" class="btn low" style="width: 195px;">Перейти по ссылке</div>
        </label>
    </div>

    <div title='Блок назавния источника/издания. Добавьте сначала источник на странице «Источники»' id='blockSource' class="inputblock teg">
        <label>
            <h3>Источник/издание</h3>
            <input id='blockSourceInput' title="Начните вводить источник/издание для поиска..." type="text" tabindex="12" class="newsarea"
                   placeholder="Начните вводить источник/издание для поиска..."/>
            <span id='blockSourceLoading' class="loading"></span>
        </label>
        <span id='blockSourceWrap'></span>

        <div id='blockSourceNew' class="newtag">Источник не найден.</div>
    </div>
    <div title='Блок привязок виджета' id='blockWidget' class="inputblock teg">
        <label>
            <h3>Тег опроса:</h3>
            <input id='newsWidgetTag' type="text" tabindex="15" class="newsarea"
                  title="Начните вводить тег для поиска..." placeholder="Начните вводить тег для поиска..."/>
            <span id='blockWidgetTagLoading' class="loading"></span>          
        </label>
          <span id='blockWidgetTagWrap'></span>
           <div id='blockWidgetTagNew' class="newtag">Тег не найден. <span id='blockWidgetTagNewInsert'>Добавить новый?</span></div>
         &nbsp;&nbsp;&nbsp;
        <input id="inpWidget" name="newsWidgetStart" type="checkbox"/><label for="inpWidget">
            <span></span>
            Вкл/Выкл опрос
        </label>
    </div>
     
    <div title='Блок автора статьи. Добавьте сначала автора на странице «Авторы»' id='blockAuthor' class="inputblock teg">
        <label>
            <h3>Автор</h3>
            <input id='blockAuthorInput' type="text" tabindex="13" class="newsarea"
                   title="Начните вводить автора для поиска..." placeholder="Начните вводить автора для поиска..."/>
            <span id='blockAuthorLoading' class="loading"></span>
        </label>
        <span id='blockAuthorWrap'></span>

        <div id='blockAuthorNew' class="newtag">Автор не найден.</div>
    </div>

    <div title='Блок привязки бренда/досье к материалу. Добавьте сначала бренд/досье на странице «Бренды»' id='blockBrand' class="inputblock teg">
        <label>
            <h3>Бренд</h3>
            <input id='blockBrandInput' type="text" tabindex="14" class="newsarea"
                   title="Начните вводить бренд/досье для поиска..." placeholder="Начните вводить бренд/досье для поиска..."/>
            <span id='blockBrandLoading' class="loading"></span>
        </label>
        <span id='blockBrandWrap'></span>
    </div>
	
	<div title='Блок привязки Facebook Pixel.' id='blockPixel' class="inputblock teg">
        <label>
            <h3>FB Pixel</h3>
            <select class="newsarea" id="fbpixelId" name="fbpixelId" style="width:340px;">
                <? foreach ($page_data['fbpixel'] as $k): ?>
                    <option value="<?= $k['fbpixelId'] ?>"><?= $k['fbpixelName']; ?></option>
                <? endforeach; ?>
            </select>
        </label>
        <span id='blockBrandWrap'></span>
    </div>
    
    <div title='Блок привязки темы к материалу. Добавьте сначала тему на странице «Темы»' id='blockThemes' class="inputblock teg">
        <label>
            <h3>Темы</h3>
            <input id='blockThemesInput' type="text" tabindex="14" class="newsarea"
                   title="Начните вводить тему для поиска..." placeholder="Начните вводить тему для поиска..."/>
            <span id='blockThemesLoading' class="loading"></span>
        </label>
        <span id='blockThemesWrap'></span>
    </div>

    <div title='Блок привязки тегов к материалу.' id='blockTag' class="inputblock teg">
        <label>
            <h3>Теги</h3>
            <input id='blockTagInput' type="text" tabindex="15" class="newsarea"
                  title="Начните вводить тег для поиска..." placeholder="Начните вводить тег для поиска..."/>
            <span id='blockTagLoading' class="loading"></span>
        </label>
        <span id='blockTagWrap'></span>

        <div id='blockTagNew' class="newtag">Тег не найден. <span id='blockTagNewInsert'>Добавить новый?</span></div>
    </div>

    <div title='Блок привязки регионов к материалу.'  id='blockRegion' class="inputblock teg">
        <label>
            <h3>Регионы</h3>
            <input id='blockRegionInput' type="text" tabindex="16" class="newsarea"
                  title="Начните вводить регион для поиска..." placeholder="Начните вводить регион для поиска..."/>
            <span id='blockRegionLoading' class="loading"></span>
        </label>
        <span id='blockRegionWrap'></span>

        <div id='blockRegionNew' class="newtag">Регион не найден. <span id='blockRegionNewInsert'>Добавить новый?</span>
        </div>
    </div>

<!--    <div title='SEO | TITLE  блок' class="inputblock datasearch">-->
<!--        <label>-->
<!--            <h3>Tittle</h3>-->
<!--            <textarea title="TITLE, отображаемый на странице новости"  id='newsSeoTitle' name="newsSeoTitle" type="text" tabindex="17" class="newsarea enterfield"-->
<!--                      placeholder="TITLE, отображаемый на странице новости"></textarea>-->
<!--        </label>-->
<!---->
<!--        <div id='getNewsSeoTitle' class="btn">Получить данные</div>-->
<!--    </div>-->
<!---->
<!--    <div title='SEO | KEYWORDS  блок' class="inputblock datasearch">-->
<!--        <label>-->
<!--            <h3>Keywords</h3>-->
<!--            <textarea title="KEYWORDS, отображаемый на странице новости" id='newsSeoKeywords' name="newsSeoKeywords" tabindex="18" rows="4" class="newsarea enterfield"-->
<!--                      placeholder=""></textarea>-->
<!--        </label>-->
<!---->
<!--        <div id='getNewsSeoKeywords' class="btn">Получить данные</div>-->
<!--    </div>-->
<!---->
<!--    <div title='SEO | DESCRIPTION  блок' class="inputblock datasearch">-->
<!--        <label>-->
<!--            <h3>Description</h3>-->
<!--            <textarea title="DESCRIPTION, отображаемый на странице новости" id='newsSeoDesc' name="newsSeoDesc" tabindex="19" rows="4" class="newsarea enterfield"-->
<!--                      placeholder=""></textarea>-->
<!--        </label>-->
<!---->
<!--        <div id='getNewsSeoDesc' class="btn">Получить данные</div>-->
<!--    </div>-->
<!---->
<!--    <div class="inputblock datasearch">-->
<!--        <label>-->
<!--            <h3>Поиск</h3>-->
<!--            <textarea id='searchText' name="newsSearch" tabindex="20" rows="4" class="newsarea enterfield"-->
<!--                      placeholder=""></textarea>-->
<!--        </label>-->
<!---->
<!--        <div id='getSearchText' class="btn">Получить данные</div>-->
<!--    </div>-->

    <div title='Блок загрузки документа и прикрепления его к материалу' id='blockDocs' class="inputblock teg">
        <label>
            <h3>Документ</h3>
            <input type="text" tabindex="16" class="newsarea linkdocadd" placeholder="Ссылка"/>

        </label>

        <div id="docAddFromLink" title='Нажмите для загрузки по ссылке' class="btn low">По ссылке</div>
        <div id="docAddFromPath" title='Нажмите для загрузки из папки' class="btn low">Из папки</div>
        <input class="image-file" type="file" multiple="multiple">
        <span id='blockDocsWrap' class="clear"></span>

        <div class="mkUploadDropbox"></div>
    </div>

    <div class="clear checkboxes socialbtns">

        <input id="inpFacebook" name="newsPostFb" type="checkbox"/><label for="inpFacebook">
            <span></span>
            Facebook
        </label>
        &nbsp;&nbsp;&nbsp;
        <input id="inpVkontakte" name="newsPostVk" type="checkbox"/><label for="inpVkontakte">
            <span></span>
            Vkontakte
        </label>
        &nbsp;&nbsp;&nbsp;
        <input id="inpTwitter" name="newsPostTw" type="checkbox"/><label for="inpTwitter">
            <span></span>
            Twitter
        </label>
        &nbsp;&nbsp;&nbsp;


        <span id="timePublicSocial" class="docitem"><input name="newsSocTime" class="timetext" type="text"
                                                           placeholder="Выбрать время"/><span
                class="close"><i></i></span></span>

        <div id="publicNow" class="btn low">Опубликовать сейчас</div>

    </div>
    <div id='newsSocTimePosted'></div>
    <textarea id="articleSocial" name="newsSocText" class="newsarea enterfield" rows="5"
              placeholder="Описание для социальных сетей" tabindex="342"></textarea>

</div>
<div class="row didived data without-hover">
    <div title='Дата фактического добавления материала' class="data-add">
        <div class="data-text">Дата добавления</div>
        <div class="data-data"><input type="text" name="newsTime" id="dateadd" disabled value=""/></div>
    </div>
    <div title='Дата последнего редактирвоания материала' class="data-add">
        <div class="data-text">Дата последнего редактирования</div>
        <div class="data-data"><input tabindex="21" name="newsTimeUpdate" type="text" id="dateupdate" value=""/></div>
    </div>
</div>
</form>
   <!--- <div id='addText' class="btn">AddText</div> --->
</div>
