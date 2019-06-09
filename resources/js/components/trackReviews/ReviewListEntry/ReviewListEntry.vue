<template>
  <div :class="['review-list-entry', $attrs.class]">
    <div class="review-list-entry__cover-section">
      <img
        :src="trackCoverUrl"
        alt="Track cover"
        class="review-list-entry__cover"
      >

      <span class="h4 review-list-entry__m-track-name">
        {{ track.trackName }}
      </span>

      <span class="review-list-entry__m-singer">
        {{ track.singer }}
      </span>

      <div class="review-list-entry__cover-button-container">
        <TrackToPlaylistPopover
          :track-id="trackId"
          :position-change-breakpoint="1024"
        >
          <IconButton
            passive="standard-passive"
            hover="standard-hover"
            :tooltip="tooltip.add"
          >
            <PlusIcon />
          </IconButton>
        </TrackToPlaylistPopover>

        <AddToFavouriteButton
          item-type="track"
          :item-id="trackId"
          :with-counter="true"
        />

        <TrackActionsPopover
          :track-id="trackId"
          :show-remove-option="false"
          :position-change-breakpoint="1024"
        >
          <IconButton
            passive="standard-passive"
            hover="standard-hover"
            :tooltip="tooltip.more"
          >
            <DotsIcon />
          </IconButton>
        </TrackActionsPopover>
      </div>
    </div>

    <div class="review-list-entry__player-reviews-section">
      <span class="h4 review-list-entry__d-track-name">
        {{ track.trackName }}
      </span>

      <span class="review-list-entry__d-singer">
        {{ track.singer }}
      </span>

      <div class="review-list-entry__player">
        <!--TODO: player component goes here-->
      </div>

      <!--TODO: add user data in comment query on backend-->

      <TrackReview
        v-for="comment in track.comments.slice(0, 3)"
        :key="comment.id"
        class="review-list-entry__track-review"
        reviewer="Anonymous"
        :reviewer-avatar="anonymousAvatar"
        :comment="comment.comment"
        :date="comment.createdAt"
      />

      <router-link
        v-if="track.comments.length > 3"
        :to="`/profile/review/${trackId}`"
        class="review-list-entry__more-reviews-button"
      >
        Все отзывы к этой песне
      </router-link>
    </div>
  </div>
</template>

<script>
import anonymousAvatar from 'images/anonymous-avatar.png';
import IconButton from 'components/IconButton.vue';
import PlusIcon from 'components/icons/PlusIcon.vue';
import DotsIcon from 'components/icons/DotsIcon.vue';
import AddToFavouriteButton from 'components/AddToFavouriteButton';
import TrackToPlaylistPopover from 'components/trackList/TrackToPlaylistPopover';
import TrackActionsPopover from 'components/trackList/TrackActionsPopover';
import TrackReview from '../TrackReview';
import gql from './gql';

export default {
  components: {
    AddToFavouriteButton,
    IconButton,
    PlusIcon,
    DotsIcon,
    TrackToPlaylistPopover,
    TrackActionsPopover,
    TrackReview
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
      tooltip: {
        add: {
          content: 'Добавить в плейлист'
        },
        more: {
          content: 'Еще'
        },
        actions: {
          content: 'Еще...'
        }
      },
      anonymousAvatar
    };
  },

  computed: {
    trackCoverUrl() {
      return this.track.cover
        .filter(cover => cover.size === 'size_150x150')[0].url;
    }
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
  src="./ReviewListEntry.scss"
/>
