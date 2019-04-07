<template>
  <div :class="['return-header', $attrs.class]">
    <IconButton
      class="return-header__arrow-button"
      @press="$emit('press')"
    >
      <ArrowIcon/>
    </IconButton>
    Назад
  </div>
</template>

<script>
import IconButton from '../../sharedComponents/IconButton.vue';
import ArrowIcon from '../../sharedComponents/icons/ArrowIcon.vue';

export default {
  components: {
    IconButton,
    ArrowIcon
  }
};
</script>

<style
  scoped
  lang="scss"
>
@import '../../../sass/variables';

.return-header {
  font-family: "Gotham Pro", serif;
  font-size: 14px;
  display: flex;
  align-items: center;
  height: 32px;
  padding: {
    top: 16px;
    bottom: 16px;
  }
  box-shadow: 0 1px 0 $borderColor;

  &__arrow-button {
    display: flex;
    justify-content: center;
    align-items: center;
    width: 40px;
    height: 32px;
    margin-right: 16px;
    border: 1px solid $borderColor;
    border-radius: 4px;

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
