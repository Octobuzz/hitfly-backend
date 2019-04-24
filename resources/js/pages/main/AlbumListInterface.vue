<template>
  <div>
    <AlbumScrollHorizontal
      :album-id-list="albumIdList"
      :has-more-data="hasMoreData"
      @load-more="onLoadMore"
    >
      <template #title>
        <h2 style="margin: 0;">
          Альбомы
        </h2>
      </template>
    </AlbumScrollHorizontal>
  </div>
</template>

<script>
import AlbumScrollHorizontal from 'components/AlbumScrollHorizontal';
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
        pageLimit: 30
      },
      showLoadTrigger: false
    };
  },

  computed: {
    albumIdList() {
      return this.albumList.map(album => album.id);
    }
  },

  methods: {
    fetchMoreAlbums(vars) {
      return this.$apollo.queries.albumList.fetchMore({
        variables: vars,

        updateQuery: (currentList, { fetchMoreResult: { albums } }) => {
          const { total, to, data: newAlbums } = albums;

          if (to === total) {
            this.hasMoreData = false;
          }

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
        query: gql.query.ALBUM_LIST,
        variables: this.queryVars,

        update({ albums: { total, to, data } }) {
          this.isLoading = false;
          this.showLoadTrigger = true;
          if (to === total) {
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
>

</style>
