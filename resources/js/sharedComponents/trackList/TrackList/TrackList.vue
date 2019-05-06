<template>
  <div class="track-list">
    <slot name="header" />

    <TrackListEntry
      v-for="(trackId, index) in trackIdList"
      :key="trackId"
      :index="index"
      :track-id="trackId"
      @remove-track="onTrackRemove"
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
    }
  }
};
</script>
