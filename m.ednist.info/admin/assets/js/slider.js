$(document).ready(function () {
    var currentPosition = 0;
    var slideWidth = 426;
    var numOfItems = 6;
    var slides = $('#items li');
    var numberOfSlides = Math.ceil(slides.length / numOfItems) + 1;

    // Remove scrollbar in JS
    $('#slidesContainer').css('overflow', 'hidden');
    // Wrap all .slides with #slideInner div
    newwith = (slideWidth) * ( numberOfSlides ) + 150;
//alert();
    slides.css({
        'width': 205
    }).parent()
        .wrapAll('<div id="slideInner"></div>')
        // Float left to display horizontally, readjust .slides width
        .css({
            'float': 'left',
            'width': newwith
        });

    // Set #slideInner width equal to total width of all slides
//    $('#slideInner').css('width', slideWidth * numberOfSlides);

    // Insert controls in the DOM
//    $('#slideshow')
//        .prepend('<span class="control" id="leftControl">Clicking moves left</span>')
//        .append('<span class="control" id="rightControl">Clicking moves right</span>');

    // Hide left arrow control on first load
    manageControls(currentPosition);

    // Create event listeners for .controls clicks
    $('.control')
        .bind('click', function () {
            // Determine new position
            currentPosition = ($(this).attr('id') == 'rightControl') ? currentPosition + 1 : currentPosition - 1;

            // Hide / show controls
            manageControls(currentPosition);
            // Move slideInner using margin-left
            $('#slideInner').animate({
                'marginLeft': slideWidth * (-currentPosition)
            });
        });

    // manageControls: Hides and Shows controls depending on currentPosition
    function manageControls(position) {
        // Hide left arrow if position is first slide
        if (position == 0) {
            $('#leftControl > div').css('margin-top', '-100px');//hide();
            //$('#leftControl2').show();
        } else {
            $('#leftControl > div').css('margin-top', 0);//.show();
            //$('#leftControl2').hide()
        }
        // Hide right arrow if position is last slide
        if (position == numberOfSlides - 1) {
            $('#rightControl > div').css('margin-top', '-100px');//.hide();
            //$('#rightControl2').show(); 
        } else {
            $('#rightControl > div').css('margin-top', 0);//.show();
            //$('#rightControl2').hide(); 
        }
    }

    var elem45 = $("#countSlideLinks");
    setCountSlideLinks();
    $('#items li').bind('click', function () {
        setCountSlideLinks();
    })
    function setCountSlideLinks() {
        numbAll = $('#items input[type="checkbox"]:checked').length;
        elem45.text(numbAll + '/' + slides.length);
    }

});