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
        :track-id-list="popularTrackIdList"
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

    <template v-if="newAlbumTracks.length > 0">
      <div :class="containerPaddingClass">
        <div class="other-user-music__new-album-container">
          <div class="other-user-music__new-album-cover">
            <TrackReviewHeader
              :track-id="newAlbumPlayingTrackId"
            />
            <button
              class="other-user-music__listen-button"
              @click="playAlbum"
            >
              <template v-if="currentPlaying">
                <CirclePauseIcon />
                Пауза
              </template>
              <template v-else>
                <CirclePlayIcon />
                Слушать
              </template>
            </button>
          </div>

          <div class="other-user-music__new-album-tracks">
            <TrackList
              :track-id-list="newAlbumTrackIdList"
              :show-remove-button="false"
            >
              <template #header>
                <div class="other-user-music__new-album-tracks-header">
                  <span class="h2">
                    {{ newAlbum.title }}
                  </span>
                  <router-link
                    :to="`/user/${userId}/album/${newAlbum.id}`"
                    class="other-user-music__track-list-more-button"
                  >
                    Все песни
                  </router-link>
                </div>

                <div class="other-user-music__new-album-info">
                  <span class="other-user-music__new-album-total-tracks">
                    {{ totalTracksInfo(newAlbum.tracksCount) }}
                  </span>
                  {{ totalDurationInfo('hours', newAlbum.tracksTime) }}
                  {{ totalDurationInfo('mins', newAlbum.tracksTime) }}
                </div>
              </template>
            </TrackList>
          </div>
        </div>
      </div>
    </template>

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
import { mapGetters } from 'vuex';
import containerPaddingClass from 'mixins/containerPaddingClass';
import playingTrackId from 'mixins/playingTrackId';
import totalInfoFormatting from 'mixins/totalInfoFormatting';
import TrackList from 'components/trackList/TrackList';
import AlbumScrollHorizontal from 'components/AlbumScrollHorizontal';
import CollectionScrollHorizontal from 'components/CollectionScrollHorizontal';
import TrackReviewHeader from 'components/trackReviewsInterface/TrackReviewHeader';
import CirclePlayIcon from 'components/icons/CirclePlayIcon.vue';
import UniversalAlbumsContainer from '../UniversalAlbumsContainer';
import UniversalCollectionsContainer from '../UniversalCollectionsContainer';
import gql from './gql';

export default {
  components: {
    TrackList,
    AlbumScrollHorizontal,
    CollectionScrollHorizontal,
    TrackReviewHeader,
    CirclePlayIcon,
    UniversalAlbumsContainer,
    UniversalCollectionsContainer
  },

  mixins: [containerPaddingClass, playingTrackId, totalInfoFormatting],

  data() {
    return {
      popularTracks: [],
      newAlbum: null,
      newAlbumTracks: [],
      newAlbumExists: true,
      tooltip: {
        more: {
          content: 'Еще'
        }
      }
    };
  },

  computed: {
    currentPlaying() {
      console.log(this.currentType.type + ' ' + this.currentType.id + ' ' + this.albumId);
      return this.currentType.type === 'album' && this.currentType.id === this.albumId && this.$store.getters['player/isPlaying'];
    },

    currentType() {
      return this.$store.getters['player/getCurrentType'];
    },

    userId() {
      return +this.$route.params.userId;
    },

    popularTrackIdList() {
      return this.popularTracks.map(track => track.id);
    },

    newAlbumTrackIdList() {
      return this.newAlbumTracks.map(track => track.id);
    },

    newAlbumPlayingTrackId() {
      // eslint-disable-next-line no-shadow
      const { playingTrackId, newAlbumTrackIdList } = this;

      if (newAlbumTrackIdList.length === 0) return null;

      if (newAlbumTrackIdList.includes(playingTrackId)) {
        return playingTrackId;
      }

      return newAlbumTrackIdList[0];
    },

    ...mapGetters(['isAuthenticated', 'apolloClient'])
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
    },

    onNewAlbumInitialized({ success }) {
      this.$store.commit('loading/setMusic', {
        newAlbum: {
          initialized: true,
          success
        }
      });
    },

    fetchNewAlbumTracks(albumId) {
      this.$apollo.provider.clients[this.apolloClient].query({
        query: gql.query.USER_ALBUM_TRACKS,
        variables: {
          isAuthenticated: this.isAuthenticated,
          albumId
        }
      })
        .then(({ data: { tracks: { data } } }) => {
          this.newAlbumTracks = data;

          this.onNewAlbumInitialized({
            success: true
          });
        })
        .catch((err) => {
          this.onNewAlbumInitialized({
            success: false
          });

          console.dir(err);
        });
    },

    playAlbum(){
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
                albumId: this.newAlbum.id
              }
            },
          })
          .then(response => {
            let data = {
              'type': 'album',
              'id': this.newAlbum.id
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
    popularTracks() {
      return {
        client: this.apolloClient,
        query: gql.query.USER_POPULAR_TRACKS,
        fetchPolicy: 'network-only',
        variables() {
          return {
            isAuthenticated: this.isAuthenticated,
            id: this.userId
          };
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
      };
    },

    newAlbum() {
      return {
        client: this.apolloClient,
        query: gql.query.USER_NEW_ALBUM,
        fetchPolicy: 'network-only',
        variables() {
          return {
            isAuthenticated: this.isAuthenticated,
            id: this.userId
          };
        },
        update({ albums: { data: albums } }) {
          if (albums.length === 0) {
            this.onNewAlbumInitialized({
              success: false
            });

            return null;
          }
          this.fetchNewAlbumTracks(albums[0].id);

          return albums[0];
        },
        error(err) {
          console.dir(err);
        }
      };
    }
  }
};
</script>

<style
  scoped
  lang="scss"
  src="./OtherUserMusic.scss"
/>
