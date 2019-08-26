<template>
  <div>
    <slot
      v-if="genresList.length > 0"
      name="default"
      :genres-list="genresList"
    />
    <p
      v-if="initialFetchError"
      class="universal-collections-container__error"
    >
      На сервере произошла ошибка. Не удалось загрузить данные жанров.
      Пожалуйста, попробуйте позже или обратитесь к администратору.
    </p>
  </div>
</template>
<script>
import gql from './gql';

export default {
  data() {
    return {
      genresList: [],
      isLoading: true
    };
  },

  computed: {
    initialFetchError() {
      const loading = this.$store.getters['loading/mainPage'].genres;

      return loading.initialized && !loading.success;
    }
  },

  methods: {
    notifyInitialization(success) {
      this.$store.commit('loading/setMainPage', {
        genres: {
          initialized: true,
          success
        }
      });
    },
  },

  apollo: {
    genresList() {
      return {
        query: gql.query.GENRES,
        fetchPolicy: 'network-only',

        update(data) {
          this.isLoading = false;
          this.notifyInitialization(true);

          this.hasMoreData = false;

          return data.genre;
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
  src="./UniversalGenresContainer.vue"
/>
