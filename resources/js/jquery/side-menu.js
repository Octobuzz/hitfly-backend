import $ from 'jquery';

// css classes
const IS_SHOWN = 'is-show';
const IS_HIDDEN = 'is-close';

let trigger;
let menu;

const showMenu = () => {
  trigger.removeClass(IS_HIDDEN);
  trigger.addClass(IS_SHOWN);
  menu.addClass(IS_SHOWN);
};

const hideMenu = () => {
  trigger.removeClass(IS_SHOWN);
  trigger.addClass(IS_HIDDEN);
  menu.removeClass(IS_SHOWN);
};

const handleMenu = ({ target }) => {
  const menuIsShown = menu.hasClass(IS_SHOWN);
  const menuIsTarget = menu.is(target);
  const menuHasTarget = menu.has(target).length > 0;

  if (menuIsShown
    && !menuIsTarget
    && !menuHasTarget
  ) {
    hideMenu();
  }

  const triggerIsTarget = trigger.is(target);
  const triggerHasTarget = trigger.has(target).length > 0;
  const triggerIsOrHasTarget = triggerIsTarget || triggerHasTarget;

  if (!menuIsShown && triggerIsOrHasTarget) {
    showMenu();
  }
};

$(document).mouseup(handleMenu);
$(document).ready(() => {
  trigger = $('.menu-call');
  menu = $('.drop-menu');
});

$('.drop-menu-list__item').each((i, item) => {
  $(item).find('span').css('animation-delay', '.5s');
});
