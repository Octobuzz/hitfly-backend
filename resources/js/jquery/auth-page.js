// App chunks are loaded with 'defer' script attribute
// so the dom is considered as constructed

import $ from 'jquery';
import 'jquery-ui/ui/core';
import 'jquery-ui/ui/widgets/datepicker';
import 'jquery-ui/themes/base/core.css';
import 'jquery-ui/themes/base/theme.css';
import 'jquery-ui/themes/base/datepicker.css';
import './autofill';
import datapickerDefaults from './datapicker-defaults';

// css classes
const INPUT_FULL = 'input-full';
const REG_PAGE_LINE_INPUT = 'reg-page__line-input';
const ERROR = 'error';

$.datepicker.setDefaults(datapickerDefaults);
$('.input-text.js-datepicker  input').datepicker();

function handleInputClassOnChange() {
  const $input = $(this);
  const $inputParent = $input.parent();

  if ($input.val() === '') {
    $inputParent.removeClass(INPUT_FULL);
  } else {
    $inputParent.addClass(INPUT_FULL);
  }
}

function handleInputClassOnFocus() {
  $(this).parent().addClass(INPUT_FULL);
}

function handleInputClassOnFocusOut() {
  const $input = $(this);

  if ($input.val() === '') {
    $input.parent().removeClass(INPUT_FULL);
  }
}

const $input = $('.input-text input');

$input.checkAndTriggerAutoFillEvent();
$input.each(handleInputClassOnChange);
$input.on('change', handleInputClassOnChange);
$input.on('focus', handleInputClassOnFocus);
$input.on('focusout', handleInputClassOnFocusOut);

const $regForm = $('#form-auth');

const emailRegex = /^[-a-z0-9~!$%^&*_=+}{'?]+(\.[-a-z0-9~!$%^&*_=+}{'?]+)*@([a-z0-9_][-a-z0-9_]*(\.[-a-z0-9_]+)*\.(aero|arpa|biz|com|coop|edu|gov|info|int|mil|museum|team|name|net|org|pro|travel|mobi|[a-z][a-z])|([0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}))(:[0-9]{1,5})?$/i;
const $emailInput = $regForm.find('input[name="email"]');
const $emailContainer = $emailInput.closest(`.${REG_PAGE_LINE_INPUT}`);

const validateEmail = () => {
  const regexSatisfied = emailRegex.test($emailInput.val());

  if (regexSatisfied) {
    $emailContainer.removeClass(ERROR);
  } else {
    $emailContainer.addClass(ERROR);
  }
  return regexSatisfied;
};

const pwdRegex = /(?=^.{8,}$)(?=.*\d)(?![.\n])(?=.*[A-Z])(?=.*[a-z])(?=.*[!@#$%^&*]).*$/i;
const $pwdInput = $regForm.find('input[name="password"]');
const $pwdContainer = $pwdInput.closest(`.${REG_PAGE_LINE_INPUT}`);

const validatePwd = () => {
  const regexSatisfied = pwdRegex.test($pwdInput.val());

  if (regexSatisfied) {
    $pwdContainer.removeClass(ERROR);
  } else {
    $pwdContainer.addClass(ERROR);
  }
  return regexSatisfied;
};

const $pwdConfInput = $regForm.find('input[name="password_confirmation"]');
const $pwdConfContainer = $pwdConfInput.closest(`.${REG_PAGE_LINE_INPUT}`);

const validatePwdConf = () => {
  const pwdMatch = $pwdInput.val() === $pwdConfInput.val();

  if (pwdMatch) {
    $pwdConfContainer.removeClass(ERROR);
  } else {
    $pwdConfContainer.addClass(ERROR);
  }
  return pwdMatch;
};

const $checkbox = $regForm.find('input[name="agreement"]');
const $checkboxContainer = $checkbox.closest('.reg-page__agreement');

const validateAgreement = () => {
  const checked = $checkbox.prop('checked');

  if (checked) {
    $checkboxContainer.removeClass(ERROR);
  } else {
    $checkboxContainer.addClass(ERROR);
  }
  return checked;
};

$regForm.on('submit', (e) => {
  const validationPassed = [
    validateEmail,
    validatePwd,
    validatePwdConf,
    validateAgreement
  ].reduce(
    (acc, validator) => validator() && acc,
    true
  );

  if (!validationPassed) {
    e.preventDefault();

    $('html, body').animate({
      scrollTop: $regForm.offset().top
    }, 500);
  }
});
