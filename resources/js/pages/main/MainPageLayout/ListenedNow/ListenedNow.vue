<template>
  <div class="asideBlock">
    <div class="asideBlock__header">
      <h2 class="h2">Сейчас слушают</h2>
      <p v-show="!isLoading && !noData">{{ currentlyListening }} человек слушают эти песни</p>
      <router-link v-show="!isLoading && !noData" to="listening_now" class="asideBlock__button">Все</router-link>
    </div>
    <div class="asideBlock__body" style="max-height: 168px; overflow: hidden;">
      <div
        v-show="isLoading"
        class="profile__user-card-loader_first"
      >
        <SpinnerLoader />
      </div>
      <template v-if="!noData">
        <TrackList
          for-type="collection"
          for-id="listening_now"
          :track-id-list="tracks.map(track => track.id)"
          :showTableHeader="false"
          :showRemoveButton="false"
          :showAddToPlayList="false"
          :columnLayout="true"
        >
        </TrackList>
      </template>
      <template v-else>
        <p>В данной категории нет песен</p>
      </template>
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
      noData: false,
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
            if(data.GetListenedNow !== null && data.GetListenedNow.data.length !== 0) {
              this.tracks = data.GetListenedNow.data;
              this.isLoading = false;
            } else {
              this.noData = true;
              this.isLoading = false;
            }
          }
        }
      },
      quantityListening() {
        return {
          client: this.apolloClient,
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
