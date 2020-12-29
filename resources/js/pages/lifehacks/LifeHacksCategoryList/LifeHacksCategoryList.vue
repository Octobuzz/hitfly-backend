<template>
  <v-simplebar ref="root" class="life-hacks-category-list">
    <span class="h3 life-hacks-category-list__sidebar-header">
      Разделы
    </span>

    <template v-if="categories.length">
      <ul class="life-hacks-category-list__list">
        <li
          v-for="category in categories"
          :key="category.id"
          :class="[
            'life-hacks-category-list__list-item-wrapper',
            {
              'life-hacks-category-list__list-item-wrapper_selected': category.id
                === selectedCategoryId
            }
          ]"
          @click="setSelectedCategoryId(category.id)"
        >
          <span class="life-hacks-category-list__list-item">
            {{ category.name }}
          </span>
        </li>
      </ul>
      <SpinnerLoader
        v-if="hasMoreData"
        class="life-hacks-category-list__list-loader"
        size="small"
      />
    </template>

    <SpinnerLoader
      v-if="!categories.length && hasMoreData"
      class="life-hacks-category-list__main-loader"
    />

    <p
      v-if="!categories.length && !hasMoreData"
      class="life-hacks-category-list__empty"
    >
      Список лайфхаков пуст
    </p>
  </v-simplebar>
</template>

<script>
import VSimplebar from 'simplebar-vue';
import 'simplebar/dist/simplebar.min.css';
import debounce from 'lodash.debounce';
import { mapGetters, mapMutations } from 'vuex';
import SpinnerLoader from 'components/SpinnerLoader.vue';
import gql from './gql';

export default {
  components: {
    VSimplebar,
    SpinnerLoader
  },

  data() {
    return {
      categories: [],
      isLoading: true,
      hasMoreData: true,
      queryVars: {
        pageNumber: 1,
        pageLimit: 30
      }
    };
  },

  computed: {
    ...mapGetters({
      selectedCategoryId: 'selectedLifeHacksCategoryId'
    })
  },

  mounted() {
    this.$refs.root.scrollElement
      .addEventListener('scroll', this.loadOnScroll);
  },

  beforeDestroy() {
    this.$refs.root.scrollElement
      .removeEventListener('scroll', this.loadOnScroll);
  },

  methods: {
    fetchMoreCategories(vars) {
      return this.$apollo.queries.categories.fetchMore({
        variables: vars,

        updateQuery: (currentList, { fetchMoreResult: { tag } }) => {
          const { total, to, data: newTags } = tag;

          return {
            tag: {
              // eslint-disable-next-line no-underscore-dangle
              __typename: currentList.tag.__typename,
              total,
              to,
              data: [...currentList.tag.data, ...newTags]
            }
          };
        },
      });
    },

    loadMore() {
      if (this.isLoading) return;

      this.isLoading = true;

      this.fetchMoreCategories({
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

    loadOnScroll() {
      if (!this.debouncedOnScroll) {
        this.debouncedOnScroll = debounce(() => {
          const container = this.$refs.root.scrollElement;
          const { clientHeight: scrollClientHeight, scrollHeight, scrollTop } = container;

          // keep match value small cause it approaches scroller border from bottom
          // this approach also allows not to check empty bottom space

          const maybeLoadMore = Math.abs(
            (scrollClientHeight + scrollTop) - scrollHeight
          ) <= 15;

          if (maybeLoadMore && this.hasMoreData) {
            this.loadMore();
          }
        });
      }

      if (!this.loadOnScrollDisabled) {
        this.debouncedOnScroll();
      }
    },

    attemptToLoadMore() {
      this.loadOnScroll();
    },

    ...mapMutations({
      setSelectedCategoryId: 'setSelectedLifeHacksCategoryId'
    })
  },

  apollo: {
    categories: {
      client: 'public',
      fetchPolicy: 'network-only',
      query: gql.query.LIFE_HACKS_CATEGORY_LIST,
      variables() {
        return this.queryVars;
      },
      update({ tag: { total, to, data } }) {
        this.isLoading = false;
        this.hasMoreData = to < total;

        if (data.length && !this.selectedCategoryId) {
          this.setSelectedCategoryId(+data[0].id);
        }

        // check if the screen has empty space to load more categories

        this.$nextTick(() => {
          if (!this.hasMoreData) return;

          const componentRoot = this.$refs.root;
          const mounted = componentRoot !== undefined;

          if (!mounted) {
            this.$once('hook:mounted', this.attemptToLoadMore.bind(this));

            return;
          }
          this.attemptToLoadMore();
        });

        return data;
      },
      error(err) {
        console.dir(err);
      }
    }
  }
};
</script>

<style
  scoped
  lang="scss"
  src="./LifeHacksCategoryList.scss"
/>
