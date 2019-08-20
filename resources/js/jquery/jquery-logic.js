import $ from "jquery";
import './nice-select';
import './different-scroll';
import './autofill';

import 'jquery-ui/themes/base/core.css';
import 'jquery-ui/themes/base/theme.css';
import 'jquery-ui/themes/base/datepicker.css';
import 'jquery-ui/ui/core';
import 'jquery-ui/ui/widgets/datepicker';

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
    $('.reg-page input[type="email"]').on('keyup', function(){
    });
    $('.reg-page button[type="submit"]').click(function(event){
      event.preventDefault();

      // TODO: fix bugs

      // //проверка подтверждения пароля
      // const password = $('.reg-page__line-input input[name="password"]');
      // const passwordConfirm = $('.reg-page__line-input input[name="password_confirmation"]');
      // if(password.val() == ''){
      //   password.parent().parent().addClass('error');
      // }else{
      //   password.parent().parent().removeClass('error');
      //   if(password.val() !== passwordConfirm.val()){
      //     passwordConfirm.parent().parent().addClass('error');
      //   }else{
      //     passwordConfirm.parent().parent().removeClass('error');
      //   };
      // }

      //проверка пароля
      const passwordInput = $('.reg-page__line-input input[name="password"]');
      const passwordContainer = passwordInput.parent().parent();

      const passwordPattern = /(?=^.{8,}$)(?=.*\d)(?![.\n])(?=.*[A-Z])(?=.*[a-z])(?=.*[!@#$%^&*]).*$/i;
      const passwordPatternSatisfied = passwordPattern.test(passwordInput.val());

      if (passwordPatternSatisfied) {
        passwordContainer.removeClass('error');
      } else {
        passwordContainer.addClass('error');
      }

      // //проверка восстановления пароля
      // const passwordRestore = $('.forgot-pass__input input[name="password"]');
      //
      // if(passwordRestore.val() !== undefined && passwordRestore.val().length >= 8){
      //   if(passwordRestore.val().search(passwordPattern) == 0){
      //     passwordRestore.parent().parent().removeClass('error');
      //   }else{
      //     passwordRestore.parent().parent().addClass('error');
      //   }
      // }else{
      //   password.parent().parent().addClass('error');
      // };

      //проверка почты
      const pattern = /^[-a-z0-9~!$%^&*_=+}{\'?]+(\.[-a-z0-9~!$%^&*_=+}{\'?]+)*@([a-z0-9_][-a-z0-9_]*(\.[-a-z0-9_]+)*\.(aero|arpa|biz|com|coop|edu|gov|info|int|mil|museum|team|name|net|org|pro|travel|mobi|[a-z][a-z])|([0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}))(:[0-9]{1,5})?$/i;
      const email = $('.reg-page__line-input input[name="email"]');
      if(email.val().length >= 8){
        if(email.val().search(pattern) == 0){
          email.parent().parent().removeClass('error');
        }else{
          email.parent().parent().addClass('error');
        }
      }else{
        email.parent().parent().addClass('error');
      };

      //проверка чекбокса
      const checkbox = $('.reg-page__agreement input');
      if(!checkbox.prop('checked')){
        checkbox.parent().parent().addClass('error');
      }else{
        checkbox.parent().parent().removeClass('error');
      }

      //проверка наличия ошибок в форме
      if($('.reg-page form .input-text-wrapper').hasClass('error')){
        $('html, body').animate({
            scrollTop: $('form').offset().top
        }, 500);
      }else{
        $('.reg-page form').submit();
      };
    })
});
