const monthNames = [
  'Январь',
  'Февраль',
  'Март',
  'Апрель',
  'Май',
  'Июнь',
  'Июль',
  'Август',
  'Сентябрь',
  'Октябрь',
  'Ноябрь',
  'Декабрь'
];
const monthNamesShort = [
  'Янв',
  'Фев',
  'Мар',
  'Апр',
  'Май',
  'Июн',
  'Июл',
  'Авг',
  'Сен',
  'Окт',
  'Ноя',
  'Дек'
];
const dayNames = [
  'воскресенье',
  'понедельник',
  'вторник',
  'среда',
  'четверг',
  'пятница',
  'суббота'
];
const dayNamesShort = [
  'вск',
  'пнд',
  'втр',
  'срд',
  'чтв',
  'птн',
  'сбт'
];
const dayNamesMin = [
  'Вс',
  'Пн',
  'Вт',
  'Ср',
  'Чт',
  'Пт',
  'Сб'
];

export default {
  dayNames,
  dayNamesShort,
  dayNamesMin,
  monthNames,
  monthNamesShort,
  currentText: 'Сегодня',
  prevText: '&#x3C;Пред',
  nextText: 'След&#x3E;',
  closeText: 'Закрыть',
  weekHeader: 'Нед',
  dateFormat: 'dd.mm.yy',
  firstDay: 1,
  isRTL: false,
  showMonthAfterYear: false,
  yearSuffix: '',
  maxDate: -1,
  changeYear: true,
  yearRange: '-100:+0'
};
