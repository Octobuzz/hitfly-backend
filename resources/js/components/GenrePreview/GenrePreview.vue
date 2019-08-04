<template>
  <div :class="['album-preview', $attrs.class]">
    <div
      v-if="isLoading"
      class="album-preview__loader"
    >
      loading track...
    </div>
    <div
      v-if="!isLoading"
      class="album-preview__content"
    >
      <div class="album-preview__drape" />

      <img
        :key="genreId"
        :src="genreimageUrl"
        alt="Track image"
        class="album-preview__image"
      >
      </div>
    </div>

    <div
      v-if="!isLoading"
      class="album-preview__footer"
    >
      <router-link to="/">
        <span class="album-preview__title">
          {{ genre.name }}
        </span>
      </router-link>
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
    genreId: {
      type: Number,
      required: true
    }
  },

  data() {
    return {
      isLoading: true,
      genre: null
    };
  },

  computed: {
    genreimageUrl() {
      if (this.windowWidth <= MOBILE_WIDTH) {
        return this.genre.image
          .filter(image => image.size === 'size_104x104')[0].url;
      }

      return this.genre.image
        .filter(image => image.size === 'size_120x120')[0].url;
    },
  },

  methods: {

  },

  apollo: {
    track() {
      return {
        query: gql.query.TRACKS,
        variables() {
          // use function to allow rendering another album when the prop changes

          return {
            id: this.genreId
          };
        },
        update: ({ track }) => {
          this.isLoading = false;

          return track;
        },
        error: (error) => {
          console.log(error);
        }
      };
    }
  }
};
</script>

<style
  scoped
  lang="scss"
  src="./GenrePreview.scss"
/>
