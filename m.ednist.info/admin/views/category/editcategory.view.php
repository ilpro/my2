<div class="news-page box newspage-content" id="categoryInlay" style="display: none;">

    <form method="post" id="formSave" class="mkUploadForm">

        <div class="head">

            <div id='categoryInlayId' class="numberId"></div>

            <div class="filter">

                <div class="floatRight">

                    <div class="update" title='Отменить редактирование новости (обновить)'><i></i></div>

                    <div class="save" type="submit" title='Сохранить категорию'><i></i></div>

                    <div class="delete" title='Удалить категорию'><i></i></div>

                    <div class="switch-left" title='Показать предыдущую категорию'><i></i></div>

                    <div class="switch-right" title='Показать следующую категорию'><i></i></div>

                    <div class="close" title='Закрыть редактирование категории'><i class="close"><span></span></i></div>

                </div>

            </div>

            <div class="clear"></div>

        </div>

        <div class="content clear">

            <input type="text" id="categoryName" class="newsarea enterfield" placeholder="Заголовок" tabindex="1"
                   autofocus/>

            <input type="text" id="categoryTranslit" class="newsarea enterfield" placeholder="Транслит" tabindex="2"
                   autofocus/>

            <textarea id="categorySearch" class="newsarea enterfield" name="categoryText" style="width: 100%;"
                      placeholder="Поиск"></textarea>

        </div>

        <textarea id="maincontent" name="newsText"></textarea>

    </form>

</div>

