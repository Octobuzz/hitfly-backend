<template>
  <div :class="['my-music', { 'my-music_loading': !dataInitialized }]">
    <SpinnerLoader
      v-if="!dataInitialized"
      class="my-music__loader"
    />

    <MyTracksContainer
      v-show="dataInitialized"
      :container-padding-class="containerPaddingClass"
      @data-initialized="tracksData = true"
    />
    <MyAlbumsContainer
      v-show="dataInitialized"
      :container-padding-class="containerPaddingClass"
      @data-initialized="albumsData = true"
    />
    <MyCollectionsContainer
      v-show="dataInitialized"
      :container-padding-class="containerPaddingClass"
      @data-initialized="collectionsData = true"
    />
  </div>
</template>

<script>
import SpinnerLoader from 'components/SpinnerLoader.vue';
import MyTracksContainer from '../MyTracksContainer';
import MyAlbumsContainer from '../MyAlbumsContainer';
import MyCollectionsContainer from '../MyCollectionsContainer';

export default {
  components: {
    SpinnerLoader,
    MyTracksContainer,
    MyAlbumsContainer,
    MyCollectionsContainer
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
      collectionsData: false
    };
  },

  computed: {
    dataInitialized() {
      return this.tracksData
        && this.albumsData
        && this.collectionsData;
    }
  }
};
</script>

<style
  scoped
  lang="scss"
  src="./MyMusic.scss"
/>
