<template>
  <UniversalCollectionsContainer
    for-type="recommended-tracks"
    for-id="collection"
  >
    <template #default="container">
      <CollectionScrollHorizontal
        class="my-music__collections-container"
        :header-class="containerPaddingClass"
        :collection-id-list="container.collectionIdList"
        :has-more-data="container.hasMoreData"
      >
        <template #title>
          <router-link to="/recommended">
            <span class="h2">
              Рекомендуем
            </span>
            <span class="universal-collections-container__subtitle">
              Плейлисты, собранные специально для тебя
            </span>
          </router-link>
        </template>
      </CollectionScrollHorizontal>
    </template>
  </UniversalCollectionsContainer>
</template>
<script>
import UniversalCollectionsContainer from '../UniversalCollectionsContainer/UniversalCollectionsContainer.vue';
import CollectionScrollHorizontal from 'components/CollectionScrollHorizontal';

export default {
  data: () => ({

  }),

  components: {
    UniversalCollectionsContainer,
    CollectionScrollHorizontal
  },

  computed: {
    containerPaddingClass() {
      return this.$store.getters['appColumns/paddingClass'];
    },
    mobileUser() {
      return /Mobi|Android/i.test(navigator.userAgent);
    },
    tracksContainerInitialized() {
      return this.$store.getters['loading/mainPage'].recommended;
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
    onTracksContainerInitialized({ success }) {
      this.$store.commit('loading/setMainPage', {
        recommended: {
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
  src="./RecommendedTracks.scss"
>
</style>
