<template>
  <div class="other-user-music">
    <UniversalTracksContainer
      for-type="user"
      :for-id="userId"
      :shown-tracks-count="3"
      @initialized="onTrackListInitialized"
    >
      <template #default="container">
        <div
          :class="[
            'other-user-music__track-list',
            containerPaddingClass
          ]"
        >
          <TrackList
            v-if="container.trackIdList.length > 0"
            :track-id-list="container.trackIdList"
            :show-remove-button="false"
          >
            <template #header>
              <div class="other-user-music__track-list-header">
                <span class="h2">
                  Популярные песни
                </span>
                <router-link
                  :to="`/user/${userId}/music/tracks`"
                  class="other-user-music__track-list-more-button"
                >
                  Все песни
                </router-link>
              </div>
            </template>
          </TrackList>
        </div>
      </template>
    </UniversalTracksContainer>

    <UniversalAlbumsContainer
      for-type="user-album-list"
      :for-id="userId"
    >
      <template #default="container">
        <AlbumScrollHorizontal
          class="other-user-music__albums-container"
          :header-class="containerPaddingClass"
          :album-id-list="container.albumIdList"
          :has-more-data="container.hasMoreData"
        >
          <template #title>
            <router-link :to="`/user/${userId}/music/albums`">
              <span class="h2 other-user-music__albums-title">
                Альбомы
              </span>
            </router-link>
          </template>
        </AlbumScrollHorizontal>
      </template>
    </UniversalAlbumsContainer>

    <UniversalCollectionsContainer
      for-type="user-playlist-list"
      :for-id="userId"
    >
      <template #default="container">
        <CollectionScrollHorizontal
          class="other-user-music__collections-container"
          :header-class="containerPaddingClass"
          :collection-id-list="container.collectionIdList"
          :has-more-data="container.hasMoreData"
        >
          <template #title>
            <router-link :to="`/user/${userId}/music/playlists`">
              <span class="h2 other-user-music__collections-title">
                Плейлисты
              </span>
            </router-link>
          </template>
        </CollectionScrollHorizontal>
      </template>
    </UniversalCollectionsContainer>
  </div>
</template>

<script>
import containerPaddingClass from 'mixins/containerPaddingClass';
import TrackList from 'components/trackList/TrackList';
import AlbumScrollHorizontal from 'components/AlbumScrollHorizontal';
import CollectionScrollHorizontal from 'components/CollectionScrollHorizontal';
import UniversalTracksContainer from 'components/UniversalTracksContainer';
import UniversalAlbumsContainer from '../UniversalAlbumsContainer';
import UniversalCollectionsContainer from '../UniversalCollectionsContainer';

export default {
  components: {
    TrackList,
    AlbumScrollHorizontal,
    CollectionScrollHorizontal,
    UniversalTracksContainer,
    UniversalAlbumsContainer,
    UniversalCollectionsContainer
  },

  mixins: [containerPaddingClass],

  computed: {
    userId() {
      return +this.$route.params.userId;
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
    onTrackListInitialized({ success }) {
      this.$store.commit('loading/setMusic', {
        tracks: {
          initialized: true,
          success
        }
      });
    }
  }
};
</script>

<style
  scoped
  lang="scss"
  src="./OtherUserMusic.scss"
/>
