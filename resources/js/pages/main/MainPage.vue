<template>
  <div>
    <div
      v-if="$route.fullPath === '/'"
      style="margin-top: 107px;"
    />
    <TrackListInterface/>
    <AlbumListInterface/>
    <CollectionPreview :collection-id="443" />
  </div>
</template>

<script>
import CollectionPreview from 'components/CollectionPreview';
import TrackListInterface from './TrackListInterface.vue';
import AlbumListInterface from './AlbumListInterface.vue';

export default {
  components: {
    TrackListInterface,
    AlbumListInterface,
    CollectionPreview
  }
};
</script>

<style
  scoped
  lang="scss"
>

</style>
