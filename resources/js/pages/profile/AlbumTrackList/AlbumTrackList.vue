<template>
  <div :class="['album-track-list', containerPaddingClass]">
    <ReturnHeader class="album-track-list__return-button" />

    <template v-if="albumFetched">
      <span class="album-track-list__singer">
        {{ album.title }}
      </span>

      <div
        v-if="shownTrack && trackListDataExists"
        class="album-track-list__track-section"
      >
        <img
          :src="
            shownTrack.cover.filter(
              cover => cover.size === 'size_150x150'
            )[0].url
          "
          alt="Track cover"
          class="album-track-list__track-cover"
        >

        <div class="album-track-list__player-section">
          <span class="h3 album-track-list__album-title">
            {{ shownTrack.trackName }}
          </span>
          <span class="album-track-list__album-data">
            <span class="album-track-list__total-tracks-info">
              {{ totalTracksInfo(album.tracksCount) }}
            </span>
            {{ totalDurationInfo('hours', album.tracksTime) }}
            {{ totalDurationInfo('mins', album.tracksTime) }}
          </span>

          <div class="album-track-list__player">
            <WavePlayer />
          </div>
        </div>
      </div>

      <div v-if="!trackListDataExists">
        <img
          :src="
            album.cover.filter(
              cover => cover.size === 'size_150x150'
            )[0].url
          "
          alt="Track cover"
          class="album-track-list__track-cover"
        >
      </div>

      <div class="album-track-list__button-section">
        <button
          @click="playAlbum"
          :class="[
            'album-track-list__button',
            'album-track-list__button_listen',
            { 'album-track-list__button_disabled': !trackListDataExists }
          ]"
        >
          <template v-if="currentPlaying">
            <CirclePauseIcon />
            Пауза
          </template>
          <template v-else>
            <CirclePlayIcon />
            Слушать
          </template>
        </button>

        <UnauthenticatedPopoverWrapper
          class="album-track-list__button"
          placement="right"
        >
          <template #auth-content>
            <AddToFavButton
              ref="addToFavButton"
              passive="standard-passive"
              hover="standard-hover"
              modifier="squared bordered"
              item-type="album"
              :item-id="albumId"
            />
          </template>

          <template #unauth-popover-trigger>
            <AddToFavButton
              ref="addToFavButton"
              passive="standard-passive"
              hover="standard-hover"
              modifier="squared bordered"
              item-type="album"
              :item-id="albumId"
              :fake="true"
            />
          </template>
        </UnauthenticatedPopoverWrapper>

        <AlbumPopover
          :album-id="albumId"
          :hide-player-actions="!trackListDataExists"
          @press-favourite="onPressFavourite"
          @album-removed="goBack"
        >
          <IconButton
            class="album-track-list__button album-track-list__button_more"
            passive="standard-passive"
            hover="standard-hover"
            modifier="squared bordered"
            :tooltip="tooltip.more"
          >
            <DotsIcon />
          </IconButton>
        </AlbumPopover>
      </div>
    </template>

    <UniversalTrackList
      for-type="album"
      :for-id="albumId"
      :show-remove-button="showRemoveButton"
      @initialized="onTrackListInitialized"
      @tracks-removed="refetchTotalInfo"
    />
  </div>
</template>

<script>
// When receive update from vuex for the id of currently playing track
// we should check if the track album is the same as the album in question.
//
// If true, we should update current data regarding to the track.
// If false, we should ignore.
//
// We also have a case where we visit the page while already listening a track
// from the album. This case will be handled automatically thanks to reactivity.

import { mapGetters } from 'vuex';
import currentPath from 'mixins/currentPath';
import containerPaddingClass from 'mixins/containerPaddingClass';
import playingTrackId from 'mixins/playingTrackId';
import totalTracksInfo from 'mixins/totalInfoFormatting';
import IconButton from 'components/IconButton.vue';
import CirclePlayIcon from 'components/icons/CirclePlayIcon.vue';
import CirclePauseIcon from 'components/icons/CirclePauseIcon.vue';
import DotsIcon from 'components/icons/DotsIcon.vue';
import AddToFavButton from 'components/AddToFavouriteButton';
import WavePlayer from 'components/WavePlayer';
import UniversalTrackList from 'components/UniversalTrackList';
import AlbumPopover from 'components/AlbumPopover';
import UnauthenticatedPopoverWrapper from 'components/UnauthenticatedPopoverWrapper';
import ReturnHeader from '../ReturnHeader.vue';
import gql from './gql';

const ofNumber = arg => typeof arg === 'number';

