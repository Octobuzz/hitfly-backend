<template>
  <div>
    <div
      v-if="$route.fullPath === '/'"
      style="margin-top: 107px;"
    />

    <!--TODO: move to the root component-->
    <IconGradientRadial/>
    <!--<TrackListInterface/>-->
    <!--<AlbumScrollHorizontal/>-->

  </div>
</template>

<script>
import IconGradientRadial from 'components/IconGradientRadial.vue';
// import TrackListInterface from './TrackListInterface.vue';
// import AlbumScrollHorizontal from './AlbumListInterface.vue';

import TRACK from 'gql/query/Track.graphql';

export default {
  components: {
    IconGradientRadial,
    // TrackListInterface,
    // AlbumScrollHorizontal
  },
  data() {
    return {
      test: null
    };
  },
  apollo: {
    test: {
      query: TRACK,
      variables: {
        id: 1201
      },
      update(data) {
        console.log(data);
      },
      error(error) {
        console.log(error);
      }
    }
  }
};
</script>

<style
  scoped
  lang="scss"
>

</style>
