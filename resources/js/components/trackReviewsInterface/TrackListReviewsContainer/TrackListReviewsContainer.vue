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
import SpinnerLoader from 'components/SpinnerLoader.vue';
import TrackListReviews from '../TrackListReviews';
import gql from './gql';

export default {
  components: {
    SpinnerLoader,
    TrackListReviews
  },

  props: {
    forType: {
      validator: val => [
        'user-track-list',
        'music-group-track-list'
      ].includes(val),
      required: true
    },
    forId: {
      validator: val => val === 'me' || typeof val === 'number',
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
    const { forType, forId, commentedInPeriod } = this;
    const filters = {};

    switch (forType) {
      case 'user-track-list':
        if (forId === 'me') {
          filters.my = true;

          // TODO: api bug ?
          filters.my = false;
        } else {
          filters.userId = forId;
        }
        break;

      case 'music-group-track-list':
        filters.musicGroupId = forId;
        break;

      default:
        throw new Error(
          `Incorrect value for property "forType" passed to TrackReviewsContainerList: ${forType}`
        );
    }

    return {
      trackList: [],
      isLoading: true,
      hasMoreData: true,
      queryVars: {
        pageNumber: 1,
        pageLimit: 5,
        commentedInPeriod,
        filters
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
      this.trackList = [];
      this.isLoading = true;
      this.hasMoreData = true;

      this.queryVars = {
        ...this.queryVars,
        pageNumber: 1,
        commentedInPeriod: val,
      };
    }
  },

  mounted() {
    window.addEventListener('scroll', this.onScroll);
  },

  destroyed() {
    window.removeEventListener('scroll', this.onScroll);
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
    },

    onScroll() {
      const { innerHeight, pageYOffset } = window;
      const { scrollHeight } = document.body;

      const maybeLoadMore = Math.abs(
        (innerHeight + pageYOffset) - scrollHeight
      ) <= 200;

      if (maybeLoadMore && this.hasMoreData) {
        this.loadMore();
      }
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
          this.onScroll();
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
