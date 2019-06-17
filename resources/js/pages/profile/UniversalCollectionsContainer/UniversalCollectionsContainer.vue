<template>
  <div>
    <slot
      v-if="collectionList.length > 0"
      name="default"
      :collection-id-list="collectionIdList"
      :has-more-data="hasMoreData"
    />
    <p
      v-if="initialFetchError"
      class="favourite-collections-container__error"
    >
      На сервере произошла ошибка. Не удалось загрузить данные плейлистов.
      Пожалуйста, попробуйте позже или обратитесь к администратору.
    </p>
  </div>
</template>

<script>
import gql from './gql';

export default {
  data() {
    return {
      collectionList: [],
      isLoading: true,
      hasMoreData: true,
      queryVars: {
        pageNumber: 1,
        pageLimit: 10,
        my: true
      }
    };
  },

  computed: {
    collectionIdList() {
      return this.collectionList.map(collection => collection.id);
    },

    initialFetchError() {
      const loading = this.$store.getters['loading/music'].collections;

      return loading.initialized && !loading.success;
    }
  },

  mounted() {
    this.$on('load-more', this.onLoadMore.bind(this));
  },

  methods: {
    notifyInitialization(success) {
      this.$store.commit('loading/setMusic', {
        collections: {
          initialized: true,
          success
        }
      });
    },

    fetchMoreCollections(vars) {
      return this.$apollo.queries.collectionList.fetchMore({
        variables: vars,

        updateQuery: (currentList, { fetchMoreResult: { collections } }) => {
          const { total, to, data: newCollections } = collections;

          return {
            collections: {
              // eslint-disable-next-line no-underscore-dangle
              __typename: currentList.collections.__typename,
              total,
              to,
              data: [
                ...currentList.collections.data,
                ...newCollections
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

      this.fetchMoreCollections({
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
    collectionList() {
      return {
        query: gql.query.COLLECTIONS,
        variables: this.queryVars,
        fetchPolicy: 'network-only',

        update({ collections: { total, to, data } }) {
          this.isLoading = false;
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
  }
};
</script>

<style
  scoped
  lang="scss"
  src="./UniversalCollectionsContainer.scss"
/>
