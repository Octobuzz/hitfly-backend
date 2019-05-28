<template>
  <div :class="['my-music', { 'my-music_loading': !dataInitialized }]">
    <SpinnerLoader
      v-if="!dataInitialized"
      class="my-music__loader"
    />

    <FavouriteTracksContainer
      v-show="dataInitialized"
      :container-padding-class="containerPaddingClass"
      @data-initialized="tracksData = true"
    />
    <FavouriteAlbumsContainer
      v-show="dataInitialized"
      class="favourite__albums"
      :container-padding-class="containerPaddingClass"
      @data-initialized="albumsData = true"
    />
    <FavouriteCollectionsContainer
      v-show="dataInitialized"
      class="favourite__collections"
      :container-padding-class="containerPaddingClass"
      @data-initialized="collectionsData = true"
    />
    <FavouriteSetsContainer
      v-show="dataInitialized"
      :container-padding-class="containerPaddingClass"
      @data-initialized="setsData = true"
    />
  </div>
</template>

<script>
import SpinnerLoader from '~/components/shared/SpinnerLoader.vue';
import FavouriteTracksContainer from '../FavouriteTracksContainer';
import FavouriteAlbumsContainer from '../FavouriteAlbumsContainer';
import FavouriteCollectionsContainer from '../FavouriteCollectionsContainer';
import FavouriteSetsContainer from '../FavouriteSetsContainer';

export default {
  components: {
    SpinnerLoader,
    FavouriteTracksContainer,
    FavouriteAlbumsContainer,
    FavouriteCollectionsContainer,
    FavouriteSetsContainer
  },

  props: {
    containerPaddingClass: {
      type: String,
      default: ''
    }
  },

  data() {
    return {
      tracksData: false,
      albumsData: false,
      collectionsData: false,
      setsData: false
    };
  },

  computed: {
    dataInitialized() {
      return this.tracksData
        && this.albumsData
        && this.collectionsData
        && this.setsData;
    }
  },

  beforeRouteLeave(to, from, next) {
    this.$apollo.provider.clients.defaultClient
      .clearStore().then(() => next());
  }
};
</script>

<styles
  scoped
  lang="scss"
  src="./Favourite.scss"
/>
