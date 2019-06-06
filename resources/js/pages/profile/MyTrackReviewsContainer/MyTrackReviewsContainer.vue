<template>
  <ReviewList :track-id-list="trackIdList">
    <template v-if="isLoading" #loader>
      <SpinnerLoader />
    </template>
  </ReviewList>
</template>

<script>
import SpinnerLoader from 'components/SpinnerLoader.vue';
import ReviewList from 'components/reviewList/ReviewList';
import gql from './gql';

export default {
  components: {
    SpinnerLoader,
    ReviewList
  },

  props: {
    commentedInPeriod: {
      type: String,
      default: 'month'
    }
  },

  data() {
    return {
      trackList: [],
      isLoading: true,
      hasMoreData: true,
      queryVars: {
        // TODO: use query vars specific to reviews' data
        // TODO: pass user/id group as a prop
        pageNumber: 1,
        pageLimit: 5,
        my: false,
        commentedInPeriod: this.commentedInPeriod
      }
    };
  },

  computed: {
    trackIdList() {
      return this.trackList.map(track => track.id);
    }
  },

  mounted() {
    window.loadMoreComments = this.loadMore.bind(this);
  },

  methods: {
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
              data: [...currentList.tracks.data, ...newTracks]
            }
          };
        },
      });
    },

    loadMore() {
      if (this.isLoading) return;

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
    }
  },

  apollo: {
    trackList() {
      return {
        query: gql.query.MY_TRACKS_WITH_COMMENTS,
        variables: this.queryVars,
        fetchPolicy: 'network-only',

        update({ tracks: { total, to, data } }) {
          this.isLoading = false;
          if (to === total) {
            this.hasMoreData = false;
          }

          return data;
        },

        error(err) {
          console.dir(err);
        }
      };
    }
  }
};
</script>
