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
        :src="albumCoverUrl"
        alt="Album cover"
      >
    </button>

    <!--TODO: add plugin code to render vue components depending on the screen width-->

    <AddToFavouriteButton
      ref="addToFavButton"
      class="track-list-entry__icon-button"
      passive="secondary-passive"
      hover="secondary-hover"
      item-type="track"
      :item-id="trackId"
    />

    <span class="track-list-entry__track-name">
      {{ track.trackName }}
    </span>
    <span class="track-list-entry__track-author">
      {{ track.album.author }}
    </span>

    <TrackToPlaylistPopover :track-id="trackId">
      <IconButton
        passive="secondary-passive"
        hover="secondary-hover"
        :tooltip="tooltip.add"
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
        :tooltip="tooltip.actions"
      >
        <DotsIcon/>
      </IconButton>
    </TrackActionsPopover>

    <IconButton
      class="track-list-entry__icon-button"
      passive="secondary-passive"
      hover="secondary-hover"
      :tooltip="tooltip.remove"
      @press="onRemovePress"
    >
      <CrossIcon/>
    </IconButton>
  </div>
</template>

<script>
import AddToFavouriteButton from 'components/AddToFavouriteButton';
import IconButton from 'components/IconButton.vue';
import DotsIcon from 'components/icons/DotsIcon.vue';
import PlusIcon from 'components/icons/PlusIcon.vue';
import CrossIcon from 'components/icons/CrossIcon.vue';
import gql from './gql';
import TrackToPlaylistPopover from '../TrackToPlaylistPopover';
import TrackActionsPopover from '../TrackActionsPopover';

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
      tooltip: {
        remove: {
          content: 'Удалить из списка воспроизведения'
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
    albumCoverUrl() {
      return this.track.album.cover
        .filter(cover => cover.size === 'size_32x32')[0].url;
    }
  },

  methods: {
    onAlbumPress() {
      console.log('album pressed');
    },
    onFavouritePress() {
      this.$refs.addToFavButton.onPress();
    },
    onRemovePress() {
      console.log('remove pressed');

      this.$emit('remove', this.trackId);
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
