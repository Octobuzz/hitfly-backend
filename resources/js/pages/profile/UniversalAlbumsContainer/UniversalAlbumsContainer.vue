<template>
  <div>
    <slot
      v-if="albumList.length > 0"
      name="default"
      :album-id-list="albumIdList"
      :has-more-data="hasMoreData"
    />
    <slot v-else-if="!isLoading" name="no-data" />
    <p
      v-if="initialFetchError"
      class="universal-albums-container__error"
    >
      На сервере произошла ошибка. Не удалось загрузить данные альбомов.
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
        'user-album-list',
        'music-group-album-list'
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
      case 'user-album-list':
        if (forId === 'me') {
          filters.my = true;
        } else {
          filters.userId = forId;
        }
        break;

      case 'music-group-album-list':
        filters.musicGroupId = forId;
        break;

      default:
        throw new Error(
          `Incorrect value for property "forType" passed to UniversalAlbumsContainer: ${forType}`
        );
    }

    return {
      albumList: [],
      isLoading: true,
      hasMoreData: true,
      queryVars: {
        isAuthenticated: this.$store.getters.isAuthenticated,
        pageNumber: 1,
        pageLimit: 30,
        filters
      }
    };
  },

  computed: {
    albumIdList() {
      return this.albumList.map(album => album.id);
    },

    initialFetchError() {
      const loading = this.$store.getters['loading/music'].albums;

      return loading.initialized && !loading.success;
    },

    ...mapGetters(['apolloClient'])
  },

  mounted() {
    this.$on('load-more', this.onLoadMore.bind(this));

    this.boundRemoveAlbumFromStore = this.removeAlbumFromStore.bind(this);

    this.$eventBus.$on(
      'album-removed',
      this.boundRemoveAlbumFromStore
    );
  },

  beforeDestroy() {
    this.$eventBus.$off(
      'album-removed',
      this.boundRemoveAlbumFromStore
    );
  },

  methods: {
    notifyInitialization(success) {
      this.$store.commit('loading/setMusic', {
        albums: {
          initialized: true,
          success
        }
      });
    },

    fetchMoreAlbums(vars) {
      return this.$apollo.queries.albumList.fetchMore({
        variables: vars,

        updateQuery: (currentList, { fetchMoreResult: { albums } }) => {
          const { total, to, data: newAlbums } = albums;

          return {
            albums: {
              // eslint-disable-next-line no-underscore-dangle
              __typename: currentList.albums.__typename,
              total,
              to,
              data: [
                ...currentList.albums.data,
                ...newAlbums
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

      this.fetchMoreAlbums({
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

    removeAlbumFromStore(id) {
      const store = this.$apollo.provider.clients[this.apolloClient];

      const { albums } = store.readQuery({
        query: gql.query.ALBUMS,

        // cause updateQuery does not update variables in cache entry
        // we always refer to the first page

        variables: {
          ...this.queryVars,
          pageNumber: 1
        }
      });

      const updatedAlbums = {
        albums: {
          ...albums,
          data: [
            ...albums.data.filter(album => album.id !== id)
          ]
        }
      };

      store.writeQuery({
        query: gql.query.ALBUMS,
        variables: {
          ...this.queryVars,
          pageNumber: 1
        },
        data: updatedAlbums
      });
    }
  },

  apollo: {
    albumList() {
      return {
        client: this.apolloClient,
        query: gql.query.ALBUMS,
        variables: this.queryVars,
        fetchPolicy: 'network-only',

        update({ albums: { total, to, data } }) {
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
  src="./UniversalAlbumsContainer.scss"
/>
