// App chunks are loaded with 'defer' script attribute
// so the dom is considered as constructed

import $ from 'jquery';
import 'jquery-ui/themes/base/core.css';
import 'jquery-ui/themes/base/theme.css';
import 'jquery-ui/themes/base/datepicker.css';
import 'jquery-ui/ui/core';
import 'jquery-ui/ui/widgets/datepicker';
import './autofill';
import datapickerDefaults from './datapicker-defaults';

$.datepicker.setDefaults(datapickerDefaults);
$('.input-text.js-datepicker  input').datepicker();

function handleInputClassOnChange() {
  const $input = $(this);
  const $inputParent = $input.parent();

  if ($input.val() === '') {
    $inputParent.removeClass('input-full');
  } else {
    $inputParent.addClass('input-full');
  }
}

function handleInputClassOnFocus() {
  $(this).parent().addClass('input-full');
}

function handleInputClassOnFocusOut() {
  const $input = $(this);

  if ($input.val() === '') {
    $input.parent().removeClass('input-full');
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
const $emailContainer = $emailInput.closest('.reg-page__line-input');

const validateEmail = () => {
  const regexSatisfied = emailRegex.test($emailInput.val());

  if (regexSatisfied) {
    $emailContainer.removeClass('error');
  } else {
    $emailContainer.addClass('error');
  }
  return regexSatisfied;
};

const pwdRegex = /(?=^.{8,}$)(?=.*\d)(?![.\n])(?=.*[A-Z])(?=.*[a-z])(?=.*[!@#$%^&*]).*$/i;
const $pwdInput = $regForm.find('input[name="password"]');
const $pwdContainer = $pwdInput.closest('.reg-page__line-input');

const validatePwd = () => {
  const regexSatisfied = pwdRegex.test($pwdInput.val());

  if (regexSatisfied) {
    $pwdContainer.removeClass('error');
  } else {
    $pwdContainer.addClass('error');
  }
  return regexSatisfied;
};

const $pwdConfInput = $regForm.find('input[name="password_confirmation"]');
const $pwdConfContainer = $pwdConfInput.closest('.reg-page__line-input');

const validatePwdConf = () => {
  const pwdMatch = $pwdInput.val() === $pwdConfInput.val();

  if (pwdMatch) {
    $pwdConfContainer.removeClass('error');
  } else {
    $pwdConfContainer.addClass('error');
  }
  return pwdMatch;
};

const $checkbox = $regForm.find('input[name="agreement"]');
const $checkboxContainer = $checkbox.closest('.reg-page__agreement');

const validateAgreement = () => {
  const checked = $checkbox.prop('checked');

  if (checked) {
    $checkboxContainer.removeClass('error');
  } else {
    $checkboxContainer.addClass('error');
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
