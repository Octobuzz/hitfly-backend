<template>
  <div>
    <div
      v-if="$route.fullPath === '/'"
      style="margin-top: 107px;"
    />
    <TrackListInterface/>
    <AlbumListInterface/>
  </div>
</template>

<script>
import TrackListInterface from './TrackListInterface.vue';
import AlbumListInterface from './AlbumListInterface.vue';

export default {
  components: {
    TrackListInterface,
    AlbumListInterface
  }
};
</script>

<style
  scoped
  lang="scss"
>

</style>
