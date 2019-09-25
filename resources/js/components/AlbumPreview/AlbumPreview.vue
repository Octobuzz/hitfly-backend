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
      <div
        class="album-preview__drape"
        @click="followTitleLink"
      />

      <img
        :key="albumId"
        :src="albumCoverUrl"
        alt="Album cover"
        class="album-preview__cover"
      >

      <div class="album-preview__button-section">
        <UnauthenticatedPopoverWrapper placement="right">
          <template #auth-content>
            <AddToFavouriteButton
              ref="addToFavouriteButton"
              class="album-preview__icon-button"
              passive="mobile-passive"
              hover="mobile-hover"
              item-type="album"
              :item-id="album.id"
            />
          </template>

          <template #unauth-popover-trigger>
            <AddToFavouriteButton
              ref="addToFavouriteButton"
              class="album-preview__icon-button"
              passive="mobile-passive"
              hover="mobile-hover"
              item-type="album"
              :item-id="album.id"
              :fake="true"
            />
          </template>
        </UnauthenticatedPopoverWrapper>

        <template v-if="album.tracksCount > 0">
          <IconButton
            v-if="tracksCount > 0 && !currentPlaying"
            :class="[
              'album-preview__icon-button',
              'album-preview__play-button'
            ]"
            passive="mobile-passive"
            hover="mobile-hover"
            @press="playAlbum"
          >
            <PlayIcon />
          </IconButton>
          <IconButton
            v-else
            :class="[
              'album-preview__icon-button'
            ]"
            passive="mobile-passive"
            hover="mobile-hover"
            @press="playAlbum"
          >
            <PauseIcon />
          </IconButton>
        </template>

        <AlbumPopover
          :album-id="albumId"
          @press-favourite="onPressFavourite"
        >
          <IconButton
            class="album-preview__icon-button"
            passive="mobile-passive"
            hover="mobile-hover"
          >
            <DotsIcon />
          </IconButton>
        </AlbumPopover>
      </div>
    </div>

    <div
      v-if="!isLoading"
      class="album-preview__footer"
    >
      <router-link :to="titleLink">
        <span class="album-preview__title">
          {{ album.title }}
        </span>
      </router-link>
      <span class="album-preview__author album-preview__author_no-highlight">
        {{ album.author }}
      </span>
    </div>
  </div>
</template>

<script>
import { mapGetters } from 'vuex';
import AlbumPopover from 'components/AlbumPopover';
import AddToFavouriteButton from 'components/AddToFavouriteButton/AddToFavouriteButton.vue';
import IconButton from 'components/IconButton.vue';
import DotsIcon from 'components/icons/DotsIcon.vue';
import PlayIcon from 'components/icons/PlayIcon.vue';
import PauseIcon from 'components/icons/PauseIcon.vue';
import UnauthenticatedPopoverWrapper from 'components/UnauthenticatedPopoverWrapper';
import gql from './gql';

export default {
  components: {
    AlbumPopover,
    AddToFavouriteButton,
    IconButton,
    DotsIcon,
    PlayIcon,
    PauseIcon,
    UnauthenticatedPopoverWrapper
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
      return this.album.cover
        .filter(cover => cover.size === 'size_120x120')[0].url;
    },

    tracksCount() {
      return this.album.tracksCount;
    },

    titleLink() {
      return this.$store.getters['links/album'](
        this.$route,
        this.albumId
      );
    },

    currentPlaying() {
      return this.currentType.type === 'album' && this.currentType.id === this.albumId && this.$store.getters['player/isPlaying'];
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

    playAlbum() {
      if(this.currentPlaying){
        this.$store.commit('player/pausePlaying');
      }else{
        if(this.currentType.type === 'album' && this.currentType.id === this.albumId) {
          this.$store.commit('player/startPlaying');
        }else{
          this.$apollo.provider.clients[this.apolloClient].query({
            query: gql.query.QUEUE_TRACKS,
            variables: {
              isAuthenticated: this.isAuthenticated,
              pageLimit: 30,
              pageNumber: 1,
              filters: {
                albumId: this.album.id
              }
            },
          })
          .then(response => {
            let data = {
              'type': 'album',
              'id': this.albumId
            };
            this.$store.commit('player/pausePlaying');
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
    album() {
      return {
        client: this.apolloClient,
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
