<template>
  <div :class="['return-header', $attrs.class]">
    <IconButton
      class="return-header__arrow-button"
      @press="onReturn"
    >
      <ArrowIcon />
    </IconButton>
    Назад
  </div>
</template>

<script>
import IconButton from 'components/IconButton.vue';
import ArrowIcon from 'components/icons/ArrowIcon.vue';

export default {
  components: {
    IconButton,
    ArrowIcon
  },

  methods: {
    onReturn() {
      this.$emit('press');

      const history = this.$store.getters['history/history'];

      // TODO: handle all go(-n) statements properly

      if (history.length === 0) {
        this.$router.replace('/profile/my-music');
      } else {
        this.$router.go(-1);
      }
    }
  }
};
</script>

<style
  scoped
  lang="scss"
>
@import '~scss/_variables.scss';

.return-header {
  font-family: 'Gotham Pro', sans-serif;
  font-size: 14px;
  display: flex;
  align-items: center;
  height: 32px;
  padding: 16px 0 16px 16px;
  box-shadow: 0 1px 0 $layout_border_color;

  &__arrow-button {
    display: flex;
    justify-content: center;
    align-items: center;
    width: 40px;
    height: 32px;
    margin-right: 16px;
    border: 1px solid $layout_border_color;
    border-radius: $input_border_radius;

    svg {
      width: 23px;
      height: 15px;
      opacity: .75;

      &:hover {
        opacity: 1;
      }
    }
  }
}
</style>
