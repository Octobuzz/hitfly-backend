<template>
  <UniversalGenresContainer>
    <template #default="container">
      <GenresScrollHorizontal
        class="other-user-music__albums-container"
        :header-class="containerPaddingClass"
        :genres-list="container.genresList"
        :has-more-data="container.hasMoreData"
      >
        <template #title>
          <span class="h2 other-user-music__albums-title">
            Жанры<br>
            <span class="scrollSubheader">
              Музыка по жанрам и настроению
            </span>
          </span>
        </template>
      </GenresScrollHorizontal>
    </template>
  </UniversalGenresContainer>
</template>
<script>
  import UniversalGenresContainer from '../UniversalGenresContainer';
  import GenresScrollHorizontal from 'components/GenresScrollHorizontal';

  export default{
    data: () => ({

    }),

    components: {
      UniversalGenresContainer,
      GenresScrollHorizontal
    },

    computed: {
      containerPaddingClass() {
        return this.$store.getters['appColumns/paddingClass'];
      },
      mobileUser() {
        return /Mobi|Android/i.test(navigator.userAgent);
      },
      genresContainerInitialized() {
        return this.$store.getters['loading/mainPage'].genres;
      }
    },

    beforeRouteLeave(to, from, next) {
      this.$store.commit('loading/setMainPage', {
        news: {
          initialized: false
        },
        recommended: {
          initialized: false
        },
        genres: {
          initialized: false
        },
        newTracks: {
          initialized: false
        },
        superMelomaniac: {
          initialized: false
        },
        weeklyTop: {
          initialized: false
        },
      });

      next();
    },
  }
</script>
<style
  scoped
  lang="scss"
  src="./MainPageGenres.scss"
/>
