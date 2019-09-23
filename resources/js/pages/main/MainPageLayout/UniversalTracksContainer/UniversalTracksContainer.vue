<template>
  <div>
    <slot
      v-if="trackList.length > 0"
      name="default"
      :track-id-list="trackIdList"
      :has-more-data="hasMoreData"
    />
    <p
      v-if="initialFetchError"
      class="universal-albums-container__error"
    >
      На сервере произошла ошибка. Не удалось загрузить данные альбомов.
      Пожалуйста, попробуйте позже или обратитесь к администратору.
    </p>
  </div>
</template>

<script>
import { mapGetters } from 'vuex';
import gql from './gql';

export default {
  props: {
    query: {
      type: String,
      required: true
    },
    forId: {
      type: Number,
      required: false
    }
  },
  data() {
    return {
      trackList: [],
      isLoading: true,
      hasMoreData: true,
      currentType: null,
      queryVars: {
        pageNumber: 1,
        pageLimit: 30,
      }
    };
  },

  computed: {
    ...mapGetters(['isAuthenticated', 'apolloClient']),

    trackIdList() {
      return this.trackList.map(track => track.id);
    },

    initialFetchError() {
      const loading = this.$store.getters['loading/mainPage'].newTracks;

      return loading.initialized && !loading.success;
    }
  },

  mounted() {
    this.$on('load-more', this.onLoadMore.bind(this));

    this.boundRemoveTrackFromStore = this.removeTrackFromStore.bind(this);

    this.$eventBus.$on(
      'track-removed',
      this.boundRemoveTrackFromStore
    );
  },

  beforeDestroy() {
    this.$eventBus.$off(
      'track-removed',
      this.boundRemoveTrackFromStore
    );
  },

  watch: {
    'query': function(){
      let query = null;
      if(this.query === 'top50') {
        query = gql.query.GET_TOP_FIFTY
      } else if (this.query === 'listening_now') {
        query = gql.query.GET_LISTENED_NOW
      } else if (this.query === 'weekly_top') {
        query = gql.query.WEEKLY_TOP
      } else if (this.query === 'new_songs' || this.query === 'genre') {
        query = gql.query.TRACKS
      };
      this.$apollo.provider.clients[this.apolloClient].query({
        query: query,
        variables: {
          isAuthenticated: this.isAuthenticated,
          pageLimit: 50,
          pageNumber: 1,
        },
      })
      .then(response => {
        let collectionResponse = null;
        if(this.query === 'top50'){
          collectionResponse = response.data.GetTopFifty.data;
        } else if (this.query === 'listening_now'){
          collectionResponse = response.data.GetListenedNow.data;
        } else if (this.query === 'weekly_top'){
          collectionResponse = response.data.TopWeeklyQuery.data;
        } else if (this.query === 'new_songs'){
          collectionResponse = response.data.tracks.data;
        } else if (this.query === 'genre'){
          collectionResponse = response.data.tracks.data;
        };
        this.trackList = collectionResponse;
        return collectionResponse;
      })
      .catch(error => {
        console.log(error);
      });
    }
  },

  methods: {
    notifyInitialization(success, type) {
      let storeItem = {[type]: {
        initialized: true,
        success
      }};
      this.$store.commit('loading/setMainPage', storeItem);
    },

    fetchMoreTracks(vars) {
      return this.$apollo.queries.trackList.fetchMore({
        variables: vars,

        updateQuery: (currentList, { fetchMoreResult: { tracks } }) => {
          const { total, to, data: newTracks } = tracks;

          return {
            tracks: {
              // eslint-disable-next-line no-underscore-dangle
              __typename: currentList.tracks.__typename,
              total,
              to,
              data: [
                ...currentList.tracks.data,
                ...newTracks
              ]
            }
          };
        },
      });
    },

    onLoadMore() {
      if (!this.hasMoreData || this.isLoading) {
        return;
      }

      this.isLoading = true;

      this.fetchMoreTracks({
        ...this.queryVars,
        pageNumber: this.queryVars.pageNumber + 1
      })
        .then(() => {
          this.queryVars.pageNumber += 1;
          this.isLoading = false;
        })
        .catch((err) => {
          console.dir(err);
        });
    },

    removeTrackFromStore(id) {
      const store = this.$apollo.provider.defaultClient;

      const { tracks } = store.readQuery({
        query: gql.query.TRACKS,

        // cause updateQuery does not update variables in cache entry
        // we always refer to the first page

        variables: {
          ...this.queryVars,
          pageNumber: 1
        }
      });

      const updatedTracks = {
        tracks: {
          ...tracks,
          data: [
            ...tracks.data.filter(track => track.id !== id)
          ]
        }
      };

      store.writeQuery({
        query: gql.query.TRACKS,
        variables: {
          ...this.queryVars,
          pageNumber: 1
        },
        data: updatedTracks
      });
    }
  },

  apollo: {
    trackList() {
      let query = null;
      if(this.query === 'tracks' || this.query === 'new_songs' || this.query === 'genre') {
        query = gql.query.TRACKS
      } else if(this.query === 'TopWeeklyQuery' || this.query === 'weekly_top') {
        query = gql.query.WEEKLY_TOP
      } else if(this.query === 'top50') {
        query = gql.query.GET_TOP_FIFTY
      }else if(this.query === 'listening_now') {
        query = gql.query.GET_LISTENED_NOW
      };
      if(this.query === 'genre') {
        this.queryVars.filter = {'genre': this.forId};
      };
      return {
        query: query,
        client: this.apolloClient,
        variables: {
          ...this.queryVars,
          isAuthenticated: this.isAuthenticated
        },
        fetchPolicy: 'network-only',

        update(commonObject) {
          let to = Object.values(commonObject.tracks)[0].to;
          let total = Object.values(commonObject.tracks)[0].total;
          let data = Object.values(commonObject.tracks)[0].data;
          this.isLoading = false;
          this.$emit('initialized', {
            data,
            success: true,
            error: null
          });
          let currentType = null;
          if(this.query === 'tracks') {
            currentType = 'newTracks';
          } else if (this.query === 'TopWeeklyQuery'){
            currentType = 'weeklyTop';
          } else if (this.query === 'top50'){
            currentType = 'top50';
          } else if (this.query === 'genre'){
            currentType = 'genre';
          };
          this.notifyInitialization(true, currentType);

          if (to >= total) {
            this.hasMoreData = false;
          }

          return data;
        },

        error(error) {
          this.notifyInitialization(false);

          console.dir(error);
        }
      };
    }
  }
};
</script>

<style
  scoped
  lang="scss"
  src="./UniversalTracksContainer.scss"
/>
