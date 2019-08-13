<template>
  <div>
    <slot
      v-if="newsList.length > 0"
      name="default"
      :news-list="newsList"
      :has-more-data="hasMoreData"
    />
    <p
      v-if="initialFetchError"
      class="universal-news-container__error"
    >
      На сервере произошла ошибка. Не удалось загрузить данные альбомов.
      Пожалуйста, попробуйте позже или обратитесь к администратору.
    </p>
  </div>
</template>

<script>
import gql from './gql';

export default {
  props: {
    query: {
      type: String,
      required: true
    }
  },
  data() {
    return {
      newsList: [],
      isLoading: true,
      hasMoreData: true,
      queryVars: {
        pageNumber: 1,
        pageLimit: 30
      }
    };
  },

  computed: {
    initialFetchError() {
      const loading = this.$store.getters['loading/mainPage'].news;

      return loading.initialized && !loading.success;
    }
  },

  mounted() {
    this.$on('load-more', this.onLoadMore.bind(this));

    this.boundRemoveNewsFromStore = this.removeNewsFromStore.bind(this);

    this.$eventBus.$on(
      'news-removed',
      this.boundRemoveNewsFromStore
    );
  },

  beforeDestroy() {
    this.$eventBus.$off(
      'news-removed',
      this.boundRemoveNewsFromStore
    );
  },

  methods: {
    notifyInitialization(success) {
      this.$store.commit('loading/setMainPage', {news: {
        initialized: true,
        success
      }});
    },

    fetchMoreNews(vars) {
      return this.$apollo.queries.newsList.fetchMore({
        variables: vars,

        updateQuery: (currentList, { fetchMoreResult: { news } }) => {
          const { total, to, data: newNews } = news;

          return {
            news: {
              // eslint-disable-next-line no-underscore-dangle
              __typename: currentList.tracks.__typename,
              total,
              to,
              data: [
                ...currentList.news.data,
                ...newNews
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

      this.fetchMoreNews({
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

    removeNewsFromStore(id) {
      const store = this.$apollo.provider.defaultClient;

      const { news } = store.readQuery({
        query: gql.query.NEWS,

        // cause updateQuery does not update variables in cache entry
        // we always refer to the first page

        variables: {
          ...this.queryVars,
          pageNumber: 1
        }
      });

      const updatedNews = {
        news: {
          ...news,
          data: [
            ...news.data.filter(track => news.id !== id)
          ]
        }
      };

      store.writeQuery({
        query: gql.query.NEWS,
        variables: {
          ...this.queryVars,
          pageNumber: 1
        },
        data: updatedNews
      });
    }
  },

  apollo: {
    newsList() {
      return {
        query: gql.query.NEWS,
        variables: this.queryVars,
        fetchPolicy: 'network-only',

        update(commonObject) {
          let to = Object.values(commonObject)[0].to;
          let total = Object.values(commonObject)[0].total;
          let data = Object.values(commonObject)[0].data;
          this.isLoading = false;
          this.$emit('initialized', {
            data,
            success: true,
            error: null
          });
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
  src="./UniversalNewsContainer.scss"
/>
