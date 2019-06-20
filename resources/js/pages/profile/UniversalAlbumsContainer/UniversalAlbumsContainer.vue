<template>
  <div>
    <slot
      v-if="albumList.length > 0"
      name="default"
      :album-id-list="albumIdList"
      :has-more-data="hasMoreData"
    />
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
import gql from './gql';

export default {
  data() {
    return {
      albumList: [],
      isLoading: true,
      hasMoreData: true,
      queryVars: {
        pageNumber: 1,
        pageLimit: 30,
        my: true
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
    }
  },

  mounted() {
    this.$on('load-more', this.onLoadMore.bind(this));
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
    }
  },

  apollo: {
    albumList() {
      return {
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