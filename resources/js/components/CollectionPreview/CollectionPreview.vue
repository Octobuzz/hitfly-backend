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
      <div
        class="collection-preview__drape"
        @click="followTitleLink"
      />

      <span class="collection-preview__title">
        <router-link
          :to="titleLink"
          class="collection-preview__title-link"
        >
          {{ trimmedCollectionTitle }}
        </router-link>
      </span>

      <img
        :key="collectionId"
        :src="collectionImageUrl"
        alt="Collection image"
        class="collection-preview__image"
      >

      <div class="collection-preview__button-section">
        <UnauthenticatedPopoverWrapper placement="right">
          <template #auth-content>
            <AddToFavouriteButton
              ref="addToFavouriteButton"
              class="collection-preview__icon-button"
              passive="mobile-passive"
              hover="mobile-hover"
              item-type="collection"
              :item-id="collection.id"
            />
          </template>

          <template #unauth-popover-trigger>
            <AddToFavouriteButton
              class="collection-preview__icon-button"
              passive="mobile-passive"
              hover="mobile-hover"
              item-type="collection"
              :item-id="collection.id"
              :fake="true"
            />
          </template>
        </UnauthenticatedPopoverWrapper>

        <template v-if="collection.countTracks > 0">
          <IconButton
            v-if="!currentPlaying"
            :class="[
              'collection-preview__icon-button',
              'collection-preview__play-button'
            ]"
            passive="mobile-passive"
            hover="mobile-hover"
            @press="playCollection"
          >
            <PlayIcon />
          </IconButton>
          <IconButton
            v-else
            :class="[
              'collection-preview__icon-button'
            ]"
            passive="mobile-passive"
            hover="mobile-hover"
            @press="playCollection"
          >
            <PauseIcon />
          </IconButton>
        </template>

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
import { mapGetters } from 'vuex';
import CollectionPopover from 'components/CollectionPopover';
import AddToFavouriteButton from 'components/AddToFavouriteButton/AddToFavouriteButton.vue';
import IconButton from 'components/IconButton.vue';
import DotsIcon from 'components/icons/DotsIcon.vue';
import PlayIcon from 'components/icons/PlayIcon.vue';
import UnauthenticatedPopoverWrapper from 'components/UnauthenticatedPopoverWrapper';
import PauseIcon from 'components/icons/PauseIcon.vue';
import gql from './gql';

export default {
  components: {
    CollectionPopover,
    AddToFavouriteButton,
    IconButton,
    DotsIcon,
    PlayIcon,
    PauseIcon,
    UnauthenticatedPopoverWrapper,
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
      if (!this.collection.image) {
        return '';
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
    },

    trimmedCollectionTitle() {
      if (!this.collection) return '';

      const { title } = this.collection;

      return title.length > 95 ? `${title.slice(0, 95)}...` : title;
    },

    currentPlaying() {
      return this.currentType.type === 'collection' && this.currentType.id === this.collectionId && this.$store.getters['player/isPlaying'];
    },

    currentType() {
      return this.$store.getters['player/getCurrentType'];
    },

    ...mapGetters(['isAuthenticated', 'apolloClient'])
  },

  methods: {
    followTitleLink() {
      this.$router.push(this.titleLink);
    },

    onPressFavourite() {
      this.$refs.addToFavouriteButton.$el.dispatchEvent(new Event('click'));
    },

    playCollection() {
      if (this.currentPlaying) {
        this.$store.commit('player/pausePlaying');
      } else {
        if (this.currentType.type === 'collection' && this.currentType.id === this.collectionId) {
          this.$store.commit('player/startPlaying');
        } else {
          this.$apollo.provider.clients[this.apolloClient].query({
            query: gql.query.QUEUE_TRACKS,
            variables: {
              isAuthenticated: this.isAuthenticated,
              pageLimit: 30,
              pageNumber: 1,
              filters: {
                collectionId: this.collectionId
              }
            },
          })
          .then(response => {
            let data = {
              'type': 'collection',
              'id': this.collectionId
            };
            this.$store.commit('player/changeCurrentType', data);
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
      }
    }
  },

  apollo: {
    collection() {
      return {
        client: this.apolloClient,
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
          console.dir(error);
        }
      };
    }
  },
};
</script>

<style
  scoped
  lang="scss"
  src="./CollectionPreview.scss"
/>
