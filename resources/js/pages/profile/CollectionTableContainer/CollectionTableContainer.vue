<template>
  <div :class="['collection-table-container', containerPaddingClass]">
    <ReturnHeader />

    <span class="h2 collection-table-container__header">
      {{ header }}
    </span>

    <UniversalCollectionsContainer
      v-if="containerComponent === 'universal'"
      :for-type="universalContainerProps.forType"
      :for-id="universalContainerProps.forId"
    >
      <template #default="container">
        <CollectionTable
          :collection-id-list="container.collectionIdList"
          :has-more-data="container.hasMoreData"
        />
      </template>
    </UniversalCollectionsContainer>

    <FavouriteCollectionsContainer
      v-if="containerComponent === 'favourite-playlists'"
    >
      <template #default="container">
        <CollectionTable
          :collection-id-list="container.collectionIdList"
          :has-more-data="container.hasMoreData"
        />
      </template>
    </FavouriteCollectionsContainer>

    <FavouriteSetsContainer
      v-if="containerComponent === 'favourite-sets'"
    >
      <template #default="container">
        <CollectionTable
          :collection-id-list="container.collectionIdList"
          :has-more-data="container.hasMoreData"
        />
      </template>
    </FavouriteSetsContainer>
  </div>
</template>

<script>
import currentPath from 'mixins/currentPath';
import ReturnHeader from '../ReturnHeader.vue';
import UniversalCollectionsContainer from '../UniversalCollectionsContainer';
import FavouriteCollectionsContainer from '../FavouriteCollectionsContainer';
import FavouriteSetsContainer from '../FavouriteSetsContainer';
import CollectionTable from '../CollectionTable';

export default {
  components: {
    ReturnHeader,
    UniversalCollectionsContainer,
    FavouriteCollectionsContainer,
    FavouriteSetsContainer,
    CollectionTable
  },

  mixins: [currentPath],

  computed: {
    // TODO: music-group playlists in switches

    containerPaddingClass() {
      return this.$store.getters['appColumns/paddingClass'];
    },

    header() {
      switch (this.currentPath) {
        case '/profile/my-music/playlists':
          return 'Мои плейлисты';

        case '/profile/favourite/playlists':
          return 'Любимые плейлисты';

        case '/profile/favourite/sets':
          return 'Любимые подборки';

        case '/user/:userId/music/playlists':
          return 'Плейлисты';

        default:
          return 'Плейлисты';
      }
    },

    containerComponent() {
      switch (this.currentPath) {
        case '/profile/my-music/playlists':
        case '/user/:userId/music/playlists':
          return 'universal';

        case '/profile/favourite/playlists':
          return 'favourite-playlists';

        case '/profile/favourite/sets':
          return 'favourite-sets';

        default:
          return 'universal';
      }
    },

    universalContainerProps() {
      const { params } = this.$route;

      switch (this.currentPath) {
        case '/profile/my-music/playlists':
          return {
            forType: 'user-playlist-list',
            forId: 'me'
          };

        case '/user/:userId/music/playlists':
          return {
            forType: 'user-playlist-list',
            forId: +params.userId
          };

        default:
          return {};
      }
    }
  }
};
</script>

<style
  scoped
  lang="scss"
  src="./CollectionTableContainer.scss"
/>