export default {
  components: {
    WavePlayer,
    UniversalTrackList,
    ReturnHeader,
    AlbumPopover,
    IconButton,
    CirclePlayIcon,
    DotsIcon,
    AddToFavButton,
    CirclePauseIcon,
    UnauthenticatedPopoverWrapper
  },

  mixins: [
    currentPath,
    containerPaddingClass,
    playingTrackId,
    totalTracksInfo,
  ],

  data() {
    return {
      playingTrack: null,
      shownTrack: null,
      firstAlbumTrack: null,
      firstAlbumTrackId: null,
      newVar: String,
      album: null,
      albumFetched: false,
      trackListDataExists: false,
      tooltip: {
        more: {
          content: 'Еще'
        }
      }
    };
  },

  computed: {
    currentPlaying() {
      return this.$store.getters['player/getCurrentType'].type === 'album' && this.$store.getters['player/getCurrentType'].id === this.albumId && this.$store.getters['player/isPlaying'];
    },
    currentType() {
      return this.$store.getters['player/getCurrentType'];
    },
    albumId() {
      return +this.$route.params.albumId;
    },

    showRemoveButton() {
      const pathPrefix = this.currentPath.split('/')[1];

      switch (pathPrefix) {
        case 'profile':
          return true;

        case 'user':
          return false;

        case 'music-group':
          // TODO: check if the group belongs to current user
          return false;

        default:
          return false;
      }
    },

    ...mapGetters(['isAuthenticated', 'apolloClient'])
  },

  watch: {
    playingTrackId: {
      handler() {
        this.$nextTick(() => {
          this.updateShownTrack();
        });
      },
      immediate: true
    },

    'album.tracksCount': function collelctionTracksCount(count) {
      if (count === 0) {
        this.trackListDataExists = false;
      }
    }
  },

  methods: {
    onTrackListInitialized(data) {
      if (data instanceof Array === false
          || data.length === 0) {
        this.trackListDataExists = false;

        return;
      }
      this.trackListDataExists = true;
      this.firstAlbumTrackId = data[0].id;
    },

    onPressFavourite() {
      this.$refs.addToFavButton.$el.dispatchEvent(new Event('click'));
    },

    goBack() {
      this.$router.go(-1);
    },

    updateShownTrack() {
      const getShownTrack = () => {
        const {
          playingTrack,
          firstAlbumTrack,
          albumId
        } = this;

        if (!firstAlbumTrack) {
          return null;
        }

        if (playingTrack && playingTrack.album.id === albumId) {
          return playingTrack;
        }

        return firstAlbumTrack;
      };

      this.shownTrack = getShownTrack();
    },

    refetchTotalInfo() {
      this.$apollo.query({
        client: this.apolloClient,
        query: gql.query.ALBUM_TOTAL_INFO,
        fetchPolicy: 'network-only',
        variables: {
          id: this.albumId
        },
        error(err) {
          console.dir(err);
        }
      });
    },

    playAlbum(){
      // prevent attempt to listen nonexistent track
      if (!this.shownTrack) return;

      if(this.currentPlaying){
        this.$store.commit('player/pausePlaying');
      }else{
        if(this.currentType.type === 'album' && this.currentType.id === this.albumId) {
          this.$store.commit('player/startPlaying');
        }else{
          this.$apollo.provider.clients[this.apolloClient].query({
            query: gql.query.QUEUE_TRACKS,
            variables: {
              isAuthenticated: this.isAuthenticated,
              pageLimit: 30,
              pageNumber: 1,
              filters: {
                albumId: this.albumId
              }
            },
          })
          .then(response => {
            let data = {
              'type': 'album',
              'id': this.albumId
            };
            this.$store.commit('player/pausePlaying');
            this.$store.commit('player/changeCurrentType', data);
            this.$store.commit('player/pickTrack', response.data.tracks.data[0]);
            let arrayTr = response.data.tracks.data.map(data => {
              return data.id;
            });
            this.$store.commit('player/pickPlaylist', arrayTr);
          })
          .catch(error => {
            console.log('queue error', error);
          })
        }
      }
    }
  },

  apollo: {
    album() {
      return {
        client: this.apolloClient,
        query: gql.query.ALBUM,
        variables() {
          return {
            isAuthenticated: this.isAuthenticated,
            id: this.albumId
          };
        },
        update({ album }) {
          this.albumFetched = true;

          return album;
        },
        error(err) {
          console.dir(err);
        }
      };
    },

    playingTrack() {
      return {
        client: this.apolloClient,
        query: gql.query.TRACK,
        variables() {
          return {
            isAuthenticated: this.isAuthenticated,
            id: this.playingTrackId
          };
        },
        update({ track }) {
          return track;
        },
        error(err) {
          console.dir(err);
        },
        skip() {
          return !ofNumber(this.playingTrackId);
        }
      };
    },

    firstAlbumTrack() {
      return {
        client: this.apolloClient,
        query: gql.query.TRACK,
        variables() {
          return {
            isAuthenticated: this.isAuthenticated,
            id: this.firstAlbumTrackId
          };
        },
        update({ track }) {
          this.$nextTick(() => {
            this.updateShownTrack();
          });

          return track;
        },
        error(err) {
          console.dir(err);
        },
        skip() {
          return !ofNumber(this.firstAlbumTrackId);
        }
      };
    }
  }
};
</script>

<style
  scoped
  lang="scss"
  src="./AlbumTrackList.scss"
/>
