<template>
  <div class="my-music">
    <MyTracksContainer
      :container-padding-class="containerPaddingClass"
    />

    <UniversalAlbumsContainer>
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

    <UniversalCollectionsContainer>
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
import AlbumScrollHorizontal from 'components/AlbumScrollHorizontal';
import CollectionScrollHorizontal from 'components/CollectionScrollHorizontal';
import UniversalAlbumsContainer from '../UniversalAlbumsContainer';
import UniversalCollectionsContainer from '../UniversalCollectionsContainer';
import MyTracksContainer from '../MyTracksContainer';

export default {
  components: {
    MyTracksContainer,
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
  }
};
</script>

<style
  scoped
  lang="scss"
  src="./MyMusic.scss"
/>
