const formatting = {
  mins: {
    1: 'минута',
    234: 'минуты',
    567890: 'минут'
  },
  hours: {
    1: 'час',
    234: 'часа',
    567890: 'часов'
  },
  tracks: {
    1: 'песня',
    234: 'песни',
    567890: 'песен'
  }
};

export default {
  methods: {
    totalDurationInfo(type, sec) {
      const mins = Math.ceil(sec / 60);
      let duration = mins;

      if (type === 'hours') {
        duration = Math.floor(mins / 60);
      }

      const lastDigit = duration % 10;
      let lastDigitSet = 567890;

      if (lastDigit === 1) {
        lastDigitSet = 1;
      }
      if (lastDigit >= 2 && lastDigit <= 4) {
        lastDigitSet = 234;
      }

      return `${duration} ${formatting[type][lastDigitSet]}`;
    },

    totalTracksInfo(total) {
      const lastDigit = total % 10;
      let lastDigitSet = 567890;

      if (lastDigit === 1) {
        lastDigitSet = 1;
      }
      if (lastDigit >= 2 && lastDigit <= 4) {
        lastDigitSet = 234;
      }

      return `${total} ${formatting.tracks[lastDigitSet]}`;
    }
  }
};
