<template>
  <div>
    <MyTracksContainer/>
    <MyAlbumsContainer/>
  </div>
</template>

<script>
import MyTracksContainer from '../MyTracksContainer';
import MyAlbumsContainer from '../MyAlbumsContainer';

export default {
  components: {
    MyTracksContainer,
    MyAlbumsContainer
  }
};
</script>
