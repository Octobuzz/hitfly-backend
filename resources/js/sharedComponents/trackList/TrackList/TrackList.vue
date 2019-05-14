<template>
  <div class="track-list">
    <slot name="header" />

    <TrackListEntry
      v-for="(trackId, index) in trackIdList"
      :key="trackId"
      :index="index"
      :track-id="trackId"
      :fake-fav-button="fakeFavButton"
      @remove-track="onTrackRemove"
      @press-favourite="onFavouritePress"
    />
    <slot name="loader" />
    <slot name="loadButton" />
  </div>
</template>

<script>
import TrackListEntry from '../TrackListEntry';

export default {
  components: {
    TrackListEntry
  },
  props: {
    trackIdList: {
      type: Array,
      required: true
    },
    fakeFavButton: {
      type: Boolean,
      default: false
    }
  },
  methods: {
    onTrackRemove(id) {
      this.$emit('remove-track', id);
    },
    onFavouritePress(id) {
      this.$emit('press-favourite', id);
    }
  }
};
</script>
