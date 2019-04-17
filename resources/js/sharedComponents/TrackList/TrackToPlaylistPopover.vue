<template>
  <v-popover
    ref="popover"
    popover-base-class="track-to-playlist-popover"
    popover-wrapper-class="track-to-playlist-popover__wrapper"
    popover-inner-class="track-to-playlist-popover__inner"
    popover-arrow-class="track-to-playlist-popover__arrow"
    placement="left-start"
    :popper-options="popperOptions"
    :auto-hide="true"
    @show="fetchPlaylistList"
  >
    <slot/>

    <button
      ref="closeButton"
      v-close-popover
      style="display: none;"
    />

    <template #popover>
      <span class="track-to-playlist-popover__header">
        Добавить в плейлист
      </span>

      <hr class="track-to-playlist-popover__delimiter">

      <TrackToPlaylist
        ref="playlistMenu"
        :track-id="trackId"
        @track-added="onTrackAdded"
      />
    </template>
  </v-popover>
</template>

<script>
import gql from './gql';
import TrackToPlaylist from './TrackToPlayList.vue';

export default {
  components: {
    TrackToPlaylist
  },

  props: {
    trackId: {
      type: Number,
      required: true
    }
  },

  data() {
    return {
      popperOptions: { modifiers: { offset: { offset: '-30%p' } } },
      track: null,
      isFetching: true,
      playlistList: [],
      newPlaylistTitle: ''
    };
  },

  methods: {
    fetchPlaylistList() {
      this.$refs.playlistMenu.fetchPlaylistList();
    },
    onTrackAdded(addedTrack, toPlaylist) {
      setTimeout(() => {
        this.$refs.closeButton.click();
      }, 200);
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
  lang="scss"
>
// popover cannot use scoped styles

.track-to-playlist-popover {
  $bg-color: #222;

  display: block !important;
  min-width: 250px;
  border-radius: 5px;
  z-index: 10000;
  background: $bg-color;
  box-shadow: 0 0 30px 5px rgba(black, .1);

  &__wrapper {
    padding: 24px;
  }

  &__inner {
    background: $bg-color;
    color: black;
    padding: 0;
  }

  &__arrow {
    position: absolute;
    width: 0;
    height: 0;
    right: -5px;
    top: calc(50% - 5px);
    margin: 5px 0;
    border: {
      width: 5px 0 5px 5px;
      style: solid;
      top-color: transparent !important;
      right-color: transparent !important;
      bottom-color: transparent !important;
      left-color: $bg-color;
    }
    z-index: 990;
  }

  &__header {
    font-family: "GothamPro_bold", serif;
    font-size: 14px;
    color: white;
    display: block;
    padding: 12px 0 18px 0;
  }

  &__delimiter {
    height: 1px;
    background: #424242;
    border: none;
  }

  &[x-placement^="left"] {
    margin-right: 5px;
  }

  &[aria-hidden='true'] {
    visibility: hidden;
    opacity: 0;
    transition: opacity .2s, visibility .2s;
  }

  &[aria-hidden='false'] {
    visibility: visible;
    opacity: 1;
    transition: opacity .2s;
  }
}
</style>
