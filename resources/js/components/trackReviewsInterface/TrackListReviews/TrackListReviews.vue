<template>
  <div class="track-list-reviews">
    <slot name="header" />

    <TrackListReviewsEntry
      v-for="trackId in trackIdList"
      :key="trackId"
      :track-id="trackId"
      :commented-in-period="commentedInPeriod"
      class="track-list-reviews__entry"
    />
    <slot name="loader" />
  </div>
</template>

<script>
import TrackListReviewsEntry from '../TrackListReviewsEntry';

export default {
  components: {
    TrackListReviewsEntry
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

<style
  scoped
  lang="scss"
  src="./TrackListReviews.scss"
/>
