<template>
  <div class="my-music">
    <UniversalTracksContainer
      for-type="user"
      for-id="me"
      :shown-tracks-count="5"
      @initialized="onTracksContainerInitialized"
    >
      <template #default="container">
        <TrackList
          v-if="container.trackIdList.length > 0"
          :class="[
            containerPaddingClass,
            'my-music__my-tracks'
          ]"
          :track-id-list="container.trackIdList"
          :show-remove-button="true"
        >
          <template #header>
            <div class="my-music__my-tracks-header">
              <span class="h2">
                Мои песни
              </span>
              <router-link
                to="/profile/my-music/tracks"
                class="my-music__my-tracks-header-button"
              >
                Все песни
              </router-link>
            </div>
          </template>
        </TrackList>
      </template>
    </UniversalTracksContainer>

    <UniversalAlbumsContainer
      for-type="user-album-list"
      for-id="me"
    >
      <template #default="container">
        <AlbumScrollHorizontal
          class="my-music__albums-container"
          :header-class="containerPaddingClass"
          :album-id-list="container.albumIdList"
          :has-more-data="container.hasMoreData"
        >
          <template #title>
            <router-link to="/profile/my-music/albums">
              <span class="h2 my-music__albums-title">
                Мои альбомы
              </span>
            </router-link>
          </template>
        </AlbumScrollHorizontal>
      </template>
    </UniversalAlbumsContainer>

    <UniversalCollectionsContainer
      for-type="user-playlist-list"
      for-id="me"
    >
      <template #default="container">
        <CollectionScrollHorizontal
          class="my-music__collections-container"
          :header-class="containerPaddingClass"
          :collection-id-list="container.collectionIdList"
          :has-more-data="container.hasMoreData"
        >
          <template #title>
            <router-link to="/profile/my-music/playlists">
              <span class="h2 my-music__collections-title">
                Мои плейлисты
              </span>
            </router-link>
          </template>
        </CollectionScrollHorizontal>
      </template>
    </UniversalCollectionsContainer>
  </div>
</template>

<script>
import TrackList from 'components/trackList/TrackList';
import UniversalTracksContainer from 'components/UniversalTracksContainer';
import AlbumScrollHorizontal from 'components/AlbumScrollHorizontal';
import CollectionScrollHorizontal from 'components/CollectionScrollHorizontal';
import UniversalAlbumsContainer from '../UniversalAlbumsContainer';
import UniversalCollectionsContainer from '../UniversalCollectionsContainer';

export default {
  components: {
    TrackList,
    UniversalTracksContainer,
    AlbumScrollHorizontal,
    CollectionScrollHorizontal,
    UniversalAlbumsContainer,
    UniversalCollectionsContainer
  },

  computed: {
    containerPaddingClass() {
      return this.$store.getters['appColumns/paddingClass'];
    }
  },

  beforeRouteLeave(to, from, next) {
    this.$store.commit('loading/setMusic', {
      tracks: {
        initialized: false
      },
      albums: {
        initialized: false
      },
      collections: {
        initialized: false
      }
    });

    next();
  },

  methods: {
    onTracksContainerInitialized({ success }) {
      this.$store.commit('loading/setMusic', {
        tracks: {
          success,
          initialized: true
        }
      });
    }
  }
};
</script>

<style
  scoped
  lang="scss"
  src="./MyMusic.scss"
/>
