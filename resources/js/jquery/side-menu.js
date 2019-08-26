import $ from 'jquery';

// css classes
const IS_SHOWN = 'is-show';
const IS_HIDDEN = 'is-close';

const trigger = $('.menu-call');
const menu = $('.drop-menu');

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

  // const menuIsHidden = !menu.hasClass(IS_SHOWN);
  // const menuIsOrHasTarget = (menuIsTarget || menuHasTarget);

  // debugger;

  // if (menuIsHidden && !menuIsOrHasTarget) {
  //   showMenu();
  // }

  if (!menuIsShown) {
    showMenu();
  }
};

$(document).mouseup(handleMenu);

$('.drop-menu-list__item').each(function (i, item) {
  let row = $(item);
  let span = row.find('span');
  let time = 500 + Number(`${i}00`);

  span.css('animation-delay', `${time}ms`);
});
