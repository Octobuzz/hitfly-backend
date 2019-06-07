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
@import '~scss/_variables.scss';

.form-button {
  box-sizing: border-box;
  font-family: 'Gotham Pro', sans-serif;
  font-size: 14px;
  position: relative;
  width: 100%;
  border-radius: $input_border_radius;
  cursor: pointer;
  user-select: none;
  transition: color .2s;

  &_primary {
    color: white;
    padding: 17px 15px 17px 15px;
    border: none;
    background: $gradient-linear;

    &:hover {
      color: #231f20;
      padding: 16px 14px 16px 14px;
      border: 1px solid $red-violet;
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
    color: #231f20;
    padding: 16px 14px 16px 14px;
    border: 1px solid $red-violet;

    &:hover {
      color: white;
      padding: 17px 15px 17px 15px;
      border: none;
      background: $gradient-linear;

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
