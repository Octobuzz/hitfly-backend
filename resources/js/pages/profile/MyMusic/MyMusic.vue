<template>
  <div class="my-music">
    <UniversalTracksContainer
      ref="myTracksContainer"
      for-type="user"
      for-id="me"
      :shown-tracks-count="shownTracksCount"
      @initialized="onTracksContainerInitialized"
      @tracks-removed="maybeLoadMoreTracks"
    >
      <template #default="container">
        <div
          v-if="!mobileUser
            && tracksContainerInitialized
            && container.trackIdList.length === 0"
          :class="['my-tracks-download', containerPaddingClass]"
        >
          <span class="h2 my-music__my-tracks-download-text">
            Загрузите свою первую песню
          </span>
          <FormButton
            class="my-music__my-tracks-download-button"
            @press="onDownloadPress"
          >
            Загрузить музыку
          </FormButton>
        </div>

        <TrackList
          v-if="container.trackIdList.length > 0"
          ref="myTracksList"
          :class="[
            containerPaddingClass,
            'my-music__my-tracks'
          ]"
          :track-id-list="container.trackIdList"
          :show-remove-button="true"
          @remove-track="callContainerRemove"
        >
          <template #header>
            <div class="my-music__my-tracks-header">
              <span class="h2">
                Мои песни
              </span>
              <router-link
                to="/profile/my-music/tracks"
                class="my-music__my-tracks-header-button"
              >
                Все песни
              </router-link>
            </div>
          </template>
        </TrackList>
      </template>
    </UniversalTracksContainer>

    <UniversalAlbumsContainer
      for-type="user-album-list"
      for-id="me"
    >
      <template #default="container">
        <AlbumScrollHorizontal
          class="my-music__albums-container"
          :header-class="containerPaddingClass"
          :album-id-list="container.albumIdList"
          :has-more-data="container.hasMoreData"
        >
          <template #title>
            <router-link to="/profile/my-music/albums">
              <span class="h2 my-music__albums-title">
                Мои альбомы
              </span>
            </router-link>
          </template>
        </AlbumScrollHorizontal>
      </template>
    </UniversalAlbumsContainer>

    <UniversalCollectionsContainer
      for-type="user-playlist-list"
      for-id="me"
    >
      <template #default="container">
        <CollectionScrollHorizontal
          class="my-music__collections-container"
          :header-class="containerPaddingClass"
          :collection-id-list="container.collectionIdList"
          :has-more-data="container.hasMoreData"
        >
          <template #title>
            <router-link to="/profile/my-music/playlists">
              <span class="h2 my-music__collections-title">
                Мои плейлисты
              </span>
            </router-link>
          </template>
        </CollectionScrollHorizontal>
      </template>
    </UniversalCollectionsContainer>
  </div>
</template>

<script>
import FormButton from 'components/FormButton.vue';
import TrackList from 'components/trackList/TrackList';
import UniversalTracksContainer from 'components/UniversalTracksContainer';
import AlbumScrollHorizontal from 'components/AlbumScrollHorizontal';
import CollectionScrollHorizontal from 'components/CollectionScrollHorizontal';
import UniversalAlbumsContainer from '../UniversalAlbumsContainer';
import UniversalCollectionsContainer from '../UniversalCollectionsContainer';

export default {
  components: {
    FormButton,
    TrackList,
    UniversalTracksContainer,
    AlbumScrollHorizontal,
    CollectionScrollHorizontal,
    UniversalAlbumsContainer,
    UniversalCollectionsContainer
  },

  data() {
    return {
      shownTracksCount: 5
    };
  },

  computed: {
    containerPaddingClass() {
      return this.$store.getters['appColumns/paddingClass'];
    },
    myTracksContainer() {
      return this.$refs.myTracksContainer;
    },
    myTracksList() {
      return this.$refs.myTracksList;
    },
    mobileUser() {
      return /Mobi|Android/i.test(navigator.userAgent);
    },
    tracksContainerInitialized() {
      return this.$store.getters['loading/music'].tracks;
    }
  },

  beforeRouteLeave(to, from, next) {
    this.$store.commit('loading/setMusic', {
      tracks: {
        initialized: false
      },
      albums: {
        initialized: false
      },
      collections: {
        initialized: false
      }
    });

    next();
  },

  methods: {
    onTracksContainerInitialized({ success }) {
      this.$store.commit('loading/setMusic', {
        tracks: {
          success,
          initialized: true
        }
      });
    },
    callContainerRemove(trackId) {
      this.myTracksContainer.callRemoveTrack(trackId);
    },
    maybeLoadMoreTracks() {
      if (!this.myTracksList) return;

      if (this.myTracksList.trackIdList.length < this.shownTracksCount) {
        this.myTracksContainer.callLoadMore();
      }
    },
    onDownloadPress() {
      this.$router.push('/upload');
    },
  }
};
</script>

<style
  scoped
  lang="scss"
  src="./MyMusic.scss"
/>
