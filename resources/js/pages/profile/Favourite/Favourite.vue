<template>
  <div class="favourite">
    <FavouriteTracksContainer />

    <FavouriteAlbumsContainer>
      <template #default="container">
        <AlbumScrollHorizontal
          class="favourite__albums-container"
          :header-class="containerPaddingClass"
          :album-id-list="container.albumIdList"
          :has-more-data="container.hasMoreData"
        >
          <template #title>
            <router-link to="/profile/favourite/albums">
              <span class="h2 favourite__albums-title">
                Любимые альбомы
              </span>
            </router-link>
          </template>
        </AlbumScrollHorizontal>
      </template>
    </FavouriteAlbumsContainer>

    <FavouriteCollectionsContainer>
      <template #default="container">
        <CollectionScrollHorizontal
          class="favourite__collections-container"
          :header-class="containerPaddingClass"
          :collection-id-list="container.collectionIdList"
          :has-more-data="container.hasMoreData"
        >
          <template #title>
            <router-link to="/profile/favourite/playlists">
              <span class="h2 favourite__collections-title">
                Любимые плейлисты
              </span>
            </router-link>
          </template>
        </CollectionScrollHorizontal>
      </template>
    </FavouriteCollectionsContainer>

    <FavouriteSetsContainer>
      <template #default="container">
        <CollectionScrollHorizontal
          class="favourite__collections-container"
          :header-class="containerPaddingClass"
          :collection-id-list="container.collectionIdList"
          :has-more-data="container.hasMoreData"
        >
          <template #title>
            <router-link to="/profile/favourite/sets">
              <span class="h2 favourite__collections-title">
                Любимые подборки
              </span>
            </router-link>
          </template>
        </CollectionScrollHorizontal>
      </template>
    </FavouriteSetsContainer>
  </div>
</template>

<script>
import AlbumScrollHorizontal from 'components/AlbumScrollHorizontal';
import CollectionScrollHorizontal from 'components/CollectionScrollHorizontal';
import FavouriteTracksContainer from '../FavouriteTracksContainer';
import FavouriteAlbumsContainer from '../FavouriteAlbumsContainer';
import FavouriteCollectionsContainer from '../FavouriteCollectionsContainer';
import FavouriteSetsContainer from '../FavouriteSetsContainer';

export default {
  components: {
    AlbumScrollHorizontal,
    CollectionScrollHorizontal,
    FavouriteTracksContainer,
    FavouriteAlbumsContainer,
    FavouriteCollectionsContainer,
    FavouriteSetsContainer
  },

  computed: {
    containerPaddingClass() {
      return this.$store.getters['appColumns/paddingClass'];
    }
  },

  beforeRouteLeave(to, from, next) {
    this.$store.commit('loading/setFavourite', {
      tracks: {
        initialized: false
      },
      albums: {
        initialized: false
      },
      collections: {
        initialized: false
      },
      sets: {
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
  src="./Favourite.scss"
/>
