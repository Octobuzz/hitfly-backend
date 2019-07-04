<template>
  <div :class="['track-list-reviews-entry', $attrs.class]">
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
        v-for="comment in track.comments.slice(0, 3)"
        :key="comment.id"
        class="track-list-reviews-entry__track-review"
        :reviewer="comment.user.username || 'Anonymous'"
        :reviewer-avatar="
          comment.user.avatar.filter(
            avatar => avatar.size === 'size_56x56'
          )[0].url
        "
        :comment="comment.comment"
        :date="comment.createdAt"
      />

      <router-link
        v-if="track.comments.length > 3"
        :to="`${$route.fullPath}/${trackId}`"
        class="track-list-reviews-entry__more-reviews-button"
      >
        Все отзывы к этой песне
      </router-link>
    </div>
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
    commentedInPeriod: {
      validator: val => (
        ['week', 'month', 'year'].indexOf(val) !== -1
      ),
      default: 'month'
    }
  },

  data() {
    return {
      track: null,
      queryVars: {
        id: this.trackId,
        commentedInPeriod: this.commentedInPeriod
      },
      anonymousAvatar
    };
  },

  apollo: {
    track() {
      return {
        query: gql.query.TRACK_WITH_COMMENTS,
        variables: this.queryVars,
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
  src="./TrackListReviewsEntry.scss"
/>
