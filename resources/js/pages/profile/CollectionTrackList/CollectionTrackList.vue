<template>
  <div :class="['collection-track-list', containerPaddingClass]">
    <ReturnHeader class="collection-track-list__return-button" />

    <template v-if="collectionFetched && shownTrack">
      <span class="collection-track-list__singer">
        {{ shownTrack.singer }}
      </span>

      <div class="collection-track-list__track-section">
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
            {{ collection.title }}
          </span>
          <span class="collection-track-list__collection-data">
            stub: collection data
          </span>

          <div class="collection-track-list__player" />
        </div>
      </div>

      <div class="collection-track-list__button-section">
        <button
          :class="[
            'collection-track-list__button',
            'collection-track-list__button_listen'
          ]"
        >
          <CirclePlayIcon />
          Слушать
        </button>

        <AddToFavButton
          class="collection-track-list__button"
          passive="standard-passive"
          hover="standard-hover"
          modifier="squared bordered"
          item-type="collection"
          :item-id="collectionId"
        />

        <CollectionPopover :collection-id="collectionId">
          <IconButton
            class="collection-track-list__button"
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
    />
  </div>
</template>

<script>
// When receive update from vuex for the id of currently playing track
// we should check if the track belongs to the collection in question.
//
// If true, we should update current data regarding to the track.
// If false, we should ignore.
//
// We also have a case where we visit the page while already listening a track
// from the collection. This case will be handled automatically thanks to reactivity.

import anonymousAvatar from 'images/anonymous-avatar.png';
import currentPath from 'mixins/currentPath';
import containerPaddingClass from 'mixins/containerPaddingClass';
import IconButton from 'components/IconButton.vue';
import CirclePlayIcon from 'components/icons/CirclePlayIcon.vue';
import DotsIcon from 'components/icons/DotsIcon.vue';
import AddToFavButton from 'components/AddToFavouriteButton';
import UniversalTrackList from 'components/UniversalTrackList';
import CollectionPopover from 'components/CollectionPopover';
import ReturnHeader from '../ReturnHeader.vue';
import gql from './gql';

const ofNumber = arg => typeof arg === 'number';

export default {
  components: {
    UniversalTrackList,
    ReturnHeader,
    CollectionPopover,
    IconButton,
    CirclePlayIcon,
    DotsIcon,
    AddToFavButton
  },

  mixins: [currentPath, containerPaddingClass],

  data() {
    return {
      anonymousAvatar,
      firstCollectionTrack: null,
      firstCollectionTrackId: null,
      collection: null,
      collectionFetched: false,
      playingTrackBelongsToCollection: false,
      tooltip: {
        more: {
          content: 'Еще'
        }
      }
    };
  },

  computed: {
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
    },

    shownTrack() {
      const {
        playingTrack,
        playingTrackBelongsToCollection,
        firstCollectionTrack
      } = this;

      if (!firstCollectionTrack) {
        return null;
      }

      if (playingTrackBelongsToCollection) {
        return playingTrack;
      }

      return firstCollectionTrack;
    }
  },

  watch: {
    playingTrackId: {
      handler(id) {
        // TODO: check if track belongs to current collection asynchronously

        this.$nextTick(() => this.playingTrackBelongsToCollection = false);

        // if (!ofNumber(id)) {
        //   this.playingTrackBelongsToCollection = false;
        //
        //   return;
        // }
        //
        // const playingTrack = this.$apollo.query({
        //   // fetch playing track
        // });
        // const playingTrackBelongsToCollection = this.$apollo.query({
        //   // ask if it belongs
        // });
        //
        // Promise.all([playingTrack, playingTrackBelongsToCollection])
        //   .then(([{ track }, pendingApiResponse]) => {
        //     this.playingTrack = track;
        //     this.trackBelongsToCollection = pendingApiResponse;
        //   });
      },
      immediate: true
    }
  },

  methods: {
    onTrackListInitialized(data) {
      if (data instanceof Array === false
        || data.length === 0) return;

      this.firstCollectionTrackId = data[0].id;
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
        return track;
      },
      error(err) {
        console.dir(err);
      },
      skip() {
        return !ofNumber(this.firstCollectionTrackId);
      }
    }
  }
};
</script>

<style
  scoped
  lang="scss"
  src="./CollectionTrackList.scss"
/>
