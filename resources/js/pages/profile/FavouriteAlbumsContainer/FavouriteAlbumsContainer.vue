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
      class="favourite-albums-container__error"
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
        pageLimit: 30
      }
    };
  },

  computed: {
    albumIdList() {
      return this.albumList.map(album => album.id);
    },

    initialFetchError() {
      const loading = this.$store.getters['loading/favourite'].albums;

      return loading.initialized && !loading.success;
    }
  },

  mounted() {
    this.$on('load-more', this.onLoadMore.bind(this));
  },

  methods: {
    notifyInitialization(success) {
      this.$store.commit('loading/setFavourite', {
        albums: {
          initialized: true,
          success
        }
      });
    },

    fetchMoreAlbums(vars) {
      return this.$apollo.queries.albumList.fetchMore({
        variables: vars,

        updateQuery: (currentList, { fetchMoreResult: { favouriteAlbum } }) => {
          const { total, to, data: newAlbums } = favouriteAlbum;

          return {
            favouriteAlbum: {
              // eslint-disable-next-line no-underscore-dangle
              __typename: currentList.favouriteAlbum.__typename,
              total,
              to,
              data: [
                ...currentList.favouriteAlbum.data,
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
        query: gql.query.FAVOURITE_ALBUMS,
        variables: this.queryVars,
        fetchPolicy: 'network-only',

        update({ favouriteAlbum: { total, to, data } }) {
          this.isLoading = false;
          this.notifyInitialization(true);

          if (to >= total) {
            this.hasMoreData = false;
          }

          return data.map(favAlbum => favAlbum.album);
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
  src="./FavouriteAlbumsContainer.scss"
/>
