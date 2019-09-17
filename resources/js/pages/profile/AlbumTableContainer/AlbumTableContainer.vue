<template>
  <div :class="['album-table-container', containerPaddingClass]">
    <ReturnHeader />

    <span class="h2 album-table-container__header">
      {{ header }}
      <router-link
        v-if="inMyMusic"
        class="album-table-container__button"
        tag="button"
        to="/profile/create/album"
      >
        Создать новый альбом
      </router-link>
    </span>

    <UniversalAlbumsContainer
      v-if="containerComponent === 'universal'"
      :for-type="universalContainerProps.forType"
      :for-id="universalContainerProps.forId"
    >
      <template #default="container">
        <AlbumTable
          :album-id-list="container.albumIdList"
          :has-more-data="container.hasMoreData"
        />
      </template>
      <template #no-data>
        <p>
          В списке нет ни одного альбома.
        </p>
      </template>
    </UniversalAlbumsContainer>

    <FavouriteAlbumsContainer
      v-if="containerComponent === 'favourite'"
    >
      <template #default="container">
        <AlbumTable
          :album-id-list="container.albumIdList"
          :has-more-data="container.hasMoreData"
        />
      </template>
      <template #no-data>
        <p>
          В списке нет ни одного альбома.
        </p>
      </template>
    </FavouriteAlbumsContainer>
  </div>
</template>

<script>
import currentPath from 'mixins/currentPath';
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

  mixins: [currentPath],

  computed: {
    // TODO: music-group albums in switches

    containerPaddingClass() {
      return this.$store.getters['appColumns/paddingClass'];
    },

    inMyMusic() {
      return this.$route.fullPath === '/profile/my-music/albums';
    },

    header() {
      switch (this.currentPath) {
        case '/profile/my-music/albums':
          return 'Мои альбомы';

        case '/profile/favourite/albums':
          return 'Любимые альбомы';

        case '/user/:userId/music/albums':
          return 'Альбомы';

        default:
          return 'Альбомы';
      }
    },

    containerComponent() {
      switch (this.currentPath) {
        case '/profile/my-music/albums':
        case '/user/:userId/music/albums':
          return 'universal';

        case '/profile/favourite/albums':
          return 'favourite';

        default:
          return 'universal';
      }
    },

    universalContainerProps() {
      const { params } = this.$route;

      switch (this.currentPath) {
        case '/profile/my-music/albums':
          return {
            forType: 'user-album-list',
            forId: 'me'
          };

        case '/user/:userId/music/albums':
          return {
            forType: 'user-album-list',
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
  src="./AlbumTableContainer.scss"
/>
