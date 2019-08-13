<template>
  <section>
    <div
      v-show="!showLoader"
      class="mainPage__loader"
    >
      <SpinnerLoader />
    </div>

    <MainPageNews v-show="showLoader">

    </MainPageNews>

    <RecommendedTracks v-show="showLoader">

    </RecommendedTracks>

    <MainPageGenres v-show="showLoader">

    </MainPageGenres>

    <NewTracks v-show="showLoader">

    </NewTracks>

    <SuperMelomaniac v-show="showLoader">

    </SuperMelomaniac>

    <WeeklyTop v-show="showLoader">

    </WeeklyTop>

    <MainColumnFooter v-show="showLoader">

    </MainColumnFooter>
  </section>
</template>
<script>
  import SpinnerLoader from 'components/SpinnerLoader.vue';
  import RecommendedTracks from '../RecommendedTracks/RecommendedTracks.vue';
  import SuperMelomaniac from '../SuperMelomaniac/SuperMelomaniac.vue';
  import NewTracks from '../NewTracks/NewTracks.vue';
  import WeeklyTop from '../WeeklyTop/WeeklyTop.vue';
  import MainPageGenres from '../MainPageGenres/MainPageGenres.vue';
  import MainPageNews from '../MainPageNews/MainPageNews.vue';
  import MainColumnFooter from 'components/MainColumnFooter.vue';

  export default {
    data: () => ({

    }),
    components: {
      SpinnerLoader,
      RecommendedTracks,
      SuperMelomaniac,
      NewTracks,
      WeeklyTop,
      MainPageGenres,
      MainColumnFooter,
      MainPageNews
    },
    computed: {
      showLoader() {
        let loadingObjects = Object.keys(this.$store.getters['loading/mainPage']);
        let storageObj = this.$store.getters['loading/mainPage'];
        let initCount = loadingObjects.filter(object => storageObj[object].initialized);
        return initCount.length >= 6;
      }
    }
  }
</script>
<style
  scoped
  lang="scss"
  src="./MainPageDefault.scss"
/>
