<template>
  <TrackList
    v-if="trackList.length > 0"
    :class="[
      'my-tracks-container',
      { [containerPaddingClass]: desktop }
    ]"
    :show-remove-button="false"
    :track-id-list="trackIdList"
  >
    <template #header>
      <div
        :class="[
          'my-tracks-container__header',
          { [containerPaddingClass]: !desktop }
        ]"
      >
        <span class="h2">
          Любимые песни
        </span>
        <button class="my-tracks-container__header-button">
          Все песни
        </button>
      </div>
    </template>
  </TrackList>
</template>

<script>
import TrackList from 'components/trackList/TrackList';
import gql from './gql';

const MOBILE_WIDTH = 767;

export default {
  components: {
    TrackList
  },

  data() {
    return {
      trackList: [],
      queryVars: {
        pageNumber: 1,
        pageLimit: 10
      },
      shownLength: 5
    };
  },

  computed: {
    trackIdList() {
      return this.trackList
        .map(favTrack => favTrack.track.id)
        .slice(0, this.shownLength);
    },

    desktop() {
      return this.windowWidth > MOBILE_WIDTH;
    },

    containerPaddingClass() {
      return this.$store.getters['appColumns/paddingClass'];
    }
  },

  methods: {
    notifyInitialization(success) {
      this.$store.commit('loading/setFavourite', {
        tracks: {
          initialized: true,
          success
        }
      });
    },
  },

  apollo: {
    trackList() {
      return {
        query: gql.query.FAVOURITE_TRACKS,
        variables: this.queryVars,
        fetchPolicy: 'network-only',

        update({ favouriteTrack: { total, to, data } }) {
          this.notifyInitialization(true);

          if (to >= total) {
            this.hasMoreData = false;
          }

          return data;
        },

        error(err) {
          this.notifyInitialization(false);

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
  src="../MyTracksContainer/MyTracksContainer.scss"
/>
