import $ from "jquery";
import './nice-select';
import './different-scroll';
import './autofill';

import 'jquery-ui/themes/base/core.css';
import 'jquery-ui/themes/base/theme.css';
import 'jquery-ui/themes/base/datepicker.css';
import 'jquery-ui/ui/core';
import 'jquery-ui/ui/widgets/datepicker';

console.log('jquery logic');

$(function () {

    $('.custom-select').niceSelect();
    $.datepicker.setDefaults({
        closeText: "Закрыть",
        prevText: "&#x3C;Пред",
        nextText: "След&#x3E;",
        currentText: "Сегодня",
        monthNames: [ "Январь","Февраль","Март","Апрель","Май","Июнь",
            "Июль","Август","Сентябрь","Октябрь","Ноябрь","Декабрь" ],
        monthNamesShort: [ "Янв","Фев","Мар","Апр","Май","Июн",
            "Июл","Авг","Сен","Окт","Ноя","Дек" ],
        dayNames: [ "воскресенье","понедельник","вторник","среда","четверг","пятница","суббота" ],
        dayNamesShort: [ "вск","пнд","втр","срд","чтв","птн","сбт" ],
        dayNamesMin: [ "Вс","Пн","Вт","Ср","Чт","Пт","Сб" ],
        weekHeader: "Нед",
        dateFormat: "dd.mm.yy",
        firstDay: 1,
        isRTL: false,
        showMonthAfterYear: false,
        yearSuffix: "",
        maxDate: -1,
        changeYear: true,
        yearRange: "-100:+0",
    });
    $('[data-scroll-speed]').moveIt();

    $('.is-tooltip').each(function () {
       let item = $(this);
       let title = item.data('tooltip-text');

        new Tooltip(item, {
            placement: 'top',
            title: title,
        });
    });

    $('.input-text.js-datepicker  input').datepicker();

    $(".input-text input").each(function() {
        if ($(this).val() === "") {
            $(this).parent().removeClass("input-full");
        } else{
            $(this).parent().addClass("input-full");
        }
    });

    $(".input-text input").on('change',function(){
        if ($(this).val() === "") {
            $(this).parent().removeClass("input-full");
        } else{
            $(this).parent().addClass("input-full");
        }
    });

    $(".input-text input").on('focus',function(){
        $(this).parent().addClass("input-full");
    });

    $(".input-text input").focusout(function(){
        if($(this).val() === ""){
            $(this).parent().removeClass("input-full");
        }
    });

    function closeMenu(){
        let button = $('.menu-call');
        let menu = $('.drop-menu');

        button.removeClass('is-show');
        button.addClass('is-close');
        menu.toggleClass('is-show');
    }

    function showMenu(){
        let button = $('.menu-call');
        let menu = $('.drop-menu');

        button.removeClass('is-close');
        button.addClass('is-show');
        menu.toggleClass('is-show');
    }

    $(document).mouseup(function(e) {
        let container = $(".drop-menu");
        let button = $(".menu-call");

        if(container.hasClass('is-show')){
            if(!container.is(e.target) && container.has(e.target).length === 0){
                closeMenu();
            }
        } else{
            if(button.is(e.target) || button.has(e.target).length !== 0){
                showMenu();
            }
        }
    });

    $('.drop-menu-list__item').each(function (i, item) {
       let row = $(item);
       let span = row.find('span');
       let time = 500 + Number(`${i}00`);

       span.css('animation-delay', `${time}ms`);
    });

    $('#form-register').on('submit', function () {
        let form = $(this);
        let password = form.find('#pass');
        let conf_password = form.find('#pass_two');

        if(password.val() === conf_password.val()){
            password.closest('.input-text').removeClass('error');
            conf_password.closest('.input-text').removeClass('error');
            $('.reg-page__pass-error').hide();
            return true;
        } else{
            password.closest('.input-text').addClass('error');
            conf_password.closest('.input-text').addClass('error');
            $('.reg-page__pass-error').show();
            return false;
        }

    });
});

$(document).ready(function () {
    $('.input-text input').checkAndTriggerAutoFillEvent();
});
