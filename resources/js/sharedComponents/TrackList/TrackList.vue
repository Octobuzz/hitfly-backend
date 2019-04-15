<template>
  <div class="track-list">
    <TrackListEntry
      v-for="(track, index) in trackList"
      :key="track.id"
      :index="index"
      :track="track"
    />
    <slot name="preloader" />
    <slot name="loadButton" />
  </div>
</template>

<script>
import TrackListEntry from './TrackListEntry.vue';

export default {
  components: {
    TrackListEntry
  },

  props: {
    trackList: {
      type: Array,
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
