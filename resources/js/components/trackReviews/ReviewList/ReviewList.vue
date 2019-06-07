<template>
  <div class="review-list">
    <slot name="header" />

    <ReviewListEntry
      v-for="trackId in trackIdList"
      :key="trackId"
      :track-id="trackId"
    />
    <slot name="loader" />
  </div>
</template>

<script>
import ReviewListEntry from '../ReviewListEntry';

export default {
  components: {
    ReviewListEntry
  },
  props: {
    trackIdList: {
      type: Array,
      required: true
    }
  }
};
</script>
