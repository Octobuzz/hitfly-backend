import $ from "jquery";
import './nice-select';
import './different-scroll';

$(function () {

    $('.custom-select').niceSelect();

    $('[data-scroll-speed]').moveIt();

    $(".input-text input").each(function() {
        if ($(this).val() != "") {
            $(this).parent().addClass("input-full");
        }
    });

    $(".input-text input").focus(function(){
        $(this).parent().addClass("input-full");
    });

    //Remove animation(s) when input is no longer focused
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

       span.css('transition-delay', `${i}00ms`);
    });

    $('#form-register').on('submit', function () {
        let form = $(this);
        let day = form.find('.bd-block__date').val();
        let month = form.find('.bd-block__month').val();
        let year = form.find('.bd-block__year').val();
        let bday = form.find('#bday-date');
        let password = form.find('#pass');
        let conf_password = form.find('#pass_two');
        let date;

        if(day && month && year){
            date = new Date(Date.UTC(year, month, day)).toISOString();
            bday.val(date);
        } else{
            bday.val('');
        }

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
