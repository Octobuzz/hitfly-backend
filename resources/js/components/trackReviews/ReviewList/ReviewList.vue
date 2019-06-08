<template>
  <div class="review-list">
    <slot name="header" />

    <ReviewListEntry
      v-for="trackId in trackIdList"
      :key="trackId"
      :track-id="trackId"
      :commented-in-period="commentedInPeriod"
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
    },
    commentedInPeriod: {
      validator: val => (
        ['week', 'month', 'year'].indexOf(val) !== -1
      ),
      default: 'month'
    }
  }
};
</script>
