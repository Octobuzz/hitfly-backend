<template>
  <div>
    <slot
      v-if="collectionList.length > 0"
      name="default"
      :collection-id-list="collectionIdList"
      :has-more-data="hasMoreData"
    />
    <slot v-else-if="!isLoading" name="no-data" />
    <p
      v-if="initialFetchError"
      class="universal-collections-container__error"
    >
      На сервере произошла ошибка. Не удалось загрузить данные плейлистов.
      Пожалуйста, попробуйте позже или обратитесь к администратору.
    </p>
  </div>
</template>

<script>
import { mapGetters } from 'vuex';
import gql from './gql';

export default {
  props: {
    forType: {
      validator: val => [
        'user-playlist-list',
        'music-group-playlist-list'
      ].includes(val),
      required: true
    },

    forId: {
      validator: val => (
        val === 'me' || typeof val === 'number'
      ),
      required: true
    },
  },

  data() {
    const { forType, forId } = this;
    const filters = {};

    switch (forType) {
      case 'user-playlist-list':
        if (forId === 'me') {
          filters.my = true;
        } else {
          filters.userId = forId;
        }
        break;

      case 'music-group-playlist-list':
        filters.musicGroupId = forId;
        break;

      default:
        throw new Error(
          `Incorrect value for property "forType" passed to UniversalCollectionsContainer: ${forType}`
        );
    }

    return {
      collectionList: [],
      isLoading: true,
      hasMoreData: true,
      queryVars: {
        // do not use computed cause it is not defined yet
        isAuthenticated: this.$store.getters.isAuthenticated,
        pageNumber: 1,
        pageLimit: 10,
        filters
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
    },

    ...mapGetters(['apolloClient'])
  },

  mounted() {
    this.$on('load-more', this.onLoadMore.bind(this));

    this.boundRemoveCollectionFromStore = this.removeCollectionFromStore.bind(this);

    this.$eventBus.$on(
      'collection-removed',
      this.boundRemoveCollectionFromStore
    );
  },

  beforeDestroy() {
    this.$eventBus.$off(
      'collection-removed',
      this.boundRemoveCollectionFromStore
    );
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
    },

    removeCollectionFromStore(id) {
      const store = this.$apollo.provider.clients[this.apolloClient];

      try {
        const { collections } = store.readQuery({
          query: gql.query.COLLECTIONS,

          // cause updateQuery does not update variables in cache entry
          // we always refer to the first page

          variables: {
            ...this.queryVars,
            pageNumber: 1
          }
        });

        const updatedCollections = {
          collections: {
            ...collections,
            data: [
              ...collections.data.filter(col => col.id !== id)
            ]
          }
        };

        store.writeQuery({
          query: gql.query.COLLECTIONS,
          variables: {
            ...this.queryVars,
            pageNumber: 1
          },
          data: updatedCollections
        });
      } catch (e) {
        // no data to read from store
      }

      try {
        const { collections: myCollections } = store.readQuery({
          query: gql.query.MY_COLLECTIONS
        });

        const updatedMyCollections = {
          collections: {
            ...myCollections,
            data: [
              ...myCollections.data.filter(col => col.id !== id)
            ]
          }
        };

        store.writeQuery({
          query: gql.query.MY_COLLECTIONS,
          data: updatedMyCollections
        });
      } catch (e) {
        // no data to read from store
      }
    }
  },

  apollo: {
    collectionList() {
      return {
        client: this.apolloClient,
        query: gql.query.COLLECTIONS,
        variables: {
          ...this.queryVars
        },
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
