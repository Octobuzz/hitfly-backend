<template>
  <div class="asideBlock">
    <div class="asideBlock__header">
      <h2 class="h2">Сейчас слушают</h2>
      <p v-show="!isLoading">{{ currentlyListening }} человек слушают эти песни</p>
      <button>Все</button>
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
        @remove-track="onRemoveTrack"
      >
      </TrackList>
    </div>
  </div>
</template>
<script>
import SpinnerLoader from 'components/SpinnerLoader.vue';
import TrackList from 'components/trackList/TrackList/TrackList.vue';
import gql from './gql';

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
    methods: {
      onRemoveTrack() {
        console.log('onRemoveTrack');
      }
    },
    apollo: {
      GetListenedNow() {
        return {
          query: gql.query.CURRENTLY_LISTENING,
          variables: {
            pageLimit: 50,
            pageNumber: 1
          },
          update(data) {
            this.tracks = data.GetListenedNowUser;
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
