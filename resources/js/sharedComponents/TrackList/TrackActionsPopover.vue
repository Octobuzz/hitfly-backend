<template>
  <v-popover
    popover-base-class="track-actions-popover"
    popover-wrapper-class="track-actions-popover__wrapper"
    popover-inner-class="track-actions-popover__inner"
    popover-arrow-class="track-actions-popover__arrow"
    placement="left-start"
    :popper-options="{ modifiers: { offset: { offset: '-30%p' } } }"
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
      <!--TODO: use "track.album.cover" as src-->
      <img
        :src="'/images/playlist3.png'"
        alt="Album cover"
        class="track-actions-popover__album-cover"
      >
      <div class="track-actions-popover__header">
        <span class="track-actions-popover__album-song">
          {{ track.trackName }}
        </span>
        <span class="track-actions-popover__album-author">
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
          @click="addTrackToFavourites"
        >
          Добавить в любимые треки
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
        v-if="inPlaylistMenu"
        class="track-actions-popover__add-playlist-header"
      >
        Добавить в плейлист
      </span>

      <TrackToPlaylist
        v-if="inPlaylistMenu"
        :track="track"
        :playlist-list="playlistList"
        :is-fetching="isFetching"
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
import TrackToPlaylist from './TrackToPlayList.vue';

export default {
  components: {
    TrackToPlaylist
  },

  props: {
    track: {
      type: Object,
      required: true
    }
  },

  data() {
    return {
      inPlaylistMenu: false,
      playlistList: [],
      isFetching: true
    };
  },

  computed: {
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

    addTrackToFavourites() {

    },

    enterPlaylistMenu() {
      this.fetchPlaylistList();

      // have problem capturing click:
      // popover becomes hidden when inPlayListMenu updates (hides) menu div
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
      }, 200);
    },

    fetchPlaylistList() {
      this.$apollo.addSmartQuery('playlistList', {
        query: gql.query.PLAYLIST_LIST,
        manual: true,
        result(res) {
          const { data: { collections }, networkStatus } = res;

          if (networkStatus === 7) {
            this.playlistList = collections.data;
            this.isFetching = false;
          }
        },
        error(err) {
          console.log(err);
        },
      });
    }
  }
};
</script>

<style
  lang="scss"
  src="./TrackActions.scss"
/>
