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
      :container-padding-class="containerPaddingClass"
      @data-initialized="albumsData = true"
    />
  </div>
</template>

<script>
import SpinnerLoader from 'components/SpinnerLoader.vue';
import FavouriteTracksContainer from '../FavouriteTracksContainer';
import FavouriteAlbumsContainer from '../FavouriteAlbumsContainer';

export default {
  components: {
    SpinnerLoader,
    FavouriteTracksContainer,
    FavouriteAlbumsContainer
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
      albumsData: false
    };
  },

  computed: {
    dataInitialized() {
      return this.tracksData
        && this.albumsData;
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
  src="../MyMusic/MyMusic.scss"
/>
