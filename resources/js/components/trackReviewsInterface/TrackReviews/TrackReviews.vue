<template>
  <div class="track-list-reviews-entry">
    <template v-if="track">
      <TrackReviewHeader :track-id="trackId" />

      <!--TODO: make a slot for comments-->

      <div class="track-list-reviews-entry__player-reviews-section">
        <span class="h4 track-list-reviews-entry__d-track-name">
          {{ track.trackName }}
        </span>

        <span class="track-list-reviews-entry__d-singer">
          {{ track.singer }}
        </span>

        <div class="track-list-reviews-entry__player">
          <!--TODO: player component goes here-->
        </div>

        <TrackReview
          v-for="review in reviews"
          :key="review.id"
          class="track-list-reviews-entry__track-review"
          :reviewer-id="review.user.id"
          :reviewer="review.user.username || 'Anonymous'"
          :reviewer-avatar="
            review.user.avatar.filter(
              avatar => avatar.size === 'size_56x56'
            )[0].url
          "
          :comment="review.comment"
          :date="review.createdAt"
        />

        <div class="track-reviews__loader">
          <slot v-if="showLoader" name="scroll-loader" />
        </div>
      </div>
    </template>
    <slot v-else name="main-loader" />
  </div>
</template>

<script>
import anonymousAvatar from 'images/anonymous-avatar.png';
import TrackReview from '../TrackReview';
import TrackReviewHeader from '../TrackReviewHeader';
import gql from './gql';

export default {
  components: {
    TrackReview,
    TrackReviewHeader
  },

  props: {
    trackId: {
      type: Number,
      required: true
    },
    reviews: {
      type: Array,
      required: true
    },
    showLoader: {
      type: Boolean,
      default: false
    }
  },

  data() {
    return {
      track: null,
      anonymousAvatar
    };
  },

  apollo: {
    track() {
      return {
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
  src="./TrackReviews.scss"
/>
