import pluralFormEndingsFormatter from 'modules/plural-form-endings-formatter';

export default {
  methods: {
    totalDurationInfo(type, sec) {
      const mins = Math.floor(sec / 60);
      const word = type === 'mins' ? 'MINUTE' : 'HOUR';
      let duration = mins;

      if (type === 'hours') {
        duration = Math.floor(mins / 60);
      }

      return `${duration} ${pluralFormEndingsFormatter(word, duration)}`;
    },

    totalTracksInfo(total) {
      return `${total} ${pluralFormEndingsFormatter('TRACK', total)}`;
    }
  }
};
