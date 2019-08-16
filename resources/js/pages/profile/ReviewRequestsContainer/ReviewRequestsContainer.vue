<template>
  <div ref="root">
    <ReviewRequestList
      :product-id-list="productIdList"
      :is-loading="hasMoreData"
      @remove-track-from-requests="removeTrackFromRequests"
    />
  </div>
</template>

<script>
import elHasSpaceBelow from 'modules/elHasSpaceBelow';
import loadOnScroll from 'mixins/loadOnScroll';
import ReviewRequestList from '../ReviewRequestList';
import gql from './gql';

export default {
  components: {
    ReviewRequestList
  },

  mixins: [loadOnScroll],

  data() {
    return {
      productIdList: [],
      isLoading: true,
      hasMoreData: true,
      queryVars: {
        pageNumber: 1,
        pageLimit: 5
      }
    };
  },

  methods: {
    fetchMoreTracks(vars) {
      return this.$apollo.queries.productIdList.fetchMore({
        variables: vars,

        updateQuery: (currentList, { fetchMoreResult: { requestsForComments } }) => {
          const { total, to, data: newRequests } = requestsForComments;

          return {
            requestsForComments: {
              // eslint-disable-next-line no-underscore-dangle
              __typename: currentList.requestsForComments.__typename,
              total,
              to,
              data: [
                ...currentList.requestsForComments.data,
                ...newRequests
              ]
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

    attemptToLoadMore() {
      if (elHasSpaceBelow(this.$refs.root, 350)) {
        this.loadMore();
      } else {
        this.loadOnScroll();
      }
    },

    removeTrackFromRequests({ productId }) {
      const store = this.$apollo.provider.clients.private;

      try {
        const { requestsForComments } = store.readQuery({
          query: gql.query.REVIEW_REQUESTS,
          variables: this.queryVars
        });

        const updatedRequests = requestsForComments.data.filter(
          product => product.productId !== productId
        );

        store.writeQuery({
          query: gql.query.REVIEW_REQUESTS,
          variables: this.queryVars,
          data: {
            requestsForComments: {
              ...requestsForComments,
              data: updatedRequests
            }
          }
        });
      } catch (e) {
        // nothing found in the store
      }
    }
  },

  apollo: {
    productIdList() {
      return {
        client: 'private',
        query: gql.query.REVIEW_REQUESTS,
        variables() {
          return this.queryVars;
        },
        fetchPolicy: 'network-only',

        update({ requestsForComments: { total, to, data } }) {
          this.isLoading = false;
          this.hasMoreData = to < total;

          // check if the screen has empty space to load more comments

          this.$nextTick(() => {
            if (!this.hasMoreData) return;

            const componentRoot = this.$refs.root;
            const mounted = componentRoot !== undefined;

            if (!mounted) {
              this.$once('hook:mounted', this.attemptToLoadMore.bind(this));

              return;
            }
            this.attemptToLoadMore();
          });

          // debugger;

          return data.map(({ productId, productAttribute }) => ({
            productId,
            // eslint-disable-next-line no-underscore-dangle
            trackId: productAttribute.find(attr => attr.__typename === 'Track').id
          }));
        },

        error(err) {
          console.dir(err);
        }
      };
    }
  }
};
</script>

<style scoped lang="scss"/>
