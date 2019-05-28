<template>
  <div class="track-list">
    <slot name="header" />

    <TrackListEntry
      v-for="(trackId, index) in trackIdList"
      :key="trackId"
      :index="index + 1"
      :track-id="trackId"
      :fake-fav-button="fakeFavButton"
      :show-remove-button="showRemoveButton"
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
    },
    showRemoveButton: {
      type: Boolean,
      default: true
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
