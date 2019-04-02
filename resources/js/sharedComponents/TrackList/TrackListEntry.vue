<template>
  <div>
    {{ track.trackName }}

    <!--TODO: implement track playlist id on the backend-->

    <AddToPlaylist
      :track-id="track.id"
      :track-playlist-id="track.playlistId"
    >
      <button>
        add to playlist
      </button>
    </AddToPlaylist>
  </div>
</template>

<script>
import AddToPlaylist from './AddToPlaylist.vue';

export default {
  components: {
    AddToPlaylist
  },
  props: {
    track: {
      type: Object,
      default: () => {}
    },
    index: {
      type: Number,
      required: true
    }
  }
};
</script>

<style
  scoped
  lang="scss"
>

</style>
