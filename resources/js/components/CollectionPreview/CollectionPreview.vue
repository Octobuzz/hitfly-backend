<template>
  <div :class="['collection-preview', $attrs.class]">
    <div
      v-if="isLoading"
      class="collection-preview__loader"
    >
      loading collection...
    </div>
    <div
      v-if="!isLoading"
      class="collection-preview__content"
    >
      <div class="collection-preview__drape" />

      <span class="collection-preview__title">
        <router-link
          :to="titleLink"
          class="collection-preview__title-link"
        >
          {{ collection.title }}
        </router-link>
      </span>

      <img
        :key="collectionId"
        :src="collectionImageUrl"
        alt="Collection image"
        class="collection-preview__image"
      >

      <div class="collection-preview__button-section">
        <AddToFavouriteButton
          class="collection-preview__icon-button"
          passive="mobile-passive"
          hover="mobile-hover"
          item-type="collection"
          :item-id="collection.id"
        />

        <IconButton
          :class="[
            'collection-preview__play-button',
            'collection-preview__icon-button'
          ]"
          passive="mobile-passive"
          hover="mobile-hover"
        >
          <PlayIcon />
        </IconButton>

        <IconButton
          class="collection-preview__icon-button"
          passive="mobile-passive"
          hover="mobile-hover"
        >
          <DotsIcon />
        </IconButton>
      </div>
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
    collectionId: {
      type: Number,
      required: true
    }
  },

  data() {
    return {
      isLoading: true,
      collection: null
    };
  },

  computed: {
    collectionImageUrl() {
      if (this.windowWidth <= MOBILE_WIDTH) {
        return this.collection.image
          .filter(image => image.size === 'size_150x150')[0].url;
      }

      return this.collection.image
        .filter(image => image.size === 'size_214x160')[0].url;
    },

    titleLink() {
      // TODO: wait for backend changes and change flag name (isCollection)

      const type = this.collection.isCollection ? 'playlist' : 'set';

      return this.$store.getters[`links/${type}`](
        this.$route,
        this.collectionId
      );
    }
  },

  apollo: {
    collection() {
      return {
        query: gql.query.COLLECTION,
        variables() {
          // use function to allow rendering another album when the prop changes

          return {
            id: this.collectionId
          };
        },
        update: ({ collection }) => {
          this.isLoading = false;

          return collection;
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
  src="./CollectionPreview.scss"
/>
