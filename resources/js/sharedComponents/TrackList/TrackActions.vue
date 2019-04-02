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
    }
  }
};
</script>

<style
  scoped
  lang="scss"
>

</style>
