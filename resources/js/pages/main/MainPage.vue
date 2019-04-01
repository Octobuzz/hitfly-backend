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
    <div style="margin: 300px"/>

    <TrackList/>

  </div>
</template>

<script>
import TrackList from '../../sharedComponents/TrackList.vue';

export default {
  components: {
    TrackList
  },
  data() {
    return {};
  }
};
</script>
