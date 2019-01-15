

function success_tip(text) {
    $('#condition').mkCondition({
        condition: 'tooltip',
        text: text,
        action: 'fade',
        color: 'green',
        fadeout: 2000
    });
}
function error_tip(text) {
    $('#condition').mkCondition({
        condition: 'tooltip',
        text: text,
        action: 'show',
        color: 'red'
    });
}


$(function () {
    $(document).ajaxStart(function () {
        $('#condition').mkCondition({
            action: 'blink',
            color: 'yellow'
        });
    });

});