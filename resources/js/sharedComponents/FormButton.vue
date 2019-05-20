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
    <SpinnerLoader
      v-if="isLoading"
      class="form-button__loader"
      size="small"
    />
    <slot v-else />
  </button>
</template>

<script>
import SpinnerLoader from 'components/SpinnerLoader.vue';

export default {
  components: {
    SpinnerLoader
  },
  props: {
    modifier: {
      type: String,
      default: 'primary'
    },
    isLoading: {
      type: Boolean,
      default: false
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
  position: relative;
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

      .form-button__loader::v-deep div:after {
        background: #999 !important;
      }
    }

    .form-button__loader::v-deep div:after {
      background: white !important;
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

      .form-button__loader::v-deep div:after {
        background: white !important;
      }
    }

    .form-button__loader::v-deep div:after {
      background: #999 !important;
    }
  }

  &__loader {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
  }
}
</style>
