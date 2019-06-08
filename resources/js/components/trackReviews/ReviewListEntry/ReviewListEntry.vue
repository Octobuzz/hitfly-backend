<template>
  <div class="review-list-entry">
    <div class="review-list-entry__cover-section">
      <img
        :src="trackCoverUrl"
        alt="Track cover"
        class="review-list-entry__cover"
      >

      <div class="review-list-entry__cover-button-container">
        <TrackToPlaylistPopover :track-id="trackId">
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
      </div>
    </div>
  </div>
</template>

<script>
import IconButton from 'components/IconButton.vue';
import PlusIcon from 'components/icons/PlusIcon.vue';
import AddToFavouriteButton from 'components/AddToFavouriteButton';
import TrackToPlaylistPopover from 'components/trackList/TrackToPlaylistPopover';
import gql from './gql';

export default {
  components: {
    AddToFavouriteButton,
    IconButton,
    PlusIcon,
    TrackToPlaylistPopover
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
        actions: {
          content: 'Еще...'
        }
      }
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
          console.log(track);

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
