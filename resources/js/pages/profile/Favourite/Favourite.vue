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
  </div>
</template>

<script>
import SpinnerLoader from 'components/SpinnerLoader.vue';
import FavouriteTracksContainer from '../FavouriteTracksContainer';

export default {
  components: {
    SpinnerLoader,
    FavouriteTracksContainer
  },

  props: {
    containerPaddingClass: {
      type: String,
      default: ''
    }
  },

  data() {
    return {
      tracksData: false
    };
  },

  computed: {
    dataInitialized() {
      return this.tracksData;
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
