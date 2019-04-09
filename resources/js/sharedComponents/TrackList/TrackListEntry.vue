<template>
  <div class="track-list-entry">
    <span class="track-list-entry__index">
      {{ index }}
    </span>

    <button
      class="track-list-entry__album"
      @click="onAlbumPress"
    >
      <img
        :src="track.album.cover"
        alt="Album cover"
      >
    </button>

    <!--TODO: shared like button-->

    {{ track.album.author }}

    <!--TODO: implement track playlist id on the backend-->

    <TrackToPlaylistPopover :track="track">
      <button>
        {{ track.trackName }}
      </button>
    </TrackToPlaylistPopover>

    <TrackActions :track="track">
      <button>
        track actions
      </button>
    </TrackActions>
  </div>
</template>

<script>
import TrackToPlaylistPopover from './TrackToPlaylistPopover.vue';
import TrackActions from './TrackActions.vue';

export default {
  components: {
    TrackToPlaylistPopover,
    TrackActions
  },
  props: {
    track: {
      type: Object,
      required: true
    },
    index: {
      type: Number,
      required: true
    }
  },
  data() {
    return {};
  },
  methods: {
    onAlbumPress() {
      console.log('album pressed');
    }
  }
};
</script>

<style
  scoped
  lang="scss"
>
.track-list-entry {
  display: flex;
  align-items: center;
  height: 56px;

  &__index {
    display: flex;
    justify-content: center;
    width: 16px;
    margin-right: 8px;
  }

  &__album {
    width: 32px;
    height: 32px;
    position: relative;
    margin-right: 8px;
    border-radius: 0;
    overflow: hidden;

    background: #00c0ef; // temp

    &_paused:before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      z-index: 100;
      background: black;
      opacity: .5;
    }
  }
}
</style>
