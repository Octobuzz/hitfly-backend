<template>
  <div :class="['collection-track-list', containerPaddingClass]">
    <ReturnHeader class="collection-track-list__return-button" />

    <template v-if="collectionFetched && trackListDataExists && firstCollectionTrack !== null">
      <span class="collection-track-list__singer">
        {{ currentPlaylistName }}
      </span>

      <div class="collection-track-list__track-section">
        <img
          :src="shownTrack.cover.filter(
            cover => cover.size === 'size_150x150'
          )[0].url
          "
          alt="Track cover"
          class="collection-track-list__track-cover"
        >

        <div class="collection-track-list__player-section">
          <span class="h3 collection-track-list__collection-title">
            {{ shownTrack.trackName }}
          </span>

          <div class="collection-track-list__player">
            <WavePlayer />
          </div>
        </div>
      </div>

      <div v-if="!trackListDataExists">
        <img
          :src="pathToImage"
          alt="Track cover"
          :class="[
            'collection-track-list__track-cover',
            'collection-track-list__track-cover_margin-bottom'
          ]"
        >
      </div>

      <div class="collection-track-list__button-section">
        <button
          @click="playCollection"
          :class="[
            'collection-track-list__button',
            'collection-track-list__button_listen',
            { 'collection-track-list__button_disabled': !trackListDataExists }
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

        <!-- <CollectionPopover
          :collection-id="collectionId"
          :hide-player-actions="!trackListDataExists"
          @press-favourite="onPressFavourite"
          @collection-removed="goBack"
        >
          <IconButton
            class="collection-track-list__button collection-track-list__button_more"
            passive="standard-passive"
            hover="standard-hover"
            modifier="squared bordered"
            :tooltip="tooltip.more"
          >
            <DotsIcon />
          </IconButton>
        </CollectionPopover> -->
      </div>
    </template>

    <UniversalTrackList
      for-id="collection"
      :for-type="currentCollectionPath"
      :show-table-header="false"
      :show-remove-button="false"
      @initialized="onTrackListInitialized"
    />
  </div>
</template>

<script>
// When receive update from vuex for the id of currently playing track
// we should check if the track belongs to the collection in question.
//
// If true, we should update current data regarding to the track.
// The update is async since we cannot define statically if the track
// belongs to to the collection. For the time of the update last played
// track lies in place.
//
// If false, we should ignore.
//
// We also have a case where we visit the page while already listening a track
// from the collection. This case will be handled automatically thanks to reactivity.

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
import UniversalTrackList from '../UniversalTrackList';
import UnauthenticatedPopoverWrapper from 'components/UnauthenticatedPopoverWrapper';
import CollectionPopover from 'components/CollectionPopover';
import ReturnHeader from '../ReturnHeader.vue';
import gql from './gql';

const ofNumber = arg => typeof arg === 'number';

