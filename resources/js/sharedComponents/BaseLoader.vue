<template>
  <vue-element-loading
    v-bind="$attrs"
    background-color="rgba(255, 255, 255, 0)"
    :color="$attrs.color || '#aaa'"
    :spinner="$attrs.spinner || 'bar-fade-scale'"
  />
</template>

<script>
import VueElementLoading from 'vue-element-loading';

export default {
  components: {
    VueElementLoading
  }
};
</script>
