<template>
  <TrackList
    :class="[
      'my-tracks-container',
      { [containerPaddingClass]: desktop }
    ]"
    :track-id-list="trackIdList"
    @remove-track="onTrackRemove"
  >
    <template #header>
      <div
        :class="[
          'my-tracks-container__header',
          { [containerPaddingClass]: !desktop }
        ]"
      >
        <h2>
          Песни
        </h2>
        <button class="my-tracks-container__header-button">
          Все песни
        </button>
      </div>
    </template>

    <template #loader>
      <SpinnerLoader
        v-if="isLoading"
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
      isLoading: true,
      hasMoreData: true,
      queryVars: {
        pageNumber: 1,
        pageLimit: 5,
        my: false // TODO: set to true
      },
      dataInitialized: false
    };
  },

  computed: {
    trackIdList() {
      return this.trackList
        .map(track => track.id)
        .slice(0, 5);
    },

    desktop() {
      return this.windowWidth > MOBILE_WIDTH;
    }
  },

  methods: {
    fetchMoreTracks(vars) {
      return this.$apollo.queries.trackList.fetchMore({
        variables: vars,

        updateQuery: (currentList, { fetchMoreResult: { tracks } }) => {
          const { total, to, data: newTracksData } = tracks;

          if (to === total) {
            this.hasMoreData = false;
          }

          return {
            tracks: {
              // eslint-disable-next-line no-underscore-dangle
              __typename: currentList.tracks.__typename,
              total,
              to,
              data: [...currentList.tracks.data, ...newTracksData]
            }
          };
        },
      });
    },

    loadMore() {
      this.isLoading = true;

      this.fetchMoreTracks({
        ...this.queryVars,
        pageNumber: this.queryVars.pageNumber + 1
      })
        .then(() => {
          this.queryVars.pageNumber += 1;
          this.isLoading = false;
        })
        .catch((err) => {
          console.log(err);
        });
    },

    onTrackRemove(id) {
      console.log('remove button pressed for track ', id);
    }
  },

  apollo: {
    trackList() {
      return {
        query: gql.query.TRACKS,
        variables: this.queryVars,

        update({ tracks: { total, to, data } }) {
          this.isLoading = false;
          this.dataInitialized = true;
          this.$emit('data-initialized');

          if (to === total) {
            this.hasMoreData = false;
          }

          return data;
        },

        error(err) {
          console.log(err);
        }
      };
    }
  }
};
</script>

<styles
  scoped
  lang="scss"
  src="./MyTracksContainer.scss"
/>
