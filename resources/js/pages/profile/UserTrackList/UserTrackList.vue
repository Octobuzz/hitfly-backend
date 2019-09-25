<template>
  <div :class="['user-track-list', containerPaddingClass]">
    <ReturnHeader class="user-track-list__return-button" />

    <template v-if="shownTrack">
      <span class="user-track-list__singer">
        {{ shownTrack.singer }}
      </span>

      <div class="user-track-list__track-section">
        <img
          :src="
            shownTrack.albumCover.filter(
              cover => cover.size === 'size_150x150'
            )[0].url
          "
          alt="Track cover"
          class="user-track-list__track-cover"
        >

        <div class="user-track-list__player-section">
          <span class="h3 user-track-list__list-title">
            {{ userId === 'me' ? 'Мои песни' : 'Песни пользователя' }}
          </span>

<!--          TODO: add tracks data-->
<!--          <span class="user-track-list__list-data">-->
<!--            stub: tracks data-->
<!--          </span>-->

          <div class="user-track-list__player">
            <WavePlayer />
          </div>
        </div>
      </div>

      <div class="user-track-list__button-section">
        <button
          @click="playTracks"
          :class="[
            'user-track-list__button',
            'user-track-list__button_listen'
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
          class="user-track-list__button"
          placement="right"
        >
          <template #auth-content>
            <AddToFavButton
              ref="addToFavButton"
              passive="standard-passive"
              hover="standard-hover"
              modifier="squared bordered"
              item-type="track"
              :item-id="shownTrack.id"
            />
          </template>

          <template #unauth-popover-trigger>
            <AddToFavButton
              ref="addToFavButton"
              passive="standard-passive"
              hover="standard-hover"
              modifier="squared bordered"
              item-type="track"
              :item-id="shownTrack.id"
              :fake="true"
            />
          </template>
        </UnauthenticatedPopoverWrapper>

        <span class="user-track-list__button user-track-list__button_more">
          <TrackActionsPopover
            :track-id="shownTrack.id"
            :show-remove-option="userId === myId"
            @press-favourite="onPressFavourite"
          >
            <IconButton
              passive="standard-passive"
              hover="standard-hover"
              modifier="squared bordered"
              :tooltip="tooltip.more"
            >
              <DotsIcon />
            </IconButton>
          </TrackActionsPopover>
        </span>
      </div>
    </template>

    <UniversalTrackList
      for-type="user"
      :for-id="userId"
      :show-remove-button="userId === 'me'"
      @initialized="onTrackListInitialized"
    />
  </div>
</template>

<script>
// When receive update from vuex for the id of currently playing track
// we should check if the track belongs to user tracks.
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
import IconButton from 'components/IconButton.vue';
import CirclePlayIcon from 'components/icons/CirclePlayIcon.vue';
import CirclePauseIcon from 'components/icons/CirclePauseIcon.vue';
import DotsIcon from 'components/icons/DotsIcon.vue';
import AddToFavButton from 'components/AddToFavouriteButton';
import WavePlayer from 'components/WavePlayer';
import UniversalTrackList from 'components/UniversalTrackList';
import TrackActionsPopover from 'components/trackList/TrackActionsPopover';
import UnauthenticatedPopoverWrapper from 'components/UnauthenticatedPopoverWrapper';
import ReturnHeader from '../ReturnHeader.vue';
import gql from './gql';

const ofNumber = arg => typeof arg === 'number';

export default {
  components: {
    WavePlayer,
    UniversalTrackList,
    ReturnHeader,
    TrackActionsPopover,
    IconButton,
    CirclePlayIcon,
    DotsIcon,
    AddToFavButton,
    CirclePauseIcon,
    UnauthenticatedPopoverWrapper
  },

  mixins: [currentPath, containerPaddingClass, playingTrackId],

  data() {
    return {
      isAuthenticated: this.$store.getters.isAuthenticated,
      playingTrack: null,
      firstTrack: null,
      firstTrackId: null,
      tooltip: {
        more: {
          content: 'Еще'
        }
      }
    };
  },

  computed: {
    currentPlaying() {
      return this.currentType.type === 'tracks'
        && this.currentType.id === this.currentId
        && this.$store.getters['player/isPlaying'];
    },

    currentType() {
      return this.$store.getters['player/getCurrentType'];
    },

    userId() {
      const pathPrefix = this.currentPath.split('/')[1];

      if (pathPrefix === 'profile') {
        return 'me';
      }

      return +this.$route.params.userId;
    },

    myId() {
      return this.$store.getters['profile/myId'];
    },

    currentId() {
      const pathPrefix = this.currentPath.split('/')[1];

      if (pathPrefix === 'profile') {
        return this.$store.getters['profile/myId'];
      }

      return +this.$route.params.userId;
    },

    shownTrack() {
      const {
        currentId,
        playingTrack,
        firstTrack
      } = this;

      if (!firstTrack) {
        return null;
      }

      if (playingTrack && playingTrack.user.id === currentId) {
        return playingTrack;
      }

      return firstTrack;
    },

    ...mapGetters(['apolloClient'])
  },

  methods: {
    onTrackListInitialized(data) {
      if (data instanceof Array === false
        || data.length === 0) return;

      this.firstTrackId = data[0].id;
    },

    onPressFavourite() {
      this.$refs.addToFavButton.$el.dispatchEvent(new Event('click'));
    },

    playTracks(){
      if(this.currentPlaying){
        this.$store.commit('player/pausePlaying');
      }else{
        if(this.currentType.type === 'tracks' && this.currentType.id === this.currentId) {
          this.$store.commit('player/startPlaying');
        }else{
          let userId = null;
          if(this.userId === 'me'){
            userId = this.$store.getters['profile/myId']
          }else{
            userId = this.$route.params.userId
          };

          this.$apollo.query({
            client: this.apolloClient,
            query: gql.query.QUEUE_TRACKS,
            variables: {
              isAuthenticated: this.isAuthenticated,
              pageLimit: 30,
              pageNumber: 1,
              filters: {
                userId
              }
            },
          })
          .then(response => {
            let data = {
              'type': 'tracks',
              'id': this.currentId
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
            console.log(error);
          })
        }
      }
    }
  },

  apollo: {
    // TODO: user/my tracks data

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

    firstTrack() {
      return {
        client: this.apolloClient,
        query: gql.query.TRACK,
        variables() {
          return {
            isAuthenticated: this.isAuthenticated,
            id: this.firstTrackId
          };
        },
        update({ track }) {
          return track;
        },
        error(err) {
          console.dir(err);
        },
        skip() {
          return !ofNumber(this.firstTrackId);
        }
      };
    }
  }
};
</script>

<style
  scoped
  lang="scss"
  src="./UserTrackList.scss"
/>
