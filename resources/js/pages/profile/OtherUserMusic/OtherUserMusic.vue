<template>
  <div class="other-user-music">
    <div
      :class="[
        'other-user-music__track-list',
        containerPaddingClass
      ]"
    >
      <TrackList
        v-if="popularTracks.length > 0"
        :track-id-list="trackIdList"
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
import UniversalAlbumsContainer from '../UniversalAlbumsContainer';
import UniversalCollectionsContainer from '../UniversalCollectionsContainer';
import gql from './gql';

export default {
  components: {
    TrackList,
    AlbumScrollHorizontal,
    CollectionScrollHorizontal,
    UniversalAlbumsContainer,
    UniversalCollectionsContainer
  },

  mixins: [containerPaddingClass],

  data() {
    return {
      popularTracks: []
    };
  },

  computed: {
    userId() {
      return +this.$route.params.userId;
    },

    trackIdList() {
      return this.popularTracks.map(track => track.id);
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
  },

  apollo: {
    popularTracks: {
      query: gql.query.USER_POPULAR_TRACKS,
      variables() {
        return { id: this.userId };
      },
      update({ topTrackForUser }) {
        this.onTrackListInitialized({
          success: true
        });

        return topTrackForUser;
      },
      error(err) {
        this.onTrackListInitialized({
          success: false
        });

        console.dir(err);
      }
    }
  }
};
</script>

<style
  scoped
  lang="scss"
  src="./OtherUserMusic.scss"
/>
