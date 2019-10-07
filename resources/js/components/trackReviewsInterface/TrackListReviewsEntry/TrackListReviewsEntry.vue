<template>
  <div :class="['track-list-reviews-entry', $attrs.class]">
    <TrackReviewHeader :track-id="trackId" />

    <!--TODO: make a slot for comments-->

    <div v-if="track" class="track-list-reviews-entry__player-reviews-section">
      <span class="h4 track-list-reviews-entry__d-track-name">
        {{ track.trackName }}
      </span>

      <span class="track-list-reviews-entry__d-singer">
        {{ track.singer }}
      </span>

      <div class="track-list-reviews-entry__player">
        <WavePlayer />
      </div>

      <TrackReview
        v-for="comment in track.comments.slice(0, 3)"
        :key="comment.id"
        class="track-list-reviews-entry__track-review"
        :reviewer-id="comment.user.id"
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
        v-if="track.comments.length > 0"
        :to="trackReviewsLink"
        class="track-list-reviews-entry__more-reviews-button"
      >
        Все отзывы к этой песне
      </router-link>
    </div>
  </div>
</template>

<script>
import { mapGetters } from 'vuex';
import anonymousAvatar from 'images/anonymous-avatar.png';
import WavePlayer from 'components/WavePlayer';
import TrackReview from '../TrackReview';
import TrackReviewHeader from '../TrackReviewHeader';
import gql from './gql';

export default {
  components: {
    TrackReview,
    TrackReviewHeader,
    WavePlayer
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

  computed: {
    trackReviewsLink() {
      const prefix = this.$route.fullPath.split('/').slice(0, -1).join('/');
      if(prefix.length === 0) {
        return `user/${this.track.user.id}/reviews/${this.trackId}`;
      } else {
        return `${prefix}/reviews/${this.trackId}`;
      }
    },

    ...mapGetters(['apolloClient'])
  },

  apollo: {
    track() {
      return {
        client: this.apolloClient,
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
