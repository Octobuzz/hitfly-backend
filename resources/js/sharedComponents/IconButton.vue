<template>
  <button
    :class="`button-icon ${className}`"
    @click="$emit('press')"
  >
    <slot/>
  </button>
</template>

<script>
export default {
  props: {
    className: {
      type: String,
      default: ''
    }
  }
};
</script>

<style
  scoped
  lang="scss"
>
@import '../../sass/variables';

.button-icon {
  width: 40px;
  height: 40px;

  svg {
    width: 16px;
    height: 16px;
    opacity: .25;
    transition: $trns;
  }

  &:hover {
    background: #f4f4f4;

    svg {
      opacity: .75;
    }
  }
}
</style>
