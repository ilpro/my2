<div class="header">
    <a href="/" class="pull-left logo" title="ЄДНІСТЬ | Головна сторінка">
        <i class="ico ico-logo"></i>
    </a>
    <div class="header-center pull-left">
        <div class="top-block pull-right">
            <div class="weather">
                <div class="weather-row">
                    <?
                    $weather = Rates::getWeather();
                    $temp = round($weather['main']['temp']);
                    $temp = ($temp > 0)? '+'.$temp.' ºC' : $temp.' ºC';
                    $wind = $weather['wind']['speed'] . " м/с";
                    ?>
                    <span class="city">Київ</span>
                    <span class="temperature"><?=$temp?></span>
                    <span class="wind">, <?=$wind?></span>
                    <div class="clearfix"></div>
                </div>
                <div class="weather-row">
                    <?=$weather['weather'][0]['description'];?>
                </div>
            </div>
<!--             <div class="money pull-right">
                <?
                    $rates = Rates::getRates();
                    foreach($rates as $item){
                        echo '<div class="money-row">
                                <span>' . $item["ccy"] . '</span>
                                <span>' . round($item["buy"], 2) .'</span>
                                <span>' . round($item["sale"], 2) . '</span>
                             </div>';
                    }
                ?>
            </div> -->
            <div class="clearfix"></div>
        </div>
<!--        <a class="bottom-block pull-left" href="/newyear/"><img src="/assets/img/new_year_banner.png"></a>-->
        <a href="http://www.ednist.info/about" class="site-info pull-right">Інформаційне агентство «єдність»</a>
<!--         <div class="bottom-block pull-right">
            <a href="/todaynews" title="ЄДНІСТЬ | Новини за сьогодні">
                <div class="day pull-left">
                    <?=MKdate::getNowWeek()?>
                </div>
                <div class="date pull-left">
                    <div class="pull-left">
                        <?=date("d")?>
                    </div>
                    <div class="year pull-left">
                        <span><?=MKdate::getNowMonth()?></span>
                        <span><?=date("Y")?></span>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </a>
            <div class="clearfix"></div>
        </div> -->

    </div>
    <div class="pull-right header-right">
        <div class="social pull-left">
            <ul>
                <li class="first-row">
                    <a href="<?=$settings['settingsPageFb'];?>" title="Єдність у «Facebook»">
                        <i class="ico ico-fb"></i>
                    </a>
                </li>
                <li class="first-row">
                    <a href="<?=$settings['settingsPageVk'];?>" title="Єдність у «Вконтакте»">
                        <i class="ico ico-vk"></i>
                    </a>
                </li>
                <li class="first-row">
                    <a href="<?= $settings['settingsPageOk']; ?>" title="Єдність у «Одноклассники»">
                        <i class="ico ico-ok"></i>
                    </a>
                </li>
                <li class="first-row">
                    <a href="<?= $settings['settingsPageGl']; ?>" title="Єдність у «Google+»">
                        <i class="ico ico-gp"></i>
                    </a>
                </li>
                <li>
                    <a href="<?=$settings['settingsPageTw'];?>" title="Єдність у «Twitter»">
                        <i class="ico ico-tw"></i>
                    </a>
                </li>
                <li>
                    <a href="<?= $settings['settingsPageYou']; ?>" title="Єдність у «Youtube»">
                        <i class="ico ico-yt"></i>
                    </a>
                </li>
                <li>
                    <a href="/rss" title="RSS Єдність">
                        <i class="ico ico-rss"></i>
                    </a>
                </li>
            </ul>
            <div class="clearfix"></div>
        </div>
        <a href="/about" class="about pull-right" title="ЄДНІСТЬ | Про нас">
            <i class="ico ico-about"></i>
        </a>
        <div class="clearfix"></div>
        <div class="search">
            <form action="/search/" style="margin: 0;">
                <input type="text" name="find" placeholder="">
                <button type="submit" formaction="/search/" class="search-link" title="Пошук">
                    <i class="ico ico-search"></i>
                </button>
                <div class="clearfix"></div>
            </form>
        </div>
    </div>
    <div class="clearfix"></div>
