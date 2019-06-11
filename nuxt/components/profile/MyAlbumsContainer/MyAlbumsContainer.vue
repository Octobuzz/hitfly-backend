<template>
  <div>
    <AlbumScrollHorizontal
      v-show="albumListLength"
      class="my-albums-container"
      :header-class="containerPaddingClass"
      :album-id-list="albumIdList"
      :has-more-data="hasMoreData"
      @load-more="onLoadMore"
    >
      <template #title>
        <span class="h2 my-albums-container__title">
          Альбомы
        </span>
      </template>
    </AlbumScrollHorizontal>
  </div>
</template>

<script>
import AlbumScrollHorizontal from '~/components/shared/AlbumScrollHorizontal';
import gql from './gql';

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
        pageLimit: 30,
        my: true
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
      return this.$store.getters['app-columns/paddingClass'];
    }
  },

  methods: {
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
              data: [...currentList.albums.data, ...newAlbums]
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
        query: gql.query.ALBUMS,
        variables: this.queryVars,
        fetchPolicy: 'network-only',

        update({ albums: { total, to, data } }) {
          this.isLoading = false;
          if (!this.dataInitialized) {
            this.dataInitialized = true;
            this.$emit('data-initialized');
          }

          if (to >= total) {
            this.hasMoreData = false;
          }

          return data;
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
  src="./MyAlbumsContainer.scss"
/>
