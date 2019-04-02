<template>
  <v-popover
    offset="16"
    placement="left"
    :auto-hide="true"
  >
    <slot/>

    <template slot="popover">
      <a v-close-popover>
        Close
      </a>

      <ul>
        <li
          v-for="playlist in playlists"
          :key="playlist.id"
          @click="setTrackPlaylist(playlist.id)"
        >
          <span v-if="trackPlaylistId === playlist.id">
            SELECTED
          </span>
          {{ playlist.title }}
        </li>
      </ul>

      <BaseInput
        v-model="newPlaylist"
        label="Создать новый плейлист"
        @press:enter="createNewPlaylist"
      />
    </template>
  </v-popover>
</template>

<script>
import gql from 'graphql-tag';
import BaseInput from '../BaseInput.vue';

export default {
  components: {
    BaseInput
  },

  props: {
    trackId: {
      type: Number,
      required: true
    },
    // TODO: when the track playlist id will be implemented change 'default' to 'required'

    trackPlaylistId: {
      type: Number,
      default: 1
    }
  },

  data() {
    return {
      playlists: [],
      newPlaylist: ''
    };
  },

  methods: {
    // TODO: implement once the backend is ready

    setTrackPlaylist(id) {
      // TODO: apollo mutation
    },
    createNewPlaylist() {
      // TODO: playlist name validation
      // TODO: apollo mutation

      this.newPlaylist = '';
    }
  },

  apollo: {
    playlists: {
      // TODO: implement pagination or loadable list
      // TODO: fetch user's personal list of collections (not from global space)

      // There will be no duplication of the data because the 'result' callback is
      // always passed the same object and popover always renders its content
      // in the same place

      query: gql`
        query FetchPlaylistNames {
          collections(limit: 100, page: 1) {
            data {
              id
              title
            }
          }
        }
      `,
      manual: true,
      result(res) {
        const { data: { collections }, networkStatus } = res;

        if (networkStatus === 7) {
          this.playlists = collections.data;
        }
      }
    }
  }
};
</script>

<style
  scoped
  lang="scss"
>

</style>