</div>
<div class="menu-container">

    <div class="top-menus">
        <div class="main-menu">
            <ul>
                <li <?=$page=='important'?" class='active'":'';?>>
                    <a title='Головні, важливі новини' href="/important">Важливо!</a>
                </li>
                <li <?=$page=='exclusive'?" class='active'":'';?>>
                    <a title='Ексклюзив!' href="/exclusive">Ексклюзив!</a>
                </li>
                <li <?=$page=='exclusive'?" class='active'":'';?>>
                    <a title='Ексклюзив!' href="/interview">IНТЕРВ’Ю</a>
                </li>
                <li <?=$page=='exclusive'?" class='active'":'';?>>
                    <a title='Ексклюзив!' href="/analytics">АНАЛIТИКА</a>
                </li>
                <li <?=($id=='resonans')?" class='active'":'';?>>
                    <a title='Резонанс' href="/category/resonans">Резонанс</a>
                </li>
                <li <?=$page=='public'?" class='active'":'';?>>
                    <a title='Публікації' href="/public">Публікації</a>
                </li>
                <li <?=$page=='popular'?" class='active'":'';?>>
                    <a title='Популярне' href="/popular">Популярне</a>
                </li>
                <li <?=($id=='infografika')?" class='active'":'';?>>
                    <a title='Інфографіка' href="/category/infografika">Інфографіка</a>
                </li>
                <li <?=$page=='dossier'?" class='active'":'';?>>
                    <a title='Досье, біографії' href="/dossier">Досьє</a>
                </li>
            </ul>
            <div class="clearfix"></div>
        </div>
        <div class="sub-menu">
            <ul>
                <li>
                    <a title='Світ' href="/category/world">
                        Світ
                    </a>
                </li>
                <li>
                    <a title='Україна' href="/category/ukraine">
                        Україна
                    </a>
                </li>
                <li>
                    <a title='Київ' href="/category/kyiv%20kiev">
                        Київ
                    </a>
                </li>
                <li>
                    <a title='Події' href="/category/accidents">
                        Події
                    </a>
                </li>
                <li>
                    <a title='Політика' href="/category/politics">
                        Політика
                    </a>
                </li>
                <li>
                    <a title='Економіка та бізнес' href="/category/economy">
                        Економіка та бізнес
                    </a>
                </li>
                <li>
                    <a title='Суспільство' href="/category/society">
                        Суспільство
                    </a>
                </li>
                <li>
                    <a title='Блог' href="#">
                        Блог
                    </a>
                </li>
                <li class="dark">
                    <a title='Війна' href="/category/war">
                        Війна
                    </a>
                </li>
                <li  class="icon-item <?=$page=='photo'?" active":'';?>">
                    <a title='Фото' href="/photo">
                        <i class="ico ico-menuPhoto"></i>
                    </a>
                </li>
                <li class="icon-item <?=$page=='video'?" active":'';?>">
                    <a title='Відео' href="/video">
                        <i class="ico ico-menuVideo"></i>
                    </a>
                </li>
                <li class="dropdown-item icon-item">
                    <a href="javascript:void(0)">
                        <i class="ico ico-menuDropdown"></i>
                    </a>
                    <ul>
                        <li>
                            <a title='Шоу-біз та культура' href="/category/show-biz">
                                Шоу-біз та культура
                            </a>
                        </li>
                        <li>
                            <a title='Наукові технології' href="/category/science">
                                Наукові технології
                            </a>
                        </li>
                        <li>
                            <a title='Інтернет' href="/category/internet">
                                Інтернет
                            </a>
                        </li>
                        <li>
                            <a title='Спорт' href="/category/sport">
                                Спорт
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
            <div class="clearfix"></div>
        </div>
    </div>
</div>

<!--ADoffer add ------------------------------------------------------------------------------------------>
    <?include Config::getRoot()."/views/banners/top-banner.adoffer.view.php";?>
<!--ADoffer add ------------------------------------------------------------------------------------------>
