<template>
  <v-popover
    ref="popover"
    :popover-base-class="[
      'track-to-playlist-popover',
      `track-to-playlist-popover_breakpoint-${positionChangeBreakpoint}`
    ].join(' ')"
    popover-wrapper-class="track-to-playlist-popover__wrapper"
    popover-inner-class="track-to-playlist-popover__inner"
    popover-arrow-class="track-to-playlist-popover__arrow"
    :placement="popoverPlacement"
    :popper-options="popperOptions"
    :auto-hide="true"
    @show="fetchPlaylistList"
  >
    <slot />

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

export default {
  components: {
    TrackToPlaylist
  },

  props: {
    isFooter: {
      type: Boolean,
      required: false,
      default: false
    },
    trackId: {
      type: Number,
      required: true
    },
    positionChangeBreakpoint: {
      type: Number,
      default: 767,
      validator: val => (
        [767, 1024].includes(val)
      )
    }
  },

  data() {
    return {
      popover: {
        desktop: {
          body: {
            placement: 'left-start',
            popperOptions: { modifiers: { offset: { offset: '-30%p' } } },
          },
          footer: {
            placement: 'top',
            popperOptions: {},
          }
        },
        mobile: {
          placement: 'bottom-end',
          popperOptions: {
            modifiers: {
              flip: { enabled: false },
              preventOverflow: {
                enabled: true,
                padding: 20
              }
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
      return this.windowWidth > this.positionChangeBreakpoint && !this.isFooter ? this.popover.desktop.body.placement :
        this.windowWidth > this.positionChangeBreakpoint && this.isFooter ? this.popover.desktop.footer.placement :
        this.popover.mobile.placement
    },

    popperOptions() {
      return this.windowWidth > this.positionChangeBreakpoint && !this.isFooter ? this.popover.desktop.body.popperOptions :
        this.windowWidth > this.positionChangeBreakpoint && this.isFooter ? this.popover.desktop.footer.popperOptions :
        this.popover.mobile.placement
    },
  },

  methods: {
    fetchPlaylistList() {
      this.$refs.playlistMenu.fetchPlaylistList();
    },

    onTrackAdded(addedTrack, toPlaylist) {
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
