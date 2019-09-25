const formatting = {
  TRACK: {
    1: 'песня',
    '2-4': 'песни',
    '5-9': 'песен'
  },
  BONUS: {
    1: 'балл',
    '2-4': 'балла',
    '5-9': 'баллов'
  },
  DAY: {
    1: 'день в Hitfly',
    '2-4': 'дня в Hitfly',
    '5-9': 'дней Hitfly'
  },
  FAVOURITE_SONG: {
    1: 'любимая песня',
    '2-4': 'любимые песни',
    '5-9': 'любимых песен'
  },
  FOLLOWER: {
    1: 'поклонник',
    '2-4': 'поклонника',
    '5-9': 'поклонников'
  },
  NOVICE: {
    1: 'новичок',
    '2-4': 'новичка',
    '5-9': 'новичков'
  },
  AMATEUR: {
    1: 'любитель',
    '2-4': 'любителя',
    '5-9': 'любителей'
  },
  GENRE_CONNOISSEUR: {
    1: 'знаток жанра',
    '2-4': 'знатока жанра',
    '5-9': 'знатоков жанра'
  },
  MUSIC_LOVER: {
    1: 'супер меломан',
    '2-4': 'супер меломана',
    '5-9': 'супер меломанов'
  }
};

export default (word, total) => {
  const lastDigit = total % 10;
  let lastDigitSet = '5-9';

  if (lastDigit === 1) {
    lastDigitSet = 1;
  }
  if (lastDigit >= 2 && lastDigit <= 4) {
    lastDigitSet = '2-4';
  }

  return formatting[word][lastDigitSet];
};
