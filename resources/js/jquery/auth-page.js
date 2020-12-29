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
const INPUT_TEXT_WRAPPER = 'input-text-wrapper';
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

// there could be only one form on a page
const $authForm = $('#register-form, #reset-password-form');

const emailRegex = /^[-a-z0-9~!$%^&*_=+}{'?]+(\.[-a-z0-9~!$%^&*_=+}{'?]+)*@([a-z0-9_][-a-z0-9_]*(\.[-a-z0-9_]+)*\.(aero|arpa|biz|com|coop|edu|gov|info|int|mil|museum|team|name|net|org|pro|travel|mobi|[a-z][a-z])|([0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}))(:[0-9]{1,5})?$/i;
const $emailInput = $authForm.find('input[name="email"]');
const $emailContainer = $emailInput.closest(`.${INPUT_TEXT_WRAPPER}`);

const validateEmail = () => {
  const regexSatisfied = emailRegex.test($emailInput.val());

  if (regexSatisfied) {
    $emailContainer.removeClass(ERROR);
  } else {
    $emailContainer.addClass(ERROR);
  }
  return regexSatisfied;
};

const pwdRegex = /(?=^.{8,}$)(?=.*\d)(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$/i;
const $pwdInput = $authForm.find('input[name="password"]');
const $pwdContainer = $pwdInput.closest(`.${INPUT_TEXT_WRAPPER}`);

const validatePwd = () => {
  const regexSatisfied = pwdRegex.test($pwdInput.val());

  if (regexSatisfied) {
    $pwdContainer.removeClass(ERROR);
  } else {
    $pwdContainer.addClass(ERROR);
  }
  return regexSatisfied;
};

const $pwdConfInput = $authForm.find('input[name="password_confirmation"]');
const $pwdConfContainer = $pwdConfInput.closest(`.${INPUT_TEXT_WRAPPER}`);

const validatePwdConf = () => {
  const pwdMatch = $pwdInput.val() === $pwdConfInput.val();

  if (pwdMatch) {
    $pwdConfContainer.removeClass(ERROR);
  } else {
    $pwdConfContainer.addClass(ERROR);
  }
  return pwdMatch;
};

const $checkbox = $authForm.find('input[name="agreement"]');
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

$authForm.on('submit', (e) => {
  const getValidationChecks = (action) => {
    switch (action) {
      case 'register':
        return [
          validateEmail,
          validatePwd,
          validatePwdConf,
          validateAgreement
        ];
      case 'reset':
        return [
          validateEmail,
          validatePwd,
          validatePwdConf
        ];
      default:
        return [];
    }
  };

  let validationAction = window.location.pathname.slice(1);

  if (validationAction.slice(0, 8) === 'register') {
    validationAction = 'register';
  }
  if (validationAction.slice(0, 14) === 'password/reset') {
    validationAction = 'reset';
  }

  const validationChecks = getValidationChecks(validationAction);
  const validationPassed = validationChecks.reduce(
    (acc, validator) => validator() && acc,
    true
  );

  if (!validationPassed) {
    e.preventDefault();

    $('html, body').animate({
      scrollTop: $authForm.offset().top
    }, 500);
  }
});
