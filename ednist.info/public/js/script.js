/* NavigationMenu */
// navMenu.onclick = function myFunction() {

//   var x = document.getElementById('myTopnav');
//   if (x.className === 'topnav'){
//     x.className += 'responsive';
//   }
//   else{
//     x.className = 'topnav';
//   }
// }

// var image = document.getElementById('myImage');

//   image.addEventListener('click', function(){

//    if (image.getAttribute('src') == "/images/krest.png"){

//       image.src = "/images/kub.png"}

//    else{

//       image.src = "/images/krest.png"}


// });


/* AboutUsForm */

function openbox() {
  display = document.getElementById('box').style.display;
  document.getElementById('box').style.display = 'none';
  if (display == 'none') {
    document.getElementById('box').style.display = 'block';
  }
}

/* SLIDER SWIPER*/

var swiper = new Swiper('.swiper-container', {
      pagination: {
        el: '.swiper-pagination',
        clickable: true,
      },
    });


const relocateToSearchPage = (e) => {
  e.preventDefault();
  var searchStr = $('#searchField').val().trim();
  if(searchStr && searchStr.length > 0 && typeof searchStr === 'string'){
    window.location.href = '/search/' + searchStr;
  }
};


// SEARCH
$('#searchBtn').click(e => $('#searchField').toggleClass('show'));
  
$('#searchBtn').bind('click', function() {
      var src = ($(this).attr('src') === '/images/search.png')
                    ? '/images/cross.png' 
                    : '/images/search.png';
      $(this).attr('src', src);
});

//FORM
$('#writeUs').click(e => $('#formCont').toggleClass('showForm'));




$('#searchField').keypress(e => { if (e.keyCode == 13) relocateToSearchPage(e) });
$('#searchBtn').click(e => relocateToSearchPage(e));

