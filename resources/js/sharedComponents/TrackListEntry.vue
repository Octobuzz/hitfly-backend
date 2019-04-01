<template>
  <div>
    {{ track.trackName }}
  </div>
</template>

<script>
export default {
  props: {
    track: {
      type: Object,
      default: () => {}
    },
    index: {
      type: Number,
      required: true
    }
  }
};
</script>

<style
  scoped
  lang="scss"
>

</style>
