import pluralFormEndingsFormatter from 'modules/plural-form-endings-formatter';

export default {
  methods: {
    totalDurationInfo(type, sec) {
      const mins = Math.ceil(sec / 60);
      let duration = mins;
      const word = type === 'mins' ? 'MINUTE' : 'HOUR';

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
