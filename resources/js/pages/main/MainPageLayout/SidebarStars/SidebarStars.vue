<template>
  <div class="asideBlock">
    <div class="asideBlock__header">
      <h2 class="h2">
        Звёздные эксперты
      </h2>
      <p>Комментарии наших критиков</p>
      <router-link to="/reviews" class="asideBlock__button">
        Все
      </router-link>
    </div>
    <div class="asideBlock__body">
      <div
        v-if="isLoading && !dataInitialized"
        class="profile__user-card-loader_first"
      >
        <SpinnerLoader />
      </div>
      <template v-else>
        <div
          v-if="tracks.length > 0"
          class="track-list"
          :track-id-list="tracks.map(track => track.id)"
          :showRemoveButton="false"
          :showAddToPlayList="false"
          :includeComments="true"
          :showTrackIndex="false"
          :columnLayout="true"
        >
          <div
            v-for="(track, index) in tracks"
            :key="track.id"
            class="track-list-with-comments"
          >
            <TrackListEntry
              :track-id="track.id"
              :index="index + 1"
              :show-album-section="true"
              :fake-fav-button="false"
              :show-remove-button="false"
              :show-track-index="false"
              :show-add-to-playlist="false"
              :column-layout="true"
              @play-track="playTrack(track.id)"
            />
            <div class="comment">
              <div class="comment__header">
                <div class="sidebarStarComment">
                  <img :src="track.comments[0].user.avatar.filter(avatar => avatar.size === 'size_56x56')[0].url" class="sidebarStarComment__ava">
                  <router-link :to="`/user/${track.comments[0].user.id}/user-reviews`" class="sidebarStarComment__name">
                    {{ track.comments[0].user.username }}
                  </router-link>
                </div>
                <p class="sidebarStarDate">
                  {{ parsedDate(index) }}
                </p>
              </div>
              <div
                class="comment__body"
                :class="{ hidden: hidden }"
                @click="hidden = !hidden"
              >
                <div class="overlay" />
                <p>{{ track.comments[0].comment }}</p>
              </div>
            </div>
          </div>
        </div>
        <p v-else>
          Пока нет комментариев
        </p>
      </template>
    </div>
  </div>
</template>
<script>
import SpinnerLoader from 'components/SpinnerLoader.vue';
import TrackListEntry from 'components/trackList/TrackListEntry/TrackListEntry.vue';
import { mapGetters } from 'vuex';
import gql from './gql';

export default {
  components: {
    SpinnerLoader,
    TrackListEntry
  },
  data: () => ({
    isLoading: true,
    trackIdList: [],
    hidden: true
  }),
  methods: {
    parsedDate(id) {
      const parsedDate = new Date(this.tracks[id].comments[0].createdAt);
      const months = ['янв', 'фев', 'мар', 'апр', 'май', 'июн', 'июл', 'авг', 'сен', 'окт', 'ноя', 'дек'];
      return `${parsedDate.getDate()} ${months[parsedDate.getMonth()]} ${parsedDate.getFullYear()}`;
    },
    playTrack(id) {
      if (this.$store.getters['player/currentTrack'].id !== id) {
        this.$store.commit('player/pausePlaying');
        this.$apollo.provider.clients[this.apolloClient].query({
          variables: {
            isAuthenticated: this.isAuthenticated,
            id
          },
          query: gql.query.QUEUE_TRACK
        })
          .then((response) => {
            this.$store.commit('player/pickTrack', response.data.track);
            this.$store.commit('player/pickPlaylist', this.trackIdList);
          })
          .catch((error) => {
            console.dir(error);
          });
      } else {
        this.$store.commit('player/togglePlaying');
      }
    }
  },
  computed: {
    dataInitialized() {
      return this.$store.getters['loading/mainPage'].sidebarStars.initialized;
    },
    ...mapGetters(['isAuthenticated', 'apolloClient'])
  },
  apollo: {
    tracks() {
      return {
        client: this.apolloClient,
        query: gql.query.GET_LAST_COMMENTS,
        variables: {
          isAuthenticated: this.isAuthenticated
        },
        update(data) {
          this.$store.commit('loading/setMainPage', {
            sidebarStars: {
              success: true,
              initialized: true
            }
          });
          this.trackIdList = data.tracks.data.map(track => track.id);
          this.isLoading = false;
          return data.tracks.data;
        }
      };
    }
  }
};
</script>
<style
  scoped
  lang="scss"
  src="./SidebarStars.scss"
/>
