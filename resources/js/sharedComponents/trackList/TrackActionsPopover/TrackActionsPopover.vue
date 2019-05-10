<template>
  <v-popover
    popover-base-class="track-actions-popover"
    popover-wrapper-class="track-actions-popover__wrapper"
    popover-inner-class="track-actions-popover__inner"
    popover-arrow-class="track-actions-popover__arrow"
    :placement="popoverPlacement"
    :popper-options="popperOptions"
    :auto-hide="true"
    @auto-hide="leavePlaylistMenu(300)"
  >
    <slot/>

    <button
      ref="closeButton"
      v-close-popover
      style="display: none;"
    />

    <template #popover>
      <img
        :src="albumCoverUrl"
        alt="Album cover"
        class="track-actions-popover__album-cover"
      >
      <div class="track-actions-popover__header">
        <span class="track-actions-popover__album-song">
          {{ track.trackName }}
        </span>
        <span
          v-if="track.album"
          class="track-actions-popover__album-author"
        >
          {{ track.album.author }}
        </span>
      </div>

      <hr class="track-actions-popover__delimiter">

      <div
        v-if="!inPlaylistMenu"
        class="track-actions-popover__menu"
      >
        <!--TODO: use interactive elements-->
        <span
          class="track-actions-popover__menu-item"
        >
          Слушать далее
        </span>
        <span
          class="track-actions-popover__menu-item"
        >
          Добавить в список воспроизведения
        </span>
        <span
          class="track-actions-popover__menu-item"
          @click="enterPlaylistMenu"
        >
          Добавить в плейлист
        </span>
        <span
          class="track-actions-popover__menu-item"
          @click="onFavouritePress"
        >
          <span v-if="!track.userFavourite">
            Добавить в любимые треки
          </span>
          <span v-if="track.userFavourite">
            Убрать из любимых треков
          </span>
        </span>
        <span
          class="track-actions-popover__menu-item"
        >
          Следить за автором
        </span>
        <span
          class="track-actions-popover__menu-item"
        >
          Удалить из списка воспроизведения
        </span>
      </div>

      <span
        v-if="!inPlaylistMenu"
        class="track-actions-popover__tell-problem"
      >
        Сообщить о проблеме
      </span>
      <span
        v-else
        class="track-actions-popover__add-playlist-header"
      >
        Добавить в плейлист
      </span>

      <TrackToPlaylist
        v-show="inPlaylistMenu"
        ref="playlistMenu"
        :track-id="trackId"
        @track-added="onTrackAdded"
      />

      <span
        v-if="inPlaylistMenu"
        class="track-actions-popover__go-back-button"
        @click="leavePlaylistMenu(50)"
      >
        Назад
      </span>
    </template>
  </v-popover>
</template>

<script>
import { mapGetters } from 'vuex';
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
      inPlaylistMenu: false,
      isFetching: true
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

    albumCoverUrl() {
      return this.track.cover
        .filter(cover => cover.size === 'size_48x48')[0].url;
    },

    player() {
      const getters = mapGetters('player', [
        'isPlaying',
        'currentTrack'
      ]);
      const getterKeys = Object.keys(getters);

      return getterKeys.reduce((acc, key) => {
        acc[key] = getters[key].call(this);

        return acc;
      }, {});
    }
  },

  methods: {
    playNext() {

    },

    onFavouritePress() {
      this.$refs.closeButton.click();
      setTimeout(() => {
        this.$emit('press-favourite');
      }, 50);
    },

    enterPlaylistMenu() {
      this.$refs.playlistMenu.fetchPlaylistList();

      // have problem capturing click:
      // popover becomes hidden when inPlaylistMenu updates (hides) menu div
      // (consider the case when playlist list is empty)

      setTimeout(() => {
        this.inPlaylistMenu = true;
      }, 50);
    },

    leavePlaylistMenu(delay) {
      setTimeout(() => {
        this.inPlaylistMenu = false;
      }, delay);
    },

    onTrackAdded(addedTrack, toPlaylist) {
      setTimeout(() => {
        this.$refs.closeButton.click();
        this.leavePlaylistMenu(300);

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
  src="./TrackActionsPopover.scss"
/>
