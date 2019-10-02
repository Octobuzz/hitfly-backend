<template>
  <div :class="['collection-track-list', containerPaddingClass]">
    <ReturnHeader class="collection-track-list__return-button" />

    <template v-if="collectionFetched">
      <span class="collection-track-list__singer">
        {{ collection.title }}
      </span>

      <div
        v-if="shownTrack && trackListDataExists"
        class="collection-track-list__track-section"
      >
        <img
          :src="
            shownTrack.cover.filter(
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
          <span class="collection-track-list__collection-data">
            <span class="collection-track-list__total-tracks-info">
              <!--TODO: change when the api is ready-->
              {{ totalTracksInfo(collection.countTracks) }}
            </span>
            <!--{{ totalDurationInfo('hours', collection.tracksTime) }}-->
            <!--{{ totalDurationInfo('mins', collection.tracksTime) }}-->
          </span>

          <div class="collection-track-list__player">
            <WavePlayer />
          </div>
        </div>
      </div>

      <div v-if="!trackListDataExists">
        <img
          :src="
            collection.image.filter(
              cover => cover.size === 'size_150x150'
            )[0].url
          "
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

        <AddToFavButton
          ref="addToFavButton"
          class="collection-track-list__button"
          passive="standard-passive"
          hover="standard-hover"
          modifier="squared bordered"
          item-type="collection"
          :item-id="collectionId"
        />

        <CollectionPopover
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
        </CollectionPopover>
      </div>
    </template>

    <UniversalTrackList
      for-type="collection"
      :for-id="collectionId"
      :show-remove-button="showRemoveButton"
      @initialized="onTrackListInitialized"
      @tracks-removed="refetchTotalInfo"
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
    AddToFavButton
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
    currentPlaying() {
      return this.currentType.type === 'collection' && this.currentType.id === this.collectionId && this.$store.getters['player/isPlaying'];
    },
    currentType() {
      return this.$store.getters['player/getCurrentType'];
    },
    collectionId() {
      const { playlistId, setId } = this.$route.params;

      return +(playlistId || setId);
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
    }
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
          variables: { id }
        });
        const playingTrackBelongsToCollectionQuery = this.$apollo.query({
          query: gql.query.TRACK_BELONGS_TO_COLLECTION,
          variables: {
            trackId: id,
            collectionId: this.collectionId
          }
        });

        Promise.all([playingTrackQuery, playingTrackBelongsToCollectionQuery])
          .then(([
            { data: { track: playingTrack } },
            { data: { trackBelongsCollection: { inCollection } } }
          ]) => {
            this.playingTrack = playingTrack;
            this.playingTrackBelongsToCollection = inCollection;
            console.log(this.playingTrack);

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

      this.firstCollectionTrackId = data[0].id;
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
          playingTrackBelongsToCollection,
          firstCollectionTrack
        } = this;

        // collection and its first track aren't fetched yet
        if (!firstCollectionTrack) {
          return null;
        }

        if (playingTrackBelongsToCollection) {
          return playingTrack;
        }

        return firstCollectionTrack;
      };

      this.shownTrack = getShownTrack();
    },

    refetchTotalInfo() {
      this.$apollo.query({
        query: gql.query.COLLECTION_TOTAL_INFO,
        fetchPolicy: 'network-only',
        variables: { id: this.collectionId },
        error(err) {
          console.dir(err);
        }
      });
    },

    playCollection(){
      // prevent attempt to listen nonexistent track
      if (!this.shownTrack) return;

      if(this.currentPlaying){
        this.$store.commit('player/pausePlaying');
      }else{
        if(this.currentType.type === 'collection' && this.currentType.id === this.collectionId) {
          this.$store.commit('player/startPlaying');
        }else{
          this.$apollo.provider.defaultClient.query({
            query: gql.query.TRACKS,
            variables: {
              pageLimit: 30,
              pageNumber: 1,
              filters: {
                collectionId: this.collectionId
              }
            },
          })
          .then(response => {
            let data = {
              'type': 'collection',
              'id': this.collectionId
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
    collection: {
      query: gql.query.COLLECTION,
      variables() {
        return {
          id: this.collectionId
        };
      },
      update({ collection }) {
        this.collectionFetched = true;

        return collection;
      },
      error(err) {
        console.dir(err);
      }
    },

    firstCollectionTrack: {
      query: gql.query.TRACK,
      variables() {
        return {
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
    }
  },
};
</script>

<style
  scoped
  lang="scss"
  src="./CollectionTrackList.scss"
/>
