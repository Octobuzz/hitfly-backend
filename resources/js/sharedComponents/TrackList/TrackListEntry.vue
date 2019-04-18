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
>
@import '../../../sass/variables';

.track-list-entry {
  display: flex;
  align-items: center;
  height: 56px;
  box-shadow: 0 1px 0 $borderColor;

  &:hover {
    background: $color_5;
  }

  &__index {
    font-family: "Gotham Pro", serif;
    font-size: 12px;
    color: $color_4;
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
    border-radius: 3px;
    overflow: hidden;

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

    & img {
      width: 40px;
      height: 40px;
    }
  }

  &__track-name,
  &__track-author {
    font-size: 14px;
    color: #231f20;
    flex-grow: 1;
    transition: .2s;
    cursor: pointer;
    user-select: none;

    &:hover {
      color:#b36fcb;
    }
  }

  &__track-name {
    font-family: "GothamPro_bold", serif;
    flex-grow: 1;
    width: 0;
  }

  &__track-author {
    font-family: "Gotham Pro", serif;
    flex-grow: 1;
    width: 0;
  }

  &__icon-button {
    margin-right: 8px;
  }

  // have to specify styles for dynamic popover content here

  &::v-deep {
    .v-popover {
      display: block;
      width: 40px;
      height: 40px;
      margin-right: 16px;
    }

    span.trigger {
      width: 40px;
      height: 40px;
    }
  }
}

@media screen and (max-width: 767px) {
  .track-list-entry {

  }
}
</style>
