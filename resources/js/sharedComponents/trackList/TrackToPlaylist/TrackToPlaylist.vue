<template>
  <div class="track-to-playlist">
    <span
      v-if="isFetching"
      class="track-to-playlist__loader"
    >
      Загрузка...
    </span>

    <ul class="track-to-playlist__list">
      <li
        v-for="playlist in playlistList"
        :key="playlist.id"
        class="track-to-playlist__list-item"
        @click="addTrackPlaylist(playlist.id)"
      >
        <!--TODO: checkbox-->
        <span
          v-if="trackBelongsToPlaylist(playlist.id)"
          class="track-to-playlist__tick"
        >
          &#10004;
        </span>
        {{ playlist.title }}
      </li>
    </ul>

    <BaseInput
      v-model="newPlaylistTitle"
      class="track-to-playlist__input"
      label="Создать новый плейлист"
      @press:enter="createNewPlaylist"
    />
  </div>
</template>

<script>
import BaseInput from 'components/BaseInput.vue';
import gql from './gql';

export default {
  components: {
    BaseInput
  },

  props: {
    trackId: {
      type: Number,
      required: true
    }
  },

  data() {
    return {
      track: null,
      isFetching: true,
      playlistList: [],
      newPlaylistTitle: ''
    };
  },

  methods: {
    fetchPlaylistList() {
      if (!this.$apollo.queries.playlistList) {
        this.$apollo.addSmartQuery('playlistList', {
          query: gql.query.MY_COLLECTIONS,
          update: ({ collections: { data } }) => {
            this.isFetching = false;

            return data;
          },
          error(err) {
            console.log(err);
          },
        });
      }
    },

    trackBelongsToPlaylist(playlistId) {
      return this.track.userPlayLists
        .some(userPlaylist => userPlaylist.id === playlistId);
    },

    addTrackPlaylist(playlistId) {
      this.$apollo.mutate({
        mutation: gql.mutation.ADD_TO_COLLECTION,

        variables: {
          collectionId: playlistId,
          trackIds: [this.track.id]
        },

        update: (store, { data: { addInCollection: collection } }) => {
          this.updateMyTrackCollections(store, collection);
          this.newPlaylistTitle = '';
          this.emitTrackAdded(collection);
        }
      }).catch(error => console.log(error));
    },

    createNewPlaylist() {
      // TODO: playlist name validation
      // TODO: image for a newly created playlist should be the same as for the added track ($image)

      this.$apollo.mutate({
        mutation: gql.mutation.CREATE_COLLECTION,

        variables: {
          title: this.newPlaylistTitle,
          trackIds: [this.track.id]
        },

        update: (store, { data: { createCollection: collection } }) => {
          const { collections: collectionList } = store.readQuery({
            query: gql.query.MY_COLLECTIONS
          });

          collectionList.data.push(collection);
          store.writeQuery({
            query: gql.query.MY_COLLECTIONS,
            data: {
              collections: collectionList
            }
          });

          this.updateMyTrackCollections(store, collection);
          this.newPlaylistTitle = '';
          this.emitTrackAdded(collection);
        }
      }).catch(error => console.log(error));
    },

    updateMyTrackCollections(store, collection) {
      // This read assumes we already have track data somewhere in
      // the cache. This approach needs declared cache redirect for 'track'
      // (otherwise the query should have been fetched earlier).

      const { track: storeTrack } = store.readQuery({
        query: gql.query.TRACK,
        variables: {
          id: this.track.id
        }
      });
      let updatedUserPlayLists = [];

      if (!storeTrack.userPlayLists
        .some(playlist => playlist.id === collection.id)) {
        updatedUserPlayLists = [
          ...storeTrack.userPlayLists,
          collection
        ];
      }

      const updatedTrack = {
        ...storeTrack,
        userPlayLists: updatedUserPlayLists
      };

      store.writeQuery({
        query: gql.query.TRACK,
        variables: {
          id: this.track.id
        },
        data: {
          track: updatedTrack
        }
      });
    },

    emitTrackAdded(collection) {
      this.$emit('track-added', this.track, collection);
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
  scoped
  lang="scss"
  src="./TrackToPlaylist.scss"
/>
