var profileId = false;
var search = (getCookie("search")) ? JSON.parse(getCookie("search")) : {
  gender: [1, 2],
  place: [1, 2],
  ageFrom: 18,
  ageTo: 50,
  offset: 0
};
search.hash = userHash;

var myRe = /^#\/preview\/([0-9]+)$/g;
var matches = myRe.exec(window.location.hash);
if (matches != null) {
  profileId = matches[1];
  dataSocket.emit('getPreviewUser', JSON.stringify({profileId: profileId, hash: userHash}));
}
else
  dataSocket.emit('searchUser', JSON.stringify(search));

/** Popular places **/
dataSocket.emit('getPopularPlaces', JSON.stringify({hash: userHash}));

dataSocket.on('popularPlaces', function (places) {
  var popularPlaces = JSON.parse(places);
  var placesHtml = '';

  for (var i = 0; i < popularPlaces.length; i++) {
    placesHtml +=
      '<div class="tag-place" style="text-transform: capitalize">' + popularPlaces[i].place + '</div>';
  }

  $('.popular-places').html(placesHtml).show();
});

/*** Places user wants to visit ***/
dataSocket.emit('getPlacesToVisit', JSON.stringify({hash: userHash}));

dataSocket.on('userPlacesToVisit', function (places) {
  var places = JSON.parse(places);
  var placesHtml = '';

  for (var i = 0; i < places.length; i++) {
    placesHtml += '<div class="tag-place" style="text-transform: capitalize" data-id="' + places[i].id + '">' + places[i].place + '\
  <svg class="close" xmlns="http://www.w3.org/2000/svg" width="20.354" height="20.354" viewBox="0 0 20.354 20.354">\
    <title>close</title>\
    <line x1="0.177" y1="0.177" x2="20.177" y2="20.177" fill="none" stroke="#4d4d4d" stroke-miterlimit="10" stroke-width="1.5"></line>\
    <line x1="19.72" y1="0.634" x2="0.634" y2="19.72" fill="none" stroke="#4d4d4d" stroke-miterlimit="10" stroke-width="1.5"></line>\
  </svg>\
</div>';
  }

  $('#user-places').html(placesHtml).show();
});


$(".tag-place-container").on("tap", ".tag-place .close", function () {
  var id = $(this).parent().attr('data-id');
  dataSocket.emit('removePlaceToVisit', JSON.stringify({hash: userHash, recordId: id}));
  $(this).parent().remove();
});

/*** Swiper init */
var swiper = $('.swiper-container').swiper({
  nextButton: '.swiper-button-next',
  prevButton: '.swiper-button-prev',
  pagination: false,
  paginationClickable: false,
  preloadImages: false,
  lazyLoading: true,
  loop: true
});
$(window).on("orientationchange", function () {
  swiper.update();
});
/*** Swiper init  end*/

/*** Slider init */
var $from = $(".from-age");
var $to = $(".to-age");
var $ageSlider = $('#ageSlider');

$ageSlider.slider({
  range: true,
  min: 18,
  max: 50,
  values: [search.ageFrom, search.ageTo],
  slide: function (event, ui) {
    $from.val(ui.values[0]);
    $to.val(ui.values[1]);
  }
});


// set input value
$from.val($ageSlider.slider("values", 0));
$to.val($ageSlider.slider("values", 1));

//change from field input
$from.change(function () {
  var val1 = parseInt($from.val());
  var val2 = parseInt($to.val());
  val1 = val1 < val2 ? val1 : val2;
  $ageSlider.slider("values", 0, val1);
});
$to.change(function () {
  var val1 = parseInt($from.val());
  var val2 = parseInt($to.val());
  val2 = val2 > val1 ? val2 : val1;
  $ageSlider.slider("values", 1, val2);
});
/*** Slider init END*/

/*** Gender & want-lives ***/
$(".form-body .gender-container").click(function () {
  $(this).toggleClass("active");
});

for (var i = 0; i < search.gender.length; i++)
  $(".form-body .true-select-gender .gender-container[data-val=" + search.gender[i] + "]").addClass("active");
for (var i = 0; i < search.place.length; i++)
  $(".form-body .want-lives .gender-container[data-val=" + search.place[i] + "]").addClass("active");
/*** Gender & want-lives end ***/

$(".search-button").on("click", function () {
  var gender = [];
  $(".true-select-gender .gender-container.active").each(function () {
    gender.push($(this).data("val"));
  });

  var place = [];
  $(".want-lives .gender-container.active").each(function () {
    place.push($(this).data("val"));
  });

  search = {
    gender: gender,
    place: place,
    ageFrom: parseInt($from.val()),
    ageTo: parseInt($to.val()),
    offset: 0,
    hash: userHash
  }

  dataSocket.emit('searchUser', JSON.stringify(search));
});

