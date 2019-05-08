<template>
  <div>
    <MyTracksContainer
      :container-padding-class="containerPaddingClass"
    />
    <MyAlbumsContainer
      :container-padding-class="containerPaddingClass"
    />
    <MyCollectionsContainer
      :container-padding-class="containerPaddingClass"
    />
  </div>
</template>

<script>
import MyTracksContainer from '../MyTracksContainer';
import MyAlbumsContainer from '../MyAlbumsContainer';
import MyCollectionsContainer from '../MyCollectionsContainer';

export default {
  components: {
    MyTracksContainer,
    MyAlbumsContainer,
    MyCollectionsContainer
  },

  props: {
    containerPaddingClass: {
      type: String,
      default: ''
    }
  }
};
</script>
