<template>
  <div :class="['genre-preview', $attrs.class]">
    <div
      v-if="isLoading"
      class="genre-preview__loader"
    >
      loading genres...
    </div>

    <div v-if="!isLoading" class="s-genre-item">
      <div class="s-genre-item__label">
        <img class="s-genre-item__img" :src="genre.image" :alt="genre.name">
        <div class="s-genre-item__textBlock">
          <span class="s-genre-item__text">{{ genre.name }}</span>
          <span class="s-genre-item__quantity">{{ genre.countTracks + ' ' + num2str }}</span>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import IconButton from 'components/IconButton.vue';
import DotsIcon from 'components/icons/DotsIcon.vue';
import PlayIcon from 'components/icons/PlayIcon.vue';
import PauseIcon from 'components/icons/PauseIcon.vue';
import gql from './gql';

const MOBILE_WIDTH = 767;

export default {
  components: {
    IconButton,
    DotsIcon,
    PlayIcon,
    PauseIcon
  },

  props: {
    genre: {
      type: Object,
      required: true
    }
  },

  data() {
    return {
      isLoading: false
    };
  },

  computed: {
    num2str() {
      let n = this.genre.countTracks;
      const text_forms = ['трек', 'трека', 'треков'];
      n = Math.abs(n) % 100; var n1 = n % 10;
      if (n > 10 && n < 20) { return text_forms[2]; }
      if (n1 > 1 && n1 < 5) { return text_forms[1]; }
      if (n1 == 1) { return text_forms[0]; }
      return text_forms[2];
    },
    genreImageUrl() {
      if (this.windowWidth <= MOBILE_WIDTH) {
        return this.genre.image
          .filter(image => image.size === 'size_104x104')[0].url;
      }

      return this.genre.image
        .filter(image => image.size === 'size_120x120')[0].url;
    },
    genresId() {
      return this.genre.id;
    }
  }
};
</script>

<style
  scoped
  lang="scss"
  src="./GenrePreview.scss"
/>
