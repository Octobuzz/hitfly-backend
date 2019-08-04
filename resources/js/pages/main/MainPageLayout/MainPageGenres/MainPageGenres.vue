<template>
  <UniversalGenresContainer>
    <template #default="container">
      <GenresScrollHorizontal
        class="other-user-music__albums-container"
        :header-class="containerPaddingClass"
        :genres-id-list="container.genresIdList"
        :has-more-data="container.hasMoreData"
      >
        <template #title>
          <router-link to="/">
            <span class="h2 other-user-music__albums-title">
              Жанры
            </span>
            <span class="scrollSubheader">
              Музыка по жанрам и настроению
            </span>
          </router-link>
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

    methods: {
      onGenresContainerInitialized({ success }) {
        this.$store.commit('loading/setMainPage', {
          genres: {
            success,
            initialized: true
          }
        });
      },
    },
  }
</script>
<style
  scoped
  lang="scss"
  src="./MainPageGenres.scss"
/>
