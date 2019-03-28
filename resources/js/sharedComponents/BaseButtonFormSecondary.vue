<template>
  <button
    class="base-button-form-secondary"
    @click="$emit('press')"
  >
    <slot/>
  </button>
</template>

<style
  scoped
  lang="scss"
>
@import '../../sass/variables';

.base-button-form-secondary {
  font-family: "Gotham Pro", serif;
  font-size: 14px;
  color: $color_6;
  width: 100%;
  padding: 16px 14px 15px 14px;
  border: 1px solid $color_pink;
  border-radius: $border-radius;
  cursor: pointer;
  user-select: none;
  transition: .2s;

  &:hover {
    color: white;
    border: 1px solid transparent;
    background: $linear-gradient;
  }
}
</style>
