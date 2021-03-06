<template>
  <div :class="['collection-track-list', containerPaddingClass]">
    <ReturnHeader class="collection-track-list__return-button" />

    <PageHeader class="main-page-genre-track-list__header">
      {{ genre.name && genre.name.toUpperCase() }}
    </PageHeader>

    <template v-if="!collectionFetched">
      <SpinnerLoader />
    </template>
    <template v-else>
      <template v-if="trackListDataExists && firstCollectionTrack">
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
      <template v-else>
        <p>Данный список пуст</p>
      </template>
    </template>

    <UniversalTrackList
      :for-id="genreId"
      for-type="genre"
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
import PageHeader from 'components/PageHeader.vue';
import SpinnerLoader from 'components/SpinnerLoader.vue';
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
    PageHeader,
    SpinnerLoader,
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
      genre: {},
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
    genreId() {
      const pathPrefix = this.$router.history.current.path.split('/')[2];
      return Number(pathPrefix);
    },

    currentPlaying() {
      return this.currentType.type === 'genre' && this.currentType.id === this.genreId && this.$store.getters['player/isPlaying'];
    },

    currentType() {
      return this.$store.getters['player/getCurrentType'];
    },

    ...mapGetters(['isAuthenticated', 'apolloClient'])
  },

  watch: {
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

        let idCollection = collection.tracks.data.map(item => item.id);

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
        if(this.currentType.type === 'genre' && this.currentType.id === this.genreId) {
          this.$store.commit('player/startPlaying');
        }else{
          this.$apollo.provider.clients[this.apolloClient].query({
            query: gql.query.QUEUE_TRACKS,
            variables: {
              isAuthenticated: this.isAuthenticated,
              pageLimit: 50,
              pageNumber: 1,
              filters: {
                genre: this.genreId
              }
            },
          })
          .then(response => {
            let data = {
              'type': 'genre',
              'id': this.genreId
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
    genre() {
      return{
        client: this.apolloClient,
        query: gql.query.GENRES,
        update(data) {
          let genreData = data.genre.find(genre => (
            genre.id === Number(this.genreId)
          ));

          return genreData;
        }
      }
    },

    collection() {
      this.collectionFetched = false;
      return {
        client: this.apolloClient,
        query: gql.query.QUEUE_TRACKS,
        variables: {
          isAuthenticated: this.isAuthenticated,
          pageLimit: 50,
          pageNumber: 1,
          filters: {
            genre: this.genreId
          }
        },
        update(data) {

          this.tracks = data.tracks.data;
          this.collectionFetched = true;
          return data;
        }
      }
    },

    firstCollectionTrack() {
      return {
        client: this.apolloClient,
        query: gql.query.TRACK,
        fetchPolicy: 'network-only',
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
  src="./MainPageGenreTrackList.scss"
/>
