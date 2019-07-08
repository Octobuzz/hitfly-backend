<template>
  <div class="track-list-entry">
    <span class="track-list-entry__index">
      {{ index }}
    </span>

    <button
      class="track-list-entry__album"
      @click="onAlbumPress"
    >
      <img v-if="!activeTrack"
        :src="albumCoverUrl"
        alt="Album cover"
      >
      <PauseIcon v-else-if="activeTrack && this.$store.getters['player/isPlaying']"/>
      <PlayIcon v-else/>
    </button>

    <AddToFavouriteButton
      v-show="desktop"
      ref="addToFavButton"
      class="track-list-entry__icon-button"
      passive="secondary-passive"
      hover="secondary-hover"
      item-type="track"
      :item-id="trackId"
      :fake="fakeFavButton"
      @press-favourite="onFavouritePress"
    />

    <span
      v-if="desktop"
      class="track-list-entry__track-name"
    >
      {{ track.trackName }}
    </span>
    <span
      v-if="track.album && desktop"
      class="track-list-entry__track-author"
    >
      {{ track.album.author }}
    </span>

    <div
      v-if="!desktop"
      class="track-list-entry__track-data"
    >
      <span class="track-list-entry__track-name">
        {{ track.trackName }}
      </span>
      <br>
      <span
        v-if="track.album"
        class="track-list-entry__track-author"
      >
        {{ track.album.author }}
      </span>
    </div>

    <TrackToPlaylistPopover
      v-if="desktop"
      :track-id="trackId"
    >
      <IconButton
        passive="secondary-passive"
        hover="secondary-hover"
        :tooltip="tooltip.add"
      >
        <PlusIcon />
      </IconButton>
    </TrackToPlaylistPopover>

    <TrackActionsPopover
      :track-id="trackId"
      :show-remove-option="showRemoveButton"
      @press-favourite="pressFavourite"
      @remove-track="onRemovePress"
    >
      <IconButton
        passive="secondary-passive"
        hover="secondary-hover"
        :tooltip="tooltip.actions"
      >
        <DotsIcon />
      </IconButton>
    </TrackActionsPopover>

    <IconButton
      v-if="desktop && showRemoveButton"
      class="track-list-entry__icon-button"
      passive="secondary-passive"
      hover="secondary-hover"
      :tooltip="tooltip.remove"
      @press="onRemovePress"
    >
      <CrossIcon />
    </IconButton>
  </div>
</template>

<script>
import AddToFavouriteButton from 'components/AddToFavouriteButton';
import IconButton from 'components/IconButton.vue';
import DotsIcon from 'components/icons/DotsIcon.vue';
import PlusIcon from 'components/icons/PlusIcon.vue';
import CrossIcon from 'components/icons/CrossIcon.vue';
import PauseIcon from 'components/icons/PauseIcon.vue';
import PlayIcon from 'components/icons/PlayIcon.vue';
import gql from './gql';
import TrackToPlaylistPopover from '../TrackToPlaylistPopover';
import TrackActionsPopover from '../TrackActionsPopover';

const MOBILE_WIDTH = 767;

export default {
  components: {
    TrackToPlaylistPopover,
    TrackActionsPopover,
    AddToFavouriteButton,
    IconButton,
    DotsIcon,
    PlusIcon,
    CrossIcon,
    PauseIcon,
    PlayIcon
  },

  props: {
    trackId: {
      type: Number,
      required: true
    },
    index: {
      type: Number,
      required: true
    },
    fakeFavButton: {
      type: Boolean,
      default: false
    },
    showRemoveButton: {
      type: Boolean,
      default: true
    }
  },

  data() {
    return {
      track: null,
      tooltip: {
        remove: {
          content: 'Удалить из текущего списка'
        },
        add: {
          content: 'Добавить в плейлист'
        },
        actions: {
          content: 'Еще...'
        },
      }
    };
  },

  computed: {
    desktop() {
      return this.windowWidth > MOBILE_WIDTH;
    },

    albumCoverUrl() {
      return this.track.cover
        .filter(cover => cover.size === 'size_32x32')[0].url;
    },

    activeTrack() {
      return this.trackId === this.$store.getters['player/currentTrack'].id;
    },
  },

  methods: {
    onAlbumPress() {
      this.$emit('play-track', this.trackId);
    },
    onFavouritePress() {
      this.$emit('press-favourite', this.trackId);
    },
    onRemovePress() {
      this.$emit('remove-track', this.trackId);
    },
    pressFavourite() {
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
  src="./TrackListEntry.scss"
/>
