<template>
  <div :class="['spinner-loader-wrapper', wrapperClass]">
    <SpinnerLoader
      v-if="isLoading"
      class="spinner-loader-wrapper__spinner"
      v-bind="$attrs"
      :is-loading="isLoading"
    />
    <div
      v-show="!isLoading"
      :class="contentContainerClass"
    >
      <slot v-if="!isBlocking" />
    </div>
  </div>
</template>

<script>
import SpinnerLoader from './SpinnerLoader.vue';

export default {
  components: {
    SpinnerLoader
  },
  props: {
    isLoading: {
      type: Boolean,
      required: true
    },
    isBlocking: {
      type: Boolean,
      default: false
    },
    wrapperClass: {
      type: String,
      default: ''
    },
    contentContainerClass: {
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
.spinner-loader-wrapper {
  height: 100%;

  &__spinner {
    display: block;
    margin: auto;
  }
}
</style>
