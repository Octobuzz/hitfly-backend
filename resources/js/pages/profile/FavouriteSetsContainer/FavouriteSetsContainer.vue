<template>
  <div>
    <CollectionScrollHorizontal
      v-show="collectionListLength"
      class="my-collections-container"
      :header-class="containerPaddingClass"
      :collection-id-list="collectionIdList"
      :has-more-data="hasMoreData"
      @load-more="onLoadMore"
    >
      <template #title>
        <span class="h2 my-collections-container__title">
          Любимые подборки
        </span>
      </template>
    </CollectionScrollHorizontal>
  </div>
</template>

<script>
import CollectionScrollHorizontal from 'components/CollectionScrollHorizontal';
import gql from './gql';

export default {
  components: {
    CollectionScrollHorizontal
  },

  props: {
    containerPaddingClass: {
      type: String,
      default: ''
    }
  },

  data() {
    return {
      collectionList: [],
      isLoading: true,
      hasMoreData: true,
      queryVars: {
        pageNumber: 1,
        pageLimit: 10
      },
      dataInitialized: false
    };
  },

  computed: {
    collectionIdList() {
      return this.collectionList.map(collection => collection.id);
    },

    collectionListLength() {
      return this.collectionList.length > 0;
    }
  },

  methods: {
    fetchMoreCollections(vars) {
      return this.$apollo.queries.collectionList.fetchMore({
        variables: vars,

        updateQuery: (currentList, { fetchMoreResult: { favouriteSet } }) => {
          const { total, to, data: newCollections } = favouriteSet;

          return {
            favouriteSet: {
              // eslint-disable-next-line no-underscore-dangle
              __typename: currentList.favouriteSet.__typename,
              total,
              to,
              data: [...currentList.favouriteSet.data, ...newCollections]
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
          console.log(err);
        });
    }
  },

  apollo: {
    collectionList() {
      return {
        query: gql.query.FAVOURITE_SETS,
        variables: this.queryVars,

        update({ favouriteSet: { total, to, data } }) {
          this.isLoading = false;
          if (!this.dataInitialized) {
            this.dataInitialized = true;
            this.$emit('data-initialized');
          }

          if (to >= total) {
            this.hasMoreData = false;
          }

          return data.map(favSet => favSet.collection);
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
  src="../MyCollectionsContainer/MyCollectionsContainer.scss"
/>
