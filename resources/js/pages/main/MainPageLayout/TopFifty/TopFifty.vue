<template>
  <div class="asideBlock">
    <div class="asideBlock__header">
      <h2 class="h2">Топ 50</h2>
      <p v-show="!isLoading && !noData">Рейтинг лучших музыкантов</p>
      <router-link v-show="!isLoading && !noData" to="top50" class="asideBlock__button">Все</router-link>
    </div>
    <div class="asideBlock__body" style="max-height: 168px; overflow: hidden;">
      <div
        v-show="isLoading && !dataInitialized"
        class="profile__user-card-loader_first"
      >
        <SpinnerLoader />
      </div>
      <template v-if="!noData">
        <TrackList
          for-type="collection"
          for-id="top50"
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
import { mapGetters } from 'vuex';
import gql from './gql';

  export default {
    data: () => ({
      isLoading: true,
      noData: false,
      tracks: []
    }),
    components: {
      SpinnerLoader,
      TrackList
    },
    computed: {
      dataInitialized() {
        return this.$store.getters['loading/music'].tracks.initialized;
      },
      ...mapGetters(['isAuthenticated', 'apolloClient'])
    },
    apollo: {
      topFifty() {
        return {
          client: this.apolloClient,
          query: gql.query.GET_TOP_FIFTY,
          variables: {
            isAuthenticated: this.isAuthenticated,
            pageLimit: 50,
            pageNumber: 1
          },
          update(data) {
            if(data.GetTopFifty !== null && data.GetTopFifty.data.length !== 0) {
              this.tracks = data.GetTopFifty.data;
              this.isLoading = false;
            } else {
              this.noData = true;
              this.isLoading = false;
            }
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
