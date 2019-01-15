<style>
    .ad2 .menu-item .ad-text {
        color: white;
    }
</style>


<div id="view-news" class="content" style="margin-bottom: 10px">
	<div class="left-content pull-left">
		<div class="left-content pull-left">
			<div class="head">
				<a href="/category/society"><span itemprop="articleSection">Суспільство</span></a>
			</div>
			<div class="news-header">
				<div class="image pull-left">
					<div class="main-img-block">
						<a href="https://www.ednist.info/media/images/35540/raw/07ddae7418c2d642d8b2312ae8ef2850.jpg" data-caption="NASA запропонувало користувачам мережі 3D екскурсію по Марсу (ВІДЕО)">
							<img itemprop="image" src="https://www.ednist.info/media/images/35540/big/07ddae7418c2d642d8b2312ae8ef2850.jpg" alt="NASA запропонувало користувачам мережі 3D екскурсію по Марсу (ВІДЕО)" title="NASA запропонувало користувачам мережі 3D екскурсію по Марсу (ВІДЕО)">
						</a>
					</div>
				</div>
				<div class="news-description">
					<div class="pull-left date">
						<div class="pull-left time" itemprop="datePublished" content="09.02.2016 о 18:00">
							09.02.2016 о 18:00                    </div>
						<div class="pull-left icons">
							<a href="/video"><i class="ico ico-video" title="Відео"></i></a>                        </div>
						<div class="clearfix"></div>
					</div>
					<h1 itemprop="name" class="news-title">
						NASA запропонувало користувачам мережі 3D екскурсію по Марсу (ВІДЕО)                </h1>
					<h2 class="news-subtitle">
						Національне управління з аеронавтики і дослідження космічного простору оприлюднило 3D-екскурсію по поверхні Марса, яке склали зі знімків ровера Curiosity.                </h2>
					<div class="news-tags">
						<div class="clearfix"></div>
					</div>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="news-body" itemprop="articleBody">
				<p>Як повідомляє <a href="../" rel="follow">«Є!»</a>, агенство опублікувало дивовижне відео, зняте у&nbsp;піщаній дюні Наміб у регіоні Bagnold Dune Field.</p>
				<p>Користувачі можуть власноруч керувати зображенням у кадрі. Як відомо, марсохід Curiosity не обладнаний камерою, що дозволяє робити 360-градусне відео, проте експерти створили відео з десятків знімків, які зробив апарат.</p>
				<p>Спочатку екскурсію робили для перегляду на смартфонах, проте потім вирішили запустити її й для користувачів ПК.</p>
				<p><iframe src="https://www.youtube.com/embed/ME_T4B1rxCg" width="560" height="315" frameborder="0" allowfullscreen=""></iframe></p>
			</div>

		</div>




		<? include Config::getRoot().'/views/banners/video.banner.php';?>

		<? include Config::getRoot().'/views/block/social.view.php';?>

        <!--ADoffer add ------------------------------------------------------------------------------------------>
        <div class="video-container flexrow">
            <div class="adware-publish-video flexrow">
                <div class="video-min flexrow">
                    <p class="ad-places-text">
                        Відео 640px Х 480px <br>
                           або 580px Х 360px
                    </p>
                </div>
            </div>
        </div>
        <!--ADoffer add ------------------------------------------------------------------------------------------>




		<? if(count($item['images'])>2): ?>
			<div class="gallery">
				<? foreach($item['images'] as $k=>$img) {
					if($k==='main') continue; /// пофіксити в моделі
					?>
					<a class='galleryImg' href="<?=$img['raw'];?>" data-caption="<?=$img['desc'];?>">
						<img alt="<?=$img['desc'];?>" title="<?=$img['desc'];?>" src="<?=$img['gallery'];?>">
					</a>
				<? } ?>
				<div class="clearfix"></div>
			</div>
		<? endif; ?>

		<?
		if(!empty($page_data['news_bottom']))
			$i=0;
		foreach($page_data['news_bottom'] as $item){
			include Config::getRoot()."/views/sprite/readToo.view.php";
		}
		?>
		<?
		if(!empty($page_data['material']['brands']))
			foreach($page_data['material']['brands'] as $item)
				include Config::getRoot()."/views/sprite/brand.view.php";
		?>
	</div>
	<div class="right-content pull-right">

		<?include Config::getRoot()."/views/block/mediastealerWidget.view.php"; ?>


        <!--ADoffer add ------------------------------------------------------------------------------------------>
        <ins style="display:inline-block;width:300px;height:250px">

            <div class="adware-main-middle-bottom flexrow">
                <p class="ad-places-text">
                    Ширина: 300px <br>
                    Висота: 250px
                </p>
            </div>


        </ins>
        <!--ADoffer add ------------------------------------------------------------------------------------------>


		<div class="category-sidebar">
			<div class="block-head">
				<a href="/category/resonans" title="Резонанс">
					РЕЗОНАНС
				</a>
			</div>
			<div class="news-list" id="append-category">
				<a href="/news/41678" class="small-list-item">
					<div class="time">
						22.04.2016 о 12:00    </div>
					<div class="title">
						Насалик прокоментував чутки, що його заступником стане людина з «ЛНР»    </div>
				</a><a href="/news/41509" class="small-list-item">
					<div class="time">
						20.04.2016 о 18:45    </div>
					<div class="title">
						Свободівця Мірошниченка облили фекаліями в Київраді (ВІДЕО)    </div>
				</a><a href="/news/41308" class="small-list-item">
					<div class="time">
						18.04.2016 о 16:40    </div>
					<div class="title">
						В Ужгороді почали судити шкуродера, який відрубав собаці голову    </div>
				</a><a href="/news/41106" class="small-list-item">
					<div class="time">
						15.04.2016 о 22:45    </div>
					<div class="title">
						Будівлю Росспівробітництва закидали фаєрами та димовими шашками    </div>
				</a><a href="/news/40812" class="small-list-item">
					<div class="time">
						12.04.2016 о 15:00    </div>
					<div class="title">
						Нардепи внесли до ВР закон про офшори    </div>
				</a><a href="/news/40779" class="small-list-item">
					<div class="time">
						12.04.2016 о 09:38    </div>
					<div class="title">
						Машину зниклого Тараса Познякова знайшли під Києвом, - ЗМІ    </div>
				</a><a href="/news/40498" class="small-list-item">
					<div class="time">
						08.04.2016 о 15:54    </div>
					<div class="title">
						Пошуки Тараса Познякова - на даний час відпрацьовується ще одна точка    </div>
				</a><a href="/news/40410" class="small-list-item">
					<div class="time">
						07.04.2016 о 17:22    </div>
					<div class="title">
						Батьки Тараса Познякова готові дати винагороду за інформацію про сина    </div>
				</a><a href="/news/40248" class="small-list-item">
					<div class="time">
						05.04.2016 о 18:20    </div>
					<div class="title">
						Банк «Хрещатик» луснув    </div>
				</a><a href="/news/40245" class="small-list-item">
					<div class="time">
						05.04.2016 о 16:41    </div>
					<div class="title">
						Під банком «Хрещатик» сотні киян вимагають повернути їм кошти    </div>
				</a><a href="/news/40136" class="small-list-item">
					<div class="time">
						04.04.2016 о 14:42    </div>
					<div class="title">
						Пєсков прокоментував інформацію про пов'язані з Путіним офшори    </div>
				</a><a href="/news/40117" class="small-list-item">
					<div class="time">
						04.04.2016 о 12:08    </div>
					<div class="title">
						Інформацію про офшори Mossack Fonseca «злила» екс-коханка партнера компанії...    </div>
				</a><a href="/news/39706" class="small-list-item">
					<div class="time">
						29.03.2016 о 10:47    </div>
					<div class="title">
						Встановлено особистість зловмисника, який захопив єгипетський...    </div>
				</a><a href="/news/39703" class="small-list-item">
					<div class="time">
						29.03.2016 о 10:14    </div>
					<div class="title">
						В мережі з'явилися перші фото захопленого єгипетського літака    </div>
				</a><a href="/news/39701" class="small-list-item">
					<div class="time">
						29.03.2016 о 10:07    </div>
					<div class="title">
						Шокін звільнив Сакварелідзе перед голосуванням у ВР    </div>
				</a>    </div>
		</div>


        <!--ADoffer add ------------------------------------------------------------------------------------------>
        <ins style="display:inline-block;width:300px;height:250px">

            <div class="adware-main-middle-bottom flexrow">
                <p class="ad-places-text">
                    Ширина: 300px <br>
                    Висота: 250px
                </p>
            </div>


        </ins>
        <!--ADoffer add ------------------------------------------------------------------------------------------>



		<?include Config::getRoot()."/views/block/twitter.view.php";?>
	</div>
	<div class="clearfix"></div>
</div>

<div class="flexrow ad-indent">
    <div class="adware-main-middle-top flexrow">
        <p class="ad-places-text">
            Ширина: 728px <br>
            Висота: 90px
        </p>
    </div>
    <div class="empty"></div>
</div>

<? include Config::getRoot()."/views/block/social.block.view.php"; ?>

<? include Config::getRoot()."/views/block/newsSeoBlock.view.php"; ?>