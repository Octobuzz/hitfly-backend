<template>
  <div class="asideBlock">
    <div class="asideBlock__header">
      <h2 class="h2">Сейчас слушают</h2>
      <p v-show="!isLoading">{{ currentlyListening }} человек слушают эти песни</p>
      <router-link to="currently-listening" class="asideBlock__button">Все</router-link>
    </div>
    <div class="asideBlock__body">
      <div
        v-show="isLoading"
        class="profile__user-card-loader_first"
      >
        <SpinnerLoader />
      </div>
      <TrackList
        v-if="tracks.length > 0"
        :track-id-list="tracks.map(track => track.id)"
        :showTableHeader="false"
        :showRemoveButton="false"
        :showAddToPlayList="false"
        :columnLayout="true"
      >
      </TrackList>
    </div>
  </div>
</template>
<script>
import SpinnerLoader from 'components/SpinnerLoader.vue';
import TrackList from 'components/trackList/TrackList/TrackList.vue';
import gql from './gql';
import { mapGetters } from 'vuex';

  export default {
    data: () => ({
      isLoading: true,
      tracks: [],
      currentlyListening: Number
    }),
    components: {
      SpinnerLoader,
      TrackList
    },
    computed: {
      ...mapGetters(['isAuthenticated', 'apolloClient'])
    },
    apollo: {
      GetListenedNow() {
        return {
          client: this.apolloClient,
          query: gql.query.GET_LISTENED_NOW,
          variables: {
            isAuthenticated: this.isAuthenticated,
            pageLimit: 50,
            pageNumber: 1
          },
          update(data) {
            this.tracks = data.GetListenedNow.data;
            this.isLoading = false;
          }
        }
      },
      quantityListening() {
        return {
          query: gql.query.CURRENTLY_LISTENING,
          update(data) {
            this.currentlyListening = data.GetListenedNowUser;
          }
        }
      },
    }
  }
</script>
<style
  scoped
  lang="scss"
  src="./ListenedNow.scss"
/>
