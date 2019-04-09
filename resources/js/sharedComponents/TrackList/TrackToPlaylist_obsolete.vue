<template>
  <v-popover
    offset="16"
    placement="left-start"
    :auto-hide="true"
    @show="fetchPlaylistList"
  >
    <slot/>

    <template #popover>
      <span class="track-to-playlist__header">
        Добавить в плейлист
      </span>

      <hr class="track-to-playlist__delimiter">

      <ul class="track-to-playlist__list">
        <li
          v-for="playlist in playlistList"
          :key="playlist.id"
          class="track-to-playlist__list-item"
          @click="setTrackPlaylist(playlist.id)"
        >
          <!--TODO: checkbox-->
          <span
            v-if="trackBelongsToPlaylist(playlist.id)"
            class="track-to-playlist__tick"
          >
            ✔
          </span>
          {{ playlist.title }}
        </li>
      </ul>

      <BaseInput
        v-model="newPlaylistTitle"
        label="Создать новый плейлист"
        @press:enter="createNewPlaylist"
      />
    </template>
  </v-popover>
</template>

<script>
import gql from './gql';
import BaseInput from '../BaseInput.vue';

export default {
  components: {
    BaseInput
  },

  props: {
    track: {
      type: Object,
      required: true
    }
  },

  data() {
    return {
      playlistList: [],
      newPlaylistTitle: ''
    };
  },

  methods: {
    fetchPlaylistList() {
      this.$apollo.addSmartQuery('playlistList', {
        query: gql.query.PLAYLIST_LIST,
        manual: true,
        result(res) {
          const { data: { collections }, networkStatus } = res;

          if (networkStatus === 7) {
            this.playlistList = collections.data;
          }
        },
        error(err) {
          console.log(err);
        },
      });
    },

    trackBelongsToPlaylist(playlistId) {
      return this.track.userPlayLists
        .some(userPlaylist => userPlaylist.id === playlistId);
    },

    setTrackPlaylist(playlistId) {
      this.$apollo.mutate({
        mutation: gql.mutation.ADD_TO_COLLECTION,

        variables: {
          collectionId: playlistId,
          trackIds: [this.track.id]
        },

        update: (store, { data: { addInCollection: collection } }) => {
          // update only 'my' tracks cause other tracks are not supposed to have userPlayLists field

          const { tracks: trackList } = store.readQuery({
            query: gql.query.TRACK_LIST,
            variables: {
              my: true
            }
          });
          let trackIdx;

          for (let i = 0; i < trackList.data.length; i += 1) {
            if (trackList.data[i].id === this.track.id) {
              trackIdx = i;
              break;
            }
          }
          trackList.data[trackIdx].userPlayLists.push(collection);

          store.writeQuery({
            query: gql.query.TRACK_LIST,
            variables: {
              my: true
            },
            data: {
              tracks: trackList
            }
          });
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
          trackIds: []
        },

        update: (store, { data: { createCollection: collection } }) => {
          const { collections: collectionList } = store.readQuery({
            query: gql.query.PLAYLIST_LIST
          });

          collectionList.data.push(collection);
          store.writeQuery({
            query: gql.query.PLAYLIST_LIST,
            data: {
              collections: collectionList
            }
          });
          this.newPlaylistTitle = '';
        }
      }).catch(error => console.log(error));
    }
  }
};
</script>

<style
  lang="scss"
>
.tooltip {
  $bg-color: #222;

  display: block !important;
  min-width: 240px;
  z-index: 10000;
  background: $bg-color;
  box-shadow: 0 0 30px 5px rgba(black, .1);;

  &.popover {
    .popover-inner {
      background: $bg-color;
      color: black;
      padding: 12px;
      border-radius: 5px;
    }

    &[x-placement^="left"] {
      margin-right: 5px;

      .popover-arrow {
        position: absolute;
        width: 0;
        height: 0;
        right: -5px;
        top: calc(50% - 5px);
        margin: 5px 0 5px 0;
        border: {
          width: 5px 0 5px 5px;
          style: solid;
          top-color: transparent !important;
          right-color: transparent !important;
          bottom-color: transparent !important;
          left-color: $bg-color;
        }
        z-index: 1;
      }
    }
  }
}

.track-to-playlist {
  &__header {
    font-family: "GothamPro_bold", serif;
    font-size: 14px;
    color: white;
    display: block;
    padding: 12px 0 18px 0;
  }

  &__delimiter {
    height: 1px;
    background: #424242;
    border: none;
  }

  &__list {
    font-size: 14px;
    margin: 6px 0 18px 28px;
    padding-left: 0;
    list-style: none;
    color: #6e6e6e;
  }

  &__list-item {
    position: relative;
    padding: 12px 8px;
    border-radius: 5px;
    user-select: none;
    cursor: pointer;

    &:hover {
      color: white;
      background: #333;
    }
  }

  &__tick {
    position: absolute;
    color: white;
    left: -22px;
  }
}
</style>
