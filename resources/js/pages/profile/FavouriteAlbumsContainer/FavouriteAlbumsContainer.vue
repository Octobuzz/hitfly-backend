<template>
  <div>
    <AlbumScrollHorizontal
      v-show="albumListLength"
      :class="['favourite-albums-container', $attrs.class]"
      :header-class="containerPaddingClass"
      :album-id-list="albumIdList"
      :has-more-data="hasMoreData"
      @load-more="onLoadMore"
    >
      <template #title>
        <span class="h2 favourite-albums-container__title">
          Любимые альбомы
        </span>
      </template>
    </AlbumScrollHorizontal>
  </div>
</template>

<script>
import AlbumScrollHorizontal from 'components/AlbumScrollHorizontal';
import gql from './gql';

// TODO: consider merge of this component into MyAlbumsComponent
// We could use js-module for different kind of parsing logic in the query update
// depending on a prop.

export default {
  components: {
    AlbumScrollHorizontal
  },

  data() {
    return {
      albumList: [],
      isLoading: true,
      hasMoreData: true,
      queryVars: {
        pageNumber: 1,
        pageLimit: 30
      },
      dataInitialized: false
    };
  },

  computed: {
    albumIdList() {
      return this.albumList.map(album => album.id);
    },

    albumListLength() {
      return this.albumList.length > 0;
    },

    containerPaddingClass() {
      return this.$store.getters['appColumns/paddingClass'];
    }
  },

  methods: {
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
              data: [...currentList.favouriteAlbum.data, ...newAlbums]
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
          console.log(err);
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
          if (!this.dataInitialized) {
            this.dataInitialized = true;
            this.$emit('data-initialized');
          }

          if (to >= total) {
            this.hasMoreData = false;
          }

          return data.map(favAlbum => favAlbum.album);
        },

        error(error) {
          console.log(error);
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
