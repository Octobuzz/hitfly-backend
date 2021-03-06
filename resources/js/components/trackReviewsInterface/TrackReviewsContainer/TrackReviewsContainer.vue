<template>
  <TrackReviews
    :track-id="trackId"
    :reviews="reviews"
    :show-loader="hasMoreData"
  >
    <template #main-loader>
      <SpinnerLoader class="track-reviews-container__loader" />
    </template>

    <template #scroll-loader>
      <SpinnerLoader class="track-reviews-container__loader" />
    </template>
  </TrackReviews>
</template>

<script>
import { mapGetters } from 'vuex';
import loadOnScroll from 'mixins/loadOnScroll';
import { commentPeriod } from 'modules/validators';
import SpinnerLoader from 'components/SpinnerLoader.vue';
import TrackReviews from '../TrackReviews';
import gql from './gql';

export default {
  components: {
    SpinnerLoader,
    TrackReviews
  },

  mixins: [loadOnScroll],

  props: {
    trackId: {
      type: Number,
      required: true
    },
    commentedInPeriod: {
      validator: commentPeriod,
      default: 'month'
    }
  },

  data() {
    return {
      reviews: [],
      isLoading: true,
      hasMoreData: true,
      queryVars: {
        pageNumber: 1,
        pageLimit: 5,
        commentPeriod: this.commentedInPeriod
      }
    };
  },

  computed: {
    ...mapGetters(['apolloClient'])
  },

  watch: {
    commentedInPeriod(val) {
      this.isLoading = true;
      this.hasMoreData = true;

      this.queryVars = {
        ...this.queryVars,
        pageNumber: 1,
        commentPeriod: val
      };
    }
  },

  methods: {
    fetchMoreReviews(vars) {
      return this.$apollo.queries.reviews.fetchMore({
        variables: vars,

        updateQuery: (currentList, { fetchMoreResult: { commentsTrack } }) => {
          const { total, to, data: newComments } = commentsTrack;

          return {
            commentsTrack: {
              // eslint-disable-next-line no-underscore-dangle
              __typename: currentList.commentsTrack.__typename,
              total,
              to,
              data: [
                ...currentList.commentsTrack.data,
                ...newComments
              ]
            }
          };
        },
      });
    },

    loadMore() {
      if (this.isLoading) return;

      this.isLoading = true;

      this.fetchMoreReviews({
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
    reviews() {
      return {
        client: this.apolloClient,
        query: gql.query.TRACK_COMMENTS,
        variables() {
          return this.queryVars;
        },
        fetchPolicy: 'network-only',

        update({ commentsTrack: { total, to, data } }) {
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
      };
    }
  }
};
</script>

<style
  scoped
  lang="scss"
  src="./TrackReviewsContainer.scss"
/>
