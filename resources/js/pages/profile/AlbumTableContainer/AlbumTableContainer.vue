<template>
  <div :class="['album-table-container', containerPaddingClass]">
    <ReturnHeader />

    <span class="h2 album-table-container__header">
      {{ header }}
    </span>

    <UniversalAlbumsContainer
      v-if="containerComponent === 'universal'"
    >
      <template #default="container">
        <AlbumTable
          :album-id-list="container.albumIdList"
          :has-more-data="container.hasMoreData"
        />
      </template>
    </UniversalAlbumsContainer>

    <FavouriteAlbumsContainer v-else>
      <template #default="container">
        <AlbumTable
          :album-id-list="container.albumIdList"
          :has-more-data="container.hasMoreData"
        />
      </template>
    </FavouriteAlbumsContainer>
  </div>
</template>

<script>
import ReturnHeader from '../ReturnHeader.vue';
import UniversalAlbumsContainer from '../UniversalAlbumsContainer';
import FavouriteAlbumsContainer from '../FavouriteAlbumsContainer';
import AlbumTable from '../AlbumTable';

export default {
  components: {
    ReturnHeader,
    UniversalAlbumsContainer,
    FavouriteAlbumsContainer,
    AlbumTable
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
          return 'Мои альбомы';
        }
        if (path[2] === 'favourite') {
          return 'Любимые альбомы';
        }
      }
      return 'Альбомы';
    },

    containerComponent() {
      const { fullPath } = this.$route;
      const path = fullPath.split('/');

      if (path[2] === 'favourite') {
        return 'favourite';
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
  src="./AlbumTableContainer.scss"
/>
