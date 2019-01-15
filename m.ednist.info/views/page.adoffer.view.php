<style>
    .ad1 .menu-item .ad-text {
        color: white;
    }
</style>

<div id="main_page" class="content">
	<div class="left-content pull-left">
		<div class="slider">
			<div id="myCarousel" class="carousel slide" data-ride="carousel" interval="0">
				<ol class="carousel-indicators">
					<li data-target="#myCarousel" data-slide-to="0" class="active"></li>
					<li data-target="#myCarousel" data-slide-to="1"></li>
					<li data-target="#myCarousel" data-slide-to="2"></li>
					<li data-target="#myCarousel" data-slide-to="3"></li>
				</ol>
				<div class="carousel-inner">
					<?
					if (!empty($page_data['main_news']))
						include Config::getRoot()."/views/sprite/main.view.php";
					?>
				</div>
			</div>
		</div>
		<div class="pull-left newsline">
			<a href="/newsline" class="head" title="Стрічка новин">
				Стрічка новин
			</a>
			<div class="news-list main-newsline" id="append" data-controller="main">
				<?
				include Config::getRoot()."/views/block/newsBlock.view.php";
				?>
			</div>
			<a href="javascript:void(0)" title="Більше новин" class="more-main">Більше новин</a>
		</div>
		<div class="clearfix"></div>
	</div>
	<div class="right-content pull-right">
		<div id="popular-sidebar" class="pull-right popular">
			<a title="Популярне за добу" href="/popular" class="head">
				<i class="ico ico-popular"></i>
				Популярне за добу
			</a>
			<div class="news-list">
				<?
				$i = 0;
				if (!empty($page_data['popular_news']))
					foreach ($page_data['popular_news'] as $item)
						include Config::getRoot()."/views/sprite/popular.main.view.php";
				?>
			</div>
		</div>



		<!--ADoffer add ------------------------------------------------------------------------------------------>
		<?include Config::getRoot()."/views/banners/300x250.adoffer.view.php";?>
		<!--ADoffer add ------------------------------------------------------------------------------------------>



		<div class="pull-right last-media" id="ms-widget">
			<?include Config::getRoot()."/views/block/mediastealerWidget.view.php"; ?>
		</div>

		<?include Config::getRoot()."/views/block/twitter.view.php";?>

	</div>
	<div class="clearfix"></div>
</div>
<div class="tags">
	<?
	$mainTag = null;
	if(is_array($static_data['header_tags']))
		foreach($static_data['header_tags'] as $tag){
			if( $tag['tagName']!='' )
				include Config::getRoot()."/views/sprite/tag.view.php";
			if($id == $tag['tagId']){
				$mainTag=$tag;
			}
		}
	?>
	<div class="clearfix"></div>
</div>

<? include Config::getRoot()."/views/block/exclusive.block.view.php"; ?>


<!--ADoffer add ------------------------------------------------------------------------------------------>
<? include Config::getRoot()."/views/block/banner.adoffer.view.php"; ?>
<!--ADoffer add ------------------------------------------------------------------------------------------>


<? include Config::getRoot()."/views/block/interview.block.view.php"; ?>

<? include Config::getRoot()."/views/block/dossier.block.view.php"; ?>

<? include Config::getRoot()."/views/block/resonans.block.view.php"; ?>

<? //include Config::getRoot()."/views/block/blogs.block.view.php"; ?>

<? include Config::getRoot()."/views/block/social.block.view.php"; ?>