export default {
  components: {
    WavePlayer,
    UniversalTrackList,
    ReturnHeader,
    CollectionPopover,
    IconButton,
    CirclePlayIcon,
    CirclePauseIcon,
    DotsIcon,
    AddToFavButton,
    UnauthenticatedPopoverWrapper
  },

  mixins: [
    currentPath,
    containerPaddingClass,
    playingTrackId,
    totalTracksInfo
  ],

  data() {
    return {
      firstCollectionTrack: null,
      firstCollectionTrackId: null,
      collection: null,
      collectionFetched: false,
      pathChanged: false,
      trackListDataExists: false,
      playingTrackBelongsToCollection: false,
      playingTrack: null,
      shownTrack: null,
      tooltip: {
        more: {
          content: 'Еще'
        }
      }
    };
  },

  computed: {
    currentCollectionPath() {
      const pathPrefix = this.currentPath.split('/')[1];
      return pathPrefix;
    },
    currentPlaylistName() {
      let tracklistName = '';
      const pathPrefix = this.currentPath.split('/')[1];
      this.pathChanged = !this.pathChanged;
      switch(pathPrefix){
        case 'top50':
          tracklistName = "Топ 50";
          break;
        case 'listening_now':
          tracklistName = "Сейчас слушают";
          break;
        case 'weekly_top':
          tracklistName = "Открытие недели";
          break;
        case 'new_songs':
          tracklistName = "Новые песни";
          break;
      };
      return tracklistName;
    },

    currentPlaying() {
      return this.$store.getters['player/getCurrentType'].type === 'collection' && this.$store.getters['player/getCurrentType'].id === this.currentCollectionPath && this.$store.getters['player/isPlaying'];
    },

    currentType() {
      return this.$store.getters['player/getCurrentType'];
    },

    ...mapGetters(['isAuthenticated', 'apolloClient'])
  },

  watch: {
    '$route': function fetchNewTracks() {
      this.collectionFetched = false;
      let query = null;
      if(this.currentCollectionPath === 'top50') {
        query = gql.query.GET_TOP_FIFTY
      } else if (this.currentCollectionPath === 'listening_now') {
        query = gql.query.GET_LISTENED_NOW
      } else if (this.currentCollectionPath === 'weekly_top') {
        query = gql.query.WEEKLY_TOP
      } else if (this.currentCollectionPath === 'new_songs') {
        query = gql.query.QUEUE_TRACKS
      };
      this.$apollo.provider.clients[this.apolloClient].query({
        query: query,
        variables: {
          isAuthenticated: this.isAuthenticated,
          pageLimit: 50,
          pageNumber: 1,
        },
      })
      .then(response => {
        let collectionResponse = null;
        if(this.currentCollectionPath === 'top50'){
          collectionResponse = response.data.GetTopFifty.data;
        }else if (this.currentCollectionPath === 'listening_now'){
          collectionResponse = response.data.GetListenedNow.data;
        }else if (this.currentCollectionPath === 'weekly_top'){
          collectionResponse = response.data.TopWeeklyQuery.data;
        }else if (this.currentCollectionPath === 'new_songs'){
          collectionResponse = response.data.tracks.data;
        };
        this.tracks = collectionResponse;
        this.collectionFetched = true;
        return collectionResponse;
      })
      .catch(error => {
        console.log(error);
      });
    },

    playingTrackId: {
      handler(id) {
        if (!ofNumber(id)) {
          this.playingTrackBelongsToCollection = false;

          return;
        }

        const playingTrackQuery = this.$apollo.query({
          query: gql.query.TRACK,
          variables: {
            isAuthenticated: this.isAuthenticated,
            id
          }
        });
        // const playingTrackBelongsToCollectionQuery = this.$apollo.query({
        //   query: gql.query.TRACK_BELONGS_TO_COLLECTION,
        //   variables: {
        //     trackId: id,
        //     collectionId: this.collectionId
        //   }
        // });

        // Promise.all([playingTrackQuery, playingTrackBelongsToCollectionQuery])
        Promise.all([playingTrackQuery])
          .then(([
            { data: { track: playingTrack } },
            // { data: { trackBelongsCollection: { inCollection } } }
          ]) => {
            this.playingTrack = playingTrack;
            // this.playingTrackBelongsToCollection = inCollection;

            this.$nextTick(() => {
              this.updateShownTrack();
            });
          })
          .catch(err => console.dir(err));
      },
      immediate: true
    },

    'collection.countTracks': function collelctionTracksCount(count) {
      if (count === 0) {
        this.trackListDataExists = false;
      }
    },
  },

  methods: {
    onTrackListInitialized(data) {
      if (data instanceof Array === false
          || data.length === 0) {
        this.trackListDataExists = false;

        return;
      }
      this.trackListDataExists = true;
      this.firstCollectionTrackId = data[0].id;
    },

    goBack() {
      this.$router.go(-1);
    },

    updateShownTrack() {
      const getShownTrack = () => {
        const {
          playingTrack,
          // playingTrackBelongsToCollection,
          firstCollectionTrack,
          collection
        } = this;

        let idCollection = collection.map(item => item.id);

        // collection and its first track aren't fetched yet
        if (!firstCollectionTrack) {
          return null;
        }

        if (playingTrack !== null && idCollection.indexOf(playingTrack.id) != -1) {
          return playingTrack;
        }

        return firstCollectionTrack;
      };

      this.shownTrack = getShownTrack();
    },

    playCollection(){
      // prevent attempt to listen nonexistent track
      if (!this.shownTrack) return;

      if(this.currentPlaying){
        this.$store.commit('player/pausePlaying');
      }else{
        if(this.currentType.type === 'collection' && this.currentType.id === this.currentCollectionPath) {
          this.$store.commit('player/startPlaying');
        }else{
          let query = null;
          let filters = null;
          if(this.currentCollectionPath === 'top50'){
            query = gql.query.GET_TOP_FIFTY;
          } else if (this.currentCollectionPath === 'listening_now') {
            query = gql.query.GET_LISTENED_NOW
          } else if (this.currentCollectionPath === 'weekly_top') {
            query = gql.query.WEEKLY_TOP
          } else if (this.currentCollectionPath === 'new_songs') {
            query = gql.query.QUEUE_TRACKS
          };
          this.$apollo.provider.clients[this.apolloClient].query({
            query: query,
            variables: {
              isAuthenticated: this.isAuthenticated,
              pageLimit: 30,
              pageNumber: 1,
              filters: filters
            },
          })
          .then(response => {
            let collectionId = null;
            let collectionResponse = null;
            if(this.currentCollectionPath === 'top50'){
              collectionId = 'top50';
              collectionResponse = response.data.GetTopFifty;
            }else if (this.currentCollectionPath === 'listening_now'){
              collectionId = 'listening_now';
              collectionResponse = response.data.GetListenedNow;
            }else if (this.currentCollectionPath === 'weekly_top'){
              collectionId = 'weekly_top';
              collectionResponse = response.data.TopWeeklyQuery;
            }else if (this.currentCollectionPath === 'new_songs'){
              collectionId = 'new_songs';
              collectionResponse = response.data.tracks;
            };
            let data = {
              'type': 'collection',
              'id': collectionId
            };
            this.$store.commit('player/pausePlaying');
            this.$store.commit('player/changeCurrentType', data);
            this.$store.commit('player/pickTrack', collectionResponse.data[0]);
            let arrayTr = collectionResponse.data.map(data => {
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
    collection() {
      let query = null;
      if(this.currentCollectionPath === 'top50') {
        query = gql.query.GET_TOP_FIFTY
      } else if (this.currentCollectionPath === 'listening_now') {
        query = gql.query.GET_LISTENED_NOW
      } else if (this.currentCollectionPath === 'weekly_top') {
        query = gql.query.WEEKLY_TOP
      } else if (this.currentCollectionPath === 'new_songs') {
        query = gql.query.QUEUE_TRACKS
      };
      return {
        client: this.apolloClient,
        query: query,
        variables: {
          isAuthenticated: this.isAuthenticated,
          pageLimit: 50,
          pageNumber: 1
        },
        update(data) {
          let collectionResponse = null;
          if(this.currentCollectionPath === 'top50'){
            collectionResponse = data.GetTopFifty.data;
          }else if (this.currentCollectionPath === 'listening_now'){
            collectionResponse = data.GetListenedNow.data;
          }else if (this.currentCollectionPath === 'weekly_top'){
            collectionResponse = data.TopWeeklyQuery.data;
          }else if (this.currentCollectionPath === 'new_songs'){
            collectionResponse = data.tracks.data;
          };
          this.tracks = collectionResponse;
          this.collectionFetched = true;
          return collectionResponse;
        }
      }
    },

    firstCollectionTrack() {
      return {
        client: this.apolloClient,
        query: gql.query.TRACK,
        variables() {
          return {
            isAuthenticated: this.isAuthenticated,
            id: this.firstCollectionTrackId
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
          return !ofNumber(this.firstCollectionTrackId);
        }
      };
    }
  },
};
</script>

<style
  scoped
  lang="scss"
  src="./MainPageTrackList.scss"
/>
