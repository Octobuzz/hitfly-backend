<template>
  <div class="track-list-entry">
    <span class="track-list-entry__index">
      {{ index }}
    </span>

    <button class="track-list-entry__album">
      <img
        src="/images/note.svg"
        alt="album"
      >
    </button>

    <!--TODO: implement track playlist id on the backend-->

    <AddToPlaylist :track-id="track.id">
      <button>
        {{ track.trackName }}
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
  },
  data() {
    return {};
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
