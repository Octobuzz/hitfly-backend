<template>
  <ReviewList
    :track-id-list="trackIdList"
    :commented-in-period="commentedInPeriod"
  >
    <template v-if="isLoading" #loader>
      <SpinnerLoader />
    </template>
  </ReviewList>
</template>

<script>
import SpinnerLoader from 'components/SpinnerLoader.vue';
import ReviewList from 'components/trackReviews/ReviewList';
import gql from './gql';

export default {
  components: {
    SpinnerLoader,
    ReviewList
  },

  props: {
    userId: {
      validator: val => (
        typeof value === 'number' || val === 'me'
      ),
      required: true
    },
    commentedInPeriod: {
      validator: val => (
        ['week', 'month', 'year'].indexOf(val) !== -1
      ),
      default: 'month'
    }
  },

  data() {
    return {
      trackList: [],
      isLoading: true,
      hasMoreData: true,
      queryVars: {
        // TODO: pass user/group id as a prop
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

  watch: {
    commentedInPeriod(val) {
      this.queryVars = {
        ...this.queryVars,
        commentedInPeriod: val
      };
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
    trackList: {
      query: gql.query.TRACKS_WITH_COMMENTS,
      variables() {
        return this.queryVars;
      },
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
    }
  }
};
</script>
