<template>
  <div class="track-list">
    <slot name="header" />

    <TrackListEntry
      v-for="(trackId, index) in trackIdList"
      :key="trackId"
      :index="index"
      :track-id="trackId"
      @remove-track="onTrackRemove"
      @favourite-pressed="onFavouritePressed"
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
    }
  },
  methods: {
    onTrackRemove(id) {
      this.$emit('remove-track', id);
    },
    onFavouritePressed(id) {
      this.$emit('favourite-pressed', id);
    }
  }
};
</script>
