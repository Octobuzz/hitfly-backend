<template>
  <TrackList
    v-if="dataInitialized && trackListLength"
    :class="[
      'my-tracks-container',
      { [containerPaddingClass]: desktop }
    ]"
    :track-id-list="trackIdList"
    :fake-fav-button="true"
    @remove-track="onTrackRemove"
    @press-favourite="onTrackRemove"
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
        v-if="hasMoreData && dataInitialized && trackListLength < shownLength"
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
      isLoading: false,
      shouldLoadMore: false,
      removesInProcess: 0,
      pendingRemoves: [],
      removedCount: 0,
      hasMoreData: true,
      shownLength: 5,
      queryVars: {
        pageNumber: 1,
        pageLimit: 10 // aspire to have two times more than you have in viewport
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

    trackListLength() {
      return this.trackList.length;
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
    fetchMoreTracks(vars) {
      return this.$apollo.queries.trackList.fetchMore({
        variables: vars,

        updateQuery: (currentList, { fetchMoreResult: { favouriteTrack } }) => {
          const { total, to, data } = favouriteTrack;

          const newTracks = data.filter(newTrack => (
            !currentList.favouriteTrack.data.some(
              currentTrack => currentTrack.track.id === newTrack.track.id
            )
          ));

          return {
            favouriteTrack: {
              // eslint-disable-next-line no-underscore-dangle
              __typename: currentList.favouriteTrack.__typename,
              total,
              to,
              data: [...currentList.favouriteTrack.data, ...newTracks]
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
        mutation: gql.mutation.REMOVE_FROM_FAVOURITE,
        variables: {
          id,
          type: 'track'
        },

        update: (store, { data: { deleteFromFavourite } }) => {
          store.writeQuery({
            query: gql.query.TRACK,
            variables: { id },
            data: {
              track: {
                __typename: 'Track',
                id,
                userFavourite: false
              }
            }
          });

          this.removeTrackFromCache(id);
        },

        optimisticResponse: {
          __typename: 'Mutation',
          deleteFromFavourite: {
            __typename: 'FavouriteTrack',
            id: -1,
            track: {
              __typename: 'Track',
              id,
              userFavourite: false
            }
          }
        }
      }).then(() => {
        this.removesInProcess -= 1;
        this.removedCount += 1;

        console.log(
          'favourite updated:',
          'type: track',
          `id: ${id};`,
          'isFav: false;'
        );
      }).catch((error) => {
        this.removesInProcess -= 1;

        console.dir(error);
      });
    },

    removeTrackFromCache(id) {
      const store = this.$apollo.provider.clients.defaultClient;

      const { favouriteTrack } = store.readQuery({
        query: gql.query.FAVOURITE_TRACKS,

        // cause updateQuery does not update variables in cache entry
        // we always refer to the first page

        variables: {
          ...this.queryVars,
          pageNumber: 1
        }
      });

      const updatedTracks = {
        favouriteTrack: {
          ...favouriteTrack,
          data: [
            ...favouriteTrack.data
              .filter(favTrack => favTrack.track.id !== id)
          ]
        }
      };

      store.writeQuery({
        query: gql.query.FAVOURITE_TRACKS,
        variables: {
          ...this.queryVars,
          pageNumber: 1
        },
        data: updatedTracks
      });
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
  src="../MyTracksContainer/MyTracksContainer.scss"
/>
