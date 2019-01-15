<div class="news-page box newspage-content" id="authorInlay" style="display: none;">

    <form method="post" id="formSave" class="mkUploadForm">

        <div class="head">

            <div id='authorInlayId' class="numberId"></div>

            <div class="filter">

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

            <input type="text" id="authorName" class="newsarea enterfield" placeholder="Заголовок" tabindex="1"
                   autofocus/>

            <textarea id="authorSearch" class="newsarea enterfield" name="authorText" style="width: 100%;"
                      placeholder="Поиск"></textarea>

            <div id="newsGallery" class="clear">

                <div class="left">

                    <div id="imgAddFromPath" class="btn" title="Загрузите изображение" tabindex="4">Из папки</div>
                    <input class="image-file" type="file" multiple="multiple">

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

