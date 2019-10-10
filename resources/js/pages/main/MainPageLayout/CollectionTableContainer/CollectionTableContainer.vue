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
      <template #no-data>
        <p>
          В списке нет ни одного плейлиста/подборки.
        </p>
      </template>
    </UniversalCollectionsContainer>
  </div>
</template>

<script>
import currentPath from 'mixins/currentPath';
import ReturnHeader from '../ReturnHeader.vue';
import UniversalCollectionsContainer from '../UniversalCollectionsContainer';
import CollectionTable from '../CollectionTable';

export default {
  components: {
    ReturnHeader,
    UniversalCollectionsContainer,
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
        case '/recommended':
          return 'Рекомендуем';

        case '/superMelomaniac':
          return 'Супер меломан';
      }
    },

    containerComponent() {
      switch (this.currentPath) {
        default:
          return 'universal';
      }
    },

    universalContainerProps() {
      const { params } = this.$route;

      switch (this.currentPath) {
        case '/recommended':
          return {
            forType: 'recommended-tracks',
            forId: 'collection'
          };

        case '/super-melomaniac':
          return {
            forType: 'super-melomaniac',
            forId: 'collection'
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
