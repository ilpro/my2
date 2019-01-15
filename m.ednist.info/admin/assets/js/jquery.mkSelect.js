function hideMkSelects() {
    $('.mkSelect').each(function () {
        if ($(this).hasClass('expanded')) {
            var dropDown = $(this).children('.dropDown');
            dropDown.trigger('toggle');
        }
    });
}
function mkSelectInitialize() {
    $('.dropDown').each(function () {
        var mkSelect = $(this).closest('.mkSelect');
        $(this).unbind().bind('show', function () {
            var mySelectNumber = mkSelect.attr('number');
            if ($(this).is(':animated')) {
                return false;
            }
            mkSelect.addClass('expanded');
            $(this).slideToggle(100, function () {
                $('li', $(this)).unbind('hover').bind('hover', function () {
                    setSelected($(this), $(this).closest('.mkSelect'));
                });
                if (mkSelect.children('a').attr('value') != '') {
                    $("li[value= " + mkSelect.children('a').attr('value') + "]", mkSelect).addClass("itemhover");
                }
            });
        }).bind('hide', function () {
            if ($(this).is(':animated')) {
                return false;
            }
            $('li', $(this)).unbind('hover');
            mkSelect.removeClass('expanded');
            $(this).slideToggle(100, function () {
                $(this).children().removeClass("itemhover");
            });
        }).bind('toggle', function () {
            if (mkSelect.hasClass('expanded')) {
                $(this).trigger('hide');
            }
            else {
                $(this).trigger('show')
            }
        });

    });
    $('a', '.mkSelect').each(function () {
        $(this).unbind().bind('click', function () {
            hideMkSelects();
            $(this).blur();
            var dropDown = $(this).closest('.mkSelect').children('.dropDown');
            dropDown.trigger('toggle');
            return false;
        });
    });
    $('li', '.dropDown').each(function () {
        $(this).unbind().bind('click', function (e) {
            var mkSelect = $(this).closest('.mkSelect');
            var mySelectNumber = mkSelect.attr('number');
            $('a', mkSelect).focus();
            $('a', mkSelect).text($(this).text());
            $('a', mkSelect).attr('value', $(this).attr('value'));
            if ($('a', mkSelect).hasClass('importantTitle')) {
                //mkTitleHide(mkSelect.next());
            }
            $(this).parent().trigger('hide');
            if (!mkSelect.hasClass('checked')) {
                mkSelect.toggleClass('checked');
            }
        }).bind('hover', function () {
            setSelected($(this), $(this).closest('.mkSelect'));
        });
    });
    $(window).click(function () {
        hideMkSelects();
    });
    $(document).bind('keydown', function (e) {
        if (e.keyCode == 38 || e.which == 38 || e.keyCode == 40 || e.which == 40 || e.keyCode == 13 || e.which == 13 || e.keyCode == 27 || e.which == 27) {
            if ($('.mkSelect').hasClass('expanded')) {
                var mkSelect = $('.mkSelect.expanded');
                var mySelectNumber = mkSelect.attr('number');
                if (e.which == 38) {//up
                    navigate('up', mkSelect);
                    ;
                }
                else if (e.which == 40) {//down
                    navigate('down', mkSelect);
                }
                else if (e.which == 13) {//enter
                    if ($('.itemhover', mkSelect).size() != 0) {
                        $('.itemhover', mkSelect).click();
                    }
                }
                else if (e.which == 27) {//esc
                    hideMkSelects();
                    $('a', mkSelect).focus();
                }
                return false;
            }
        }

    });
    for (var i = 0; i < $(".mkSelect").size(); i++) {
        $(".mkSelect").eq(i).data("number", i);
        $(".mkSelect").eq(i).attr("number", i);
    }
    for (var i = 0; i < $(".mkSelect ul li").size(); i++) {
        $(".mkSelect ul li").eq(i).data("number", i);
        $(".mkSelect ul li").eq(i).attr("number", i);
    }

}
function navigate(direction, mkSelect) {
    var maxValue = 0;
    var minValue = 9999;
    $('ul li', mkSelect).each(function () {
        maxValue = Math.max(maxValue, parseInt($(this).attr("number")));
        minValue = Math.min(minValue, parseInt($(this).attr("number")));
    });
    var mySelectNumber = mkSelect.attr('number');
    var num = $('.itemhover', mkSelect).attr('number');
    if (num == undefined) {
        num = minValue - 1;
    }
    if (direction == 'up') {
        if (num != minValue - 1 && num != minValue) {
            num--;
            var myLi = $("li[number= " + num + "]");
            setSelected(myLi, mkSelect);
            return;
        }
        else {
            num = maxValue;
            var myLi = $("li[number= " + num + "]");
            setSelected(myLi, mkSelect);
            return;
        }
    } else if (direction == 'down') {
        if (num != maxValue) {
            num++;
            var myLi = $("li[number= " + num + "]");
            setSelected(myLi, mkSelect);
            return;
        }
        else {
            num = minValue;
            var myLi = $("li[number= " + num + "]");
            setSelected(myLi, mkSelect);
        }
    }

}
function setSelected(myLi, mkSelect) {
    var mySelectNumber = mkSelect.attr('number');
    $("ul li", mkSelect).removeClass("itemhover");
    myLi.addClass("itemhover");
}