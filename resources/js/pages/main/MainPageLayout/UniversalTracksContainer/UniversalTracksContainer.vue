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
import gql from './gql';

export default {
  props: {
    query: {
      type: String,
      required: true
    }
  },
  data() {
    return {
      trackList: [],
      isLoading: true,
      hasMoreData: true,
      queryVars: {
        pageNumber: 1,
        pageLimit: 30
      }
    };
  },

  computed: {
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

  methods: {
    notifyInitialization(success) {
      this.$store.commit('loading/setMainPage', {
        newTracks: {
          initialized: true,
          success
        }
      });
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
      if(this.query === 'tracks') {
        query = gql.query.TRACKS
      } else if(this.query === 'TopWeeklyQuery') {
        query = gql.query.WEEKLY_TOP
      };
      return {
        query: query,
        variables: this.queryVars,
        fetchPolicy: 'network-only',

        update(commonObject) {
          let to = Object.values(commonObject)[0].to;
          let total = Object.values(commonObject)[0].total;
          let data = Object.values(commonObject)[0].data;
          this.isLoading = false;
          this.notifyInitialization(true);

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
