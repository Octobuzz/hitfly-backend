<template>
  <div class="other-user-music">
    <!--TODO: tracks container-->

    <UniversalAlbumsContainer
      for-type="user-album-list"
      :for-id="userId"
    >
      <template #default="container">
        <AlbumScrollHorizontal
          class="other-user-music__albums-container"
          :header-class="containerPaddingClass"
          :album-id-list="container.albumIdList"
          :has-more-data="container.hasMoreData"
        >
          <template #title>
            <router-link :to="`/user/${userId}/music/albums`">
              <span class="h2 other-user-music__albums-title">
                Альбомы
              </span>
            </router-link>
          </template>
        </AlbumScrollHorizontal>
      </template>
    </UniversalAlbumsContainer>

    <UniversalCollectionsContainer
      for-type="user-playlist-list"
      :for-id="userId"
    >
      <template #default="container">
        <CollectionScrollHorizontal
          class="other-user-music__collections-container"
          :header-class="containerPaddingClass"
          :collection-id-list="container.collectionIdList"
          :has-more-data="container.hasMoreData"
        >
          <template #title>
            <router-link :to="`/user/${userId}/music/playlists`">
              <span class="h2 other-user-music__collections-title">
                Плейлисты
              </span>
            </router-link>
          </template>
        </CollectionScrollHorizontal>
      </template>
    </UniversalCollectionsContainer>
  </div>
</template>

<script>
import containerPaddingClass from 'mixins/containerPaddingClass';
import AlbumScrollHorizontal from 'components/AlbumScrollHorizontal';
import CollectionScrollHorizontal from 'components/CollectionScrollHorizontal';
import UniversalAlbumsContainer from '../UniversalAlbumsContainer';
import UniversalCollectionsContainer from '../UniversalCollectionsContainer';

export default {
  components: {
    AlbumScrollHorizontal,
    CollectionScrollHorizontal,
    UniversalAlbumsContainer,
    UniversalCollectionsContainer
  },

  mixins: [containerPaddingClass],

  computed: {
    userId() {
      return +this.$route.params.userId;
    }
  }
};
</script>

<style
  scoped
  lang="scss"
  src="./OtherUserMusic.scss"
/>