dataSocket.on('searchUser', function (data) {
  data = JSON.parse(data);

  if (data.user) {
    profileId = data.user.userId;

    window.location.hash = "/preview/" + profileId;

    document.title = data.user.userNickname + ", " + data.user.userAge + ", " + data.user.userCity;

    //Информация в шапке
    $(".header-profile-info .user-avatar img").attr("src", data.user.userPhoto);
    $(".header-profile-info .recipient-id").text(data.user.userId).show();
    $(".header-profile-info .nickname").text(data.user.userNickname);
    $(".header-profile-info .user-city").text(data.user.userCity + ", " + data.user.userCountry);
    $('#message-link').attr('href', 'chat#id=' + data.user.userId);

    //картинки вместе со слайдами
    swiper.removeAllSlides();
    swiper.appendSlide('<div class="swiper-slide">\
			<a href="/profile/' + profileId + '" class="sizebox check-auth"></a>\
			<img data-src="' + data.user.userPhoto + '" class="swiper-lazy pick-image first">\
			<div class="swiper-lazy-preloader swiper-lazy-preloader-white"></div>\
		</div>');
    for (var i = 0; i < data.images.length; i++) {
      swiper.appendSlide('<div class="swiper-slide">\
				<a href="/profile/' + profileId + '" class="sizebox check-auth"></a>\
				<img data-src="' + data.images[i].path + '" class="swiper-lazy pick-image">\
				<div class="swiper-lazy-preloader swiper-lazy-preloader-white"></div>\
			</div>');
    }
    swiper.appendSlide('<div class="swiper-slide">\
			<a href="/profile/' + profileId + '" class="sizebox check-auth"></a>\
			<div class="endslide-box">\
				<svg xmlns="http://www.w3.org/2000/svg" width="50" height="44" viewBox="0 0 50 44">\
				<g fill="#FFF">\
					<path d="M49.63 13.8L21.3.08c-.33-.16-.73-.02-.9.3l-6.15 13.1h3.36l4.53-9.62L46.7\
					15.77 39.02 32.1 34.7 30v8.63l2.65 1.3c.33.15.73 0 .9-.33l11.7-24.9c.15-.33 0-.73-.32-.9zm0 0"/>\
					<path d="M32.06 15.07H.66c-.36 0-.66.3-.66.66v27.6c0 .37.3.67.67.67h31.4c.36 0\
					.65-.3.65-.67v-27.6c0-.36-.3-.66-.66-.66zm-1.8 21.14h-2c-1.16-3-2.6-7.23-4.75-6.67-2.53.66-3.8\
					6.68-3.8 6.68s-1.3-6.78-4.88-10.42C11.24 22.14 7.77 36.2 7.77 36.2H3.03V18.13h27.22v18.1zm0 0"/>\
					<path d="M9.64 22c0 1.25-1 2.26-2.22 2.26-1.22 0-2.22-1-2.22-2.26 0-1.25 1-2.26 2.22-2.26\
					1.23 0 2.22 1 2.22 2.26zm0 0M22.32 23.6c.5 0 .97-.06 1.38-.15.5.2 1.08.34 1.72.34\
					1.63 0 2.96-.82 2.96-1.82s-1.33-1.82-2.96-1.82c-.62 0-1.2.12-1.66.32-.05\
					0-.43-.28-.6-.3-.37-.05-.8-.06-1.13.14-.28.18-.45.48-.5.8-1.32.15-2.3.64-2.3 1.2 0\
					.7 1.38 1.27 3.1 1.27zm0 0"/>\
				</g>\
				</svg>\
			<div class="endslide-button check-auth">' + lang.lDatingMorePhotos + '</div>\
			</div> \
		</div>');
    swiper.slideTo(1);

    if (data.user.userStatus) {
      $(".page-single-pick .quote span").text(data.user.userStatus).show();
      $(".page-single-pick .quote").show();
    }
    else
      $(".page-single-pick .quote").hide();

    var html = "";
    if (data.user.userWantToVisit) {
      data.user.userWantToVisit.split(', ').forEach(function (el) {
        html += '<div class="tag-place" style="text-transform: capitalize">' + el.split(':')[1] + '</div>'
      });
      $(".tag-place-container.slider").html(html);
    }
    else
      $(".tag-place-container.slider").html("");

    $(".menu .profile").attr('href', "/profile/" + data.user.userId);
    $("#message-link").attr('href', "/chat#" + data.user.userId);

    if (data.user.favorite)
      $(".menu-item.favorite").addClass("active");
    else
      $(".menu-item.favorite").removeClass("active");

    search.offset = data.offset;
    setCookie("search", JSON.stringify(search), {"path": "/", "expires": 31536000});
  }
  else
    alert("Nothing found :(");
});

// следуйщий профиль
$(".page-single-pick .next-user").on("click", function () {
  search.offset += 1;
  dataSocket.emit('searchUser', JSON.stringify(search));
});
// предидущий профиль
$(".page-single-pick .previous-user").on("click", function () {
  search.offset -= 1;
  dataSocket.emit('searchUser', JSON.stringify(search));
});

// добавить в фавориты
$(".menu-item.favorite").on("click", function () {
  if (userHash && profileId)
    dataSocket.emit('addUserFavorite', JSON.stringify({hash: userHash, profileId: profileId}));
});
dataSocket.on('addUserFavorite', function (data) {
  data = JSON.parse(data);

  if (data.action == "add")
    $(".menu-item.favorite").addClass("active");
  else
    $(".menu-item.favorite").removeClass("active");
});