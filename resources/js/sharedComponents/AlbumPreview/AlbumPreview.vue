<template>
  <div :class="['album-preview', $attrs.class]">
    <div
      v-if="isLoading"
      class="album-preview__loader"
    >
      loading album...
    </div>
    <div
      v-if="!isLoading"
      class="album-preview__content"
    >
      <div class="album-preview__drape"/>

      <img
        :key="albumId"
        :src="albumCoverUrl"
        alt="Album cover"
        class="album-preview__cover"
      >

      <div class="album-preview__button-section">
        <AddToFavouriteButton
          class="album-preview__icon-button"
          passive="mobile-passive"
          hover="mobile-hover"
          item-type="album"
          :item-id="album.id"
        />

        <IconButton
          :class="[
            'album-preview__play-button',
            'album-preview__icon-button'
          ]"
          passive="mobile-passive"
          hover="mobile-hover"
        >
          <PlayIcon/>
        </IconButton>

        <IconButton
          class="album-preview__icon-button"
          passive="mobile-passive"
          hover="mobile-hover"
        >
          <DotsIcon/>
        </IconButton>
      </div>
    </div>
    <div
      v-if="!isLoading"
      class="album-preview__footer"
    >
      <span class="album-preview__title">
        {{ album.title }}
      </span>
      <span class="album-preview__author">
        {{ album.author }}
      </span>
    </div>
  </div>
</template>

<script>
import AddToFavouriteButton from 'components/AddToFavouriteButton/AddToFavouriteButton.vue';
import IconButton from 'components/IconButton.vue';
import DotsIcon from 'components/icons/DotsIcon.vue';
import PlayIcon from 'components/icons/PlayIcon.vue';
import gql from './gql';

const MOBILE_WIDTH = 767;

export default {
  components: {
    AddToFavouriteButton,
    IconButton,
    DotsIcon,
    PlayIcon
  },

  props: {
    albumId: {
      type: Number,
      required: true
    }
  },

  data() {
    return {
      isLoading: true,
      album: null
    };
  },

  computed: {
    albumCoverUrl() {
      // TODO: wait for the api

      // if (this.windowWidth > MOBILE_WIDTH) {
      //   return this.album.cover
      //     .filter(cover => cover.size === 'size_104x104')[0].url;
      // }

      return this.album.cover
        .filter(cover => cover.size === 'size_120x120')[0].url;
    }
  },

  apollo: {
    album() {
      return {
        query: gql.query.ALBUM,
        variables() {
          // use function to allow rendering another album when the prop changes

          return {
            id: this.albumId
          };
        },
        update: ({ album }) => {
          this.isLoading = false;

          return album;
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
  src="./AlbumPreview.scss"
/>
