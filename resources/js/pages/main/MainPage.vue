<template>
  <div>
    <div
      v-if="$route.fullPath === '/'"
      style="margin-top: 107px;"
    />

    <!--TODO: move to the root component-->
    <IconGradientRadial/>
    <TrackListInterface/>
    <!--<AlbumScrollHorizontal/>-->

  </div>
</template>

<script>
import IconGradientRadial from 'components/IconGradientRadial.vue';
import TrackListInterface from './TrackListInterface.vue';
// import AlbumScrollHorizontal from './AlbumListInterface.vue';

export default {
  components: {
    IconGradientRadial,
    TrackListInterface,
    // AlbumScrollHorizontal
  }
};
</script>

<style
  scoped
  lang="scss"
>

</style>
