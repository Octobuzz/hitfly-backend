<template>
  <div :class="['collection-table-container', containerPaddingClass]">
    <ReturnHeader />

    <span class="h2 collection-table-container__header">
      {{ header }}
    </span>

    <UniversalCollectionsContainer
      v-if="containerComponent === 'universal'"
    >
      <template #default="container">
        <CollectionTable
          :collection-id-list="container.collectionIdList"
          :has-more-data="container.hasMoreData"
        />
      </template>
    </UniversalCollectionsContainer>

    <FavouriteCollectionsContainer
      v-else-if="containerComponent === 'favouritePlaylists'"
    >
      <template #default="container">
        <CollectionTable
          :collection-id-list="container.collectionIdList"
          :has-more-data="container.hasMoreData"
        />
      </template>
    </FavouriteCollectionsContainer>

    <FavouriteSetsContainer v-else>
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

  computed: {
    containerPaddingClass() {
      return this.$store.getters['appColumns/paddingClass'];
    },

    header() {
      const { fullPath } = this.$route;
      const path = fullPath.split('/');

      if (path[1] === 'profile') {
        if (path[2] === 'my-music') {
          return 'Мои плейлисты';
        }
        if (path[2] === 'favourite') {
          if (path[3] === 'playlists') {
            return 'Любимые плейлисты';
          }
          if (path[3] === 'sets') {
            return 'Любимые подборки';
          }
        }
      }
      return 'Плейлисты';
    },

    containerComponent() {
      const { fullPath } = this.$route;
      const path = fullPath.split('/');

      if (path[2] === 'favourite') {
        if (path[3] === 'playlists') {
          return 'favouritePlaylists';
        }
        if (path[3] === 'sets') {
          return 'favouriteSets';
        }
      }
      return 'universal';
    },

    // TODO: container args depending on the route
  }
};
</script>

<style
  scoped
  lang="scss"
  src="./CollectionTableContainer.scss"
/>
