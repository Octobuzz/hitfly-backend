<template>
  <UniversalTracksContainer
    query="new_songs"
  >
    <template #default="container">
      <TrackScrollHorizontal
        class="other-user-music__albums-container"
        :header-class="containerPaddingClass"
        :track-id-list="container.trackIdList"
        :has-more-data="container.hasMoreData"
      >
        <template #title>
          <router-link to="new_songs">
            <span class="h2 other-user-music__albums-title">
              Новое
            </span>
          </router-link>
        </template>
      </TrackScrollHorizontal>
    </template>
  </UniversalTracksContainer>
</template>
<script>
  import UniversalTracksContainer from '../UniversalTracksContainer';
  import TrackScrollHorizontal from 'components/TrackScrollHorizontal';

  export default{
    data: () => ({

    }),

    components: {
      UniversalTracksContainer,
      TrackScrollHorizontal
    },

    computed: {
      containerPaddingClass() {
        return this.$store.getters['appColumns/paddingClass'];
      },
      mobileUser() {
        return /Mobi|Android/i.test(navigator.userAgent);
      },
      tracksContainerInitialized() {
        return this.$store.getters['loading/mainPage'].newTracks;
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
          newTracks: {
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
  src="./NewTracks.scss"
/>
