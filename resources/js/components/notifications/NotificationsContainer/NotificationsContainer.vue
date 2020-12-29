<template>
  <NotificationList
    :notifications="notifications"
    :show-loader="hasMoreData"
  />
</template>

<script>
import loadOnScroll from 'mixins/loadOnScroll';
import NotificationList from '../NotificationList';
import gql from './gql';

export default {
  components: {
    NotificationList
  },

  mixins: [loadOnScroll],

  data() {
    return {
      notifications: [],
      isLoading: true,
      hasMoreData: true,
      queryVars: {
        pageNumber: 1,
        pageLimit: 15
      }
    };
  },

  methods: {
    fetchMoreNotifications(vars) {
      return this.$apollo.queries.notifications.fetchMore({
        variables: vars,

        updateQuery: (currentList, { fetchMoreResult: { NotificationQuery } }) => {
          const { total, to, data: newNotifications } = NotificationQuery;

          return {
            NotificationQuery: {
              // eslint-disable-next-line no-underscore-dangle
              __typename: currentList.NotificationQuery.__typename,
              total,
              to,
              data: [
                ...currentList.NotificationQuery.data,
                ...newNotifications
              ]
            }
          };
        },
      });
    },

    loadMore() {
      if (this.isLoading) return;

      this.isLoading = true;

      this.fetchMoreNotifications({
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
    notifications: {
      query: gql.query.NOTIFICATIONS,
      variables() {
        return this.queryVars;
      },
      fetchPolicy: 'network-only',

      update({ NotificationQuery: { total, to, data } }) {
        this.isLoading = false;
        this.hasMoreData = to < total;

        // check if the screen has empty space to load more comments

        this.$nextTick(() => {
          this.loadOnScroll();
        });

        if (this.queryVars.pageNumber === 1 && data[0]) {
          this.$eventBus.$emit('notifications-read', data[0].id);
        }

        return data.map(entry => ({
          ...entry,
          data: JSON.parse(entry.data)
        }));
      },

      error(err) {
        console.dir(err);
      }
    }
  }
};
</script>
