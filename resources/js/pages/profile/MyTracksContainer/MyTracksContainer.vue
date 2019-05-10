<template>
  <div>
    <div
      v-if="!mobileUser && dataInitialized && !trackListLength"
      :class="['my-tracks-download', containerPaddingClass]"
    >
      <span class="h2 my-tracks-download__text">
        Загрузите свою первую песню
      </span>
      <FormButton
        class="my-tracks-download__button"
        @press="onDownloadPress"
      >
        Загрузить музыку
      </FormButton>
    </div>
    <TrackList
      v-if="dataInitialized && trackListLength"
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
          <span class="h2">
            Песни
          </span>
          <button class="my-tracks-container__header-button">
            Все песни
          </button>
        </div>
      </template>

      <template #loader>
        <SpinnerLoader
          v-if="hasMoreData && dataInitialized && trackListLength < shownLength"
          class="my-tracks-container__loader"
        />
      </template>
    </TrackList>
  </div>
</template>

<script>
import TrackList from 'components/trackList/TrackList';
import SpinnerLoader from 'components/SpinnerLoader.vue';
import FormButton from 'components/FormButton.vue';
import gql from './gql';

const MOBILE_WIDTH = 767;

// Due to loadMore blocking ability to remove more tracks the separation was implemented

export default {
  components: {
    TrackList,
    SpinnerLoader,
    FormButton
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
      isLoading: false,
      shouldLoadMore: false,
      removesInProcess: 0,
      pendingRemoves: [],
      removedCount: 0,
      hasMoreData: true,
      shownLength: 5,
      queryVars: {
        pageNumber: 1,
        pageLimit: 10, // aspire to have two times more than you have in viewport
        my: true
      },
      dataInitialized: false,
      mobileUser: /Mobi|Android/i.test(navigator.userAgent)
    };
  },

  computed: {
    trackIdList() {
      return this.trackList
        .map(track => track.id)
        .slice(0, this.shownLength);
    },

    trackListLength() {
      return this.trackList.length > 0;
    },

    desktop() {
      return this.windowWidth > MOBILE_WIDTH;
    }
  },

  watch: {
    removesInProcess(count) {
      if (count === 0 && this.shouldLoadMore) {
        this.loadMore();
      }
    },

    isLoading(inProcess) {
      if (!inProcess && this.pendingRemoves.length > 0) {
        this.pendingRemoves.forEach(id => this.removeTrack(id));
        this.pendingRemoves = [];
      }
    }
  },

  methods: {
    onDownloadPress() {
      console.log('"download track" pressed');
    },

    fetchMoreTracks(vars) {
      return this.$apollo.queries.trackList.fetchMore({
        variables: vars,

        updateQuery: (currentList, { fetchMoreResult: { tracks } }) => {
          const { total, to, data } = tracks;

          const newTracks = data.filter(newTrack => (
            !currentList.tracks.data.some(
              currentTrack => currentTrack.id === newTrack.id
            )
          ));

          return {
            tracks: {
              // eslint-disable-next-line no-underscore-dangle
              __typename: currentList.tracks.__typename,
              total,
              to,
              data: [...currentList.tracks.data, ...newTracks]
            }
          };
        },
      });
    },

    loadMore() {
      if (!this.hasMoreData
          || this.isLoading
          || this.removesInProcess > 0) {
        return;
      }

      this.isLoading = true;

      const pageNumber = (this.queryVars.pageNumber + 1)
        - Math.ceil(this.removedCount / this.queryVars.pageLimit);

      this.fetchMoreTracks({
        ...this.queryVars,
        pageNumber
      })
        .then(() => {
          this.queryVars.pageNumber += 1;
          this.isLoading = false;
          this.shouldLoadMore = false;
        })
        .catch((err) => {
          console.log(err);
        });
    },

    onTrackRemove(id) {
      if (this.trackList.length < 10) {
        this.shouldLoadMore = true;
      }

      if (this.isLoading) {
        this.pendingRemoves.push(id);
      } else {
        this.removeTrack(id);
      }
    },

    removeTrack(id) {
      this.removesInProcess += 1;

      this.$apollo.mutate({
        mutation: gql.mutation.REMOVE_TRACK,
        variables: { id },

        update: (store) => {
          const { tracks } = store.readQuery({
            query: gql.query.TRACKS,

            // cause updateQuery does not update variables in cache entry
            // we always refer to the first page

            variables: {
              ...this.queryVars,
              pageNumber: 1
            }
          });

          const updatedTracks = {
            tracks: {
              ...tracks,
              data: [
                ...tracks.data.filter(track => track.id !== id)
              ]
            }
          };

          store.writeQuery({
            query: gql.query.TRACKS,
            variables: {
              ...this.queryVars,
              pageNumber: 1
            },
            data: updatedTracks
          });
        },

        optimisticResponse: {
          __typename: 'Mutation',
          deleteTrackMutation: {
            __typename: 'Track',
            id
          },
        },
      }).then(() => {
        this.removesInProcess -= 1;
        this.removedCount += 1;

        console.log('track removed, id: ', id);
      }).catch((err) => {
        this.removesInProcess -= 1;

        console.log(err);
      });
    }
  },

  apollo: {
    trackList() {
      return {
        query: gql.query.TRACKS,
        variables: this.queryVars,

        update({ tracks: { total, to, data } }) {
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
