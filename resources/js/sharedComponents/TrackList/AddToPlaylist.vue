<template>
  <v-popover
    offset="16"
    placement="left"
    :auto-hide="true"
  >
    <slot/>

    <template #popover>
      <span class="add-to-playlist__header">
        Добавить в плейлист
      </span>

      <hr class="add-to-playlist__delimiter">

      <ul class="add-to-playlist__list">
        <li
          v-for="(playlist, index) in playlistList"
          :key="playlist.id"
          class="add-to-playlist__list-item"
          @click="setTrackPlaylist(playlist.id)"
        >
          <span
            v-if="index === 0"
            class="add-to-playlist__tick"
          >
            ✔
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
import gql from './gql';
import BaseInput from '../BaseInput.vue';

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
      playlistList: [],
      newPlaylist: ''
    };
  },

  methods: {
    trackBelongsToTrackList(trackList) {
      // TODO: remove 'return false;'

      return false;

      return trackList.some(track => track.id === this.trackId);
    },

    setTrackPlaylist(id) {
      // TODO: apollo mutation (wop api)
    },

    createNewPlaylist() {
      // TODO: playlist name validation
      // TODO: change implementation after fixing 'my' prop
      // TODO: change implementation after backend altering type of 'title' field to 'String'

      // this.$apollo.mutate({
      //   mutation: gql.mutation.CREATE_COLLECTION,
      //   variables: {
      //     title: Math.round(((1 + Math.random()) * 1e7))
      //   },
      //   update(store, { data: { CreateColleciton } }) {
      //     const data = store.readQuery({
      //       query: gql.query.PLAYLIST_LIST
      //     });
      //
      //     console.log(data);
      //
      //     // userData.user.musicGroups.push(createMusicGroup);
      //     // store.writeQuery({
      //     //   query: gql.query.USER,
      //     //   data: userData
      //     // });
      //   }
      // });

      this.newPlaylist = '';
    }
  },

  apollo: {
    playlistList: {
      // TODO: implement pagination or loadable list
      // TODO: change implementation after fixing 'my' prop

      // There will be no duplication of the data because the 'result' callback is
      // always passed the same object and popover always renders its content
      // in the same place

      query: gql.query.PLAYLIST_LIST,
      manual: true,
      result(res) {
        const { data: { collections }, networkStatus } = res;

        if (networkStatus === 7) {
          this.playlistList = collections.data;
        }
      }
    }
  }
};
</script>

<style
  lang="scss"
  src="./AddToPlaylist.scss"
/>
