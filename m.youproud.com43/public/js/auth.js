'use strict';

/** Global user variables **/
var userHash = getCookie("hash");
var userInfo = false; // there will be info with name, lastname, nickname and photo

/** Get user info **/
if(userHash)
  dataSocket.emit('getUserAuthInfo', JSON.stringify({"hash":userHash}));

/** Receiving user info **/
dataSocket.on('getUserAuthInfo', function (data) {
	data = JSON.parse(data);
	
	if(data.success) {
		userInfo = data;
		
		// $("#header__user-info .userPhoto").attr("src", data.userPhoto);
		// $("#header__user-info .userName").text("@" + data.userName);
	}
	else {
		deleteCookie("hash");
		window.location.href = "/login";
	}
});

var search = (getCookie("search")) ? JSON.parse(getCookie("search")) : {};
if(search.residence && search.gender && search.ageFrom && search.ageTo) {
	$(".side-menu-item.settings-item").css("display", "");
}


/* Login */
$(".form-body .login-button").click(function(e){
	e.preventDefault();
	dataSocket.emit('userLogin', JSON.stringify({
		"email"	: $(".form-body.login .email-input").val(),
		"pass"	: $(".form-body.login .password-input").val(),
	}));
});

dataSocket.on('userLogin', function (data) {
	data = JSON.parse(data);
	
	if (data.success) {
		userHash = data.hash;
		setCookie("hash", userHash, {"path": "/", "expires": 31536000});
		var prevPage = getCookie("requestedPage");
		document.location.href = (prevPage) ? prevPage : "/profile";
	}
	else
		alert(data.error);
});