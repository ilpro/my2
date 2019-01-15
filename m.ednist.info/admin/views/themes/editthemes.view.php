<div class="news-page box newspage-content" id="themesInlay" style="display: none;">

    <form method="post" id="formSave" class="mkUploadForm">

        <div class="head">

            <div id='themesInlayId' class="numberId"></div>

            <div class="filter">

                <div class="dropdown-label">
                    <select id="newsStatus" >
                        <? foreach($ini['themes.status'] as $i=>$k):?>
                            <option value="<?=$i?>" data-title="<?=$k;?>" data-foo="<?=$i?>"></option>
                        <? endforeach;?>
                    </select>
                </div>

                <div class="floatRight">

                    <div class="update" title='Отменить редактирование новости (обновить)'><i></i></div>

                    <div class="save" type="submit" title='Сохранить категорию'><i></i></div>

                    <div class="delete" title='Удалить категорию'><i></i></div>

                    <div class="switch-left" title='Показать предыдущую запись'><i></i></div>

                    <div class="switch-right" title='Показать следующую запись'><i></i></div>

                    <div class="close" title='Закрыть редактирование категории'><i class="close"><span></span></i></div>

                </div>

            </div>

            <div class="clear"></div>

        </div>

        <div class="content clear">

            <input type="text" id="themesName" class="newsarea enterfield" placeholder="Заголовок" tabindex="1"
                   autofocus/>

            <input type="text" id="themesSortName" class="newsarea enterfield" placeholder="Название для сортировки"
                   tabindex="2"/>

            <textarea id="themesSearch" class="newsarea enterfield" name="themesText" style="width: 100%;"
                      placeholder="Поиск"></textarea>
            <select id="themesType">
                <option value="0">---------</option>
                <option value="1">Физ. лицо</option>
                <option value="2">Юр. лицо</option>
                <option value="3">Партия</option>
            </select>

            <div id="newsGallery" class="clear">

                <div class="left">

                    <div id="imgAddFromPath" class="btn" title="Загрузите изображение" tabindex="4">Из папки</div><input class="image-file" type="file" multiple="multiple">

                    <div id="imgAddFromLink" class="btn" title="Загрузите изображение" tabindex="5">По ссылке</div>

                </div>

                <div class="mkUploadDropbox"></div>

                <div id="gallery">

                </div>

            </div>

        </div>

        <textarea id="maincontent" name="newsText"></textarea>

    </form>

</div>

