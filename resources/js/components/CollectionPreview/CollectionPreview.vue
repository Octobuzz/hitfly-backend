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
          ref="addToFavouriteButton"
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
          @press="playCollection"
        >
          <PlayIcon />
        </IconButton>

        <CollectionPopover
          :collection-id="collectionId"
          @press-favourite="onPressFavourite"
        >
          <IconButton
            class="collection-preview__icon-button"
            passive="mobile-passive"
            hover="mobile-hover"
          >
            <DotsIcon />
          </IconButton>
        </CollectionPopover>
      </div>
    </div>
  </div>
</template>

<script>
import CollectionPopover from 'components/CollectionPopover';
import AddToFavouriteButton from 'components/AddToFavouriteButton/AddToFavouriteButton.vue';
import IconButton from 'components/IconButton.vue';
import DotsIcon from 'components/icons/DotsIcon.vue';
import PlayIcon from 'components/icons/PlayIcon.vue';
import gql from './gql';

const MOBILE_WIDTH = 767;

export default {
  components: {
    CollectionPopover,
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
      const type = this.collection.isSet ? 'set' : 'playlist';

      return this.$store.getters[`links/${type}`](
        this.$route,
        this.collectionId
      );
    }
  },

  methods: {
    onPressFavourite() {
      this.$refs.addToFavouriteButton.$el.dispatchEvent(new Event('click'));
    },
    pressEmitted(){
      this.$apollo.provider.defaultClient.query({
        query: gql.query.TRACKS,
        variables: {
          pageLimit: 30,
          pageNumber: 1,
          filters: {
            albumId: this.collectionId
          }
        },
      })
      .then(response => {
        this.$store.commit('player/pausePlaying');
        this.$store.commit('player/pickTrack', response.data.tracks.data[0]);
        let arrayTr = response.data.tracks.data.map(data => {
          return data.id;
        });
        this.$store.commit('player/pickPlaylist', arrayTr);
      })
      .catch(error => {
        console.log(error);
      })
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
