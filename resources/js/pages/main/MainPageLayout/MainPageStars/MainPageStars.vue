<template>
  <UniversalStarsContainer>
    <template #default="container">
      <StarsScroller
        class="other-user-music__albums-container"
        :header-class="containerPaddingClass"
        :stars-list="container.starsList"
        :has-more-data="container.hasMoreData"
      >
        <template #title>
          <span class="h2 other-user-music__albums-title">
            Звёздные эксперты
          </span>
        </template>
      </StarsScroller>
    </template>
  </UniversalStarsContainer>
</template>
<script>
  import UniversalStarsContainer from '../UniversalStarsContainer';
  import StarsScroller from 'components/StarsScroller';

  export default{
    data: () => ({

    }),

    components: {
      UniversalStarsContainer,
      StarsScroller
    },

    computed: {
      containerPaddingClass() {
        return this.$store.getters['appColumns/paddingClass'];
      },
      mobileUser() {
        return /Mobi|Android/i.test(navigator.userAgent);
      },
      starsContainerInitialized() {
        return this.$store.getters['loading/mainPage'].stars;
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
        stars: {
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
