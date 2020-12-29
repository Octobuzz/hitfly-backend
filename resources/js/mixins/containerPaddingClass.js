// a component using this mixin should be a descendant of AppColumnsLayout

export default {
  computed: {
    containerPaddingClass() {
      return this.$store.getters['appColumns/paddingClass'];
    }
  }
};
