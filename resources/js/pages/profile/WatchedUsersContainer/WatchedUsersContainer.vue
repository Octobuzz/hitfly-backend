<template>
  <div ref="root" class="watched-users">
    <WatchedUserList
      :user-id-list="watchedUserIdList"
      :is-loading="hasMoreData"
    />
  </div>
</template>

<script>
import elHasSpaceBelow from 'modules/elHasSpaceBelow';
import loadOnScroll from 'mixins/loadOnScroll';
import WatchedUserList from '../WatchedUserList';
import gql from './gql';

export default {
  components: {
    WatchedUserList
  },

  mixins: [loadOnScroll],

  data() {
    return {
      watchedUsers: [],
      isLoading: true,
      hasMoreData: true,
      queryVars: {
        pageNumber: 1,
        pageLimit: 15
      }
    };
  },

  computed: {
    watchedUserIdList() {
      return this.watchedUsers.map(user => user.id);
    }
  },

  methods: {
    fetchMoreNotifications(vars) {
      return this.$apollo.queries.watchedUsers.fetchMore({
        variables: vars,

        updateQuery: (currentList, { fetchMoreResult: { watchingUser } }) => {
          const { total, to, data: newUsers } = watchingUser;

          return {
            watchingUser: {
              // eslint-disable-next-line no-underscore-dangle
              __typename: currentList.watchingUser.__typename,
              total,
              to,
              data: [
                ...currentList.watchingUser.data,
                ...newUsers
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
    },

    attemptToLoadMore() {
      if (elHasSpaceBelow(this.$refs.root, 350)) {
        this.loadMore();
      } else {
        this.loadOnScroll();
      }
    }
  },

  apollo: {
    watchedUsers() {
      return {
        client: 'private',
        query: gql.query.WATCHED_USERS,
        variables() {
          return this.queryVars;
        },
        fetchPolicy: 'network-only',

        update({ watchingUser: { total, to, data } }) {
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

          return data.map(watched => watched.user);
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
  src="./WatchedUsersContainer.scss"
/>
