<template>
  <div class="track-to-playlist">
    <SpinnerLoader
      v-if="isFetching"
      class="track-to-playlist__loader"
    />

    <ul class="track-to-playlist__list">
      <li
        v-for="playlist in playlistList"
        :key="playlist.id"
        class="track-to-playlist__list-item"
        @click="addTrackPlaylist(playlist.id)"
      >
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
    >
      <template #icon-button>
        <button
          :class="[
            'track-to-playlist__save-button',
            {
              'track-to-playlist__save-button_is-saving': isSaving
            },
          ]"
          @click.self="createNewPlaylist"
        >
          <SpinnerLoader
            v-if="isSaving"
            class="track-to-playlist__save-button-loader"
            theme="dark"
          />
        </button>
      </template>
    </BaseInput>
  </div>
</template>

<script>
import SpinnerLoader from 'components/SpinnerLoader.vue';
import BaseInput from 'components/BaseInput.vue';
import gql from './gql';

export default {
  components: {
    SpinnerLoader,
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
      isSaving: false,
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
            console.dir(err);
          },
        });
      }

      if (!this.$apollo.queries.track) {
        this.$apollo.addSmartQuery('track', {
          query: gql.query.TRACK,
          variables: {
            id: this.trackId,
          },
          update: ({ track }) => track,
          error(err) {
            console.dir(err);
          }
        });
      }
    },

    trackBelongsToPlaylist(playlistId) {
      if (!this.track) return false;

      return this.track.userPlayLists
        .some(userPlaylist => userPlaylist.id === playlistId);
    },

    addTrackPlaylist(playlistId) {
      this.isSaving = true;

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
          this.isSaving = false;
        }
      }).catch(error => console.dir(error));
    },

    createNewPlaylist() {
      if (this.isSaving
          || this.isFetching
          || this.newPlaylistTitle === '') {
        return;
      }

      // TODO: playlist name validation

      this.isSaving = true;

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

          store.writeQuery({
            query: gql.query.MY_COLLECTIONS,
            data: {
              collections: {
                ...collectionList,
                data: [
                  ...collectionList.data,
                  collection
                ]
              }
            }
          });

          this.updateMyTrackCollections(store, collection);
          this.updateMyCollectionsContainer(store, collection);
          this.newPlaylistTitle = '';
          this.isSaving = false;
          this.emitTrackAdded(collection);
        }
      }).catch(error => console.dir(error));
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
      let updatedUserPlayLists = [...storeTrack.userPlayLists];

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

    updateMyCollectionsContainer(store, collection) {
      try {
        const vars = {
          // TODO: variables should always be the same as in the actual container
          isAuthenticated: true,
          pageNumber: 1,
          pageLimit: 10,
          filters: {
            my: true
          }
        };
        const { collections } = store.readQuery({
          query: gql.query.COLLECTIONS,
          variables: vars
        });

        // it is assumed there that the collection is always added to the end of the pagination

        const { to, total } = collections;

        if (to >= total) {
          const updatedCollectionsContainer = [
            ...collections.data,
            collection
          ];

          store.writeQuery({
            query: gql.query.COLLECTIONS,
            variables: vars,
            data: {
              collections: {
                ...collections,
                data: updatedCollectionsContainer,
                to: to + 1,
                total: total + 1
              }
            }
          });
        }
      } catch (e) {
        // nothing to read
      }
    },

    emitTrackAdded(collection) {
      this.$emit('track-added', this.track, collection);
    }
  }
};
</script>

<style
  scoped
  lang="scss"
  src="./TrackToPlaylist.scss"
/>
