export default {
  computed: {
    playingTrackId() {
      return this.$store.getters['player/currentTrack'].id;
    }
  }
};
