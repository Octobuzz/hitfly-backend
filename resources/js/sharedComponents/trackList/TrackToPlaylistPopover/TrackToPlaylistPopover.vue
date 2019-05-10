<template>
  <v-popover
    ref="popover"
    popover-base-class="track-to-playlist-popover"
    popover-wrapper-class="track-to-playlist-popover__wrapper"
    popover-inner-class="track-to-playlist-popover__inner"
    popover-arrow-class="track-to-playlist-popover__arrow"
    :placement="popoverPlacement"
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
import TrackToPlaylist from '../TrackToPlaylist';

const MOBILE_WIDTH = 767;

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
      popover: {
        desktop: {
          placement: 'left-start',
          popperOptions: { modifiers: { offset: { offset: '-30%p' } } },
        },
        mobile: {
          placement: 'bottom-end',
          popperOptions: {
            modifiers: {
              flip: { enabled: false },
              preventOverflow: { enabled: false }
            }
          }
        }
      },
      track: null,
      isFetching: true,
      newPlaylistTitle: ''
    };
  },

  computed: {
    popoverPlacement() {
      return this.windowWidth > MOBILE_WIDTH
        ? this.popover.desktop.placement
        : this.popover.mobile.placement;
    },

    popperOptions() {
      return this.windowWidth > MOBILE_WIDTH
        ? this.popover.desktop.popperOptions
        : this.popover.mobile.popperOptions;
    },
  },

  methods: {
    fetchPlaylistList() {
      this.$refs.playlistMenu.fetchPlaylistList();
    },

    onTrackAdded(addedTrack, toPlaylist) {
      console.log('track added');


      setTimeout(() => {
        this.$refs.closeButton.click();

        setTimeout(() => {
          this.$message(
            `Трек добавлен в "${toPlaylist.title}"`,
            'info',
            { timeout: 2000 }
          );
        }, 200);
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
  src="./TrackToPlaylistPopover.scss"
/>
