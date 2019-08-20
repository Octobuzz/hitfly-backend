<template>
  <div class="review-request-list-entry">
    <TrackReviewHeader
      :product-id="productId"
      :track-id="trackId"
      @review-added="emitTrackReviewed"
    />

    <div v-if="track" class="review-request-list-entry__track-data">
      <span class="h4">
        {{ track.trackName }}
      </span>

      <span class="review-request-list-entry__singer">
        {{ track.singer }}
      </span>

      <div class="review-request-list-entry__player">
        <!--TODO: player component goes here-->
      </div>

      <TrackReviewRequestForm
        :product-id="productId"
        :track-id="trackId"
        @review-added="emitTrackReviewed"
      />
    </div>
  </div>
</template>

<script>
import TrackReviewHeader from 'components/trackReviewsInterface/TrackReviewHeader';
import TrackReviewRequestForm from '../TrackReviewRequestForm';
import gql from './gql';

export default {
  components: {
    TrackReviewHeader,
    TrackReviewRequestForm
  },
  props: {
    productId: {
      type: Number,
      required: true
    },
    trackId: {
      type: Number,
      required: true
    }
  },
  methods: {
    emitTrackReviewed() {
      this.$emit('track-reviewed');
    }
  },
  apollo: {
    track() {
      return {
        client: 'private',
        query: gql.query.TRACK,
        variables: {
          id: this.trackId
        },
        update({ track }) {
          return track;
        },
        error(err) {
          console.dir(err);
        }
      };
    }
  }
};
</script>

<style
  scoped
  lang="scss"
  src="./ReviewRequestListEntry.scss"
/>
