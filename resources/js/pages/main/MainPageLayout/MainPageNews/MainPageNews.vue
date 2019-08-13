<template>
  <UniversalNewsContainer
    query="news"
  >
    <template #default="container">
      <NewsScroller
        class="other-user-music__albums-container"
        :header-class="containerPaddingClass"
        :news-list="container.newsList"
        :has-more-data="container.hasMoreData"
      >
      </NewsScroller>
    </template>
  </UniversalNewsContainer>
</template>
<script>
  import NewsScroller from 'components/NewsScroller/NewsScroller.vue';
  import UniversalNewsContainer from '../UniversalNewsContainer';
  import containerPaddingClass from 'mixins/containerPaddingClass';

  export default {
    mixins: [containerPaddingClass],
    components: {
      NewsScroller,
      UniversalNewsContainer
    },
    data: () => ({

    }),

    computed: {
      containerPaddingClass() {
        return this.$store.getters['appColumns/paddingClass'];
      },
      mobileUser() {
        return /Mobi|Android/i.test(navigator.userAgent);
      },
      newsContainerInitialized() {
        return this.$store.getters['loading/mainPage'].news;
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
