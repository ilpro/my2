<?

Config::incStyleCss('jquery.multyselect.css');

Config::incStyleCss('jquery.dropdown.css');

Config::incStyleCss('global.css');

Config::incStyleCss('ui/jquery-ui.custom.min.css');

Config::incStyleCss('jquery.datetimepicker.css');

Config::incStyleCss('select2.css');

Config::incStyleCss('global.css');

Config::incStyleCss('page.news.css');


Config::incJavaScript('jquery-1.8.2.min.js');

Config::incJavaScript('jquery-ui-1.10.4.js');

Config::incJavaScript('functions.js');

Config::incJavaScript('jquery.datetimepicker.js');


Config::incJavaScript('jquery.multiselect.js');

Config::incJavaScript('jquery.dropdown.js');

Config::incJavaScript('jquery.mkLightBulb.js');

Config::incJavaScript('jquery.mkBlink.min.js');

Config::incJavaScript('jquery.mkSelect.js');

//Config::incJavaScript('jquery.mkLogin.js');


Config::incJavaScript('tinymce/js/tinymce/tinymce.min.js');


Config::incJavaScript('md5.js');

Config::incJavaScript('select2.js');

Config::incJavaScript('select2_locale_ru.js');

Config::incJavaScript('functions.js');


Config::incJavaScript('jquery.autocomplete.js', 'bottom');


Config::incJavaScript('page.news.js');

Config::incJavaScript('page.newspage.js');

//MAX    

//Config::incJavaScript('jquery.ba-bbq.min.js','bottom');

//Config::incJavaScript('jquery.mkAdminNews.js','bottom');

//Config::incJavaScript('jquery.mkAdminNewsInlay.js','bottom');

require_once "head.view.php";

include "block/editnew.view.php";

?>

    <div class="wrapper box news" id="auth">

    <div class="head">

    <div class="clear"></div>

    <?

    include $adminPath . "/views/block/logo_item.view.php";

    include $adminPath . "/views/block/menu_item.view.php";

    ?>

    <div class="search ui-widget">

        <i class="search-icon"></i>

        <input type="text" name="country" id="tags" placeholder="Поиск">

    </div>

    <div class="filter">

        <div class="people" data-dropdown="#dropdown-2">

            <p>Все материалы <i class="caret"></i></p>

        </div>

        <div class="trash">

            <i></i>

        </div>

        <div class="sort" onclick="toggle('a');">

            <i></i>

        </div>

        <div class="add" onclick="window.location.href='newspage'">

            <i></i>

        </div>

        <div class="exit" id="logout">

            <i></i>

        </div>

    </div>

    <div id="dropdown-2" class="dropdown dropdown-tip dropdown-anchor-right dropdown-relative">

        <ul class="dropdown-menu">

            <li><a href="#1">Item 1</a></li>

            <li><a href="#2">Item 2</a></li>

            <li><a href="#3">Item 3</a></li>

            <li><a href="#4">Item 4</a></li>

            <li><a href="#5">Item 5</a></li>

            <li><a href="#5">Item 6</a></li>

        </ul>

    </div>

    <div class="clear"></div>

    <!--

    <div id="flavor-nav">

        <a rel="all" class="current">All</a>

        <a rel="cla">Classic</a>

        <a rel="adv">Adventurous</a>

        <a rel="tea">Tea-Inspired</a>

    </div>-->

    <div id="a" class="dropdown-nav-new">

    <div class="check">

        <input type="checkbox" id="c2" name="myPublication"/>

        <label for="c2"><span></span></label>


    </div>

    <div class="all_news" data-dropdown="#dropdown-3"><i></i>

        <p>Все материалы </p><i class="caret_down"></i>


    </div>


    <div id="dropdown-3" class="dropdown dropdown-tip dropdown-anchor-right dropdown-relative">

        <ul class="dropdown-menu">

            <li>

                <a href="#">

                    <div class="img-down"><i></i></div>

                    <p rel="all">Все материалы </p>

                </a>

            </li>

            <li>

                <a href="#">

                    <div class="icon-holder">

                        <div class="icon-status red">

                            <span class="new-icon"></span>


                            <i class="checked"></i>

                        </div>

                    </div>

                    <div class="cat_name"><p rel="all">Новые </p></div>

                </a>

                <div class="clear"></div>

            </li>

            <li>

                <a href="#">

                    <div class="icon-holder">

                        <div class="icon-status grey">

                            <span class="status-icon"></span>

                            <i class="checked"></i>

                        </div>

                    </div>

                    <div class="cat_name"><p rel="tea">Важные </p></div>

                </a>


                <div class="clear"></div>

            </li>

            <li>

                <a href="#">

                    <div class="icon-holder">

                        <div class="icon-status blue">

                            <span class="checked-icon"></span>

                            <i class="checked"></i>

                        </div>

                    </div>

                    <div class="cat_name"><p rel="tea">Обработанные </p></div>

                </a>


                <div class="clear"></div>

            </li>


            <li>

                <a href="#">

                    <div class="icon-holder">

                        <div class="icon-status yellow">

                            <span class="clock-icon"></span>

                            <i class="checked"></i>

                        </div>

                    </div>

                    <div class="cat_name"><p rel="adv">Ожидают публикации </p></div>

                </a>


                <div class="clear"></div>

            </li>

            <li>

                <a href="#">

                    <div class="icon-holder">

                        <div class="icon-status green">

                            <span class="checkin-icon"></span>

                            <i class="checked"></i>

                        </div>

                    </div>

                    <div class="cat_name"><p rel="cla">Опубликованные </p></div>


                </a>

                <div class="clear"></div>

            </li>


        </ul>

    </div>

    <div class="myCheckbox">

        <select id="example2" name="exampl2e" multiple="multiple">

            <option value="1">efwe</option>

            <option value="2">O234</option>

            <option value="3">qwr</option>

            <option value="4">Opwer4</option>

            <option value="5">Option 5</option>

        </select>

    </div>


    <div class="myCheckbox">

        <select id="example" name="example" multiple="multiple">

            <option value="1">Все разделы</option>

            <option value="1">efwe</option>

            <option value="2">O234</option>

            <option value="3">qwr</option>

            <option value="4">Opwer4</option>

            <option value="5">Option 5</option>

            <option value="6">efwe</option>

            <option value="7">O234</option>

            <option value="8">qwr</option>

            <option value="9">Opwer4</option>

            <option value="10">Option 5</option>

        </select>

    </div>

    <!--<div class="cats"><p>5 разделов </p><i class="caret_down"></i></div>-->


    <div class="publications">


        <div class="check">

            <input type="checkbox" id="c1" name="myPublication"/>

            <label for="c1"><span></span>Мои публикации</label>

        </div>

    </div>

    <div class="close"><i onclick="toggle('a');" class="close"><span></span></i></div>


    </div>

    </div>

    <div class="clear"></div>

    <div class="content" id="all-flavors">

    </div>

    </div>



<?

include "foot.view.php";

?>