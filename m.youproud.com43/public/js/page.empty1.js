console.log('empty page 1')

$('.gender-container').click(function(){
    $('.gender-container').removeClass('active');
    $(this).addClass('active');
});

/*** Slider init START */
var $from = $(".from-age");
var $to = $(".to-age");
var $ageSlider = $('#ageSlider');

$ageSlider.slider({
    range: true,
    min: 18,
    max: 50,
    values: [ $from.val(), $to.val() ],
    slide: function( event, ui ) {
        $from.val(ui.values[ 0 ]);
        $to.val(ui.values[ 1 ]);
    }, 
	stop: function( event, ui ) {
		updateSearchSettings();
	}
});

// change from field input
$from.change(function(){
    var val1 = parseInt($from.val());
    var val2 = parseInt($to.val());
    val1 = val1 < val2 ? val1 : val2;
    $ageSlider.slider( "values", 0, val1 );
	updateSearchSettings();
});
$to.change(function(){
    var val1 = parseInt($from.val());
    var val2 = parseInt($to.val());
    val2 = val2 > val1 ? val2 : val1;
    $ageSlider.slider( "values", 1, val2 );
	updateSearchSettings();
});
/*** Slider init END */