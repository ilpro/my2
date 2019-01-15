<!--<style>
.merkieva {
	display: block;
	width: 580px;
	margin:0 auto;
}
.merkieva img {
	width: 580px;
}
.videoA {
	margin:0 auto;
	width: 580px;
	position: relative;
}
.videoA-controls {
	position: absolute;
	max-width: 100%;
	height: 40px;
	bottom: 0;
	left: 0;
	right: 0;
	max-height: 100%;
}
.videoA-controls .mute, .videoA-controls .unmute {
	position: absolute;
	display: inline-block;
	left: 10px;
	width: 32px;
	height: 32px;
	border: 0 none;
	cursor: pointer;
	background: url(http://ednist.info/media/video/1424911_1.png) no-repeat;
}
.videoA-controls .mute {
	background-position: -8px -7px;
}
.videoA-controls .unmute {
	background-position: -60px -8px;
}
.videoA-controls .close {
	position: absolute;
	display: inline-block;
	right: 10px;
	color: white;
	font-weight: bold;
	text-shadow: 0 1px 0 #fff;
	filter: alpha(opacity=20);
	opacity: 0.2;
	font-size: 16px;
	-webkit-appearance: none;
	cursor: pointer;
	background: none repeat scroll 0 0;
	border: 0 none;
}
</style>
<script src="http://ednist.info/media/video/jquery.viewportchecker.js"></script>
<script>
	$(document).ready(function(){
		var fl123 = false;
		var obj = document.getElementById("myVideo");
		obj.onended = function() {
			$(".videoA").slideUp(function(){$(".merkieva").show()});
		};
		$("#videoA-mute").click(function(){
			if($(this).hasClass("unmute")) {
				obj.volume = 0.8;
				$(this).removeClass("unmute").addClass("mute");
			}
			else {
				obj.volume = 0;
				$(this).removeClass("mute").addClass("unmute");
			}
		});
		$("#videoA-close").click(function(){
			obj.pause();
			$(".videoA").slideUp(function(){$(".merkieva").show()});
		});
		$(document).scroll(function() {
			if(!fl123) {
				$('.videoA').unbind();
				$('.videoA').viewportChecker({
					callbackFunction: function(elem, action){
						obj.play();
						$.get("/media/video/counter.php");
						fl123 = true;
					}, 
					offset: 200
				});
			}
		});
	});
</script>
<a href="https://www.facebook.com/merkieva" target="_blank" class="merkieva" style="display: none;"><img src="http://ednist.info/media/video/1475896_4.jpg" alt="" title=""></a>
<div class="videoA statistik">
	<a href="https://www.facebook.com/merkieva" target="_blank">
		<video width="580" height="350" poster="http://ednist.info/media/video/KlichkoV.png" id="myVideo">
			<source src="http://ednist.info/media/video/INTERNET_580_350.mp4" type="video/mp4">
			<source src="http://ednist.info/media/video/INTERNET_580_350.ogg" type="video/ogg">
			Your browser does not support the video tag or the file format of this video.
		</video>
	</a>
	<div class="videoA-controls">
		<button class="mute" id="videoA-mute"></button>
		<button class="close" id="videoA-close">Закрыть</button>
	</div>
</div>-->