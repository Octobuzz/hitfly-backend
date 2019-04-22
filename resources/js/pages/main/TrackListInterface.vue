<template>
  <TrackList :track-id-list="trackIdList">
    <template v-if="isLoading" #preloader>
      is loading...
    </template>
    <template #loadButton>
      <button
        v-if="showLoadTrigger"
        :disabled="!hasMoreData || isLoading"
        @click="onLoadMore"
      >
        load more tracks
      </button>
    </template>
  </TrackList>
</template>

<script>
import TrackList from 'components/TrackList';
import gql from './gql';

export default {
  components: {
    TrackList
  },

  data() {
    return {
      trackList: [],
      isLoading: true,
      hasMoreData: true,
      queryVars: {
        pageNumber: 1,
        pageLimit: 5,
        my: false
      },
      showLoadTrigger: false
    };
  },

  computed: {
    trackIdList() {
      return this.trackList.map(track => track.id);
    }
  },

  methods: {
    fetchMoreTracks(vars) {
      return this.$apollo.queries.trackList.fetchMore({
        variables: vars,

        updateQuery: (currentList, { fetchMoreResult: { tracks } }) => {
          const { total, to, data: newTracks } = tracks;

          if (to === total) {
            this.hasMoreData = false;
          }

          return {
            tracks: {
              // eslint-disable-next-line no-underscore-dangle
              __typename: currentList.tracks.__typename,
              total,
              to,
              data: [...currentList.tracks.data, ...newTracks]
            }
          };
        },
      });
    },

    onLoadMore() {
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
          console.log(err);
        });
    }
  },

  apollo: {
    trackList() {
      return {
        query: gql.query.TRACK_LIST,
        variables: this.queryVars,

        update({ tracks: { total, to, data } }) {
          this.isLoading = false;
          this.showLoadTrigger = true;
          if (to === total) {
            this.hasMoreData = false;
          }

          return data;
        },

        error(err) {
          console.log('FETCH ERROR:', err);
        }
      };
    }
  }
};
</script>
