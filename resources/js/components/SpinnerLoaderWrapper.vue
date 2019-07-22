<template>
  <div class="spinner-loader-wrapper">
    <SpinnerLoader
      v-if="isLoading"
      class="spinner-loader-wrapper__spinner"
      v-bind="$attrs"
      :is-loading="isLoading"
    />
    <slot v-else />
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
