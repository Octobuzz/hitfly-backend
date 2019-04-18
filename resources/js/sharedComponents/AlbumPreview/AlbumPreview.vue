<template>
  <div class="album-preview">
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
        :src="album.cover"
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
import AddToFavouriteButton from 'components/AddToFavouriteButton.vue';
import IconButton from 'components/IconButton.vue';
import DotsIcon from 'components/icons/DotsIcon.vue';
import PlayIcon from 'components/icons/PlayIcon.vue';
import gql from './gql';

// TODO: cache redirect

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
    },
    // TODO: this prop defines fetch policy
    preloaded: {
      type: Boolean,
      default: false
    }
  },
  data() {
    return {
      isLoading: true,
      album: null
    };
  },
  apollo: {
    album() {
      return {
        query: gql.query.ALBUM,
        variables: {
          id: this.albumId
        },
        update: ({ album }) => {
          console.log(album);
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
