<template>
  <button
    :class="[
      'form-button',
      {
        'form-button_secondary': secondary,
        'form-button_primary': primary,
        ...$attrs.class
      }
    ]"
    @click="$emit('press')"
  >
    <slot/>
  </button>
</template>

<script>
export default {
  props: {
    modifier: {
      type: String,
      default: 'primary'
    }
  },
  computed: {
    primary() {
      return this.modifier === 'primary';
    },
    secondary() {
      return this.modifier === 'secondary';
    }
  }
};
</script>

<style
  scoped
  lang="scss"
>
@import '../../sass/variables';

.form-button {
  box-sizing: border-box;
  font-family: "Gotham Pro", serif;
  font-size: 14px;
  width: 100%;
  border-radius: $border-radius;
  cursor: pointer;
  user-select: none;
  transition: color .2s;

  &_primary {
    color: white;
    padding: 17px 15px 17px 15px;
    border: none;
    background: $linear-gradient;

    &:hover {
      color: $color_6;
      padding: 16px 14px 16px 14px;
      border: 1px solid $color_pink;
      background: transparent;
    }
  }

  &_secondary {
    color: $color_6;
    padding: 16px 14px 16px 14px;
    border: 1px solid $color_pink;

    &:hover {
      color: white;
      padding: 17px 15px 17px 15px;
      border: none;
      background: $linear-gradient;
    }
  }
}
</style>
