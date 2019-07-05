<template>
  <TrackListReviews
    :track-id-list="trackIdList"
    :commented-in-period="commentedInPeriod"
  >
    <template v-if="hasMoreData" #loader>
      <SpinnerLoader class="track-list-reviews-container__loader" />
    </template>
  </TrackListReviews>
</template>

<script>
import loadOnScroll from 'mixins/loadOnScroll';
import { reviewFilterType, reviewFilterId, commentPeriod } from 'modules/validators';
import SpinnerLoader from 'components/SpinnerLoader.vue';
import TrackListReviews from '../TrackListReviews';
import gql from './gql';

export default {
  components: {
    SpinnerLoader,
    TrackListReviews
  },

  mixins: [loadOnScroll],

  props: {
    forType: {
      validator: reviewFilterType,
      required: true
    },

    forId: {
      validator: reviewFilterId,
      required: true
    },

    commentedInPeriod: {
      validator: commentPeriod,
      default: 'week'
    }
  },

  data() {
    return {
      trackList: [],
      isLoading: true,
      hasMoreData: true,
      queryVars: {
        pageNumber: 1,
        pageLimit: 5,
        commentedInPeriod: this.commentedInPeriod,
        filters: this.filters // possible backend bug
      }
    };
  },

  computed: {
    trackIdList() {
      return this.trackList.map(track => track.id);
    },

    filters() {
      const { forType, forId } = this;
      const filters = {};

      switch (forType) {
        case 'user-track-list':
          if (forId === 'me') {
            filters.my = true;
          } else {
            filters.userId = forId;
          }
          break;

        case 'commented-by-user-track-list':
          if (forId === 'me') {
            filters.iCommented = true;
          } else {
            // TODO: handle other user id
            // filters.commentedByUser = forId;
          }
          break;

        case 'music-group-track-list':
          filters.musicGroupId = forId;
          break;

        default:
          throw new Error(
            `Incorrect value for property "forType" passed to TrackListReviewsContainer: ${forType}`
          );
      }
      return filters;
    }
  },

  watch: {
    commentedInPeriod(val) {
      this.trackList = [];
      this.isLoading = true;
      this.hasMoreData = true;

      this.queryVars = {
        ...this.queryVars,
        pageNumber: 1,
        commentedInPeriod: val,
      };
    },

    forType() {
      this.trackList = [];
      this.isLoading = true;
      this.hasMoreData = true;

      this.queryVars = {
        ...this.queryVars,
        pageNumber: 1,
        filters: this.filters,
      };
    }
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
        this.hasMoreData = to < total;

        // check if the screen has empty space to load more comments

        this.$nextTick(() => {
          this.loadOnScroll();
        });

        return data;
      },

      error(err) {
        console.dir(err);
      }
    }
  }
};
</script>

<style
  scoped
  lang="scss"
  src="./TrackListReviewsContainer.scss"
/>
