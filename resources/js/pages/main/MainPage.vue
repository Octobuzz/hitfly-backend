<template>
  <div>
    <!--TODO: temp-->
    <router-link to="/profile/edit">
      edit profile
    </router-link>
    <br>
    <router-link to="/profile/obsolete">
      profile obsolete
    </router-link>
    <!--temp-->
    <div style="margin: 300px" />

    <div :style="{ marginLeft: '600px' }">
      <TrackList type="my" />
    </div>
  </div>
</template>

<script>
import TrackList from '../../sharedComponents/TrackList';

export default {
  components: {
    TrackList
  },
  data() {
    return {};
  }
};
</script>
