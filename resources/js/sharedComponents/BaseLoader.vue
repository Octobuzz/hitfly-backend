<template>
  <vue-element-loading
    :active="active"
    spinner="bar-fade-scale"
    color="#aaa"
    background-color="rgba(255, 255, 255, 0)"
  />
</template>

<script>
import VueElementLoading from 'vue-element-loading';

export default {
  components: {
    VueElementLoading
  },
  props: {
    active: {
      type: Boolean,
      required: true
    }
  }
};
</script>
