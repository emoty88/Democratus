/*!
 * ScrewDefaultButtons! v1.1.2
 * http://screwdefaultbuttons.com/
 *
 * Copyright 2011, Matt Solano
 *
 * Date: Thu June 22 2011
     
(function($){$.fn.screwDefaultButtons=function(options){options=$.extend($.fn.screwDefaultButtons.defaults,options);var checkedImage=options.checked;var uncheckedImage=options.unchecked;var selectAllBtn=options.selectAll;var width=options.width;var height=options.height;var checkPath=checkedImage.slice(4,-1);var uncheckPath=uncheckedImage.slice(4,-1);$('body').append('<img class="preloadCheck" src="'+checkPath+'" width="0" height="0" />');$('body').append('<img class="preloadUnCheck" src="'+uncheckPath+'" width="0" height="0"  />');$('.preloadCheck').fadeOut(0);$('.preloadUnCheck').fadeOut(0);if($(this).is(":radio")){var radioButton=$(this);$(radioButton).wrap('<div class="styledRadio" ></div>').hide();$('.styledRadio').css({backgroundImage:uncheckedImage,width:width,height:height});$(radioButton).filter(':checked').parent().addClass('checked').css({"background-image":checkedImage});$('.styledRadio').click(function(){thisCheckName=$(this).find("input:radio").attr("name");if(!($(this).hasClass('checked'))){$('.selected').removeClass('selected')
$(this).addClass('checked').addClass('selected').css({backgroundImage:checkedImage}).find('input:radio').attr('checked','checked');$('.styledRadio').each(function(){otherCheckName=$(this).find("input:radio").attr("name")
if(otherCheckName==thisCheckName){if(!($(this).hasClass('selected'))){$(this).removeClass('checked').css({backgroundImage:uncheckedImage});}}});}});$('label').click(function(){var labelFor=$(this).attr('for');var radioForMatch=$('input:radio').filter('#'+labelFor);var thisCheckName=radioForMatch.attr("name");if(!(radioForMatch.parent().hasClass("checked"))){$('.selected').removeClass('selected');radioForMatch.attr('checked','checked');radioForMatch.parent().addClass('checked').addClass('selected').css({backgroundImage:checkedImage});$('.styledRadio').each(function(){otherCheckName=$(this).find("input:radio").attr("name")
if(otherCheckName==thisCheckName){if(!($(this).hasClass('selected'))){$(this).removeClass('checked').css({backgroundImage:uncheckedImage});}}});}});}
else if($(this).is(":checkbox")){var checkbox=$(this);$(checkbox).wrap('<div class="styledCheckbox" ></div').hide();$('.styledCheckbox').css({backgroundImage:uncheckedImage,width:width,height:height});$(checkbox).filter(':checked').parent().addClass('checked').css({"background-image":checkedImage});$('.styledCheckbox').click(function(){if(!($(this).hasClass('checked'))){$(this).addClass('checked').css({"background-image":checkedImage}).find('input:checkbox').attr('checked','checked');}
else{$(this).removeClass('checked').css({"background-image":uncheckedImage}).find('input:checkbox').removeAttr('checked','checked');$(selectAllBtn).removeAttr('checked','checked').parent('.styledCheckbox').removeClass('checked').css({"background-image":uncheckedImage});}
if(selectAllBtn!=null){if($(this).find('input:checkbox').is(selectAllBtn)){if($(this).hasClass('checked')){$(checkbox).each(function(){$(this).attr('checked','checked').parent('.styledCheckbox').addClass('checked').css({"background-image":checkedImage});});}
else{$(checkbox).each(function(){$(this).removeAttr('checked','checked').parent('.styledCheckbox').removeClass('checked').css({"background-image":uncheckedImage});});}}}});$('label').click(function(){var labelFor=$(this).attr('for');var radioForMatch=$('input:checkbox').filter('#'+labelFor);if(!(radioForMatch.parent().hasClass("checked"))){if($.browser.msie){if($.browser.version==7.0||$.browser.version==8.0){radioForMatch.attr('checked','checked').parent('.styledCheckbox').addClass('checked').css({"background-image":checkedImage});if(radioForMatch.is(selectAllBtn)){$(checkbox).each(function(){$(this).attr('checked','checked').parent('.styledCheckbox').addClass('checked').css({"background-image":checkedImage});});}}}}
else if(radioForMatch.parent().hasClass("checked")){if($.browser.msie){if($.browser.version==7.0||$.browser.version==8.0){radioForMatch.removeAttr('checked','checked').parent('.styledCheckbox').removeClass('checked').css({"background-image":uncheckedImage});$(selectAllBtn).removeAttr('checked','checked').parent('.styledCheckbox').removeClass('checked').css({"background-image":uncheckedImage});}}}});}
$('.styledRadio').css({'cursor':'pointer',"background-repeat":"no-repeat"});$('.styledCheckbox').css({'cursor':'pointer',"background-repeat":"no-repeat"});}
$.fn.screwDefaultButtons.defaults={checked:"url(images/radio_Checked.jpg)",unchecked:"url(images/radio_Unchecked.jpg)",selectAll:null,width:20,height:20};})(jQuery);
*/

/*!
 * ScrewDefaultButtons! v1.1.2
 * http://screwdefaultbuttons.com/
 *
 * Copyright 2011, Matt Solano
 *
 * Date: Thu June 22 2011
 */
