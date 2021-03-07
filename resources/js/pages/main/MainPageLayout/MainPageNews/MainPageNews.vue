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
        <template #title>
          <span class="h2" style="margin: 0">
            Новости
          </span>
        </template>
      </NewsScroller>
    </template>
  </UniversalNewsContainer>
</template>
<script>
import NewsScroller from 'components/NewsScroller/NewsScroller.vue';
import containerPaddingClass from 'mixins/containerPaddingClass';
import UniversalNewsContainer from '../UniversalNewsContainer';

export default {
  components: {
    NewsScroller,
    UniversalNewsContainer
  },
  mixins: [containerPaddingClass],
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
};
</script>
