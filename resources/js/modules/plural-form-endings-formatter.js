const formatting = {
  TRACK: {
    1: 'песня',
    '2-4': 'песни',
    '5-20': 'песен'
  },
  BONUS: {
    1: 'балл',
    '2-4': 'балла',
    '5-20': 'баллов'
  },
  MINUTE: {
    1: 'минута',
    '2-4': 'минуты',
    '5-20': 'минут'
  },
  HOUR: {
    1: 'час',
    '2-4': 'часа',
    '5-20': 'часов'
  },
  DAY: {
    1: 'день в Hitfly',
    '2-4': 'дня в Hitfly',
    '5-20': 'дней Hitfly'
  },
  FAVOURITE_SONG: {
    1: 'любимая песня',
    '2-4': 'любимые песни',
    '5-20': 'любимых песен'
  },
  FOLLOWER: {
    1: 'поклонник',
    '2-4': 'поклонника',
    '5-20': 'поклонников'
  },
  NOVICE: {
    1: 'новичок',
    '2-4': 'новичка',
    '5-20': 'новичков'
  },
  AMATEUR: {
    1: 'любитель',
    '2-4': 'любителя',
    '5-20': 'любителей'
  },
  GENRE_CONNOISSEUR: {
    1: 'знаток жанра',
    '2-4': 'знатока жанра',
    '5-20': 'знатоков жанра'
  },
  MUSIC_LOVER: {
    1: 'супер меломан',
    '2-4': 'супер меломана',
    '5-20': 'супер меломанов'
  }
};

export default (word, total) => {
  let lastTwoDigits = total % 100;
  let lastTwoDigitsSet;
  const checkCap = 20;

  if (lastTwoDigits > checkCap) {
    lastTwoDigits %= 10;
  }

  if (lastTwoDigits === 1) {
    lastTwoDigitsSet = 1;
  }
  if (lastTwoDigits >= 2 && lastTwoDigits <= 4) {
    lastTwoDigitsSet = '2-4';
  }
  if ((lastTwoDigits >= 5 && lastTwoDigits <= checkCap) || lastTwoDigits === 0) {
    lastTwoDigitsSet = '5-20';
  }

  return formatting[word][lastTwoDigitsSet];
};
