<template>
  <div class="track-review-header">
    <img
      :src="trackCoverUrl"
      alt="Track cover"
      class="track-review-header__cover"
    >

    <span class="h4 track-review-header__m-track-name">
      {{ track.trackName }}
    </span>

    <span class="track-review-header__m-singer">
      {{ track.singer }}
    </span>

    <div class="track-review-header__cover-button-container">
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
        ref="addToFavButton"
        item-type="track"
        :item-id="trackId"
        :with-counter="true"
      />

      <TrackActionsPopover
        :track-id="trackId"
        :show-remove-option="false"
        :position-change-breakpoint="1024"
        @press-favourite="onPressFavourite"
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
</template>

<script>
import TrackToPlaylistPopover from 'components/trackList/TrackToPlaylistPopover';
import TrackActionsPopover from 'components/trackList/TrackActionsPopover';
import AddToFavouriteButton from 'components/AddToFavouriteButton';
import IconButton from 'components/IconButton.vue';
import PlusIcon from 'components/icons/PlusIcon.vue';
import DotsIcon from 'components/icons/DotsIcon.vue';
import gql from './gql';

export default {
  components: {
    AddToFavouriteButton,
    IconButton,
    PlusIcon,
    DotsIcon,
    TrackToPlaylistPopover,
    TrackActionsPopover
  },

  props: {
    trackId: {
      type: Number,
      required: true
    }
  },

  data() {
    return {
      track: null,
      queryVars: {
        id: this.trackId,
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
      }
    };
  },

  computed: {
    trackCoverUrl() {
      return this.track.cover
        .filter(cover => cover.size === 'size_150x150')[0].url;
    }
  },

  methods: {
    onPressFavourite() {
      this.$refs.addToFavButton.$el.dispatchEvent(new Event('click'));
    }
  },

  apollo: {
    track() {
      return {
        query: gql.query.TRACK,
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
  src="./TrackRreviewHeader.scss"
/>
