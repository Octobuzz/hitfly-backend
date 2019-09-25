<template>
  <div>
    <slot
      v-if="starsList.length > 0"
      name="default"
      :stars-id-list="starsIdList"
      :has-more-data="hasMoreData"
    />
    <p
      v-if="initialFetchError"
      class="universal-news-container__error"
    >
      На сервере произошла ошибка. Не удалось загрузить данные звёздных экспертов.
      Пожалуйста, попробуйте позже или обратитесь к администратору.
    </p>
  </div>
</template>

<script>
import { mapGetters } from 'vuex';
import gql from './gql';

export default {
  data() {
    return {
      starsList: [],
      isLoading: true,
      hasMoreData: true,
      queryVars: {
        pageNumber: 1,
        pageLimit: 30
      }
    };
  },

  computed: {
    ...mapGetters(['isAuthenticated', 'apolloClient']),

    initialFetchError() {
      const loading = this.$store.getters['loading/mainPage'].stars;

      return loading.initialized && !loading.success;
    },

    starsIdList() {
      return this.starsList.map(star => star.id);
    },
  },

  mounted() {
    this.$on('load-more', this.onLoadMore.bind(this));

    this.boundRemoveStarsFromStore = this.removeStarsFromStore.bind(this);

    this.$eventBus.$on(
      'stars-removed',
      this.boundRemoveStarsFromStore
    );
  },

  beforeDestroy() {
    this.$eventBus.$off(
      'stars-removed',
      this.boundRemoveStarsFromStore
    );
  },

  methods: {
    notifyInitialization(success) {
      this.$store.commit('loading/setMainPage', {stars: {
        initialized: true,
        success
      }});
    },

    fetchMoreStars(vars) {
      return this.$apollo.queries.starsList.fetchMore({
        variables: vars,

        updateQuery: (currentList, { fetchMoreResult: { stars } }) => {
          const { total, to, data: newStars } = stars;

          return {
            stars: {
              // eslint-disable-next-line no-underscore-dangle
              __typename: currentList.stars.__typename,
              total,
              to,
              data: [
                ...currentList.stars.data,
                ...newStars
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

      this.fetchMoreStars({
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

    removeStarsFromStore(id) {
      const store = this.$apollo.provider.defaultClient;

      const { stars } = store.readQuery({
        query: gql.query.USERS,

        // cause updateQuery does not update variables in cache entry
        // we always refer to the first page

        variables: {
          ...this.queryVars,
          pageNumber: 1
        }
      });

      const updatedStars = {
        stars: {
          ...stars,
          data: [
            ...stars.data.filter(star => star.id !== id)
          ]
        }
      };

      store.writeQuery({
        query: gql.query.USERS,
        variables: {
          ...this.queryVars,
          pageNumber: 1
        },
        data: updatedStars
      });
    }
  },

  apollo: {
    starsList() {
      return {
        client: this.apolloClient,
        query: gql.query.USERS,
        variables: this.queryVars,
        fetchPolicy: 'network-only',

        update(commonObject) {
          let to = Object.values(commonObject)[0].to;
          let total = Object.values(commonObject)[0].total;
          let data = Object.values(commonObject)[0].data;
          this.isLoading = false;
          this.$emit('initialized', {
            data,
            success: true,
            error: null
          });
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
  },
};
</script>

<style
  scoped
  lang="scss"
  src="./UniversalStarsContainer.scss"
/>