(function ($) {
    $.fn.screwDefaultButtons = function (options) {
        options = $.extend($.fn.screwDefaultButtons.defaults, options);
        var checkedImage = options.checked;
        var uncheckedImage = options.unchecked;
        var selectAllBtn = options.selectAll;
        var width = options.width;
        var height = options.height;
        var checkPath = checkedImage.slice(4, -1);
        var uncheckPath = uncheckedImage.slice(4, -1);
        var onchange = options.onchange;
        $('body').append('<img class="preloadCheck" src="' + checkPath + '" width="0" height="0" />');
        $('body').append('<img class="preloadUnCheck" src="' + uncheckPath + '" width="0" height="0"  />');
        $('.preloadCheck').fadeOut(0);
        $('.preloadUnCheck').fadeOut(0);
        if ($(this).is(":radio")) {
            var radioButton = $(this);
            $(radioButton).wrap('<div class="styledRadio" ></div>').hide();
            $('.styledRadio').css({
                backgroundImage: uncheckedImage,
                width: width,
                height: height
            });
            $(radioButton).filter(':checked').parent().addClass('checked').css({
                "background-image": checkedImage
            });
            $('.styledRadio').click(function () {
                if(onchange!=null) eval(onchange);
                thisCheckName = $(this).find("input:radio").attr("name");
                if (!($(this).hasClass('checked'))) {
                    $('.selected').removeClass('selected')
                    $(this).addClass('checked').addClass('selected').css({
                        backgroundImage: checkedImage
                    }).find('input:radio').attr('checked', 'checked');
                    $('.styledRadio').each(function () {
                        otherCheckName = $(this).find("input:radio").attr("name")
                        if (otherCheckName == thisCheckName) {
                            if (!($(this).hasClass('selected'))) {
                                $(this).removeClass('checked').css({
                                    backgroundImage: uncheckedImage
                                });
                            }
                        }
                    });
                }
            });
            $('label').click(function () {
                var labelFor = $(this).attr('for');
                var radioForMatch = $('input:radio').filter('#' + labelFor);
                var thisCheckName = radioForMatch.attr("name");
                if (!(radioForMatch.parent().hasClass("checked"))) {
                    $('.selected').removeClass('selected');
                    radioForMatch.attr('checked', 'checked');
                    radioForMatch.parent().addClass('checked').addClass('selected').css({
                        backgroundImage: checkedImage
                    });
                    $('.styledRadio').each(function () {
                        otherCheckName = $(this).find("input:radio").attr("name")
                        if (otherCheckName == thisCheckName) {
                            if (!($(this).hasClass('selected'))) {
                                $(this).removeClass('checked').css({
                                    backgroundImage: uncheckedImage
                                });
                            }
                        }
                    });
                }
            });
        } else if ($(this).is(":checkbox")) {
            var checkbox = $(this);
            $(checkbox).wrap('<div class="styledCheckbox" ></div').hide();
            $('.styledCheckbox').css({
                backgroundImage: uncheckedImage,
                width: width,
                height: height
            });
            $(checkbox).filter(':checked').parent().addClass('checked').css({
                "background-image": checkedImage
            });
            $('.styledCheckbox').click(function () {
                if (!($(this).hasClass('checked'))) {
                    $(this).addClass('checked').css({
                        "background-image": checkedImage
                    }).find('input:checkbox').attr('checked', 'checked');
                } else {
                    $(this).removeClass('checked').css({
                        "background-image": uncheckedImage
                    }).find('input:checkbox').removeAttr('checked', 'checked');
                    $(selectAllBtn).removeAttr('checked', 'checked').parent('.styledCheckbox').removeClass('checked').css({
                        "background-image": uncheckedImage
                    });
                }
                if (selectAllBtn != null) {
                    if ($(this).find('input:checkbox').is(selectAllBtn)) {
                        if ($(this).hasClass('checked')) {
                            $(checkbox).each(function () {
                                $(this).attr('checked', 'checked').parent('.styledCheckbox').addClass('checked').css({
                                    "background-image": checkedImage
                                });
                            });
                        } else {
                            $(checkbox).each(function () {
                                $(this).removeAttr('checked', 'checked').parent('.styledCheckbox').removeClass('checked').css({
                                    "background-image": uncheckedImage
                                });
                            });
                        }
                    }
                }
            });
            $('label').click(function () {
                var labelFor = $(this).attr('for');
                var radioForMatch = $('input:checkbox').filter('#' + labelFor);
                if (!(radioForMatch.parent().hasClass("checked"))) {
                    if ($.browser.msie) {
                        if ($.browser.version == 7.0 || $.browser.version == 8.0) {
                            radioForMatch.attr('checked', 'checked').parent('.styledCheckbox').addClass('checked').css({
                                "background-image": checkedImage
                            });
                            if (radioForMatch.is(selectAllBtn)) {
                                $(checkbox).each(function () {
                                    $(this).attr('checked', 'checked').parent('.styledCheckbox').addClass('checked').css({
                                        "background-image": checkedImage
                                    });
                                });
                            }
                        }
                    }
                } else if (radioForMatch.parent().hasClass("checked")) {
                    if ($.browser.msie) {
                        if ($.browser.version == 7.0 || $.browser.version == 8.0) {
                            radioForMatch.removeAttr('checked', 'checked').parent('.styledCheckbox').removeClass('checked').css({
                                "background-image": uncheckedImage
                            });
                            $(selectAllBtn).removeAttr('checked', 'checked').parent('.styledCheckbox').removeClass('checked').css({
                                "background-image": uncheckedImage
                            });
                        }
                    }
                }
            });
        }
        $('.styledRadio').css({
            'cursor': 'pointer',
            "background-repeat": "no-repeat"
        });
        $('.styledCheckbox').css({
            'cursor': 'pointer',
            "background-repeat": "no-repeat"
        });
    }
    $.fn.screwDefaultButtons.defaults = {
        checked: "url(images/radio_Checked.jpg)",
        unchecked: "url(images/radio_Unchecked.jpg)",
        selectAll: null,
        width: 20,
        height: 20,
        onchange: null
    };
})(jQuery);