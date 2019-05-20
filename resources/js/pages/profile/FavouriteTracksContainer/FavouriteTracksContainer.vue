<template>
  <TrackList
    v-if="dataInitialized"
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

    <template #loader>
      <SpinnerLoader
        v-if="!dataInitialized"
        class="my-tracks-container__loader"
      />
    </template>
  </TrackList>
</template>

<script>
import TrackList from 'components/trackList/TrackList';
import SpinnerLoader from 'components/SpinnerLoader.vue';
import gql from './gql';

const MOBILE_WIDTH = 767;

// Due to loadMore blocking ability to remove more tracks the separation was implemented

export default {
  components: {
    TrackList,
    SpinnerLoader
  },

  props: {
    containerPaddingClass: {
      type: String,
      default: ''
    }
  },

  data() {
    return {
      trackList: [],
      queryVars: {
        pageNumber: 1,
        pageLimit: 10
      },
      dataInitialized: false
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
    }
  },

  apollo: {
    trackList() {
      return {
        query: gql.query.FAVOURITE_TRACKS,
        variables: this.queryVars,

        update({ favouriteTrack: { total, to, data } }) {
          if (!this.dataInitialized) {
            this.dataInitialized = true;
            this.$emit('data-initialized');
          }

          if (to >= total) {
            this.hasMoreData = false;
          }

          return data;
        },

        error(err) {
          console.dir(err);
        }
      };
    }
  }
};
</script>

<styles
  scoped
  lang="scss"
  src="../MyTracksContainer/MyTracksContainer.scss"
/>
