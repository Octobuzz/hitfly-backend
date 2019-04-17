<template>
  <div class="track-list-entry">
    <span class="track-list-entry__index">
      {{ index }}
    </span>

    <button
      class="track-list-entry__album"
      @click="onAlbumPress"
    >
      <img
        :src="track.album.cover"
        alt="Album cover"
      >
    </button>

    <AddToFavouriteButton
      ref="addToFavButton"
      passive="secondary-passive"
      hover="secondary-hover"
      item-type="track"
      :item-id="trackId"
    />

    {{ track.trackName }}
    {{ track.album.author }}

    <TrackToPlaylistPopover :track-id="trackId">
      <IconButton
        passive="secondary-passive"
        hover="secondary-hover"
      >
        <PlusIcon/>
      </IconButton>
    </TrackToPlaylistPopover>

    <TrackActionsPopover
      :track-id="trackId"
      @press-favourite="onFavouritePress"
    >
      <IconButton
        passive="secondary-passive"
        hover="secondary-hover"
      >
        <DotsIcon/>
      </IconButton>
    </TrackActionsPopover>

    <IconButton
      passive="secondary-passive"
      hover="secondary-hover"
    >
      <CrossIcon/>
    </IconButton>
  </div>
</template>

<script>
import AddToFavouriteButton from 'components/AddToFavouriteButton.vue';
import IconButton from 'components/IconButton.vue';
import DotsIcon from 'components/icons/DotsIcon.vue';
import PlusIcon from 'components/icons/PlusIcon.vue';
import CrossIcon from 'components/icons/CrossIcon.vue';
import gql from './gql';
import TrackToPlaylistPopover from './TrackToPlaylistPopover.vue';
import TrackActionsPopover from './TrackActionsPopover.vue';

export default {
  components: {
    TrackToPlaylistPopover,
    TrackActionsPopover,
    AddToFavouriteButton,
    IconButton,
    DotsIcon,
    PlusIcon,
    CrossIcon
  },

  props: {
    trackId: {
      type: Number,
      required: true
    },
    index: {
      type: Number,
      required: true
    }
  },

  data() {
    return {
      track: null,
      buttonActive: false
    };
  },

  methods: {
    onAlbumPress() {
      console.log('album pressed');
    },
    onFavouritePress() {
      this.$refs.addToFavButton.onPress();
    }
  },

  apollo: {
    track() {
      return {
        query: gql.query.TRACK,
        variables: {
          id: this.trackId,
        },
        update: ({ track }) => track,
        error(error) {
          console.log(error);
        }
      };
    }
  }
};
</script>

<style
  scoped
  lang="scss"
>
.track-list-entry {
  display: flex;
  align-items: center;
  height: 56px;

  &__index {
    display: flex;
    justify-content: center;
    width: 16px;
    margin-right: 8px;
  }

  &__album {
    width: 32px;
    height: 32px;
    position: relative;
    margin-right: 8px;
    border-radius: 0;
    overflow: hidden;

    background: #00c0ef; // temp

    &_paused:before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      z-index: 100;
      background: black;
      opacity: .5;
    }
  }
}

.heart {
  background: orange;
}

.favourite {
  background: cornflowerblue;
}
</style>
