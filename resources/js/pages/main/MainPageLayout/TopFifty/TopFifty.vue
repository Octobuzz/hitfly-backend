<template>
  <div class="asideBlock">
    <div class="asideBlock__header">
      <h2 class="h2">Топ 50</h2>
      <p>Рейтинг лучших музыкантов</p>
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
      tracks: []
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
    computed: {
      dataInitialized() {
        console.log(this.$store.getters['loading/music'].tracks.initialized);
        return this.$store.getters['loading/music'].tracks.initialized;
      }
    },
    apollo: {
      topFifty() {
        return {
          query: gql.query.GET_TOP_FIFTY,
          variables: {
            pageLimit: 50,
            pageNumber: 1
          },
          update(data) {
            this.tracks = data.GetTopFifty.data;
            this.isLoading = false;
            this.visibleTracks = this.tracks.slice(0, 1);
          }
        }
      }
    }
  }
</script>
<style
  scoped
  lang="scss"
  src="./TopFifty.scss"
/>
